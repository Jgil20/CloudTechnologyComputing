/* service-worker.js */

const PRECACHE = 'precache-v8';
const RUNTIME  = 'runtime-v7';

const RAW_URLS = [
  '/',
  '/offline.html',

  '/assets/css/bootstrap.min.css',
  '/assets/css/bootstrap-icons.min.css',
  '/assets/css/swiper-bundle.min.css',
  '/assets/css/jquery.fancybox.min.css',
  '/assets/css/boxicons.min.css',
  '/assets/css/style2.min.css?v=20260711-2',
  '/style.min.css',
  '/css/seo-engagement.min.css',

  '/assets/js/jquery-3.6.0.min.js',
  '/assets/js/popper.min.js',
  '/assets/js/bootstrap.min.js',
  '/assets/js/swiper-bundle.min.js',
  '/assets/js/jquery.fancybox.min.js',
  '/assets/js/jquery.marquee.min.js',
  '/assets/js/custom.min.js?v=20260710-3',
  '/assets/js/navigation.min.js?v=20260711-1',
  '/script.min.js',
  '/sw-register.min.js',
  '/site.webmanifest',
  '/web-app-manifest-192x192.png',
  '/web-app-manifest-512x512.png'
];

const urlsToCache = RAW_URLS.map(u =>
  new URL(u, self.registration.scope).toString()
);

self.addEventListener('install', event => {
  self.skipWaiting();

  event.waitUntil((async () => {
    const cache = await caches.open(PRECACHE);

    for (const url of urlsToCache) {
      try {
        const req = new Request(url, { cache: 'reload' });
        const res = await fetch(req);

        if (res.ok) {
          await cache.put(req, res.clone());
        }
      } catch (err) {
        console.warn('[SW] Failed to precache:', url, err);
      }
    }
  })());
});

self.addEventListener('activate', event => {
  event.waitUntil((async () => {
    const keys = await caches.keys();

    await Promise.all(
      keys
        .filter(key => key !== PRECACHE && key !== RUNTIME)
        .map(key => caches.delete(key))
    );

    await self.clients.claim();
  })());
});

self.addEventListener('fetch', event => {
  const { request } = event;

  if (request.method !== 'GET') return;

  const url = new URL(request.url);

  if (url.origin !== self.location.origin) return;

  const isAsset = /\.(css|js|woff2?|png|jpe?g|webp|avif|svg|gif|ico)$/i.test(url.pathname);
  const isPageRequest =
    request.mode === 'navigate' ||
    request.destination === 'document' ||
    /\.(php|html?)$/i.test(url.pathname) ||
    url.pathname === '/';

  if (isPageRequest) {
    event.respondWith(networkFirst(request));
    return;
  }

  if (isAsset) {
    event.respondWith(staleWhileRevalidate(request));
    return;
  }

  event.respondWith(networkFirst(request));
});

async function networkFirst(request) {
  const cache = await caches.open(RUNTIME);

  try {
    const fresh = await fetch(request, { cache: 'no-store' });

    if (fresh && fresh.ok) {
      await cache.put(request, fresh.clone());
    }

    return fresh;
  } catch (err) {
    const cached = await caches.match(request);

    if (cached) {
      return cached;
    }

    if (
      request.mode === 'navigate' ||
      request.destination === 'document'
    ) {
      const fallback = await caches.match('/offline.html');

      if (fallback) {
        return fallback;
      }
    }

    throw err;
  }
}

async function staleWhileRevalidate(request) {
  const cache = await caches.open(PRECACHE);
  const cached = await caches.match(request);

  const freshPromise = fetch(request, { cache: 'reload' })
    .then(response => {
      if (response && response.ok) {
        cache.put(request, response.clone());
      }

      return response;
    })
    .catch(() => null);

  return cached || freshPromise;
}