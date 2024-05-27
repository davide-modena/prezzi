const cacheName = 'prezzi-v4';

const staticAssets = [
    './',
    './index.php',
    './add.php',
    './addprice.php',
    './addpriceguide.php',
    './footer.php',
    './head.php',
    './utils.php',
    './login.php',
    './loginDb.php',
    './logout.php',
    './signupDb.php',
    './userPage.php',
    './css/add.css',
    './css/defaults.css',
    './css/style.css',
    './manifest.webmanifest'
];

const sensitiveURLs = [
    './addplace.php',
    // Aggiungi altre URL sensibili qui
];

self.addEventListener('install', async event => {
    const cache = await caches.open(cacheName);
    await cache.addAll(staticAssets);
    return self.skipWaiting();
});

self.addEventListener('activate', event => {
    event.waitUntil(
        // Elimina la cache obsoleta 'prezzi-v1'
        caches.keys().then(keys => {
            return Promise.all(keys
                .filter(key => key.startsWith('prezzi-') && key !== cacheName)
                .map(key => caches.delete(key))
            );
        })
    );

    // Assicurati che il service worker prenda immediatamente il controllo
    return self.clients.claim();
});

self.addEventListener('fetch', event => {
    const req = event.request;
    const url = new URL(req.url);

    if (url.origin === location.origin) {
        if (sensitiveURLs.includes(url.pathname)) {
            event.respondWith(networkFirst(req));
        } else {
            event.respondWith(cacheFirst(req));
        }
    } else {
        event.respondWith(networkAndCache(req));
    }
});

async function cacheFirst(req) {
    const cache = await caches.open(cacheName);
    const cached = await cache.match(req);
    return cached || fetch(req);
}

async function networkFirst(req) {
    const cache = await caches.open(cacheName);
    try {
        const fresh = await fetch(req);
        await cache.put(req, fresh.clone());
        return fresh;
    } catch (e) {
        const cached = await cache.match(req);
        return cached || fetch(req);
    }
}

async function networkAndCache(req) {
    const cache = await caches.open(cacheName);
    try {
        const fresh = await fetch(req);
        await cache.put(req, fresh.clone());
        return fresh;
    } catch (e) {
        const cached = await cache.match(req);
        return cached;
    }
}
