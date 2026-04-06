/* UP CIRENG — Service Worker (fixed) */

const SW_URL = new URL(self.location.href);
const VERSION = (SW_URL.searchParams.get("v") || "v1").replace(/[^\w.-]/g, "");
const PREFIX = "upcire-";
const STATIC_CACHE = `${PREFIX}static-${VERSION}`;
const IMG_CACHE = `${PREFIX}img-${VERSION}`;
const PAGE_CACHE = `${PREFIX}pages-${VERSION}`;
const ORIGIN = self.location.origin;

const CORE_ASSETS = [
    "/", // pastikan server mengarahkan ke index.html
    "/index.html",
    "/styles.css", // ganti sesuai punyamu
    "/script.js",
    "/assets/qris.jpg",
    "/assets/cireng ayam.png",
    "/assets/ati.jpg",
    "/assets/bakso.jpg",
    "/assets/jamut.jpg",
    "/assets/oriiii.jpg",
    "/assets/usu.jpeg",
];

const NETWORK_ONLY_HOSTS = new Set(["script.google.com"]); // Apps Script selalu network

self.addEventListener("install", (evt) => {
    evt.waitUntil(
        caches
            .open(STATIC_CACHE)
            .then((c) =>
                c.addAll(
                    CORE_ASSETS.map((p) => new Request(p, { cache: "reload" })),
                ),
            )
            .catch(() => {}),
    );
    self.skipWaiting();
});

self.addEventListener("activate", (evt) => {
    evt.waitUntil(
        (async () => {
            const keys = await caches.keys();
            const keep = new Set([STATIC_CACHE, IMG_CACHE, PAGE_CACHE]);
            await Promise.all(
                keys
                    .filter((k) => k.startsWith(PREFIX) && !keep.has(k))
                    .map((k) => caches.delete(k)),
            );
            await self.clients.claim();
        })(),
    );
});

// helpers
async function staleWhileRevalidate(cacheName, request) {
    const cache = await caches.open(cacheName);
    const cached = await cache.match(request, { ignoreVary: true });
    const net = fetch(request)
        .then((res) => {
            if (res && res.ok) cache.put(request, res.clone());
            return res;
        })
        .catch(() => null);
    return cached || net || Response.error();
}
async function cacheFirst(cacheName, request) {
    const cache = await caches.open(cacheName);
    const cached = await cache.match(request, { ignoreVary: true });
    if (cached) return cached;
    const res = await fetch(request).catch(() => null);
    if (res && res.ok) cache.put(request, res.clone());
    return res || Response.error();
}
async function networkFirst(cacheName, request) {
    const cache = await caches.open(cacheName);
    try {
        const res = await fetch(request);
        if (res && res.ok) cache.put(request, res.clone());
        return res;
    } catch {
        const cached = await cache.match(request, { ignoreVary: true });
        return cached || Response.error();
    }
}

self.addEventListener("fetch", (evt) => {
    const req = evt.request;
    if (req.method !== "GET") return;

    const url = new URL(req.url);

    // >>> FIX: hanya handle http/https (skip chrome-extension/moz-extension, dll)
    if (url.protocol !== "http:" && url.protocol !== "https:") return;

    // Jangan cache Apps Script (selalu realtime)
    if (NETWORK_ONLY_HOSTS.has(url.hostname)) {
        evt.respondWith(fetch(req));
        return;
    }

    // Navigasi halaman
    if (req.mode === "navigate") {
        evt.respondWith(
            cacheFirst(
                PAGE_CACHE,
                new Request(url.pathname, { cache: "reload" }),
            )
                .catch(() => caches.match("/index.html"))
                .catch(() => new Response("Offline", { status: 503 })),
        );
        return;
    }

    // Gambar
    if (/\.(png|jpe?g|webp|svg|gif|ico)$/i.test(url.pathname)) {
        evt.respondWith(staleWhileRevalidate(IMG_CACHE, req));
        return;
    }

    // CSS/JS/worker
    if (
        req.destination === "style" ||
        req.destination === "script" ||
        req.destination === "worker" ||
        /\.(css|js)$/i.test(url.pathname)
    ) {
        evt.respondWith(staleWhileRevalidate(STATIC_CACHE, req));
        return;
    }

    // HTML partial same-origin
    if (url.origin === ORIGIN && /\.html$/i.test(url.pathname)) {
        evt.respondWith(cacheFirst(PAGE_CACHE, req));
        return;
    }

    // Default
    evt.respondWith(networkFirst(STATIC_CACHE, req));
});
