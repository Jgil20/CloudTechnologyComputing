// Service worker registration. Loaded with `defer` from header.php on every page.
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/service-worker.js', { scope: '/' })
            .then(function (reg) {
                // Optional: log registration scope for diagnostics
                if (window.console && console.log) console.log('SW registered:', reg.scope);
            })
            .catch(function (err) {
                if (window.console && console.warn) console.warn('SW registration failed:', err);
            });
    });
}
