/* Service worker: cachea lo necesario para poder reabrir una página ya
   visitada (por ejemplo un plano de trabajo de campo) sin conexión, y
   deja pasar todo lo demás sin tocar. No cachea nada de forma agresiva
   "por las dudas": todo se guarda recién la primera vez que se pide con
   éxito, así que solo queda disponible offline lo que el usuario ya
   visitó estando online.

   Los guardados/subidas (PATCH/POST/DELETE) del editor de planos NO pasan
   por acá: esos ya se manejan con reintento propio en plano-offline.js,
   así que este service worker ni los intercepta. */

const CACHE_VERSION = 'v2';
const CACHE_PAGINAS = `gya-paginas-${CACHE_VERSION}`;
const CACHE_ESTATICO = `gya-estatico-${CACHE_VERSION}`;
const CACHES_VIGENTES = [CACHE_PAGINAS, CACHE_ESTATICO];

self.addEventListener('install', () => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((nombres) =>
            Promise.all(
                nombres
                    .filter((nombre) => nombre.startsWith('gya-') && !CACHES_VIGENTES.includes(nombre))
                    .map((nombre) => caches.delete(nombre))
            )
        ).then(() => self.clients.claim())
    );
});

async function networkFirst(request, cacheName) {
    try {
        const respuesta = await fetch(request);
        if (respuesta && respuesta.ok) {
            const cache = await caches.open(cacheName);
            cache.put(request, respuesta.clone());
        }
        return respuesta;
    } catch (e) {
        const cacheada = await caches.match(request);
        if (cacheada) return cacheada;
        throw e;
    }
}

/* Para contenido que nunca cambia una vez subido (PDFs de planos, fotos,
   y librerías de terceros con la versión pisada en la URL): server una
   vez y no volver a chequear nunca más. */
async function cacheFirst(request, cacheName) {
    const cacheada = await caches.match(request);
    if (cacheada) return cacheada;

    const respuesta = await fetch(request);
    if (respuesta && respuesta.ok) {
        const cache = await caches.open(cacheName);
        cache.put(request, respuesta.clone());
    }
    return respuesta;
}

/* Para código propio (JS/CSS/imágenes del sitio) que sí puede cambiar de
   un deploy a otro: responde al toque con lo que haya en caché (para que
   ande offline), pero en paralelo pide la versión real y la deja guardada
   para la próxima vez. Así, estando online, un archivo editado se
   actualiza solo en la siguiente carga — sin depender de acordarse de
   bumpear CACHE_VERSION cada vez que se toca un archivo. */
async function staleWhileRevalidate(request, cacheName) {
    /* El cache se abre ANTES de fetchear (no adentro del .then de la
       respuesta): así, cuando la respuesta de red llega, clonarla es
       inmediato y sincrónico, sin ningún await en el medio. Si se abre
       el cache recién ahí, ese await de más le da tiempo al navegador a
       empezar a consumir la respuesta original (la que se devuelve más
       abajo) antes de que el código llegue a clonarla — y clonar una
       respuesta cuyo body ya se empezó a leer tira TypeError. */
    const cache = await caches.open(cacheName);
    const cacheada = await cache.match(request);

    const actualizacion = fetch(request).then((respuesta) => {
        if (respuesta && respuesta.ok) {
            cache.put(request, respuesta.clone());
        }
        return respuesta;
    }).catch(() => null);

    if (cacheada) return cacheada;

    const fresca = await actualizacion;
    if (fresca) return fresca;
    throw new Error('No se pudo obtener ' + request.url);
}

function esInmutable(url) {
    if (url.origin !== self.location.origin) return true; // CDNs (jsdelivr, cdnjs, unpkg, stackpath, google fonts, etc. — versión pisada en la URL)
    return url.pathname.startsWith('/storage/'); // PDFs de planos, fotos subidas: nunca cambian una vez creadas
}

function esCodigoPropio(url) {
    return url.origin === self.location.origin && (
        url.pathname.startsWith('/vendor/') ||
        url.pathname.startsWith('/js/') ||
        url.pathname.startsWith('/img/') ||
        url.pathname === '/manifest.json'
    );
}

self.addEventListener('fetch', (event) => {
    const request = event.request;
    if (request.method !== 'GET') return; // PATCH/POST/DELETE: no se intercepta

    const url = new URL(request.url);

    if (request.mode === 'navigate') {
        event.respondWith(networkFirst(request, CACHE_PAGINAS));
        return;
    }

    if (esInmutable(url)) {
        event.respondWith(cacheFirst(request, CACHE_ESTATICO));
        return;
    }

    if (esCodigoPropio(url)) {
        event.respondWith(staleWhileRevalidate(request, CACHE_ESTATICO));
    }
});

/* Botón "Descargar para trabajar sin conexión" (planos_tc/index.blade.php):
   la página no conoce el nombre del cache (adrede, para no tener que
   mantenerlo sincronizado a mano en dos archivos — eso fue justo lo que
   se rompió la vez pasada al subir CACHE_VERSION acá y no allá), así que
   le pide a este Service Worker que descargue las URLs por ella. */
self.addEventListener('message', (event) => {
    if (event.data?.tipo !== 'precachear') return;

    const { paginas = [], recursos = [] } = event.data;
    const puerto = event.ports[0];

    (async () => {
        const cachePaginas = await caches.open(CACHE_PAGINAS);
        const cacheEstatico = await caches.open(CACHE_ESTATICO);
        const total = paginas.length + recursos.length;
        let listos = 0;
        let fallidos = 0;

        async function guardar(cache, url) {
            try {
                const respuesta = await fetch(url, { credentials: 'same-origin' });
                if (!respuesta.ok) throw new Error('HTTP ' + respuesta.status);
                await cache.put(url, respuesta);
                listos++;
            } catch (e) {
                fallidos++;
            }
            puerto?.postMessage({ listos, fallidos, total, terminado: false });
        }

        for (const url of paginas) await guardar(cachePaginas, url);
        for (const url of recursos) await guardar(cacheEstatico, url);

        puerto?.postMessage({ listos, fallidos, total, terminado: true });
    })();
});
