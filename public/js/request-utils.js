/**
 * Request utilities: debounce, rate limiting, network fallbacks, skeleton loading.
 * Loaded globally via <script src="/js/request-utils.js" defer>.
 * Works with spa.js and survives SPA swaps via event delegation.
 */
(function () {
    'use strict';

    // Debounce
    window.debounce = function (fn, delay, immediate) {
        var timer = null;
        return function () {
            var ctx = this;
            var args = arguments;
            var callNow = immediate && !timer;

            clearTimeout(timer);
            timer = setTimeout(function () {
                timer = null;
                if (!immediate) fn.apply(ctx, args);
            }, delay);

            if (callNow) fn.apply(ctx, args);
        };
    };

    // Visibility-aware polling
    window.smartInterval = function (fn, delay) {
        var id = setInterval(fn, delay);

        function onVisibilityChange() {
            if (document.hidden) {
                if (id) {
                    clearInterval(id);
                    id = null;
                }
                return;
            }

            fn();
            if (!id) id = setInterval(fn, delay);
        }

        document.addEventListener('visibilitychange', onVisibilityChange);

        return {
            clear: function () {
                if (id) {
                    clearInterval(id);
                    id = null;
                }
                document.removeEventListener('visibilitychange', onVisibilityChange);
            }
        };
    };

    // Input sanitization
    window.sanitizeInput = function (str) {
        if (typeof str !== 'string') return '';
        return str.replace(/<[^>]*>/g, '').trim();
    };

    window.escapeHtml = function (str) {
        if (typeof str !== 'string') return '';
        var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' };
        return str.replace(/[&<>"']/g, function (c) { return map[c]; });
    };

    // Compact counters
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

        return num.toFixed(1).replace(/\.0$/, '') + units[unitIndex];
    };

    // Network notices and fetch helpers
    var _fetchTimestamps = {};
    var FETCH_COOLDOWN = 1000;
    var DEFAULT_REQUEST_TIMEOUT = 15000;
    var NETWORK_NOTICE_STYLE_ID = 'doc-trax-network-notice-style';
    var NETWORK_NOTICE_ID = 'doc-trax-network-notice';
    var _noticeTimer = null;
    var _statusNotices = {};

    function ensureNetworkNoticeStyle() {
        if (document.getElementById(NETWORK_NOTICE_STYLE_ID)) return;

        var style = document.createElement('style');
        style.id = NETWORK_NOTICE_STYLE_ID;
        style.textContent =
            '#' + NETWORK_NOTICE_ID + '{position:fixed;top:14px;left:50%;transform:translateX(-50%) translateY(-8px);' +
            'width:min(calc(100vw - 24px),560px);display:flex;align-items:flex-start;gap:10px;' +
            'padding:12px 14px;border-radius:12px;border:1px solid #e2e8f0;background:#fff;color:#0f172a;' +
            'box-shadow:0 18px 40px rgba(15,23,42,.16);opacity:0;pointer-events:none;z-index:100001;' +
            'transition:opacity .2s ease,transform .2s ease}' +
            '#' + NETWORK_NOTICE_ID + '.show{opacity:1;pointer-events:auto;transform:translateX(-50%) translateY(0)}' +
            '#' + NETWORK_NOTICE_ID + '.warning{border-color:#fcd34d;background:#fffbeb;color:#92400e}' +
            '#' + NETWORK_NOTICE_ID + '.error{border-color:#fca5a5;background:#fef2f2;color:#991b1b}' +
            '#' + NETWORK_NOTICE_ID + '.success{border-color:#86efac;background:#f0fdf4;color:#166534}' +
            '#' + NETWORK_NOTICE_ID + ' .doc-trax-network-icon{width:18px;flex:0 0 18px;text-align:center;font-size:14px;line-height:1.4}' +
            '#' + NETWORK_NOTICE_ID + ' .doc-trax-network-text{font-size:12.5px;font-weight:600;line-height:1.45}';
        document.head.appendChild(style);
    }

    function ensureNetworkNotice() {
        ensureNetworkNoticeStyle();

        var existing = document.getElementById(NETWORK_NOTICE_ID);
        if (existing) return existing;
        if (!document.body) return null;

        var notice = document.createElement('div');
        notice.id = NETWORK_NOTICE_ID;
        notice.innerHTML =
            '<div class="doc-trax-network-icon" aria-hidden="true"></div>' +
            '<div class="doc-trax-network-text"></div>';
        document.body.appendChild(notice);
        return notice;
    }

    function pickNoticeIcon(type) {
        if (type === 'success') return 'fa-check-circle';
        if (type === 'warning') return 'fa-wifi';
        return 'fa-circle-exclamation';
    }

    function renderNetworkNotice(payload) {
        var notice = ensureNetworkNotice();
        if (!notice) {
            document.addEventListener('DOMContentLoaded', function handleReady() {
                document.removeEventListener('DOMContentLoaded', handleReady);
                renderNetworkNotice(payload);
            });
            return;
        }

        var type = payload && payload.type ? payload.type : 'warning';
        var iconClass = payload && payload.icon ? payload.icon : pickNoticeIcon(type);

        notice.className = type + ' show';
        notice.querySelector('.doc-trax-network-icon').innerHTML = '<i class="fas ' + iconClass + '"></i>';
        notice.querySelector('.doc-trax-network-text').textContent =
            payload && payload.message ? payload.message : 'Connection problem detected.';
    }

    function removeNetworkNotice() {
        clearTimeout(_noticeTimer);
        _noticeTimer = null;
        _statusNotices = {};

        var notice = document.getElementById(NETWORK_NOTICE_ID);
        if (notice && notice.parentNode) {
            notice.parentNode.removeChild(notice);
        }
    }

    window.setStatusNotice = function (id, message, options) {
        removeNetworkNotice();
    };

    window.clearStatusNotice = function (id) {
        removeNetworkNotice();
    };

    window.showNetworkNotice = function (message, options) {
        removeNetworkNotice();
    };

    window.hideNetworkNotice = function () {
        removeNetworkNotice();
    };

    function createRequestError(type, message, cause) {
        var error = cause instanceof Error ? cause : new Error(message);
        error.requestType = type;
        error.userMessage = message;
        if (cause) error.originalError = cause;
        return error;
    }

    function describeRequestError(error, fallbackMessage) {
        if (error && error.userMessage) return error.userMessage;
        if (error && error.requestType === 'timeout') return 'The server is taking too long to respond. Please try again.';
        if (error && error.requestType === 'offline') return 'You appear to be offline. Please reconnect and try again.';
        if (error && error.requestType === 'server') return 'The server returned an unexpected response. Please try again.';
        if (navigator.onLine === false) return 'You appear to be offline. Please reconnect and try again.';
        return fallbackMessage || 'Could not connect to the server. Please try again.';
    }

    window.createRequestError = createRequestError;
    window.describeRequestError = describeRequestError;
    window.isRequestErrorType = function (error, type) {
        return !!(error && error.requestType === type);
    };

    window.docTraxFetch = function (url, opts) {
        opts = opts || {};

        if (navigator.onLine === false && opts.skipOfflineCheck !== true) {
            return Promise.reject(createRequestError('offline', 'You appear to be offline. Please reconnect and try again.'));
        }

        var fetchOptions = {};
        Object.keys(opts).forEach(function (key) {
            if (
                key === 'timeoutMs' ||
                key === 'skipOfflineCheck' ||
                key === 'showNoticeOnError' ||
                key === 'noticeMessage' ||
                key === 'cooldownMs' ||
                key === 'rateLimitKey' ||
                key === 'rejectOnHttpError'
            ) {
                return;
            }
            fetchOptions[key] = opts[key];
        });

        var timeoutMs = typeof opts.timeoutMs === 'number' ? opts.timeoutMs : DEFAULT_REQUEST_TIMEOUT;
        var controller = typeof AbortController !== 'undefined' ? new AbortController() : null;
        var userSignal = fetchOptions.signal;
        var timedOut = false;
        var abortRelay = null;
        var timeoutId = null;

        if (controller) {
            if (userSignal) {
                if (userSignal.aborted) {
                    controller.abort();
                } else {
                    abortRelay = function () { controller.abort(); };
                    userSignal.addEventListener('abort', abortRelay);
                }
            }
            fetchOptions.signal = controller.signal;
        }

        if (controller && timeoutMs > 0) {
            timeoutId = setTimeout(function () {
                timedOut = true;
                controller.abort();
            }, timeoutMs);
        }

        function cleanup() {
            if (timeoutId) {
                clearTimeout(timeoutId);
                timeoutId = null;
            }
            if (userSignal && abortRelay) {
                userSignal.removeEventListener('abort', abortRelay);
            }
        }

        return fetch(url, fetchOptions)
            .then(function (response) {
                cleanup();
                return response;
            })
            .catch(function (error) {
                cleanup();

                if (error && error.name === 'AbortError' && userSignal && userSignal.aborted && !timedOut) {
                    throw error;
                }

                var requestError;
                if (timedOut) {
                    requestError = createRequestError('timeout', 'The server is taking too long to respond. Please try again.', error);
                } else if (navigator.onLine === false) {
                    requestError = createRequestError('offline', 'You appear to be offline. Please reconnect and try again.', error);
                } else {
                    requestError = createRequestError('network', 'Could not connect to the server. Please try again.', error);
                }

                if (opts.showNoticeOnError) {
                    window.showNetworkNotice(opts.noticeMessage || requestError.userMessage, {
                        type: requestError.requestType === 'offline' ? 'warning' : 'error'
                    });
                }

                throw requestError;
            });
    };

    window.docTraxFetchJson = function (url, opts) {
        opts = opts || {};

        return window.docTraxFetch(url, opts)
            .then(function (response) {
                if (opts.rejectOnHttpError !== false && !response.ok) {
                    throw createRequestError('server', 'The server returned an unexpected response. Please try again.');
                }

                return response.text().then(function (text) {
                    if (!text) return {};

                    try {
                        return JSON.parse(text);
                    } catch (parseError) {
                        throw createRequestError('server', 'Received an unexpected server response. Please try again.', parseError);
                    }
                });
            });
    };

    window.safeFetch = function (url, opts) {
        opts = opts || {};
        var key = opts.rateLimitKey || ((opts.method || 'GET') + ':' + url);
        var cooldownMs = typeof opts.cooldownMs === 'number' ? opts.cooldownMs : FETCH_COOLDOWN;
        var now = Date.now();

        if (_fetchTimestamps[key] && (now - _fetchTimestamps[key]) < cooldownMs) {
            return Promise.reject(createRequestError('rate_limit', 'Please wait a moment before trying again.'));
        }

        _fetchTimestamps[key] = now;
        return window.docTraxFetch(url, opts);
    };

    window.addEventListener('offline', removeNetworkNotice);
    window.addEventListener('online', removeNetworkNotice);
    removeNetworkNotice();

    // Back-link enhancement
    var BACK_LINK_STYLE_ID = 'doc-trax-back-link-style';
    var BACK_LINK_ICON_SVG =
        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" ' +
        'stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" ' +
        'class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left">' +
        '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>' +
        '<path d="M5 12l14 0"></path>' +
        '<path d="M5 12l6 6"></path>' +
        '<path d="M5 12l6 -6"></path>' +
        '</svg>';

    function ensureBackLinkStyle() {
        if (document.getElementById(BACK_LINK_STYLE_ID)) return;

        var style = document.createElement('style');
        style.id = BACK_LINK_STYLE_ID;
        style.textContent =
            '.doc-trax-back-link{display:inline-flex!important;align-items:center!important;gap:11px!important;' +
            'padding:0!important;border:none!important;background:transparent!important;border-radius:0!important;' +
            'box-shadow:none!important;text-decoration:none!important;color:#0f172a!important;font-weight:600!important;' +
            'line-height:1.2;transition:color .18s ease!important}' +
            '.doc-trax-back-link:hover{color:#0f172a!important}' +
            '.doc-trax-back-link:focus-visible{outline:2px solid rgba(0,86,179,.28);outline-offset:4px;border-radius:999px}' +
            '.doc-trax-back-link-icon{width:38px;height:38px;display:inline-flex;align-items:center;justify-content:center;' +
            'flex:0 0 38px;border-radius:999px;background:linear-gradient(135deg,#0f4fd6 0%,#1f8ef1 100%);' +
            'color:#fff;box-shadow:none!important;transition:filter .18s ease,background .18s ease}' +
            '.doc-trax-back-link-icon svg{display:block;width:18px;height:18px;stroke:currentColor}' +
            '.doc-trax-back-link:hover .doc-trax-back-link-icon{filter:brightness(.88)}' +
            '.doc-trax-back-link-text{display:inline-block}' +
            '.doc-trax-back-link-icon-only{gap:0!important}' +
            '.doc-trax-back-link-icon-only .doc-trax-back-link-text{display:none!important}' +
            '@media (max-width:640px){' +
            '.doc-trax-back-link{gap:10px!important;font-size:12px!important}' +
            '.doc-trax-back-link-icon{width:34px;height:34px;flex-basis:34px}' +
            '.doc-trax-back-link-icon svg{width:16px;height:16px}' +
            '}';
        document.head.appendChild(style);
    }

    function isBackLinkTarget(node) {
        return !!(
            node &&
            node.nodeType === 1 &&
            node.classList &&
            (
                node.classList.contains('back-link') ||
                node.classList.contains('btn-back')
            )
        );
    }

    function upgradeBackLink(node) {
        if (!isBackLinkTarget(node) || node.dataset.backLinkUpgraded === '1') return;

        ensureBackLinkStyle();

        var label = String(node.textContent || '').replace(/\s+/g, ' ').trim();
        if (!label) return;
        var isDashboardBack = /dashboard/i.test(label);

        node.dataset.backLinkUpgraded = '1';
        node.classList.add('doc-trax-back-link');
        if (isDashboardBack) {
            node.classList.add('doc-trax-back-link-icon-only');
            node.setAttribute('aria-label', label);
            node.setAttribute('title', label);
        }
        node.textContent = '';

        var icon = document.createElement('span');
        icon.className = 'doc-trax-back-link-icon';
        icon.setAttribute('aria-hidden', 'true');
        icon.innerHTML = BACK_LINK_ICON_SVG;

        var text = document.createElement('span');
        text.className = 'doc-trax-back-link-text';
        text.textContent = label;

        node.appendChild(icon);
        if (!isDashboardBack) {
            node.appendChild(text);
        }
    }

    function initBackLinks(root) {
        if (!root) return;

        if (isBackLinkTarget(root)) {
            upgradeBackLink(root);
        }

        if (!root.querySelectorAll) return;

        root.querySelectorAll('.back-link, .btn-back').forEach(function (link) {
            upgradeBackLink(link);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function initBackLinksOnReady() {
            initBackLinks(document);
        });
    } else {
        initBackLinks(document);
    }

    var backLinkObserver = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            mutation.addedNodes.forEach(function (node) {
                if (!node || node.nodeType !== 1) return;
                initBackLinks(node);
            });
        });
    });
    backLinkObserver.observe(document.documentElement, { childList: true, subtree: true });

    // Idle auto-logout for authenticated screens
    var IDLE_LOGOUT_TIMEOUT = 30 * 60 * 1000;

    function isAuthenticatedPage() {
        if (document.body && document.body.getAttribute('data-authenticated') === 'true') return true;
        if (document.getElementById('mainSidebar')) return true;
        if (document.querySelector('.btn-logout')) return true;
        if (typeof window.logout === 'function' || typeof window.performLogout === 'function') return true;

        if (
            /^\/receive\/[A-Za-z0-9-]+$/i.test(window.location.pathname) &&
            document.getElementById('confirmBtn')
        ) {
            return true;
        }

        return false;
    }

    function fallbackLogout() {
        var csrfNode = document.querySelector('meta[name="csrf-token"]');
        var csrfToken = csrfNode ? csrfNode.getAttribute('content') : '';

        fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        }).catch(function () {
            // Best effort only; we still force the redirect.
        }).finally(function () {
            window.location.replace('/login');
        });
    }

    function initIdleLogoutGuard() {
        if (window.__docTraxIdleGuardInitialized) return;
        if (!isAuthenticatedPage()) return;

        window.__docTraxIdleGuardInitialized = true;
        window.__docTraxIdleLogoutMs = IDLE_LOGOUT_TIMEOUT;

        var idleTimer = null;

        function logoutForIdle() {
            if (typeof window.performLogout === 'function') {
                window.performLogout();
                return;
            }

            if (typeof window.logout === 'function') {
                window.logout();
                return;
            }

            fallbackLogout();
        }

        function resetIdleTimer() {
            if (idleTimer) clearTimeout(idleTimer);
            idleTimer = setTimeout(logoutForIdle, IDLE_LOGOUT_TIMEOUT);
        }

        ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'click', 'input'].forEach(function (eventName) {
            document.addEventListener(eventName, resetIdleTimer, { passive: true });
        });

        window.addEventListener('focus', resetIdleTimer);
        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) resetIdleTimer();
        });

        resetIdleTimer();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initIdleLogoutGuard);
    } else {
        initIdleLogoutGuard();
    }

    // Skeleton loading helpers
    var SKEL_STYLE_ID = 'skeleton-loading-style';
    if (!document.getElementById(SKEL_STYLE_ID)) {
        var style = document.createElement('style');
        style.id = SKEL_STYLE_ID;
        style.textContent =
            '.skeleton{position:relative;overflow:hidden;background:#e2e8f0;border-radius:6px;color:transparent!important}' +
            '.skeleton *{visibility:hidden}' +
            '.skeleton::after{content:"";position:absolute;inset:0;' +
            'background:linear-gradient(90deg,transparent 0%,rgba(255,255,255,.45) 50%,transparent 100%);' +
            'animation:skeletonShimmer 1.5s ease-in-out infinite}' +
            '@keyframes skeletonShimmer{0%{transform:translateX(-100%)}100%{transform:translateX(100%)}}' +
            '.skeleton-text{height:14px;margin:6px 0;border-radius:4px}' +
            '.skeleton-text.w-50{width:50%}.skeleton-text.w-75{width:75%}.skeleton-text.w-100{width:100%}' +
            '.skeleton-heading{height:22px;width:40%;margin:8px 0;border-radius:4px}' +
            '.skeleton-stat{height:42px;border-radius:8px;margin-bottom:8px}' +
            '.skeleton-card{height:80px;border-radius:10px;margin-bottom:12px}' +
            '.skeleton-row{height:44px;border-radius:4px;margin-bottom:8px}' +
            '.skeleton-fade-in{animation:skeletonFadeIn .3s ease forwards}' +
            '@keyframes skeletonFadeIn{from{opacity:0}to{opacity:1}}';
        document.head.appendChild(style);
    }

    window.showSkeleton = function (el) {
        if (!el) return;
        el.classList.add('skeleton');
        el.classList.remove('skeleton-fade-in');
    };

    window.hideSkeleton = function (el) {
        if (!el) return;
        el.classList.remove('skeleton');
        el.classList.add('skeleton-fade-in');
    };

    // Auto-debounce client-side filterTable
    var SEARCH_DEBOUNCE_MS = 300;

    function wrapFilterTable(input) {
        if (!input || input.dataset.filterDebounced === '1') return;

        var origHandler = input.getAttribute('oninput') || input.getAttribute('onkeyup');
        if (!origHandler || origHandler.indexOf('filterTable') === -1) return;

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

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFilterDebounce);
    } else {
        initFilterDebounce();
    }

    var debounceObserver = new MutationObserver(function (mutations) {
        var found = false;

        mutations.forEach(function (mutation) {
            mutation.addedNodes.forEach(function (node) {
                if (node.nodeType !== 1) return;

                if (
                    node.matches &&
                    (
                        node.matches('input[oninput*="filterTable"]') ||
                        node.matches('input[onkeyup*="filterTable"]')
                    )
                ) {
                    found = true;
                }

                if (node.querySelectorAll) {
                    var inputs = node.querySelectorAll('input[oninput*="filterTable"], input[onkeyup*="filterTable"]');
                    if (inputs.length) found = true;
                }
            });
        });

        if (found) initFilterDebounce();
    });
    debounceObserver.observe(document.documentElement, { childList: true, subtree: true });

    // Live SPA search/filter forms
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

        form._liveSearchController = typeof AbortController !== 'undefined' ? new AbortController() : null;
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

        form.addEventListener('submit', function (event) {
            event.preventDefault();
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
        mutations.forEach(function (mutation) {
            mutation.addedNodes.forEach(function (node) {
                if (node.nodeType !== 1) return;
                if (node.matches && node.matches(LIVE_FORM_SELECTOR)) bindLiveForm(node);
                if (node.querySelectorAll) {
                    node.querySelectorAll(LIVE_FORM_SELECTOR).forEach(bindLiveForm);
                }
            });
        });
    });
    liveFormObserver.observe(document.documentElement, { childList: true, subtree: true });

    // Form submit cooldown for GET search forms
    var FORM_COOLDOWN_MS = 2000;
    var _formTimestamps = {};

    document.addEventListener('submit', function (event) {
        var form = event.target;
        if (!form || form.tagName !== 'FORM') return;

        var method = (form.getAttribute('method') || 'POST').toUpperCase();
        if (method !== 'GET') return;
        if (form.hasAttribute('data-live-search')) return;
        if (form.hasAttribute('data-no-cooldown')) return;

        var key = form.id || form.getAttribute('action') || 'default';
        var now = Date.now();

        if (_formTimestamps[key] && (now - _formTimestamps[key]) < FORM_COOLDOWN_MS) {
            event.preventDefault();
            event.stopImmediatePropagation();
            return;
        }

        _formTimestamps[key] = now;
    }, true);

    // Global logout reliability
    var _logoutInFlight = false;

    function performReliableLogout(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        if (_logoutInFlight) return false;
        _logoutInFlight = true;

        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

        fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'same-origin'
        }).finally(function () {
            window.location.replace('/login');
        });

        return false;
    }

    window.logout = performReliableLogout;

    document.addEventListener('click', function (event) {
        var btn = event.target && event.target.closest ? event.target.closest('.btn-logout') : null;
        if (!btn) return;
        performReliableLogout(event);
    }, true);
})();
