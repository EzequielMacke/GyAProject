/* Capa de persistencia local (IndexedDB) para el editor de planos de
   trabajo de campo. Objetivo: que agregar cosas (ensayos, texto, formas,
   fotos) nunca dependa de tener conexión, y que nada se pierda si el
   dispositivo se queda sin señal y la app se cierra antes de reconectar.

   Todas las funciones son tolerantes a fallos: si IndexedDB no está
   disponible (navegador viejo, modo privado restrictivo, etc.) resuelven
   a null/no-op en vez de tirar excepción, para que el editor siga
   funcionando igual que sin esta capa. */
(function () {
    const DB_NAME = 'plano-offline-db';
    const DB_VERSION = 1;
    const STORE_ESTADO = 'estado_local';
    const STORE_FOTOS = 'fotos_pendientes';

    let dbPromise = null;
    let persistSolicitado = false;

    function abrirDb() {
        if (!('indexedDB' in window)) return Promise.resolve(null);
        if (dbPromise) return dbPromise;

        dbPromise = new Promise((resolve) => {
            const req = indexedDB.open(DB_NAME, DB_VERSION);

            req.onupgradeneeded = () => {
                const db = req.result;
                if (!db.objectStoreNames.contains(STORE_ESTADO)) {
                    db.createObjectStore(STORE_ESTADO, { keyPath: 'planoId' });
                }
                if (!db.objectStoreNames.contains(STORE_FOTOS)) {
                    db.createObjectStore(STORE_FOTOS, { keyPath: 'id' });
                }
            };

            req.onsuccess = () => resolve(req.result);
            req.onerror = () => resolve(null);
        });

        if (!persistSolicitado) {
            persistSolicitado = true;
            navigator.storage?.persist?.().catch(() => {});
        }

        return dbPromise;
    }

    async function conStore(nombreStore, modo, fn) {
        const db = await abrirDb();
        if (!db) return null;

        return new Promise((resolve) => {
            let resultado = null;
            const tx = db.transaction(nombreStore, modo);
            const store = tx.objectStore(nombreStore);

            tx.oncomplete = () => resolve(resultado);
            tx.onerror = () => resolve(null);
            tx.onabort = () => resolve(null);

            try {
                resultado = fn(store) ?? resultado;
            } catch (e) {
                resultado = null;
            }
        });
    }

    /* ─── estado_local: SOLO lo que todavía no se confirmó con el
       servidor (el mismo diff que calcularOperacionesPendientes() arma
       para mandar por red), no una copia del estado completo. Guardar el
       estado completo hacía que, en otro dispositivo, algo que ya se
       había sincronizado y después alguien borró desde otro lado
       "reviviera" al reabrir — porque no había forma de distinguir "esto
       es nuevo, todavía no lo subí" de "esto ya lo subí hace rato, quedó
       viejo en la copia local". Guardando solo lo pendiente, y
       borrándolo apenas se confirma el guardado (ver limpiarOperacionesLocal),
       esa ambigüedad desaparece: si no hay nada pendiente, no hay nada
       para reinyectar. ───────────────────────────────────────────── */

    async function guardarOperacionesLocal(planoId, operaciones) {
        return conStore(STORE_ESTADO, 'readwrite', (store) => {
            store.put({ planoId, operaciones, guardadoEn: Date.now() });
        });
    }

    async function leerOperacionesLocal(planoId) {
        const db = await abrirDb();
        if (!db) return null;

        return new Promise((resolve) => {
            const tx = db.transaction(STORE_ESTADO, 'readonly');
            const req = tx.objectStore(STORE_ESTADO).get(planoId);
            req.onsuccess = () => resolve(req.result?.operaciones ?? null);
            req.onerror = () => resolve(null);
        });
    }

    async function limpiarOperacionesLocal(planoId) {
        return conStore(STORE_ESTADO, 'readwrite', (store) => {
            store.delete(planoId);
        });
    }

    /* ─── fotos_pendientes: blobs de fotos sin subir todavía ──────── */

    async function guardarFotoPendiente(id, planoId, blob, mime) {
        return conStore(STORE_FOTOS, 'readwrite', (store) => {
            store.put({ id, planoId, blob, mime, creadoEn: Date.now() });
        });
    }

    async function eliminarFotoPendiente(id) {
        return conStore(STORE_FOTOS, 'readwrite', (store) => {
            store.delete(id);
        });
    }

    async function obtenerBlobFotoPendiente(id) {
        const db = await abrirDb();
        if (!db) return null;

        return new Promise((resolve) => {
            const tx = db.transaction(STORE_FOTOS, 'readonly');
            const req = tx.objectStore(STORE_FOTOS).get(id);
            req.onsuccess = () => resolve(req.result?.blob ?? null);
            req.onerror = () => resolve(null);
        });
    }

    async function listarFotosPendientes(planoId) {
        const db = await abrirDb();
        if (!db) return [];

        return new Promise((resolve) => {
            const tx = db.transaction(STORE_FOTOS, 'readonly');
            const req = tx.objectStore(STORE_FOTOS).getAll();
            req.onsuccess = () => resolve((req.result || []).filter(f => f.planoId === planoId));
            req.onerror = () => resolve([]);
        });
    }

    let subidaEnCurso = false;

    /* Sube secuencialmente las fotos pendientes de un plano. `opciones`:
       - urlSubirFoto: endpoint de subida
       - csrfToken: token CSRF vigente
       - resolverItemPorFotoId(id): devuelve el trazo (item de
         estadoPlano.trazos) que tiene 'local:'+id en su array `fotos`,
         o null si ya no existe (se borró el pin/la foto mientras tanto).
       Devuelve true si se subió al menos una foto. */
    async function subirFotosPendientes(planoId, opciones) {
        if (subidaEnCurso) return false;
        subidaEnCurso = true;

        let subioAlguna = false;

        try {
            const pendientes = await listarFotosPendientes(planoId);

            for (const foto of pendientes) {
                const item = opciones.resolverItemPorFotoId(foto.id);
                if (!item) {
                    await eliminarFotoPendiente(foto.id);
                    continue;
                }

                const formData = new FormData();
                formData.append('foto', foto.blob, 'foto.' + (foto.mime === 'image/png' ? 'png' : 'jpg'));
                formData.append('id', foto.id);

                let respuesta;
                try {
                    respuesta = await fetch(opciones.urlSubirFoto, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': opciones.csrfToken, 'Accept': 'application/json' },
                        body: formData,
                    });
                } catch (e) {
                    break; // sin conexión: no tiene sentido seguir probando el resto de la cola ahora
                }

                if (!respuesta.ok) {
                    /* Esta foto en particular falló (por ejemplo, supera
                       el límite de tamaño del servidor) pero SÍ hay
                       conexión — no cortar acá: eso dejaba a cualquier
                       otra foto pendiente detrás de esta en la cola
                       trabada para siempre, aunque no tuviera ningún
                       problema propio. Se la salta y se sigue con el
                       resto; esta queda pendiente y se reintenta en la
                       próxima pasada. */
                    const detalle = await respuesta.json().catch(() => null);
                    console.warn('No se pudo subir una foto pendiente (HTTP ' + respuesta.status + ')', foto.id, detalle);
                    continue;
                }

                const datos = await respuesta.json();
                const refLocal = 'local:' + foto.id;
                const idx = item.fotos.indexOf(refLocal);
                if (idx !== -1) {
                    item.fotos[idx] = datos.url;
                } else if (!item.fotos.includes(datos.url)) {
                    /* La referencia 'local:<id>' ya no está (por ejemplo,
                       se pisó item.fotos con la respuesta de un guardado
                       de estado que llegó mientras esta subida estaba en
                       curso). El archivo ya se subió con éxito: en vez de
                       perderlo, se agrega igual para no dejarlo huérfano. */
                    item.fotos.push(datos.url);
                }

                await eliminarFotoPendiente(foto.id);
                subioAlguna = true;
            }
        } finally {
            subidaEnCurso = false;
        }

        return subioAlguna;
    }

    /* Dispara `onIntentar` al reconectar, cada `intervaloMs` como
       respaldo (para el caso de conexión "técnicamente online" pero
       inestable donde el evento 'online' del navegador no siempre avisa),
       y al volver a primer plano: en el celular, una pestaña/PWA en
       segundo plano frena o espacia mucho el setInterval, así que sin
       esto alguien que saca fotos offline y recién reabre la app ya con
       señal puede quedarse esperando ese intervalo mucho más de lo que
       tarda en la práctica. */
    function iniciarReintentos({ onIntentar, intervaloMs = 25000 }) {
        window.addEventListener('online', onIntentar);
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible' && navigator.onLine) onIntentar();
        });
        setInterval(() => {
            if (navigator.onLine) onIntentar();
        }, intervaloMs);
    }

    window.PlanoOffline = {
        guardarOperacionesLocal,
        leerOperacionesLocal,
        limpiarOperacionesLocal,
        guardarFotoPendiente,
        eliminarFotoPendiente,
        obtenerBlobFotoPendiente,
        listarFotosPendientes,
        subirFotosPendientes,
        iniciarReintentos,
    };
})();
