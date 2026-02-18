/**
 * Lightweight SPA-style page navigation.
 * Intercepts internal link clicks → fetches via AJAX → swaps head+body via DOM.
 * No full page reload = no white flash = no heartbeat.
 *
 * Routes that use a different layout (sidebar dashboard, admin) are excluded
 * and trigger a normal full-page navigation instead.
 */
(function () {
    // Routes that must NOT be handled by the SPA router.
    // - /dashboard, /admin  → different layout (sidebar, no navbar)
    // - /login, /register   → rely on CSRF tokens & page-specific scripts
    //                          that break under SPA body-swap
    var FULL_RELOAD_ROUTES = ['/dashboard', '/admin', '/login', '/register', '/activate'];

    function shouldFullReload(url) {
        try {
            var path = new URL(url, location.origin).pathname.replace(/\/+$/, '') || '/';
            for (var i = 0; i < FULL_RELOAD_ROUTES.length; i++) {
                var route = FULL_RELOAD_ROUTES[i];
                if (path === route || path.indexOf(route + '/') === 0) return true;
            }
        } catch (e) { return true; }
        return false;
    }

    function init() {

        function swap(html, url, push) {
            var doc = new DOMParser().parseFromString(html, 'text/html');

            // Update title
            document.title = doc.title;

            /* ── Head cleanup ── */
            // Remove old inline styles
            document.querySelectorAll('head > style').forEach(function (s) { s.remove(); });

            // Remove old meta tags (except charset and viewport)
            document.querySelectorAll('head > meta').forEach(function (m) {
                var name = m.getAttribute('name') || '';
                if (name !== 'viewport' && !m.getAttribute('charset')) m.remove();
            });

            // Copy new meta tags
            doc.querySelectorAll('head > meta').forEach(function (m) {
                var name = m.getAttribute('name') || '';
                if (name !== 'viewport' && !m.getAttribute('charset')) {
                    document.head.appendChild(m.cloneNode(true));
                }
            });

            // Copy new inline styles
            doc.querySelectorAll('head > style').forEach(function (s) {
                document.head.appendChild(s.cloneNode(true));
            });

            /* ── Stylesheet sync — add missing AND remove stale ── */
            // Build set of stylesheets the new page needs
            var newHrefs = {};
            doc.querySelectorAll('link[rel="stylesheet"]').forEach(function (l) {
                var href = l.getAttribute('href');
                if (href) newHrefs[href] = true;
            });

            // Remove stylesheets that the new page does NOT include
            // (skip CDN fonts / icons — only remove local project sheets)
            document.querySelectorAll('head > link[rel="stylesheet"]').forEach(function (l) {
                var href = l.getAttribute('href');
                if (!href) return;
                // Keep external CDN resources (fonts, icons)
                if (href.indexOf('cdnjs.cloudflare.com') !== -1) return;
                if (href.indexOf('fonts.googleapis.com') !== -1) return;
                // Remove if not in new page
                if (!newHrefs[href]) l.remove();
            });

            // Add stylesheets from new page that don't exist yet
            var currentHrefs = {};
            document.querySelectorAll('link[rel="stylesheet"]').forEach(function (l) {
                var h = l.getAttribute('href');
                if (h) currentHrefs[h] = true;
            });
            doc.querySelectorAll('link[rel="stylesheet"]').forEach(function (l) {
                var href = l.getAttribute('href');
                if (href && !currentHrefs[href]) {
                    var link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = href;
                    document.head.appendChild(link);
                }
            });

            // Swap body content
            document.body.innerHTML = doc.body.innerHTML;

            // Re-execute inline scripts in body
            var scripts = document.body.querySelectorAll('script');
            scripts.forEach(function (old) {
                // Skip spa.js
                if (old.src && old.src.indexOf('spa.js') !== -1) return;
                var s = document.createElement('script');
                if (old.src) {
                    s.src = old.src;
                } else {
                    s.textContent = old.textContent;
                }
                if (old.type) s.type = old.type;
                old.parentNode.replaceChild(s, old);
            });

            // Push/replace URL
            if (push) history.pushState({ spa: true }, '', url);

            // Scroll to top
            window.scrollTo(0, 0);
        }

        // Intercept internal link clicks — uses event delegation on document
        document.addEventListener('click', function (e) {
            var a = e.target.closest('a');
            if (!a || !a.href || a.target) return;
            if (e.ctrlKey || e.metaKey || e.shiftKey) return;
            if (a.hostname !== location.hostname) return;
            var href = a.getAttribute('href');
            if (!href || href === '#' || href.charAt(0) === '#') return;
            if (a.href === location.href + '#' || a.href === location.href) return;

            // If target or current page is a full-reload route, skip SPA
            if (shouldFullReload(a.href) || shouldFullReload(location.href)) {
                // Let the browser do a normal navigation
                return;
            }

            e.preventDefault();

            fetch(a.href, { credentials: 'same-origin' })
                .then(function (r) { return r.text().then(function (h) { return { html: h, url: r.url }; }); })
                .then(function (r) { swap(r.html, r.url, true); })
                .catch(function () { window.location = a.href; });
        });

        // Back / Forward buttons
        window.addEventListener('popstate', function () {
            // Full reload for dashboard / admin routes
            if (shouldFullReload(location.href)) {
                location.reload();
                return;
            }
            fetch(location.href, { credentials: 'same-origin' })
                .then(function (r) { return r.text(); })
                .then(function (h) { swap(h, location.href, false); })
                .catch(function () { location.reload(); });
        });

        history.replaceState({ spa: true }, '', location.href);
    }

    // Only init once per window
    if (!window._spaReady) {
        window._spaReady = true;
        init();
    }
})();
