/* Service worker: cachea lo necesario para poder reabrir una página ya
   visitada (por ejemplo un plano de trabajo de campo) sin conexión, y
   deja pasar todo lo demás sin tocar. No cachea nada de forma agresiva
   "por las dudas": todo se guarda recién la primera vez que se pide con
   éxito, así que solo queda disponible offline lo que el usuario ya
   visitó estando online.

   Los guardados/subidas (PATCH/POST/DELETE) del editor de planos NO pasan
   por acá: esos ya se manejan con reintento propio en plano-offline.js,
   así que este service worker ni los intercepta. */

const CACHE_VERSION = 'v1';
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

function esAssetEstatico(url) {
    if (url.origin !== self.location.origin) return true; // CDNs (jsdelivr, cdnjs, unpkg, stackpath, google fonts, etc.)
    return (
        url.pathname.startsWith('/storage/') || // PDFs de planos, fotos subidas
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

    if (esAssetEstatico(url)) {
        event.respondWith(cacheFirst(request, CACHE_ESTATICO));
    }
});
