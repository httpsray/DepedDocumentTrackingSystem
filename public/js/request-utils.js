/**
 * Request Utilities — debounce, rate-limiting, input sanitization, skeleton loading.
 * Loaded globally via <script src="/js/request-utils.js" defer> on every authed page.
 * Works with spa.js — persists across SPA swaps via event delegation.
 */
(function () {
    'use strict';

    // ── Debounce ──────────────────────────────────────────────────────────────
    /**
     * Returns a debounced version of `fn` that delays invocation by `delay` ms.
     * Calling the returned function resets the timer. An optional `immediate`
     * flag fires on the leading edge instead.
     *
     * Usage:
     *   var debouncedSearch = window.debounce(filterTable, 300);
     *   input.addEventListener('input', debouncedSearch);
     */
    window.debounce = function (fn, delay, immediate) {
        var timer = null;
        return function () {
            var ctx = this, args = arguments;
            var callNow = immediate && !timer;
            clearTimeout(timer);
            timer = setTimeout(function () {
                timer = null;
                if (!immediate) fn.apply(ctx, args);
            }, delay);
            if (callNow) fn.apply(ctx, args);
        };
    };

    // ── Visibility-Aware Polling ──────────────────────────────────────────────
    /**
     * Like setInterval but pauses when the tab is hidden and resumes
     * (with an immediate tick) when the tab becomes visible again.
     * Saves server load from background tabs — essential for production.
     *
     * Usage:  window.smartInterval(refreshStats, 30000);
     * Stop:   var poll = window.smartInterval(fn, ms); poll.clear();
     */
    window.smartInterval = function (fn, delay) {
        var id = setInterval(fn, delay);
        var onVis = function () {
            if (document.hidden) {
                if (id) { clearInterval(id); id = null; }
            } else {
                fn();
                if (!id) id = setInterval(fn, delay);
            }
        };
        document.addEventListener('visibilitychange', onVis);
        return {
            clear: function () {
                if (id) { clearInterval(id); id = null; }
                document.removeEventListener('visibilitychange', onVis);
            }
        };
    };

    // ── Input Sanitization ────────────────────────────────────────────────────
    /**
     * Strip HTML tags and trim whitespace from a string.
     * Prevents XSS when inserting user-supplied text into the DOM.
     *
     * Usage: el.textContent = window.sanitizeInput(userInput);
     */
    window.sanitizeInput = function (str) {
        if (typeof str !== 'string') return '';
        return str.replace(/<[^>]*>/g, '').trim();
    };

    /**
     * Escape HTML entities for safe insertion via innerHTML when structure is needed.
     * Prefer textContent over innerHTML wherever possible.
     */
    window.escapeHtml = function (str) {
        if (typeof str !== 'string') return '';
        var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' };
        return str.replace(/[&<>"']/g, function (c) { return map[c]; });
    };

    /**
     * Compact large counters so stat cards stay stable.
     * Examples: 1400 -> 1.4K, 10000 -> 10K, 1250000 -> 1.3M
     */
    window.formatCompactCount = function (value) {
        if (value === null || value === undefined || value === '') return '0';

        var normalized = typeof value === 'string' ? value.replace(/,/g, '').trim() : value;
        var num = Number(normalized);
        if (!isFinite(num)) return String(value);

        var abs = Math.abs(num);
        if (abs < 1000) return String(Math.round(num));

        var units = ['K', 'M', 'B', 'T'];
        var unitIndex = -1;

        while (abs >= 1000 && unitIndex < units.length - 1) {
            num = num / 1000;
            abs = abs / 1000;
            unitIndex++;
        }

        var rounded = Number(num.toFixed(1));
        if (Math.abs(rounded) >= 1000 && unitIndex < units.length - 1) {
            num = rounded / 1000;
            unitIndex++;
        }

        var formatted = num.toFixed(1).replace(/\.0$/, '');
        return formatted + units[unitIndex];
    };

    // ── Fetch Wrapper with Rate Limiting ──────────────────────────────────────
    var _fetchTimestamps = {};
    var FETCH_COOLDOWN = 1000; // 1s minimum between identical requests

    /**
     * Rate-limited fetch wrapper. Prevents duplicate rapid requests to the same URL.
     * Returns a Promise just like fetch().
     *
     * Usage:
     *   window.safeFetch('/api/endpoint', { method: 'POST', ... })
     *     .then(function(r) { return r.json(); })
     *     .then(function(data) { ... });
     */
    window.safeFetch = function (url, opts) {
        var key = (opts && opts.method || 'GET') + ':' + url;
        var now = Date.now();
        if (_fetchTimestamps[key] && (now - _fetchTimestamps[key]) < FETCH_COOLDOWN) {
            return Promise.reject(new Error('Rate limited — please wait before retrying.'));
        }
        _fetchTimestamps[key] = now;
        return fetch(url, opts);
    };

    // ── Skeleton Loading Helpers ──────────────────────────────────────────────
    var SKEL_STYLE_ID = 'skeleton-loading-style';
    if (!document.getElementById(SKEL_STYLE_ID)) {
        var style = document.createElement('style');
        style.id = SKEL_STYLE_ID;
        style.textContent =
            /* Base skeleton shimmer */
            '.skeleton{position:relative;overflow:hidden;background:#e2e8f0;border-radius:6px;color:transparent!important}' +
            '.skeleton *{visibility:hidden}' +
            '.skeleton::after{content:"";position:absolute;inset:0;' +
            'background:linear-gradient(90deg,transparent 0%,rgba(255,255,255,.45) 50%,transparent 100%);' +
            'animation:skeletonShimmer 1.5s ease-in-out infinite}' +
            '@keyframes skeletonShimmer{0%{transform:translateX(-100%)}100%{transform:translateX(100%)}}' +
            /* Skeleton variants */
            '.skeleton-text{height:14px;margin:6px 0;border-radius:4px}' +
            '.skeleton-text.w-50{width:50%}.skeleton-text.w-75{width:75%}.skeleton-text.w-100{width:100%}' +
            '.skeleton-heading{height:22px;width:40%;margin:8px 0;border-radius:4px}' +
            '.skeleton-stat{height:42px;border-radius:8px;margin-bottom:8px}' +
            '.skeleton-card{height:80px;border-radius:10px;margin-bottom:12px}' +
            '.skeleton-row{height:44px;border-radius:4px;margin-bottom:8px}' +
            /* Fade-in when content loads */
            '.skeleton-fade-in{animation:skeletonFadeIn .3s ease forwards}' +
            '@keyframes skeletonFadeIn{from{opacity:0}to{opacity:1}}';
        document.head.appendChild(style);
    }

    /**
     * Show skeleton state on an element.
     * Usage: window.showSkeleton(document.getElementById('statsCard'));
     */
    window.showSkeleton = function (el) {
        if (!el) return;
        el.classList.add('skeleton');
        el.classList.remove('skeleton-fade-in');
    };

    /**
     * Remove skeleton state from an element.
     * Usage: window.hideSkeleton(document.getElementById('statsCard'));
     */
    window.hideSkeleton = function (el) {
        if (!el) return;
        el.classList.remove('skeleton');
        el.classList.add('skeleton-fade-in');
    };

    // ── Auto-debounce client-side filterTable ─────────────────────────────────
    // Applies a 300ms debounce to any search input that calls filterTable() via
    // oninput/onkeyup. Works via MutationObserver for SPA-injected inputs.

    var SEARCH_DEBOUNCE_MS = 300;

    function wrapFilterTable(input) {
        if (!input || input.dataset.filterDebounced === '1') return;

        var origHandler = input.getAttribute('oninput') || input.getAttribute('onkeyup');
        if (!origHandler || origHandler.indexOf('filterTable') === -1) return;

        // Remove the inline handler
        input.removeAttribute('oninput');
        input.removeAttribute('onkeyup');
        input.dataset.filterDebounced = '1';

        var debouncedFilter = window.debounce(function () {
            if (typeof window.filterTable === 'function') {
                window.filterTable();
            }
        }, SEARCH_DEBOUNCE_MS);

        input.addEventListener('input', debouncedFilter);
    }

    function initFilterDebounce() {
        document.querySelectorAll('input[oninput*="filterTable"], input[onkeyup*="filterTable"]')
            .forEach(wrapFilterTable);
    }

    // Run on load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFilterDebounce);
    } else {
        initFilterDebounce();
    }

    // Watch for SPA swaps
    var mo = new MutationObserver(function (mutations) {
        var found = false;
        mutations.forEach(function (m) {
            m.addedNodes.forEach(function (node) {
                if (node.nodeType !== 1) return;
                if (node.matches && (
                    node.matches('input[oninput*="filterTable"]') ||
                    node.matches('input[onkeyup*="filterTable"]')
                )) {
                    found = true;
                }
                if (node.querySelectorAll) {
                    var inputs = node.querySelectorAll(
                        'input[oninput*="filterTable"], input[onkeyup*="filterTable"]'
                    );
                    if (inputs.length) found = true;
                }
            });
        });
        if (found) initFilterDebounce();
    });
    mo.observe(document.documentElement, { childList: true, subtree: true });

    // Live SPA search/filter forms
    // Mark GET filter forms with data-live-search to auto-submit through the SPA
    // layer while typing/changing filters, without a full page refresh.

    var LIVE_FORM_SELECTOR = 'form[data-live-search][method="GET"]';

    function buildLiveFormUrl(form) {
        var action = form.getAttribute('action') || location.pathname;
        var url = new URL(action, location.origin);
        var formData = new FormData(form);
        var params = new URLSearchParams();

        formData.forEach(function (value, key) {
            var normalized = typeof value === 'string' ? window.sanitizeInput(value) : value;
            if (normalized === null || normalized === undefined) return;
            if (String(normalized).trim() === '') return;
            params.append(key, String(normalized).trim());
        });

        url.search = params.toString();
        return url.toString();
    }

    function submitLiveForm(form) {
        var url = buildLiveFormUrl(form);
        var currentUrl = location.href.split('#')[0];

        if (form.dataset.liveSearchLastUrl === url && url === currentUrl) {
            return Promise.resolve(false);
        }

        if (form._liveSearchController && typeof form._liveSearchController.abort === 'function') {
            form._liveSearchController.abort();
        }
        form._liveSearchController = (typeof AbortController !== 'undefined') ? new AbortController() : null;
        form.dataset.liveSearchLastUrl = url;

        if (typeof window.spaNavigate === 'function') {
            return window.spaNavigate(url, {
                historyMode: 'replace',
                fallbackUrl: url,
                silent: true,
                preserveScroll: true,
                preserveFocus: true,
                signal: form._liveSearchController ? form._liveSearchController.signal : undefined
            });
        }

        window.location.href = url;
        return Promise.resolve(false);
    }

    function bindLiveForm(form) {
        if (!form || form.dataset.liveSearchBound === '1') return;
        form.dataset.liveSearchBound = '1';
        form.dataset.liveSearchLastUrl = location.href.split('#')[0];

        var delay = parseInt(form.getAttribute('data-live-debounce') || '280', 10);
        if (isNaN(delay) || delay < 0) delay = 280;

        var debouncedSubmit = window.debounce(function () {
            submitLiveForm(form);
        }, delay);

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            submitLiveForm(form);
        });

        form.querySelectorAll('input, textarea, select').forEach(function (field) {
            if (!field || field.hasAttribute('data-no-live-search')) return;

            var tag = field.tagName;
            var type = (field.getAttribute('type') || '').toLowerCase();

            if (tag === 'SELECT' || type === 'date' || type === 'checkbox' || type === 'radio') {
                field.addEventListener('change', function () {
                    submitLiveForm(form);
                });
                return;
            }

            if (type === 'hidden') return;

            if (tag === 'TEXTAREA' || type === 'text' || type === 'search' || type === '') {
                field.addEventListener('input', debouncedSubmit);
                field.addEventListener('change', function () {
                    submitLiveForm(form);
                });
            }
        });
    }

    function initLiveForms() {
        document.querySelectorAll(LIVE_FORM_SELECTOR).forEach(bindLiveForm);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLiveForms);
    } else {
        initLiveForms();
    }

    var liveFormObserver = new MutationObserver(function (mutations) {
        mutations.forEach(function (m) {
            m.addedNodes.forEach(function (node) {
                if (node.nodeType !== 1) return;
                if (node.matches && node.matches(LIVE_FORM_SELECTOR)) bindLiveForm(node);
                if (node.querySelectorAll) {
                    node.querySelectorAll(LIVE_FORM_SELECTOR).forEach(bindLiveForm);
                }
            });
        });
    });
    liveFormObserver.observe(document.documentElement, { childList: true, subtree: true });

    // ── Form Submit Cooldown (auto-apply to search forms) ─────────────────────
    // Enforces a 2-second cooldown on any <form method="GET"> submission to
    // prevent rapid-fire search requests. Skips forms with data-no-cooldown.

    var FORM_COOLDOWN_MS = 2000;
    var _formTimestamps = {};

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form || form.tagName !== 'FORM') return;

        var method = (form.getAttribute('method') || 'POST').toUpperCase();
        if (method !== 'GET') return; // only apply to search/filter forms
        if (form.hasAttribute('data-live-search')) return;
        if (form.hasAttribute('data-no-cooldown')) return;

        var key = form.id || form.getAttribute('action') || 'default';
        var now = Date.now();

        if (_formTimestamps[key] && (now - _formTimestamps[key]) < FORM_COOLDOWN_MS) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return;
        }
        _formTimestamps[key] = now;
    }, true); // capture phase

})();
