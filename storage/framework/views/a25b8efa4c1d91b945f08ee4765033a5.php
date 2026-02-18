<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Manage Users - DepEd DOCTRAX</title>
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
            min-width: 240px;
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
            display: flex;
            align-items: center;
            gap: 6px;
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

        .pill {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .pill.active { background: #f0fdf4; color: #166534; }
        .pill.pending { background: #fff7ed; color: #9a3412; }
        .pill.suspended { background: #fef2f2; color: #991b1b; }

        .t-date { font-size: 12px; color: #94a3b8; }
        .t-docs { font-size: 12px; color: var(--text-muted); font-weight: 500; }

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

        .btn-sm.activate { color: #166534; border-color: #bbf7d0; }
        .btn-sm.activate:hover { background: #f0fdf4; }

        .btn-sm.suspend { color: #9a3412; border-color: #fed7aa; }
        .btn-sm.suspend:hover { background: #fff7ed; }

        .btn-sm.delete { color: #991b1b; border-color: #fecaca; }
        .btn-sm.delete:hover { background: #fef2f2; }

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
            max-width: 420px;
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

        .modal-btn.primary { background: var(--primary); color: #fff; border-color: var(--primary); }
        .modal-btn.primary:hover { background: var(--primary-dark); }

        /* ─── Edit Modal Form Fields ─── */
        .modal-field { margin-bottom: 14px; }
        .modal-field:last-child { margin-bottom: 0; }
        .modal-label { display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px; }
        .modal-input {
            width: 100%; padding: 9px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            color: var(--text-dark);
            background: #f8fafc;
            transition: border-color 0.2s;
            outline: none;
        }
        .modal-input:focus { border-color: var(--primary); background: #fff; }
        .btn-sm.edit { color: #1d4ed8; border-color: #bfdbfe; }
        .btn-sm.edit:hover { background: #eff6ff; }

        /* ─── Account type badges ─── */
        .type-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .type-badge.individual { background: #eff6ff; color: #1d4ed8; }
        .type-badge.representative { background: #fdf4ff; color: #7e22ce; }

        /* ─── Rep name cell ─── */
        .name-cell { display: flex; flex-direction: column; gap: 2px; }
        .name-office { font-weight: 600; color: var(--text-dark); font-size: 13px; }
        .name-rep { font-size: 11px; color: var(--text-muted); }
        .name-rep i { margin-right: 3px; }

        /* wider table for extra columns */
        .dtable { min-width: 900px; }
        .panel { overflow-x: auto; }

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
            .dtable th:nth-child(4), .dtable td:nth-child(4) { display: none; }
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
            <a href="/admin/users" class="nav-link active"><i class="fas fa-users"></i> Users</a>
            <a href="/admin/documents" class="nav-link"><i class="fas fa-folder-open"></i> Documents</a>

            <div class="nav-user">
                <div class="nav-avatar"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></div>
                <div class="nav-user-info">
                    <span class="nav-user-name"><?php echo e(explode(' ', $user->name)[0]); ?></span>
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
            <a href="/admin/users" class="nav-link active"><i class="fas fa-users"></i> Users</a>
            <a href="/admin/documents" class="nav-link"><i class="fas fa-folder-open"></i> Documents</a>
            <div class="mobile-user-section">
                <div style="display:flex;align-items:center;gap:8px;">
                    <div class="nav-avatar"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></div>
                    <span><?php echo e(explode(' ', $user->name)[0]); ?></span>
                </div>
                <button onclick="logout()" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </div>
        </div>
    </nav>

    <!-- ─── Content ─── -->
    <div class="dash-wrapper">

        <div class="page-header anim">
            <div>
                <h1>Manage Users</h1>
                <p>View and manage registered accounts</p>
            </div>
            <a href="/dashboard" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>

        <!-- Filters -->
        <form class="filters anim" method="GET" action="/admin/users">
            <input type="text" name="search" class="filter-input" placeholder="Search name, email, or mobile..." value="<?php echo e($filters['search']); ?>">
            <select name="status" class="filter-select">
                <option value="">All Status</option>
                <option value="active" <?php echo e($filters['status'] === 'active' ? 'selected' : ''); ?>>Active</option>
                <option value="pending" <?php echo e($filters['status'] === 'pending' ? 'selected' : ''); ?>>Pending</option>
                <option value="suspended" <?php echo e($filters['status'] === 'suspended' ? 'selected' : ''); ?>>Suspended</option>
            </select>
            <button type="submit" class="filter-btn"><i class="fas fa-search"></i> Search</button>
            <?php if($filters['search'] || $filters['status']): ?>
                <a href="/admin/users" class="filter-clear">Clear</a>
            <?php endif; ?>
        </form>

        <!-- Users Table -->
        <div class="panel anim">
            <div class="panel-head">
                <div class="panel-title">Registered Users</div>
                <span class="panel-badge"><?php echo e($users->total()); ?> total</span>
            </div>

            <?php if($users->count() > 0): ?>
            <table class="dtable">
                <thead>
                    <tr>
                        <th>Name / Office</th>
                        <th>Type</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Docs</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $isRep = $u->account_type === 'representative';
                        $officeName = '';
                        $repName = '';
                        if ($isRep && str_contains($u->name, ' - ')) {
                            [$officeName, $repName] = explode(' - ', $u->name, 2);
                        } else {
                            $officeName = $u->name;
                        }
                    ?>
                    <tr id="user-row-<?php echo e($u->id); ?>">
                        <td>
                            <div class="name-cell">
                                <span class="name-office"><?php echo e($officeName); ?></span>
                                <?php if($isRep && $repName): ?>
                                    <span class="name-rep"><i class="fas fa-user"></i><?php echo e($repName); ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <span class="type-badge <?php echo e($u->account_type ?? 'individual'); ?>">
                                <?php echo e($isRep ? 'Representative' : 'Individual'); ?>

                            </span>
                        </td>
                        <td><?php echo e($u->email); ?></td>
                        <td><?php echo e($u->mobile ?? '—'); ?></td>
                        <td class="t-docs"><?php echo e($u->documents_count); ?></td>
                        <td>
                            <span class="pill <?php echo e($u->status); ?>" id="user-status-<?php echo e($u->id); ?>"><?php echo e(ucfirst($u->status)); ?></span>
                        </td>
                        <td class="t-date"><?php echo e($u->created_at->format('M d, Y')); ?></td>
                        <td>
                            <div class="action-btns">
                                <?php if($u->status !== 'active'): ?>
                                    <button class="btn-sm activate" onclick="updateStatus(<?php echo e($u->id); ?>, 'active', '<?php echo e($u->name); ?>')" title="Activate">
                                        <i class="fas fa-check"></i>
                                    </button>
                                <?php endif; ?>
                                <?php if($u->status !== 'suspended'): ?>
                                    <button class="btn-sm suspend" onclick="updateStatus(<?php echo e($u->id); ?>, 'suspended', '<?php echo e($u->name); ?>')" title="Suspend">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                <?php endif; ?>
                                <button class="btn-sm edit" onclick="openEditModal(<?php echo e($u->id); ?>, '<?php echo e(addslashes($officeName)); ?>', '<?php echo e(addslashes($repName)); ?>', '<?php echo e($u->email); ?>', '<?php echo e($u->mobile ?? ''); ?>', '<?php echo e($u->account_type ?? 'individual'); ?>')" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button class="btn-sm delete" onclick="confirmDelete(<?php echo e($u->id); ?>, '<?php echo e($u->name); ?>')" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <?php if($users->hasPages()): ?>
            <div class="pagination-bar">
                <span>Showing <?php echo e($users->firstItem()); ?>–<?php echo e($users->lastItem()); ?> of <?php echo e($users->total()); ?></span>
                <div class="pagination-links">
                    <?php if($users->onFirstPage()): ?>
                        <span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>
                    <?php else: ?>
                        <a href="<?php echo e($users->previousPageUrl()); ?>" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                    <?php endif; ?>

                    <?php $__currentLoopData = $users->getUrlRange(1, $users->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e($url); ?>" class="page-btn <?php echo e($page == $users->currentPage() ? 'active' : ''); ?>"><?php echo e($page); ?></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php if($users->hasMorePages()): ?>
                        <a href="<?php echo e($users->nextPageUrl()); ?>" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                    <?php else: ?>
                        <span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <p>No users found.</p>
            </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- Edit User Modal -->
    <div class="modal-overlay" id="editModal">
        <div class="modal">
            <div class="modal-head">
                <h3><i class="fas fa-user-edit" style="color:var(--primary);margin-right:6px;"></i> Edit User</h3>
            </div>
            <div class="modal-body">
                <!-- Individual fields -->
                <div id="editFieldIndividual">
                    <div class="modal-field">
                        <label class="modal-label">Full Name</label>
                        <input type="text" class="modal-input" id="editName" placeholder="Full name" maxlength="255">
                    </div>
                </div>
                <!-- Representative fields -->
                <div id="editFieldRep" style="display:none;">
                    <div class="modal-field">
                        <label class="modal-label">Office / Institution Name</label>
                        <input type="text" class="modal-input" id="editOfficeName" placeholder="e.g. City Hall Office" maxlength="255">
                    </div>
                    <div class="modal-field">
                        <label class="modal-label">Representative Name</label>
                        <input type="text" class="modal-input" id="editRepName" placeholder="e.g. Juan dela Cruz" maxlength="255">
                    </div>
                </div>
                <!-- Common fields -->
                <div class="modal-field">
                    <label class="modal-label">Email Address</label>
                    <input type="email" class="modal-input" id="editEmail" placeholder="Email address" maxlength="255">
                </div>
                <div class="modal-field">
                    <label class="modal-label">Mobile Number</label>
                    <input type="text" class="modal-input" id="editMobile" placeholder="Mobile number (optional)" maxlength="20">
                </div>
            </div>
            <div class="modal-foot">
                <button class="modal-btn" onclick="closeEditModal()">Cancel</button>
                <button class="modal-btn primary" id="saveEditBtn"><i class="fas fa-save"></i> Save Changes</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <div class="modal-head">
                <h3>Delete User</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteUserName"></strong>?</p>
                <p style="font-size:12px;color:#94a3b8;margin-top:8px;">This action cannot be undone. All associated documents will be unlinked.</p>
            </div>
            <div class="modal-foot">
                <button class="modal-btn" onclick="closeModal()">Cancel</button>
                <button class="modal-btn danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast"></div>

    <footer class="dash-footer">
        <div class="footer-left">
            <span>&copy; <?php echo e(date('Y')); ?> DepEd Document Tracking System</span>
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

        // ─── Update Status ───
        window.updateStatus = function(id, status, name) {
            var label = status === 'active' ? 'activate' : 'suspend';
            if (!confirm('Are you sure you want to ' + label + ' ' + name + '?')) return;

            fetch('/api/admin/users/' + id, {
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

        // ─── Delete ───
        var deleteId = null;

        window.confirmDelete = function(id, name) {
            deleteId = id;
            document.getElementById('deleteUserName').textContent = name;
            document.getElementById('deleteModal').classList.add('show');
        };

        window.closeModal = function() {
            document.getElementById('deleteModal').classList.remove('show');
            deleteId = null;
        };

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (!deleteId) return;
            fetch('/api/admin/users/' + deleteId, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                closeModal();
                if (data.success) {
                    showToast(data.message, 'success');
                    var row = document.getElementById('user-row-' + deleteId);
                    if (row) row.style.display = 'none';
                } else {
                    showToast(data.message || 'Failed to delete.', 'error');
                }
            })
            .catch(function() { closeModal(); showToast('Something went wrong.', 'error'); });
        });

        // Click outside modal to close
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // ─── Edit User ───
        var editId = null;
        var editAccountType = 'individual';

        window.openEditModal = function(id, officeName, repName, email, mobile, accountType) {
            editId = id;
            editAccountType = accountType || 'individual';

            var isRep = editAccountType === 'representative';
            document.getElementById('editFieldIndividual').style.display = isRep ? 'none' : 'block';
            document.getElementById('editFieldRep').style.display      = isRep ? 'block' : 'none';

            if (isRep) {
                document.getElementById('editOfficeName').value = officeName;
                document.getElementById('editRepName').value   = repName;
            } else {
                document.getElementById('editName').value = officeName; // officeName holds the full name for individuals
            }
            document.getElementById('editEmail').value  = email;
            document.getElementById('editMobile').value = mobile;
            document.getElementById('editModal').classList.add('show');
        };

        window.closeEditModal = function() {
            document.getElementById('editModal').classList.remove('show');
            editId = null;
        };

        document.getElementById('saveEditBtn').addEventListener('click', function() {
            if (!editId) return;
            var email  = document.getElementById('editEmail').value.trim();
            var mobile = document.getElementById('editMobile').value.trim();
            var name   = '';

            if (editAccountType === 'representative') {
                var office = document.getElementById('editOfficeName').value.trim();
                var rep    = document.getElementById('editRepName').value.trim();
                if (!office) { showToast('Office name is required.', 'error'); return; }
                if (!rep)    { showToast('Representative name is required.', 'error'); return; }
                name = office + ' - ' + rep;
            } else {
                name = document.getElementById('editName').value.trim();
                if (!name) { showToast('Name is required.', 'error'); return; }
            }

            if (!email) { showToast('Email is required.', 'error'); return; }

            var btn = document.getElementById('saveEditBtn');
            btn.disabled = true;
            btn.textContent = 'Saving...';

            fetch('/api/admin/users/' + editId, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: JSON.stringify({ name: name, email: email, mobile: mobile })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                closeEditModal();
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(function() { window.location.reload(); }, 800);
                } else {
                    showToast(data.message || 'Failed to update.', 'error');
                }
            })
            .catch(function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                showToast('Something went wrong.', 'error');
            });
        });

        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
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
<?php /**PATH C:\Users\iamra\Desktop\DepedDocumentTrackingSystem\resources\views/admin/users.blade.php ENDPATH**/ ?>