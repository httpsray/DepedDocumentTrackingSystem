<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Profile - DepEd DOCTRAX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #0056b3;
            --primary-dark: #004494;
            --primary-gradient: linear-gradient(135deg, #0056b3 0%, #004494 100%);
            --accent: #fca311;
            --text-dark: #1b263b;
            --text-muted: #64748b;
            --white: #ffffff;
            --bg: #f0f2f5;
            --border: #e2e8f0;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { overflow-y: scroll; }

        body {
            background: var(--bg);
            font-family: 'Poppins', sans-serif;
            -webkit-font-smoothing: antialiased;
            color: var(--text-dark);
            min-height: 100vh;
        }

        /* ─── Navbar ─── */
        .navbar {
            width: 100%;
            background: var(--primary-gradient);
            padding: 15px 5%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            color: var(--white);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-content { display: flex; align-items: center; gap: 15px; }
        .brand-text { display: flex; flex-direction: column; }
        .brand-subtitle { font-size: 11px; opacity: 0.85; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 400; }
        .navbar h1 { font-size: 18px; font-weight: 700; margin: 0; line-height: 1.2; }

        .nav-actions { display: flex; align-items: center; gap: 16px; }

        .nav-link {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: #fff; }
        .nav-link.active { background: rgba(255,255,255,0.2); color: #fff; }

        .nav-user {
            display: flex; align-items: center; gap: 10px;
            margin-left: 8px; padding-left: 16px;
            border-left: 1px solid rgba(255,255,255,0.2);
        }

        .nav-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: rgba(255,255,255,0.2); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px;
        }

        .nav-user-info { display: flex; flex-direction: column; line-height: 1.2; }
        .nav-user-name { font-size: 13px; font-weight: 600; color: #fff; }
        .nav-user-role { font-size: 10px; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 0.5px; }

        .btn-logout {
            background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.9); cursor: pointer; padding: 7px 14px;
            border-radius: 8px; font-size: 13px; font-family: inherit; font-weight: 500;
            display: flex; align-items: center; gap: 6px; transition: all 0.2s;
        }
        .btn-logout:hover { background: rgba(220, 38, 38, 0.85); border-color: rgba(220, 38, 38, 0.9); color: #fff; }

        .nav-toggle { display: none; background: none; border: none; color: #fff; font-size: 20px; cursor: pointer; padding: 4px; }
        .nav-menu-mobile { display: none; width: 100%; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.15); margin-top: 12px; flex-direction: column; gap: 4px; }
        .nav-menu-mobile.open { display: flex; }

        /* ─── Main Content ─── */
        .dash-wrapper { max-width: 800px; width: 100%; margin: 0 auto; padding: 28px 24px 48px; }

        .page-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 28px;
        }
        .page-header h1 { font-size: 22px; font-weight: 600; color: var(--text-dark); }
        .page-header p { font-size: 14px; color: var(--text-muted); font-weight: 400; }

        .back-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; color: var(--primary); text-decoration: none; font-weight: 500;
            padding: 7px 14px; border-radius: 8px; border: 1px solid var(--border);
            background: var(--white); transition: all 0.15s;
        }
        .back-link:hover { background: #f8fafc; border-color: var(--primary); }

        /* ─── Profile Card ─── */
        .profile-card {
            background: var(--white);
            border-radius: 10px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .profile-header {
            background: var(--primary-gradient);
            padding: 28px 28px 24px;
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .profile-avatar {
            width: 64px; height: 64px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 24px;
            flex-shrink: 0;
            border: 3px solid rgba(255,255,255,0.3);
        }

        .profile-meta h2 {
            color: #fff; font-size: 18px; font-weight: 600; margin-bottom: 2px;
        }
        .profile-meta p {
            color: rgba(255,255,255,0.8); font-size: 13px;
        }
        .profile-meta .role-badge {
            display: inline-block;
            margin-top: 6px;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            background: rgba(255,255,255,0.2);
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .profile-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }

        .info-item {
            padding: 16px 28px;
            border-bottom: 1px solid #f1f5f9;
        }
        .info-item:nth-child(odd) { border-right: 1px solid #f1f5f9; }

        .info-label {
            font-size: 11px; font-weight: 600; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px; color: var(--text-dark); font-weight: 500;
        }

        /* ─── Section Panel ─── */
        .section-panel {
            background: var(--white);
            border-radius: 10px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .section-head {
            padding: 16px 28px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title {
            font-size: 15px; font-weight: 600; color: var(--text-dark);
            display: flex; align-items: center; gap: 8px;
        }

        .section-title i { color: var(--primary); font-size: 14px; }

        .section-body { padding: 24px 28px; }

        /* ─── Form ─── */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-row.full { grid-template-columns: 1fr; }

        .form-group { display: flex; flex-direction: column; }

        .form-group label {
            font-size: 12px; font-weight: 600; color: var(--text-muted);
            margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.3px;
        }

        .form-group input {
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            color: var(--text-dark);
            outline: none;
            transition: border-color 0.15s;
            background: var(--white);
        }
        .form-group input:focus { border-color: var(--primary); }

        .form-group input.error { border-color: #dc2626; }

        .field-error {
            font-size: 12px; color: #dc2626; margin-top: 4px;
            display: none;
        }
        .field-error.show { display: block; }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 8px;
        }

        .btn-save {
            padding: 10px 24px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex; align-items: center; gap: 6px;
            transition: background 0.15s;
        }
        .btn-save:hover { background: var(--primary-dark); }
        .btn-save:disabled { opacity: 0.6; cursor: not-allowed; }

        .btn-cancel {
            padding: 10px 20px;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-cancel:hover { background: #f8fafc; color: var(--text-dark); }

        /* ─── Toast ─── */
        .toast {
            position: fixed; top: 80px; right: 24px; z-index: 300;
            background: var(--white); border: 1px solid var(--border);
            border-radius: 8px; padding: 14px 20px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            font-size: 13px; font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            display: flex; align-items: center; gap: 8px;
            transform: translateX(120%); transition: transform 0.3s ease;
        }
        .toast.show { transform: translateX(0); }
        .toast.success { border-left: 3px solid #16a34a; }
        .toast.error { border-left: 3px solid #dc2626; }

        /* ─── Password Strength ─── */
        .pw-strength {
            margin-top: 6px;
            height: 3px;
            border-radius: 3px;
            background: #e2e8f0;
            overflow: hidden;
        }

        .pw-strength-bar {
            height: 100%;
            border-radius: 3px;
            width: 0;
            transition: width 0.3s, background 0.3s;
        }

        .pw-hint {
            font-size: 11px; color: #94a3b8; margin-top: 4px;
        }

        /* ─── Footer ─── */
        .dash-footer {
            width: 100%;
            background: var(--white);
            border-top: 1px solid var(--border);
            padding: 20px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #94a3b8;
            margin-top: 40px;
        }
        .footer-left { display: flex; align-items: center; gap: 6px; }
        .footer-right { font-size: 11px; color: #b0b8c4; }

        /* ─── Animations ─── */
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .anim { animation: fadeIn 0.25s ease forwards; }

        /* ─── Responsive ─── */
        @media (max-width: 768px) {
            .navbar { padding: 12px 4%; flex-wrap: wrap; }
            .navbar h1 { font-size: 15px; }
            .brand-subtitle { font-size: 10px; }
            .nav-actions .nav-link, .nav-user, .btn-logout { display: none; }
            .nav-toggle { display: block; }
            .nav-menu-mobile .nav-link { display: flex; width: 100%; padding: 10px 12px; font-size: 14px; }
            .nav-menu-mobile .mobile-user-section {
                display: flex; align-items: center; justify-content: space-between;
                padding: 10px 12px; margin-top: 4px; border-top: 1px solid rgba(255,255,255,0.1);
            }
            .nav-menu-mobile .mobile-user-section .nav-avatar { width: 30px; height: 30px; font-size: 12px; }
            .nav-menu-mobile .mobile-user-section span { color: rgba(255,255,255,0.9); font-size: 13px; font-weight: 500; }
            .nav-menu-mobile .btn-logout { display: flex; background: rgba(220,38,38,0.7); border-color: transparent; padding: 6px 12px; font-size: 12px; }
            .dash-wrapper { padding: 20px 16px 40px; }
            .page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
            .profile-info-grid { grid-template-columns: 1fr; }
            .info-item:nth-child(odd) { border-right: none; }
            .form-row { grid-template-columns: 1fr; }
            .profile-header { padding: 22px 20px 18px; }
            .section-body { padding: 20px; }
            .section-head { padding: 14px 20px; }
            .dash-footer { flex-direction: column; gap: 6px; text-align: center; padding: 16px 5%; }
        }

        @media (max-width: 400px) {
            .navbar { padding: 10px 3%; }
            .navbar h1 { font-size: 13px; }
            .brand-subtitle { font-size: 9px; }
        }
    </style>
</head>
<body>

    @php
        $isRep       = ($user->account_type ?? '') === 'representative';
        $navOfficeName = '';
        $navRepName    = '';
        if ($isRep && str_contains($user->name, ' - ')) {
            [$navOfficeName, $navRepName] = explode(' - ', $user->name, 2);
        }
        $navDisplayName = $isRep ? $navOfficeName : explode(' ', $user->name)[0];
        $navDisplayRole = $isRep ? 'Representative' : ucfirst($user->role ?? 'User');
    @endphp
    <!-- ─── Navigation Bar ─── -->
    <nav class="navbar">
        <div class="nav-content">
            <div class="brand-text">
                <span class="brand-subtitle">Department of Education</span>
                <h1>Document Tracking System &mdash; <strong>DOCTRAX</strong></h1>
            </div>
        </div>

        <div class="nav-actions">
            <a href="/dashboard" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="/profile" class="nav-link active"><i class="fas fa-user"></i> Profile</a>

            <div class="nav-user">
                <div class="nav-avatar" id="navAvatar">{{ strtoupper(substr($navDisplayName, 0, 1)) }}</div>
                <div class="nav-user-info">
                    <span class="nav-user-name" id="navName" title="{{ $isRep ? $user->name : '' }}">{{ $navDisplayName }}</span>
                    <span class="nav-user-role">{{ $navDisplayRole }}</span>
                </div>
            </div>

            <button onclick="logout()" class="btn-logout" title="Sign Out">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </div>

        <button class="nav-toggle" onclick="toggleMobileNav()"><i class="fas fa-bars"></i></button>

        <div class="nav-menu-mobile" id="mobileNav">
            <a href="/dashboard" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="/profile" class="nav-link active"><i class="fas fa-user"></i> Profile</a>
            <div class="mobile-user-section">
                <div style="display:flex;align-items:center;gap:8px;">
                    <div class="nav-avatar">{{ strtoupper(substr($navDisplayName, 0, 1)) }}</div>
                    <div style="display:flex;flex-direction:column;line-height:1.2;">
                        <span style="color:#fff;font-size:13px;font-weight:600;">{{ $navDisplayName }}</span>
                        @if($isRep && $navRepName)
                            <span style="color:rgba(255,255,255,0.65);font-size:10px;">{{ $navRepName }}</span>
                        @endif
                    </div>
                </div>
                <button onclick="logout()" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </div>
        </div>
    </nav>

    <!-- ─── Content ─── -->
    <div class="dash-wrapper">

        <div class="page-header anim">
            <div>
                <h1>My Profile</h1>
                <p>Manage your account information</p>
            </div>
            <a href="/dashboard" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>

        <!-- Profile Card -->
        <div class="profile-card anim">
            <div class="profile-header">
                <div class="profile-avatar" id="profileAvatar">{{ strtoupper(substr($navDisplayName, 0, 1)) }}</div>
                <div class="profile-meta">
                    @if($isRep)
                        <h2 id="profileName">{{ $navOfficeName }}</h2>
                        @if($navRepName)
                            <p style="font-size:13px;font-weight:500;color:#475569;"><i class="fas fa-user" style="margin-right:4px;"></i>{{ $navRepName }}</p>
                        @endif
                    @else
                        <h2 id="profileName">{{ $user->name }}</h2>
                    @endif
                    <p>{{ $user->email }}</p>
                    <span class="role-badge">{{ $navDisplayRole }}</span>
                </div>
            </div>
            <div class="profile-info-grid">
                @if($isRep)
                <div class="info-item">
                    <div class="info-label">Office / Institution</div>
                    <div class="info-value" id="infoName">{{ $navOfficeName }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Representative Name</div>
                    <div class="info-value">{{ $navRepName ?: '—' }}</div>
                </div>
                @else
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value" id="infoName">{{ $user->name }}</div>
                </div>
                @endif
                <div class="info-item">
                    <div class="info-label">Email Address</div>
                    <div class="info-value" id="infoEmail">{{ $user->email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Mobile Number</div>
                    <div class="info-value" id="infoMobile">{{ $user->mobile ?? '—' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Account Status</div>
                    <div class="info-value" style="color: {{ $user->status === 'active' ? '#16a34a' : '#9a3412' }};">{{ ucfirst($user->status) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Account Type</div>
                    <div class="info-value">{{ $isRep ? 'Representative / Office' : 'Individual' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Member Since</div>
                    <div class="info-value">{{ $user->created_at->format('F j, Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <div class="section-panel anim">
            <div class="section-head">
                <div class="section-title"><i class="fas fa-edit"></i> Edit Profile</div>
            </div>
            <div class="section-body">
                <form id="profileForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ $user->name }}" required>
                            <div class="field-error" id="err-name"></div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ $user->email }}" required>
                            <div class="field-error" id="err-email"></div>
                        </div>
                    </div>
                    <div class="form-row full">
                        <div class="form-group">
                            <label for="mobile">Mobile Number</label>
                            <input type="text" id="mobile" name="mobile" value="{{ $user->mobile ?? '' }}" placeholder="e.g. 09171234567">
                            <div class="field-error" id="err-mobile"></div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-save" id="btnSaveProfile">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="section-panel anim">
            <div class="section-head">
                <div class="section-title"><i class="fas fa-lock"></i> Change Password</div>
            </div>
            <div class="section-body">
                <form id="passwordForm">
                    <div class="form-row full">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" required>
                            <div class="field-error" id="err-current_password"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" id="password" name="password" required>
                            <div class="pw-strength"><div class="pw-strength-bar" id="pwBar"></div></div>
                            <div class="pw-hint" id="pwHint">Min 8 chars, uppercase, lowercase, number</div>
                            <div class="field-error" id="err-password"></div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required>
                            <div class="field-error" id="err-password_confirmation"></div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-save" id="btnChangePw">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Toast -->
    <div class="toast" id="toast"></div>

    <footer class="dash-footer">
        <div class="footer-left">
            <span>&copy; {{ date('Y') }} DepEd Document Tracking System</span>
        </div>
        <div class="footer-right">
            Developed by Raymond Bautista
        </div>
    </footer>

    <script>
    (function() {
        var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // ─── Toast ───
        function showToast(msg, type) {
            var t = document.getElementById('toast');
            t.textContent = msg;
            t.className = 'toast ' + type + ' show';
            setTimeout(function() { t.classList.remove('show'); }, 3000);
        }

        // ─── Clear Errors ───
        function clearErrors(prefix) {
            document.querySelectorAll('[id^="err-"]').forEach(function(el) {
                if (!prefix || el.id.indexOf(prefix) >= 0) {
                    el.textContent = '';
                    el.classList.remove('show');
                }
            });
            document.querySelectorAll('input.error').forEach(function(el) { el.classList.remove('error'); });
        }

        function showFieldErrors(errors) {
            for (var field in errors) {
                var el = document.getElementById('err-' + field);
                var inp = document.getElementById(field);
                if (el) {
                    el.textContent = errors[field][0];
                    el.classList.add('show');
                }
                if (inp) inp.classList.add('error');
            }
        }

        // ─── Save Profile ───
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            clearErrors();

            var btn = document.getElementById('btnSaveProfile');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            fetch('/api/profile', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    mobile: document.getElementById('mobile').value
                })
            })
            .then(function(r) { return r.json().then(function(d) { return { ok: r.ok, data: d }; }); })
            .then(function(res) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Save Changes';

                if (res.ok && res.data.success) {
                    showToast(res.data.message, 'success');

                    // Update all displayed info in real-time
                    var u = res.data.user;
                    document.getElementById('infoName').textContent = u.name;
                    document.getElementById('infoEmail').textContent = u.email;
                    document.getElementById('infoMobile').textContent = u.mobile || '—';
                    document.getElementById('profileName').textContent = u.name;
                    document.getElementById('profileAvatar').textContent = u.name.charAt(0).toUpperCase();
                    document.getElementById('navAvatar').textContent = u.name.charAt(0).toUpperCase();
                    document.getElementById('navName').textContent = u.name.split(' ')[0];
                } else {
                    if (res.data.errors) {
                        showFieldErrors(res.data.errors);
                    }
                    showToast(res.data.message || 'Failed to update.', 'error');
                }
            })
            .catch(function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                showToast('Something went wrong.', 'error');
            });
        });

        // ─── Change Password ───
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            clearErrors();

            var btn = document.getElementById('btnChangePw');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Changing...';

            fetch('/api/profile/password', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    current_password: document.getElementById('current_password').value,
                    password: document.getElementById('password').value,
                    password_confirmation: document.getElementById('password_confirmation').value
                })
            })
            .then(function(r) { return r.json().then(function(d) { return { ok: r.ok, data: d }; }); })
            .then(function(res) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-key"></i> Change Password';

                if (res.ok && res.data.success) {
                    showToast(res.data.message, 'success');
                    document.getElementById('passwordForm').reset();
                    document.getElementById('pwBar').style.width = '0';
                    document.getElementById('pwHint').textContent = 'Min 8 chars, uppercase, lowercase, number';
                } else {
                    if (res.data.errors) {
                        showFieldErrors(res.data.errors);
                    }
                    showToast(res.data.message || 'Failed to change password.', 'error');
                }
            })
            .catch(function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-key"></i> Change Password';
                showToast('Something went wrong.', 'error');
            });
        });

        // ─── Password Strength Indicator ───
        document.getElementById('password').addEventListener('input', function() {
            var pw = this.value;
            var bar = document.getElementById('pwBar');
            var hint = document.getElementById('pwHint');
            var score = 0;

            if (pw.length >= 8) score++;
            if (/[a-z]/.test(pw)) score++;
            if (/[A-Z]/.test(pw)) score++;
            if (/[0-9]/.test(pw)) score++;
            if (/[^a-zA-Z0-9]/.test(pw)) score++;

            var pct = (score / 5) * 100;
            bar.style.width = pct + '%';

            if (score <= 1) { bar.style.background = '#dc2626'; hint.textContent = 'Weak'; }
            else if (score <= 2) { bar.style.background = '#f59e0b'; hint.textContent = 'Fair'; }
            else if (score <= 3) { bar.style.background = '#f59e0b'; hint.textContent = 'Good'; }
            else if (score <= 4) { bar.style.background = '#16a34a'; hint.textContent = 'Strong'; }
            else { bar.style.background = '#16a34a'; hint.textContent = 'Very strong'; }

            if (pw.length === 0) {
                bar.style.width = '0';
                hint.textContent = 'Min 8 chars, uppercase, lowercase, number';
            }
        });

        // ─── Mobile Nav ───
        window.toggleMobileNav = function() {
            document.getElementById('mobileNav').classList.toggle('open');
        };

        // ─── Logout ───
        window.logout = function() {
            fetch('/api/logout', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
            }).then(function() { window.location.href = '/login'; })
              .catch(function() { window.location.href = '/login'; });
        };
    })();
    </script>
</body>
</html>
