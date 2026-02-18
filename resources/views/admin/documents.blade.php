<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>All Documents - DepEd DOCTRAX</title>
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
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px;
        }

        .nav-user-info { display: flex; flex-direction: column; line-height: 1.2; }
        .nav-user-name { font-size: 13px; font-weight: 600; color: #fff; }
        .nav-user-role { font-size: 10px; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 0.5px; }

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
            display: flex; align-items: center; gap: 6px;
            transition: all 0.2s;
        }
        .btn-logout:hover { background: rgba(220, 38, 38, 0.85); border-color: rgba(220, 38, 38, 0.9); color: #fff; }

        .nav-toggle { display: none; background: none; border: none; color: #fff; font-size: 20px; cursor: pointer; padding: 4px; }
        .nav-menu-mobile { display: none; width: 100%; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.15); margin-top: 12px; flex-direction: column; gap: 4px; }
        .nav-menu-mobile.open { display: flex; }

        /* ─── Main Content ─── */
        .dash-wrapper { max-width: 1200px; width: 100%; margin: 0 auto; padding: 28px 24px 48px; }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .page-header h1 { font-size: 22px; font-weight: 600; color: var(--text-dark); }
        .page-header p { font-size: 14px; color: var(--text-muted); font-weight: 400; }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            padding: 7px 14px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--white);
            transition: all 0.15s;
        }
        .back-link:hover { background: #f8fafc; border-color: var(--primary); }

        /* ─── Stats Row ─── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .mini-stat {
            background: var(--white);
            border-radius: 8px;
            padding: 14px 18px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mini-stat-icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .mini-stat-num { font-size: 18px; font-weight: 700; color: var(--text-dark); line-height: 1; margin-bottom: 1px; }
        .mini-stat-label { font-size: 12px; color: var(--text-muted); }

        /* ─── Filters ─── */
        .filters {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-input {
            padding: 9px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            font-size: 13px;
            background: var(--white);
            color: var(--text-dark);
            min-width: 260px;
            outline: none;
            transition: border-color 0.15s;
        }
        .filter-input:focus { border-color: var(--primary); }

        .filter-select {
            padding: 9px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            font-size: 13px;
            background: var(--white);
            color: var(--text-dark);
            outline: none;
            cursor: pointer;
            transition: border-color 0.15s;
        }
        .filter-select:focus { border-color: var(--primary); }

        .filter-btn {
            padding: 9px 18px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            display: flex; align-items: center; gap: 6px;
            transition: background 0.15s;
        }
        .filter-btn:hover { background: var(--primary-dark); }

        .filter-clear {
            padding: 9px 14px;
            background: none;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            font-size: 13px;
            color: var(--text-muted);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
        }
        .filter-clear:hover { background: #f8fafc; color: var(--text-dark); }

        /* ─── Panel ─── */
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

        .panel-title { font-size: 14px; font-weight: 600; color: var(--text-dark); }
        .panel-badge {
            background: rgba(0, 86, 179, 0.08);
            color: var(--primary);
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

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
        .t-user { font-size: 12px; color: var(--text-muted); }
        .t-date { font-size: 12px; color: #94a3b8; }

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

        .empty-state {
            padding: 48px 24px;
            text-align: center;
            color: #94a3b8;
        }
        .empty-state i { font-size: 32px; margin-bottom: 10px; display: block; color: #cbd5e1; }
        .empty-state p { font-size: 14px; }

        /* ─── Action Buttons ─── */
        .action-btns { display: flex; gap: 6px; }

        .btn-sm {
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-family: inherit;
            font-weight: 500;
            border: 1px solid var(--border);
            background: var(--white);
            color: var(--text-dark);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.15s;
        }
        .btn-sm:hover { background: #f8fafc; }

        .btn-sm.status-btn { color: var(--primary); border-color: #bfdbfe; }
        .btn-sm.status-btn:hover { background: #eff6ff; }

        .btn-sm.delete { color: #991b1b; border-color: #fecaca; }
        .btn-sm.delete:hover { background: #fef2f2; }

        /* ─── Status Dropdown ─── */
        .status-dropdown {
            position: relative;
            display: inline-block;
        }

        .status-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 4px;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            z-index: 50;
            min-width: 160px;
            overflow: hidden;
        }

        .status-menu.show { display: block; }

        .status-opt {
            display: block;
            width: 100%;
            padding: 10px 16px;
            border: none;
            background: none;
            font-family: inherit;
            font-size: 13px;
            text-align: left;
            cursor: pointer;
            color: var(--text-dark);
            transition: background 0.1s;
        }
        .status-opt:hover { background: #f8fafc; }
        .status-opt i { width: 18px; margin-right: 6px; }

        /* ─── Pagination ─── */
        .pagination-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 24px;
            border-top: 1px solid #f1f5f9;
            font-size: 13px;
            color: var(--text-muted);
        }

        .pagination-links { display: flex; gap: 4px; }

        .page-btn {
            padding: 6px 12px;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 12px;
            font-family: inherit;
            background: var(--white);
            color: var(--text-dark);
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.15s;
        }
        .page-btn:hover { background: #f8fafc; border-color: var(--primary); color: var(--primary); }
        .page-btn.active { background: var(--primary); color: #fff; border-color: var(--primary); }
        .page-btn.disabled { opacity: 0.4; cursor: default; pointer-events: none; }

        /* ─── Modal ─── */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.4);
            z-index: 200;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.show { display: flex; }

        .modal {
            background: var(--white);
            border-radius: 12px;
            width: 90%;
            max-width: 520px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        .modal-head {
            padding: 18px 24px;
            border-bottom: 1px solid #f1f5f9;
        }
        .modal-head h3 { font-size: 16px; font-weight: 600; color: var(--text-dark); }

        .modal-body { padding: 20px 24px; }
        .modal-body p { font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 4px; }
        .modal-body strong { color: var(--text-dark); }

        .modal-detail {
            margin-top: 12px;
            background: #fafbfc;
            border-radius: 8px;
            padding: 14px 16px;
        }

        .modal-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 13px;
        }

        .modal-detail-row .label { color: var(--text-muted); }
        .modal-detail-row .value { color: var(--text-dark); font-weight: 500; }

        .modal-foot {
            padding: 14px 24px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .modal-btn {
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
            border: 1px solid var(--border);
            background: var(--white);
            color: var(--text-dark);
        }
        .modal-btn:hover { background: #f8fafc; }

        .modal-btn.danger { background: #dc2626; color: #fff; border-color: #dc2626; }
        .modal-btn.danger:hover { background: #b91c1c; }

        /* ─── Toast ─── */
        .toast {
            position: fixed;
            top: 80px;
            right: 24px;
            z-index: 300;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 14px 20px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
            transform: translateX(120%);
            transition: transform 0.3s ease;
        }
        .toast.show { transform: translateX(0); }
        .toast.success { border-left: 3px solid #16a34a; }
        .toast.error { border-left: 3px solid #dc2626; }

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
        @media (max-width: 1024px) {
            .stats-row { grid-template-columns: repeat(2, 1fr); }
        }

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
            .filters { flex-direction: column; }
            .filter-input { min-width: 100%; }
            .stats-row { grid-template-columns: 1fr 1fr; }
            .dtable th:nth-child(3), .dtable td:nth-child(3) { display: none; }
            .dtable th:nth-child(5), .dtable td:nth-child(5) { display: none; }
            .dash-footer { flex-direction: column; gap: 6px; text-align: center; padding: 16px 5%; }
        }

        @media (max-width: 400px) {
            .navbar { padding: 10px 3%; }
            .navbar h1 { font-size: 13px; }
            .brand-subtitle { font-size: 9px; }
            .stats-row { grid-template-columns: 1fr; }
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
            <a href="/dashboard" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="/admin/users" class="nav-link"><i class="fas fa-users"></i> Users</a>
            <a href="/admin/documents" class="nav-link active"><i class="fas fa-folder-open"></i> Documents</a>

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
            <a href="/dashboard" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="/admin/users" class="nav-link"><i class="fas fa-users"></i> Users</a>
            <a href="/admin/documents" class="nav-link active"><i class="fas fa-folder-open"></i> Documents</a>
            <div class="mobile-user-section">
                <div style="display:flex;align-items:center;gap:8px;">
                    <div class="nav-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <span>{{ explode(' ', $user->name)[0] }}</span>
                </div>
                <button onclick="logout()" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </div>
        </div>
    </nav>

    <!-- ─── Content ─── -->
    <div class="dash-wrapper">

        <div class="page-header anim">
            <div>
                <h1>All Documents</h1>
                <p>Browse and manage all submitted documents</p>
            </div>
            <a href="/dashboard" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>

        <!-- Stats Row -->
        <div class="stats-row anim">
            <div class="mini-stat">
                <div class="mini-stat-icon" style="background:rgba(139,92,246,0.1);color:#7c3aed;"><i class="fas fa-file-alt"></i></div>
                <div>
                    <div class="mini-stat-num">{{ $stats['total'] }}</div>
                    <div class="mini-stat-label">Total</div>
                </div>
            </div>
            <div class="mini-stat">
                <div class="mini-stat-icon" style="background:rgba(252,163,17,0.12);color:#d97706;"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="mini-stat-num">{{ $stats['received'] }}</div>
                    <div class="mini-stat-label">Received</div>
                </div>
            </div>
            <div class="mini-stat">
                <div class="mini-stat-icon" style="background:rgba(0,86,179,0.1);color:var(--primary);"><i class="fas fa-share"></i></div>
                <div>
                    <div class="mini-stat-num">{{ $stats['forwarded'] }}</div>
                    <div class="mini-stat-label">Forwarded</div>
                </div>
            </div>
            <div class="mini-stat">
                <div class="mini-stat-icon" style="background:rgba(22,163,74,0.1);color:#16a34a;"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="mini-stat-num">{{ $stats['completed'] }}</div>
                    <div class="mini-stat-label">Completed</div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <form class="filters anim" method="GET" action="/admin/documents">
            <input type="text" name="search" class="filter-input" placeholder="Search tracking no., subject, or sender..." value="{{ $filters['search'] }}">
            <select name="status" class="filter-select">
                <option value="">All Status</option>
                <option value="received" {{ $filters['status'] === 'received' ? 'selected' : '' }}>Received</option>
                <option value="forwarded" {{ $filters['status'] === 'forwarded' ? 'selected' : '' }}>Forwarded</option>
                <option value="completed" {{ $filters['status'] === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            <button type="submit" class="filter-btn"><i class="fas fa-search"></i> Search</button>
            @if($filters['search'] || $filters['status'])
                <a href="/admin/documents" class="filter-clear">Clear</a>
            @endif
        </form>

        <!-- Documents Table -->
        <div class="panel anim">
            <div class="panel-head">
                <div class="panel-title">Documents</div>
                <span class="panel-badge">{{ $documents->total() }} total</span>
            </div>

            @if($documents->count() > 0)
            <table class="dtable">
                <thead>
                    <tr>
                        <th>Tracking No.</th>
                        <th>Subject</th>
                        <th>Submitted By</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $doc)
                    <tr id="doc-row-{{ $doc->id }}">
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
                            <span class="pill {{ $sc }}" id="doc-status-{{ $doc->id }}">{{ ucfirst($doc->status) }}</span>
                        </td>
                        <td class="t-date">{{ $doc->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-sm" onclick="viewDoc({{ $doc->id }})" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="status-dropdown">
                                    <button class="btn-sm status-btn" onclick="toggleStatusMenu({{ $doc->id }})" title="Update Status">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                    <div class="status-menu" id="status-menu-{{ $doc->id }}">
                                        <button class="status-opt" onclick="updateDocStatus({{ $doc->id }}, 'received')">
                                            <i class="fas fa-inbox" style="color:#9a3412;"></i> Received
                                        </button>
                                        <button class="status-opt" onclick="updateDocStatus({{ $doc->id }}, 'forwarded')">
                                            <i class="fas fa-share" style="color:#1e40af;"></i> Forwarded
                                        </button>
                                        <button class="status-opt" onclick="updateDocStatus({{ $doc->id }}, 'completed')">
                                            <i class="fas fa-check-circle" style="color:#166534;"></i> Completed
                                        </button>
                                    </div>
                                </div>
                                <button class="btn-sm delete" onclick="confirmDelete({{ $doc->id }}, '{{ $doc->tracking_number }}')" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($documents->hasPages())
            <div class="pagination-bar">
                <span>Showing {{ $documents->firstItem() }}–{{ $documents->lastItem() }} of {{ $documents->total() }}</span>
                <div class="pagination-links">
                    @if($documents->onFirstPage())
                        <span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $documents->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    @foreach($documents->getUrlRange(1, $documents->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page == $documents->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                    @endforeach

                    @if($documents->hasMorePages())
                        <a href="{{ $documents->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
            @endif

            @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>No documents found.</p>
            </div>
            @endif
        </div>

    </div>

    <!-- View Document Modal -->
    <div class="modal-overlay" id="viewModal">
        <div class="modal">
            <div class="modal-head">
                <h3>Document Details</h3>
            </div>
            <div class="modal-body">
                <div class="modal-detail" id="viewDocContent">
                    <p style="text-align:center;color:#94a3b8;">Loading...</p>
                </div>
            </div>
            <div class="modal-foot">
                <button class="modal-btn" onclick="closeViewModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <div class="modal-head">
                <h3>Delete Document</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete document <strong id="deleteDocNum"></strong>?</p>
                <p style="font-size:12px;color:#94a3b8;margin-top:8px;">This action cannot be undone.</p>
            </div>
            <div class="modal-foot">
                <button class="modal-btn" onclick="closeDeleteModal()">Cancel</button>
                <button class="modal-btn danger" id="confirmDeleteBtn">Delete</button>
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

    <!-- Document data for view modal -->
    <script type="application/json" id="docsData">
        @php
            $docData = [];
            foreach($documents as $doc) {
                $docData[$doc->id] = [
                    'tracking_number' => $doc->tracking_number,
                    'subject' => $doc->subject,
                    'type' => $doc->type ?? 'General',
                    'status' => ucfirst($doc->status),
                    'sender_name' => $doc->user ? $doc->user->name : ($doc->sender_name ?? 'Guest'),
                    'sender_office' => $doc->sender_office ?? '—',
                    'recipient_office' => $doc->recipient_office ?? '—',
                    'description' => $doc->description ?? '—',
                    'date' => $doc->created_at->format('M d, Y h:i A'),
                ];
            }
        @endphp
        {!! json_encode($docData) !!}
    </script>

    <script>
    (function() {
        var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var docsData = JSON.parse(document.getElementById('docsData').textContent);

        // ─── Toast ───
        function showToast(msg, type) {
            var t = document.getElementById('toast');
            t.textContent = msg;
            t.className = 'toast ' + type + ' show';
            setTimeout(function() { t.classList.remove('show'); }, 3000);
        }

        // ─── View Document ───
        window.viewDoc = function(id) {
            var d = docsData[id];
            if (!d) return;

            var html = '<div class="modal-detail-row"><span class="label">Tracking No.</span><span class="value">' + d.tracking_number + '</span></div>' +
                '<div class="modal-detail-row"><span class="label">Subject</span><span class="value">' + d.subject + '</span></div>' +
                '<div class="modal-detail-row"><span class="label">Type</span><span class="value">' + d.type + '</span></div>' +
                '<div class="modal-detail-row"><span class="label">Status</span><span class="value">' + d.status + '</span></div>' +
                '<div class="modal-detail-row"><span class="label">Submitted By</span><span class="value">' + d.sender_name + '</span></div>' +
                '<div class="modal-detail-row"><span class="label">Sender Office</span><span class="value">' + d.sender_office + '</span></div>' +
                '<div class="modal-detail-row"><span class="label">Recipient Office</span><span class="value">' + d.recipient_office + '</span></div>' +
                '<div class="modal-detail-row"><span class="label">Description</span><span class="value">' + d.description + '</span></div>' +
                '<div class="modal-detail-row"><span class="label">Date Submitted</span><span class="value">' + d.date + '</span></div>';

            document.getElementById('viewDocContent').innerHTML = html;
            document.getElementById('viewModal').classList.add('show');
        };

        window.closeViewModal = function() {
            document.getElementById('viewModal').classList.remove('show');
        };

        // ─── Status Dropdown ───
        window.toggleStatusMenu = function(id) {
            // Close all others first
            document.querySelectorAll('.status-menu.show').forEach(function(m) { m.classList.remove('show'); });
            var menu = document.getElementById('status-menu-' + id);
            menu.classList.toggle('show');
        };

        // Close dropdowns on outside click
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.status-dropdown')) {
                document.querySelectorAll('.status-menu.show').forEach(function(m) { m.classList.remove('show'); });
            }
        });

        // ─── Update Document Status ───
        window.updateDocStatus = function(id, status) {
            // Close the dropdown
            document.querySelectorAll('.status-menu.show').forEach(function(m) { m.classList.remove('show'); });

            fetch('/api/admin/documents/' + id, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: JSON.stringify({ status: status })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(function() { window.location.reload(); }, 800);
                } else {
                    showToast(data.message || 'Failed to update.', 'error');
                }
            })
            .catch(function() { showToast('Something went wrong.', 'error'); });
        };

        // ─── Delete Document ───
        var deleteId = null;

        window.confirmDelete = function(id, trackingNum) {
            deleteId = id;
            document.getElementById('deleteDocNum').textContent = trackingNum;
            document.getElementById('deleteModal').classList.add('show');
        };

        window.closeDeleteModal = function() {
            document.getElementById('deleteModal').classList.remove('show');
            deleteId = null;
        };

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (!deleteId) return;
            fetch('/api/admin/documents/' + deleteId, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                closeDeleteModal();
                if (data.success) {
                    showToast(data.message, 'success');
                    var row = document.getElementById('doc-row-' + deleteId);
                    if (row) row.style.display = 'none';
                } else {
                    showToast(data.message || 'Failed to delete.', 'error');
                }
            })
            .catch(function() { closeDeleteModal(); showToast('Something went wrong.', 'error'); });
        });

        // Click outside modals to close
        document.getElementById('deleteModal').addEventListener('click', function(e) { if (e.target === this) closeDeleteModal(); });
        document.getElementById('viewModal').addEventListener('click', function(e) { if (e.target === this) closeViewModal(); });

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
