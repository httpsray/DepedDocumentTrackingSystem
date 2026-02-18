<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - DepEd DOCTRAX</title>
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
            background: var(--primary);
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

        .nav-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-subtitle {
            font-size: 11px;
            opacity: 0.85;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 400;
        }

        .navbar h1 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

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

        .nav-link:hover {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }

        .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: 8px;
            padding-left: 16px;
            border-left: 1px solid rgba(255,255,255,0.2);
        }

        .nav-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
        }

        .nav-user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .nav-user-name {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
        }

        .nav-user-role {
            font-size: 10px;
            color: rgba(255,255,255,0.7);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-logout {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.9);
            cursor: pointer;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: rgba(220, 38, 38, 0.85);
            border-color: rgba(220, 38, 38, 0.9);
            color: #fff;
        }

        /* Mobile Nav Toggle */
        .nav-toggle {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            padding: 4px;
        }

        .nav-menu-mobile {
            display: none;
            width: 100%;
            padding-top: 12px;
            border-top: 1px solid rgba(255,255,255,0.15);
            margin-top: 12px;
            flex-direction: column;
            gap: 4px;
        }

        .nav-menu-mobile.open {
            display: flex;
        }

        /* ─── Main Content ─── */
        .dash-wrapper {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 28px 24px 48px;
        }

        /* ─── Top Bar ─── */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .greeting-section h1 {
            font-size: 22px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 2px;
        }

        .greeting-section p {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 400;
        }

        /* ─── Live Clock ─── */
        .live-clock {
            display: flex;
            align-items: center;
            gap: 14px;
            background: var(--white);
            padding: 10px 18px;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .clock-time-display {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            font-variant-numeric: tabular-nums;
            line-height: 1;
        }

        .clock-time-display .seconds {
            font-size: 14px;
            color: #9ca3af;
            font-weight: 600;
        }

        .clock-time-display .period {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            margin-left: 3px;
            vertical-align: top;
        }

        .clock-sep {
            width: 1px;
            height: 28px;
            background: var(--border);
        }

        .clock-date-display {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 400;
            line-height: 1.4;
        }

        .clock-date-display .day {
            font-weight: 600;
            color: var(--text-dark);
            display: block;
        }

        /* ─── Stats Cards ─── */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--white);
            border-radius: 10px;
            padding: 20px 22px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .s-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .s-icon.blue { background: rgba(0, 86, 179, 0.1); color: var(--primary); }
        .s-icon.orange { background: rgba(252, 163, 17, 0.12); color: #d97706; }
        .s-icon.green { background: rgba(22, 163, 74, 0.1); color: #16a34a; }
        .s-icon.purple { background: rgba(139, 92, 246, 0.1); color: #7c3aed; }

        .s-num {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1;
            margin-bottom: 2px;
        }

        .s-label { font-size: 13px; color: var(--text-muted); font-weight: 400; }

        /* ─── Content Grid ─── */
        .grid {
            display: grid;
            grid-template-columns: 1.7fr 1fr;
            gap: 20px;
        }

        .panel {
            background: var(--white);
            border-radius: 10px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            border-bottom: 1px solid #f1f5f9;
        }

        .panel-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .panel-link {
            font-size: 13px;
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: color 0.15s;
        }

        .panel-link:hover { color: var(--primary-dark); }

        /* ─── Table ─── */
        .dtable { width: 100%; border-collapse: collapse; }

        .dtable th {
            text-align: left;
            padding: 10px 24px;
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #fafbfc;
        }

        .dtable td {
            padding: 13px 24px;
            border-top: 1px solid #f1f5f9;
            font-size: 13px;
            color: #374151;
        }

        .dtable tbody tr { transition: background 0.1s; }
        .dtable tbody tr:hover { background: #f8fafc; }

        .t-num { font-weight: 600; color: var(--primary); font-size: 13px; }

        .pill {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .pill.pending { background: #fff7ed; color: #9a3412; }
        .pill.forwarded { background: #eff6ff; color: #1e40af; }
        .pill.completed { background: #f0fdf4; color: #166534; }
        .pill.other { background: #f3f4f6; color: #4b5563; }

        .t-date { font-size: 12px; color: #94a3b8; }
        .t-user { font-size: 12px; color: var(--text-muted); }

        .empty-state {
            padding: 48px 24px;
            text-align: center;
            color: #94a3b8;
        }

        .empty-state i { font-size: 32px; margin-bottom: 10px; display: block; color: #cbd5e1; }
        .empty-state p { font-size: 14px; }

        /* ─── Quick Actions ─── */
        .actions-list { padding: 10px; display: flex; flex-direction: column; gap: 6px; }

        .act {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 8px;
            text-decoration: none;
            border: 1px solid transparent;
            transition: background 0.15s;
        }

        .act:hover {
            background: #f8fafc;
            border-color: var(--border);
        }

        .act-icon {
            width: 38px; height: 38px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .act-body { flex: 1; }
        .act-title { font-size: 14px; font-weight: 600; color: var(--text-dark); margin-bottom: 2px; }
        .act-desc { font-size: 12px; color: #94a3b8; line-height: 1.4; }
        .act-arrow { color: #cbd5e1; font-size: 13px; }
        .act:hover .act-arrow { color: var(--text-muted); }

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

        .footer-left {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .footer-right {
            font-size: 11px;
            color: #b0b8c4;
        }

        @media (max-width: 768px) {
            .dash-footer {
                flex-direction: column;
                gap: 6px;
                text-align: center;
                padding: 16px 5%;
            }
        }

        /* ─── Animations ─── */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .anim { animation: fadeIn 0.25s ease forwards; }

        /* ─── Responsive ─── */
        @media (max-width: 1024px) {
            .grid { grid-template-columns: 1fr; }
            .stats { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 12px 4%;
                flex-wrap: wrap;
            }

            .navbar h1 { font-size: 15px; }
            .brand-subtitle { font-size: 10px; }

            .nav-actions .nav-link,
            .nav-user,
            .btn-logout { display: none; }

            .nav-toggle { display: block; }

            .nav-menu-mobile .nav-link {
                display: flex;
                width: 100%;
                padding: 10px 12px;
                font-size: 14px;
            }

            .nav-menu-mobile .mobile-user-section {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 10px 12px;
                margin-top: 4px;
                border-top: 1px solid rgba(255,255,255,0.1);
            }

            .nav-menu-mobile .mobile-user-section .nav-avatar {
                width: 30px; height: 30px;
                font-size: 12px;
            }

            .nav-menu-mobile .mobile-user-section span {
                color: rgba(255,255,255,0.9);
                font-size: 13px;
                font-weight: 500;
            }

            .nav-menu-mobile .btn-logout {
                display: flex;
                background: rgba(220,38,38,0.7);
                border-color: transparent;
                padding: 6px 12px;
                font-size: 12px;
            }

            .dash-wrapper { padding: 20px 16px 40px; }

            .top-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 14px;
            }

            .greeting-section h1 { font-size: 20px; }

            .live-clock { width: 100%; }

            .stats { grid-template-columns: 1fr; }
        }

        @media (max-width: 400px) {
            .navbar { padding: 10px 3%; }
            .navbar h1 { font-size: 13px; }
            .brand-subtitle { font-size: 9px; }
            .greeting-section h1 { font-size: 18px; }
        }
    </style>
</head>
<body>

    <!-- ─── Navigation Bar ─── -->
    <nav class="navbar">
        <div class="nav-content">
            <div class="brand-text">
                <span class="brand-subtitle">Department of Education</span>
                <h1>Document Tracking System &mdash; <strong>DOCTRAX</strong></h1>
            </div>
        </div>

        <div class="nav-actions">
            <a href="/dashboard" class="nav-link active"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="/admin/users" class="nav-link"><i class="fas fa-users"></i> Users</a>
            <a href="/admin/documents" class="nav-link"><i class="fas fa-folder-open"></i> Documents</a>

            <div class="nav-user">
                <div class="nav-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div class="nav-user-info">
                    <span class="nav-user-name">{{ explode(' ', $user->name)[0] }}</span>
                    <span class="nav-user-role">Admin</span>
                </div>
            </div>

            <button onclick="logout()" class="btn-logout" title="Sign Out">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </div>

        <button class="nav-toggle" onclick="toggleMobileNav()"><i class="fas fa-bars"></i></button>

        <div class="nav-menu-mobile" id="mobileNav">
            <a href="/dashboard" class="nav-link active"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="/admin/users" class="nav-link"><i class="fas fa-users"></i> Users</a>
            <a href="/admin/documents" class="nav-link"><i class="fas fa-folder-open"></i> Documents</a>
            <div class="mobile-user-section">
                <div style="display:flex;align-items:center;gap:8px;">
                    <div class="nav-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <span>{{ explode(' ', $user->name)[0] }}</span>
                </div>
                <button onclick="logout()" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </div>
        </div>
    </nav>

    <!-- ─── Dashboard Content ─── -->
    <div class="dash-wrapper">

        <!-- Top Bar -->
        <div class="top-bar anim">
            <div class="greeting-section">
                <h1>Admin Dashboard</h1>
                <p>{{ now()->format('l, F j, Y') }}</p>
            </div>

            <div class="live-clock">
                <div class="clock-time-display">
                    <span id="c-h">--</span>:<span id="c-m">--</span>:<span class="seconds" id="c-s">--</span>
                    <span class="period" id="c-p">--</span>
                </div>
                <div class="clock-sep"></div>
                <div class="clock-date-display">
                    <span class="day" id="c-day">Loading...</span>
                    <span id="c-date"></span>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats">
            <div class="stat-card anim">
                <div class="s-icon blue"><i class="fas fa-users"></i></div>
                <div class="s-data">
                    <div class="s-num">{{ $stats['total_users'] }}</div>
                    <div class="s-label">Total Users</div>
                </div>
            </div>
            <div class="stat-card anim">
                <div class="s-icon purple"><i class="fas fa-file-alt"></i></div>
                <div class="s-data">
                    <div class="s-num">{{ $stats['total_documents'] }}</div>
                    <div class="s-label">Total Documents</div>
                </div>
            </div>
            <div class="stat-card anim">
                <div class="s-icon orange"><i class="fas fa-clock"></i></div>
                <div class="s-data">
                    <div class="s-num">{{ $stats['pending_docs'] }}</div>
                    <div class="s-label">Pending</div>
                </div>
            </div>
            <div class="stat-card anim">
                <div class="s-icon green"><i class="fas fa-check-circle"></i></div>
                <div class="s-data">
                    <div class="s-num">{{ $stats['completed_docs'] }}</div>
                    <div class="s-label">Completed</div>
                </div>
            </div>
        </div>

        <!-- Grid -->
        <div class="grid">

            <!-- Recent Submissions -->
            <div class="panel anim">
                <div class="panel-head">
                    <div class="panel-title">Recent Submissions</div>
                </div>

                @if($recentDocs->count() > 0)
                <table class="dtable">
                    <thead>
                        <tr>
                            <th>Tracking No.</th>
                            <th>Subject</th>
                            <th>Submitted By</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentDocs as $doc)
                        <tr>
                            <td><span class="t-num">{{ $doc->tracking_number }}</span></td>
                            <td>{{ $doc->subject }}</td>
                            <td class="t-user">{{ $doc->user ? $doc->user->name : ($doc->sender_name ?? 'Guest') }}</td>
                            <td>
                                @php
                                    $sc = match($doc->status) {
                                        'received' => 'pending',
                                        'forwarded' => 'forwarded',
                                        'completed' => 'completed',
                                        default => 'other',
                                    };
                                @endphp
                                <span class="pill {{ $sc }}">
                                    {{ ucfirst($doc->status) }}
                                </span>
                            </td>
                            <td class="t-date">{{ $doc->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No documents submitted yet.</p>
                </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="panel anim">
                <div class="panel-head">
                    <div class="panel-title">Quick Actions</div>
                </div>
                <div class="actions-list">
                    <a href="/admin/users" class="act">
                        <div class="act-icon" style="background:rgba(0,86,179,0.1);color:var(--primary);"><i class="fas fa-users-cog"></i></div>
                        <div class="act-body">
                            <div class="act-title">Manage Users</div>
                            <div class="act-desc">View &amp; manage accounts</div>
                        </div>
                        <i class="fas fa-chevron-right act-arrow"></i>
                    </a>
                    <a href="/admin/documents" class="act">
                        <div class="act-icon" style="background:rgba(252,163,17,0.1);color:#d97706;"><i class="fas fa-folder-open"></i></div>
                        <div class="act-body">
                            <div class="act-title">All Documents</div>
                            <div class="act-desc">Browse all submissions</div>
                        </div>
                        <i class="fas fa-chevron-right act-arrow"></i>
                    </a>
                    <a href="/admin/users?status=pending" class="act">
                        <div class="act-icon" style="background:#fff7ed;color:#9a3412;"><i class="fas fa-user-clock"></i></div>
                        <div class="act-body">
                            <div class="act-title">Pending Accounts</div>
                            <div class="act-desc">Accounts waiting for activation</div>
                        </div>
                        <i class="fas fa-chevron-right act-arrow"></i>
                    </a>
                    <a href="/admin/documents?status=received" class="act">
                        <div class="act-icon" style="background:#f1f5f9;color:#475569;"><i class="fas fa-inbox"></i></div>
                        <div class="act-body">
                            <div class="act-title">Pending Documents</div>
                            <div class="act-desc">Documents awaiting processing</div>
                        </div>
                        <i class="fas fa-chevron-right act-arrow"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>

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
        // ─── Clock ───
        function tick() {
            var n = new Date();
            var h = n.getHours(), m = n.getMinutes(), s = n.getSeconds();
            var p = h >= 12 ? 'PM' : 'AM';
            var h12 = h % 12 || 12;

            document.getElementById('c-h').textContent = String(h12).padStart(2, '0');
            document.getElementById('c-m').textContent = String(m).padStart(2, '0');
            document.getElementById('c-s').textContent = String(s).padStart(2, '0');
            document.getElementById('c-p').textContent = p;

            var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
            var mos = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            document.getElementById('c-day').textContent = days[n.getDay()];
            document.getElementById('c-date').textContent = mos[n.getMonth()] + ' ' + n.getDate() + ', ' + n.getFullYear();
        }
        tick(); setInterval(tick, 1000);

        // ─── Mobile Nav ───
        window.toggleMobileNav = function() {
            document.getElementById('mobileNav').classList.toggle('open');
        };

        // ─── Logout ───
        window.logout = function() {
            var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch('/api/logout', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
            }).then(function() {
                window.location.href = '/login';
            }).catch(function() {
                window.location.href = '/login';
            });
        };
    })();
    </script>
</body>
</html>
