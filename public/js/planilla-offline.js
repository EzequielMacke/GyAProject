/* Guardado offline de las planillas de trabajo de campo.

   Cada planilla manda en cada guardado la planilla COMPLETA (el servidor
   borra y vuelve a crear los puntos), así que lo pendiente se guarda como
   una sola "foto" por planilla: la última versión, identificada por la URL
   de guardado. Antes de cada envío se guarda la foto en IndexedDB y recién
   se borra cuando el servidor confirma con { ok: true }. Así nada se pierde
   aunque el dispositivo se quede sin señal y se cierre la app.

   Cuando vuelve la conexión, o al abrir cualquier página de la app (este
   archivo se carga desde partials/head), se suben solas las planillas
   pendientes. Si para entonces venció la sesión, la foto queda guardada y
   se avisa que hay que iniciar sesión para subirla.

   Sobre una planilla trabaja una sola persona, así que no hay mezcla de
   cambios: gana el último guardado.

   Todo es tolerante a fallos: si IndexedDB no está disponible, guardar()
   se comporta igual que antes (envío directo, sin copia local). */
(function () {
    const DB_NAME = 'planilla-offline-db';
    const DB_VERSION = 1;
    const STORE = 'pendientes';
    const URL_CSRF = '/csrf-token';
    const URL_LOGIN = '/';
    const REINTENTO_MS = 30000;

    let dbPromise = null;
    let tokenVigente = null;
    let ultimoEstadoGlobal = null;

    function abrirDb() {
        if (!('indexedDB' in window)) return Promise.resolve(null);
        if (dbPromise) return dbPromise;

        dbPromise = new Promise((resolve) => {
            let req;
            try {
                req = indexedDB.open(DB_NAME, DB_VERSION);
            } catch (e) {
                resolve(null);
                return;
            }
            req.onupgradeneeded = () => {
                if (!req.result.objectStoreNames.contains(STORE)) {
                    req.result.createObjectStore(STORE, { keyPath: 'url' });
                }
            };
            req.onsuccess = () => resolve(req.result);
            req.onerror = () => resolve(null);
        });

        navigator.storage?.persist?.().catch(() => {});
        return dbPromise;
    }

    async function conStore(modo, fn) {
        const db = await abrirDb();
        if (!db) return null;

        return new Promise((resolve) => {
            let resultado = null;
            let tx;
            try {
                tx = db.transaction(STORE, modo);
            } catch (e) {
                resolve(null);
                return;
            }
            tx.oncomplete = () => resolve(resultado);
            tx.onerror = () => resolve(null);
            tx.onabort = () => resolve(null);
            try {
                const req = fn(tx.objectStore(STORE));
                if (req) req.onsuccess = () => { resultado = req.result ?? null; };
            } catch (e) {
                resolve(null);
            }
        });
    }

    const leer = (url) => conStore('readonly', store => store.get(url));
    const listar = () => conStore('readonly', store => store.getAll()).then(r => r || []);
    const escribir = (registro) => conStore('readwrite', store => store.put(registro));

    /* Borra la foto solo si sigue siendo la misma que se envió: si mientras
       viajaba el envío se guardó una versión más nueva, esa queda pendiente. */
    async function borrarSiNoCambio(url, version) {
        const actual = await leer(url);
        if (actual && actual.version === version) {
            await conStore('readwrite', store => store.delete(url));
        }
    }

    /* Un solo envío por planilla a la vez (también entre pestañas, si el
       navegador soporta Web Locks). Como cada envío lee la foto más nueva
       recién al empezar, el servidor nunca termina con una versión vieja. */
    const colas = {};
    function conCandado(url, fn) {
        if (navigator.locks?.request) {
            return navigator.locks.request('planilla-offline:' + url, fn);
        }
        const anterior = colas[url] || Promise.resolve();
        const actual = anterior.catch(() => {}).then(fn);
        colas[url] = actual;
        return actual;
    }

    /* Pide un token CSRF nuevo. Hace falta cuando la página salió del
       cache (token viejo) o si la sesión se renovó. Devuelve null si no
       hay sesión iniciada. */
    async function refrescarToken() {
        try {
            const respuesta = await fetch(URL_CSRF, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
                cache: 'no-store',
                redirect: 'manual',
            });
            if (!respuesta.ok) return null;
            const datos = await respuesta.json();
            if (!datos.sesion) return null;
            tokenVigente = datos.token;
            return tokenVigente;
        } catch (e) {
            return undefined; // sin conexión
        }
    }

    // metodo: PATCH (guardar la versión completa) o DELETE (eliminar).
    async function enviarPatch(url, payload, token, metodo = 'PATCH') {
        const respuesta = await fetch(url, {
            method: metodo,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token || '',
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
            redirect: 'manual', // sin sesión el servidor redirige al login: no hay que seguirlo
            body: metodo === 'DELETE' ? undefined : JSON.stringify(payload),
        });

        if (respuesta.type === 'opaqueredirect' || respuesta.status === 401) return 'sesion';
        if (respuesta.status === 419) return 'token';
        if (!respuesta.ok) return 'error';

        try {
            const datos = await respuesta.json();
            return datos && datos.ok === true ? 'guardado' : 'error';
        } catch (e) {
            return 'error';
        }
    }

    /* Sube la foto pendiente de una planilla. Estados posibles:
       guardado (subida) · local (sin conexión, queda pendiente) ·
       sesion (hay que iniciar sesión) · error (el servidor la rechazó). */
    function sincronizar(url, tokenSugerido) {
        return conCandado(url, async () => {
            const registro = await leer(url);
            if (!registro) return 'guardado';
            if (navigator.onLine === false) return 'local';

            let token = tokenVigente || tokenSugerido;
            let estado;
            try {
                if (!token) {
                    token = await refrescarToken();
                    if (token === undefined) return 'local';
                    if (token === null) return 'sesion';
                }
                estado = await enviarPatch(url, registro.payload, token, registro.metodo);

                if (estado === 'token' || estado === 'sesion') {
                    const nuevo = await refrescarToken();
                    if (nuevo === undefined) return 'local';
                    if (nuevo === null) return 'sesion';
                    estado = await enviarPatch(url, registro.payload, nuevo, registro.metodo);
                    // Con sesión válida y token nuevo, otro rechazo/redirección
                    // es falta de permiso, no sesión vencida.
                    if (estado === 'token' || estado === 'sesion') estado = 'error';
                }
            } catch (e) {
                return 'local'; // fetch rechazado: sin red
            }

            if (estado === 'guardado') {
                await borrarSiNoCambio(url, registro.version);
            }
            return estado;
        });
    }

    async function enviarSinCopia(url, payload, token, metodo) {
        try {
            const estado = await enviarPatch(url, payload, token, metodo);
            return estado === 'token' ? 'sesion' : estado;
        } catch (e) {
            return 'error';
        }
    }

    /* ─── API usada por las planillas y las pachometrías ──────────
       Una entrada pendiente por URL: la planilla completa, o cada tarjeta
       de pachometría. opciones.metodo = 'DELETE' deja pendiente una
       eliminación (reemplaza cualquier guardado pendiente de esa URL). */

    async function guardar(url, payload, opciones = {}) {
        const registro = {
            url,
            payload,
            metodo: opciones.metodo || 'PATCH',
            titulo: opciones.titulo || document.title,
            version: Date.now() + '-' + Math.random().toString(36).slice(2),
            actualizado: new Date().toISOString(),
        };
        const escrito = await escribir(registro);
        if (escrito === null && !(await leer(url))) {
            return enviarSinCopia(url, payload, opciones.token, registro.metodo); // sin IndexedDB
        }

        const estado = await sincronizar(url, opciones.token);
        ultimoEstadoGlobal = estado === 'sesion' ? 'sesion' : null;
        actualizarAviso();
        return estado;
    }

    /* Al abrir una planilla: si hay una versión local sin subir, es más
       nueva que la que mandó el servidor (o que la del cache), así que se
       usa esa. Restaura también los campos generales (fecha, lecturas del
       yunque...), que en la página tienen como name la misma clave. */
    async function cargarInicial(url, datosServidor) {
        const registro = await leer(url);
        if (!registro) return { datos: datosServidor, pendiente: false };

        Object.entries(registro.payload).forEach(([clave, valor]) => {
            if (clave === 'puntos' || valor === null || valor === undefined) return;
            const campo = document.querySelector(`[name="${clave}"]`);
            if (campo) campo.value = valor;
        });
        return { datos: registro.payload, pendiente: true };
    }

    /* ─── Sincronización en segundo plano ─────────────────────── */

    let sincronizandoTodo = false;
    async function sincronizarTodo() {
        if (sincronizandoTodo || navigator.onLine === false) return;
        sincronizandoTodo = true;
        try {
            const pendientes = await listar();
            ultimoEstadoGlobal = null;
            for (const registro of pendientes) {
                const estado = await sincronizar(registro.url);
                if (estado === 'sesion') ultimoEstadoGlobal = 'sesion';
                window.dispatchEvent(new CustomEvent('planilla-offline:estado', {
                    detail: { url: registro.url, estado },
                }));
                if (estado === 'sesion' || estado === 'local') break; // las demás van a dar lo mismo
            }
        } finally {
            sincronizandoTodo = false;
            actualizarAviso();
        }
    }

    /* Aviso flotante, visible en cualquier página, mientras haya planillas
       sin subir. Si la sesión venció, lleva al login. */
    let elAviso = null;
    async function actualizarAviso() {
        if (!document.body) return;
        const pendientes = await listar();

        if (pendientes.length === 0) {
            elAviso?.remove();
            elAviso = null;
            return;
        }

        if (!elAviso) {
            elAviso = document.createElement('a');
            elAviso.style.cssText = [
                'position:fixed', 'left:12px', 'bottom:12px', 'z-index:6000',
                'display:flex', 'align-items:center', 'gap:0.5rem',
                'max-width:calc(100vw - 24px)', 'padding:0.55rem 0.9rem',
                'border-radius:999px', 'font-size:0.78rem', 'font-weight:600',
                'text-decoration:none', 'box-shadow:0 6px 20px rgba(0,0,0,0.15)',
                'background:#fff8ec', 'color:#8a5a00', 'border:1.5px solid #e08e0b',
            ].join(';');
            document.body.appendChild(elAviso);
        }

        // Puede haber planillas y pachometrías: se habla de "cambios".
        const cantidad = pendientes.length === 1 ? '1 cambio' : `${pendientes.length} cambios`;
        const sinSesion = ultimoEstadoGlobal === 'sesion';
        elAviso.href = sinSesion ? URL_LOGIN : 'javascript:void(0)';
        elAviso.title = pendientes.map(p => p.titulo).join('\n');
        elAviso.innerHTML = sinSesion
            ? `<i class="fas fa-right-to-bracket"></i> <span>${cantidad} sin subir: iniciá sesión para subirlos</span>`
            : navigator.onLine === false
                ? `<i class="fas fa-cloud-arrow-up"></i> <span>${cantidad} guardado${pendientes.length === 1 ? '' : 's'} en el dispositivo: se sube${pendientes.length === 1 ? '' : 'n'} al volver la conexión</span>`
                : `<i class="fas fa-cloud-arrow-up"></i> <span>${cantidad} pendiente${pendientes.length === 1 ? '' : 's'} de subir</span>`;
        elAviso.onclick = sinSesion ? null : (e) => { e.preventDefault(); sincronizarTodo(); };
    }

    window.addEventListener('online', sincronizarTodo);
    window.addEventListener('offline', actualizarAviso);
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') sincronizarTodo();
    });
    setInterval(() => {
        listar().then(p => { if (p.length > 0) sincronizarTodo(); });
    }, REINTENTO_MS);

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', sincronizarTodo);
    } else {
        sincronizarTodo();
    }

    async function listarPendientes() {
        return (await listar()).map(({ url, titulo, actualizado }) => ({ url, titulo, actualizado }));
    }

    /* Descarta la versión local de una planilla (al eliminarla). Acepta la
       URL de la página o la de guardado: son la misma ruta. */
    async function descartar(url) {
        const ruta = new URL(url, location.href).pathname;
        const pendientes = await listar();
        for (const registro of pendientes) {
            if (new URL(registro.url, location.href).pathname === ruta) {
                await conCandado(registro.url, () => conStore('readwrite', store => store.delete(registro.url)));
            }
        }
        actualizarAviso();
    }

    /* Pendientes completas (con payload y metodo) cuya ruta empieza con
       el prefijo dado. Lo usa la pantalla de pachometrías para
       reconstruir las tarjetas guardadas en el dispositivo. */
    async function pendientesBajo(prefijoUrl) {
        const prefijo = new URL(prefijoUrl, location.href).pathname.replace(/\/?$/, '/');
        return (await listar()).filter(r => new URL(r.url, location.href).pathname.startsWith(prefijo));
    }

    window.PlanillaOffline = { guardar, cargarInicial, sincronizar, sincronizarTodo, listarPendientes, descartar, pendientesBajo };
})();
