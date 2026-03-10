/**
 * Authentication Guard
 * Prevents cached page viewing after logout
 */
(function() {
    'use strict';

    // Check if CSRF token exists (indicates valid session)
    var csrf = document.querySelector('meta[name="csrf-token"]');
    if (!csrf || !csrf.content) {
        // No CSRF token = not authenticated
        window.location.replace('/login');
        return;
    }

    // Enhanced logout function with complete cleanup
    window.performLogout = function(event) {
        if (event) event.preventDefault();
        
        var csrfToken = csrf.content;
        
        // Clear all browser storage
        try {
            sessionStorage.clear();
            localStorage.clear();
        } catch(e) {
            console.warn('Could not clear storage:', e);
        }
        
        // Clear service worker caches
        if ('caches' in window) {
            caches.keys().then(function(names) {
                for (var i = 0; i < names.length; i++) {
                    caches.delete(names[i]);
                }
            }).catch(function(e) {
                console.warn('Could not clear caches:', e);
            });
        }
        
        // Send logout request
        fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        }).then(function(response) {
            // Force navigation (not just redirect)
            window.location.replace('/login');
        }).catch(function(error) {
            // Even if logout fails, redirect to login
            console.warn('Logout request failed:', error);
            window.location.replace('/login');
        });
        
        return false;
    };

    // Override default logout function if it exists
    if (window.logout) {
        window.logout = window.performLogout;
    }

    // Periodically check authentication status
    var authCheckInterval = setInterval(function() {
        var token = document.querySelector('meta[name="csrf-token"]');
        if (!token || !token.content) {
            clearInterval(authCheckInterval);
            window.location.replace('/login');
        }
    }, 30000); // Check every 30 seconds

    // Listen for storage events (logout in another tab)
    window.addEventListener('storage', function(e) {
        if (e.key === 'logout-event') {
            window.location.replace('/login');
        }
    });

    // Clear logout event on page load
    try {
        localStorage.removeItem('logout-event');
    } catch(e) {}

    // Prevent back button after logout via pageshow event
    window.addEventListener('pageshow', function(event) {
        // If page was loaded from cache (back/forward button)
        if (event.persisted) {
            // Check if still authenticated
            var token = document.querySelector('meta[name="csrf-token"]');
            if (!token || !token.content) {
                window.location.replace('/login');
            }
        }
    });

    // ─── Idle Auto-Logout (20 minutes) ───
    var IDLE_TIMEOUT = 20 * 60 * 1000; // 20 minutes in ms
    var _idleTimer = null;

    function resetIdleTimer() {
        if (_idleTimer) clearTimeout(_idleTimer);
        _idleTimer = setTimeout(function() {
            // User has been idle for 20 minutes — log out
            if (window.performLogout) {
                window.performLogout();
            } else {
                window.location.replace('/login');
            }
        }, IDLE_TIMEOUT);
    }

    // Activity events that reset the idle timer
    ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'click'].forEach(function(evt) {
        document.addEventListener(evt, resetIdleTimer, { passive: true });
    });

    // Start the timer immediately
    resetIdleTimer();

})();
