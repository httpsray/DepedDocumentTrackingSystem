<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="<?php echo e(asset('images/DOCTRAXLOGO.svg')); ?>" type="image/svg+xml">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <title>Forgot Password - DepEd DTS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/auth.css">
    <script src="/js/request-utils.js" defer></script>
    <style>
        .container { background: transparent; box-shadow: none; animation: none; }

        @media (max-width: 600px) {
            .main-wrapper { padding-left: 10px; padding-right: 10px; }
            .auth-header h2 { font-size: 18px; }
            .auth-header p { font-size: 12px; }
        }

        /* ─── Success state ─── */
        .sent-card {
            text-align: center;
            padding: 8px 0 4px;
            display: none;
        }
        .sent-card .sent-icon {
            width: 56px; height: 56px;
            border-radius: 50%;
            background: #f0fdf4;
            border: 2px solid #bbf7d0;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            font-size: 24px;
            color: #16a34a;
        }
        .sent-card h3 { font-size: 17px; font-weight: 700; color: #1e293b; margin: 0 0 8px; }
        .sent-card p { font-size: 13px; color: #64748b; line-height: 1.6; margin: 0 0 20px; }
        .sent-card .sent-email {
            display: inline-block;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            color: #0056b3;
            margin-bottom: 20px;
        }
        .btn-back-login {
            width: 100%;
            padding: 12px;
            background: #0056b3;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s;
        }
        .btn-back-login:hover { background: #004494; color: #fff; }

        /* ─── Resend cooldown ─── */
        .resend-row {
            text-align: center;
            margin-top: 14px;
            font-size: 12px;
            color: #94a3b8;
        }
        .resend-row button {
            background: none; border: none; color: #0056b3; font-size: 12px;
            font-family: inherit; font-weight: 600; cursor: pointer; padding: 0;
            text-decoration: underline;
        }
        .resend-row button:disabled { color: #94a3b8; text-decoration: none; cursor: not-allowed; }
    </style>
    <script src="/js/spa.js" defer></script>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-content">
            <div class="brand-text">
                <span class="brand-subtitle">Department of Education</span>
                <h1>Document Tracking System &mdash; <strong>DOCTRAX</strong></h1>
            </div>
        </div>
        <button class="nav-hamburger" id="navHamburger" onclick="document.getElementById('navLinks').classList.toggle('open');this.classList.toggle('open')" aria-label="Menu">
            <i class="fas fa-bars"></i>
        </button>
        <div class="nav-links" id="navLinks">
            <a href="/" class="nav-link"><i class="fas fa-home"></i> Home</a>
            <a href="/about-us" class="nav-link"><i class="fas fa-info-circle"></i> About Us</a>
            <a href="/contact-us" class="nav-link"><i class="fas fa-envelope"></i> Contact Us</a>
        </div>
    </nav>

    <div class="main-wrapper">
        <div class="auth-container">
            <div class="auth-header" id="formHeader">
                <a href="/login" class="back-link" style="margin-bottom: 12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                    Back to login
                </a>
                <h2>Forgot your password?</h2>
                <p>Enter the email address associated with your account and we'll send you a reset link.</p>
            </div>

            <!-- Error alert -->
            <div id="errorAlert" class="error-alert" style="display:none; margin-bottom:14px;">
                <i class="fas fa-exclamation-circle"></i>
                <span id="errorText"></span>
            </div>

            <!-- Form -->
            <form id="forgotForm" novalidate autocomplete="off">
                <div class="form-group">
                    <label class="form-label">Email address</label>
                    <input type="text" id="emailInput" class="form-control"
                           placeholder="ex: myname@example.com"
                           autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                    <div class="error-alert" id="emailErr" style="display:none; margin-top:8px;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span id="emailErrText"></span>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary btn-block" id="submitBtn">
                        Send Reset Link
                    </button>
                </div>
            </form>

            <!-- Success state (hidden until sent) -->
            <div class="sent-card" id="sentCard">
                <div class="sent-icon"><i class="fas fa-envelope-open-text"></i></div>
                <h3>Check your email</h3>
                <p>We sent a password reset link to:</p>
                <div class="sent-email" id="sentEmail"></div>
                <p style="margin-bottom:20px;">Didn't get it? Check your spam folder or resend below.</p>
                <a href="/login" class="btn-back-login">Back to Login</a>
                <div class="resend-row">
                    <button id="resendBtn" onclick="resend()">Resend email</button>
                    <span id="cooldownText"></span>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function () {
        var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var lastEmail = '';
        var cooldownTimer = null;

        function showError(elId, msg) {
            var el = document.getElementById(elId);
            el.querySelector('span').textContent = msg;
            el.style.display = 'flex';
        }

        function hideError(elId) {
            document.getElementById(elId).style.display = 'none';
        }

        document.getElementById('forgotForm').addEventListener('submit', function (e) {
            e.preventDefault();
            hideError('errorAlert');
            hideError('emailErr');

            var email = document.getElementById('emailInput').value.trim();

            if (!email) { showError('emailErr', 'Email address is required.'); return; }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showError('emailErr', 'Enter a valid email address.'); return; }

            sendRequest(email, false);
        });

        function sendRequest(email, isResend) {
            var btn = isResend ? document.getElementById('resendBtn') : document.getElementById('submitBtn');
            var originalText = btn.innerHTML;
            btn.disabled = true;
            if (!isResend) btn.innerHTML = '<span class="loading-dots"><span></span></span>';

            fetch('/api/forgot-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email: email })
            })
            .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
            .then(function (res) {
                if (!isResend) btn.innerHTML = originalText;

                if (res.ok) {
                    lastEmail = email;
                    showSentCard(email);
                    startCooldown(60);
                } else {
                    if (!isResend) {
                        btn.disabled = false;
                        showError('errorAlert', res.data.message || 'Something went wrong. Try again.');
                    } else {
                        startCooldown(30);
                    }
                }
            })
            .catch(function () {
                if (!isResend) {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    showError('errorAlert', 'Network error. Please try again.');
                }
            });
        }

        function showSentCard(email) {
            document.getElementById('formHeader').style.display = 'none';
            document.getElementById('forgotForm').style.display = 'none';
            document.getElementById('errorAlert').style.display = 'none';
            document.getElementById('sentEmail').textContent = email;
            document.getElementById('sentCard').style.display = 'block';
        }

        window.resend = function () {
            if (!lastEmail) return;
            sendRequest(lastEmail, true);
        };

        function startCooldown(seconds) {
            var btn = document.getElementById('resendBtn');
            var txt = document.getElementById('cooldownText');
            btn.disabled = true;
            if (cooldownTimer) clearInterval(cooldownTimer);
            var remaining = seconds;
            txt.textContent = ' — wait ' + remaining + 's';
            cooldownTimer = setInterval(function () {
                remaining--;
                if (remaining <= 0) {
                    clearInterval(cooldownTimer);
                    btn.disabled = false;
                    txt.textContent = '';
                } else {
                    txt.textContent = ' — wait ' + remaining + 's';
                }
            }, 1000);
        }
    })();
    </script>
</body>
</html>
<?php /**PATH C:\Users\iamra\Desktop\DepedDocumentTrackingSystem\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>