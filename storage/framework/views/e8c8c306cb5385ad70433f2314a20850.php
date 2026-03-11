<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="<?php echo e(asset('images/DOCTRAXLOGO.svg')); ?>" type="image/svg+xml">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
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

        /* ─── Sidebar ─── */
        .sidebar{position:fixed;top:0;left:0;width:240px;height:100vh;background:#0056b3;display:flex;flex-direction:column;z-index:200;transform:translateX(-100%);transition:transform .28s cubic-bezier(.4,0,.2,1)}
        .sidebar.open{transform:translateX(0)}
        .sb-brand{padding:22px 20px 18px;border-bottom:1px solid rgba(255,255,255,.12);text-align:center}
        .sb-brand img{width:64px;height:64px;margin-bottom:8px}
        .sb-brand h2{font-size:18px;font-weight:700;color:#fff;margin-bottom:2px}
        .sb-brand small{font-size:11px;color:rgba(255,255,255,.65);display:block}
        .sb-nav{flex:1;padding:12px 0;overflow-y:auto}
        .sb-nav a{display:flex;align-items:center;gap:11px;padding:11px 20px;color:rgba(255,255,255,.78);text-decoration:none;font-size:13px;font-weight:500;transition:background .15s,color .15s}
        .sb-nav a:hover,.sb-nav a.active{background:rgba(255,255,255,.14);color:#fff}
        .sb-nav a i{width:16px;text-align:center}
        .sb-nav .nav-section{padding:10px 20px 4px;font-size:9px;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.4);font-weight:600}
        .sb-footer{padding:14px 20px;border-top:1px solid rgba(255,255,255,.12)}
        .sb-user{display:flex;align-items:center;gap:10px}
        .sb-avatar{width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700;flex-shrink:0}
        .sb-user-info small{font-size:10px;color:rgba(255,255,255,.55);display:block}
        .sb-user-info span{font-size:12px;font-weight:600;color:#fff}
        .btn-logout{display:flex;align-items:center;gap:7px;margin-top:8px;padding:8px 14px;background:rgba(255,255,255,.1);border:none;border-radius:8px;color:rgba(255,255,255,.8);font-size:12px;cursor:pointer;font-family:Poppins,sans-serif;width:100%;justify-content:center;transition:background .2s}
        .btn-logout:hover{background:rgba(220,38,38,.75)}

        /* ─── Mobile top bar ─── */
        .mob-topbar{display:flex;position:sticky;top:0;z-index:100;background:#0056b3;padding:12px 16px;align-items:center;justify-content:space-between;gap:12px;box-shadow:0 2px 8px rgba(0,0,0,.1)}
        .mob-hamburger{background:none;border:none;cursor:pointer;display:flex;flex-direction:column;gap:5px;z-index:1001;user-select:none;padding:4px}
        .mob-hamburger span{height:2px;width:24px;background:#fff;border-radius:2px;transition:all .4s ease}
        .mob-hamburger.toggle span:nth-child(1){transform:rotate(-45deg) translate(-4px,5px)}
        .mob-hamburger.toggle span:nth-child(2){opacity:0}
        .mob-hamburger.toggle span:nth-child(3){transform:rotate(45deg) translate(-4px,-5px)}
        .mob-brand{flex:1;display:flex;flex-direction:column;color:#fff}
        .mob-brand .brand-subtitle{font-size:clamp(9px,2vw,11px);opacity:.85;text-transform:uppercase;letter-spacing:1px}
        .mob-brand h1{font-size:clamp(13px,3.5vw,18px);font-weight:700;margin:0;line-height:1.2}
        .mob-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.35);z-index:199}
        .mob-overlay.open{display:block}

        /* ─── Main Content Area ─── */
        .main{margin-left:0;min-height:100vh}

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
            white-space: nowrap;
        }

        #c-h, #c-m {
            display: inline-block;
            width: 2ch;
            text-align: center;
        }

        .clock-time-display .seconds {
            font-size: 14px;
            color: #9ca3af;
            font-weight: 600;
            display: inline-block;
            width: 2ch;
            text-align: center;
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
            font-size: 17px;
            font-weight: 700;
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
        .pill.processing { background: #fffbeb; color: #d97706; }
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
        .site-footer {
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

        /* ─── Mobile Cards ─── */
        .mob-cards { display: none; }
        .mob-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: box-shadow .15s, border-color .15s;
        }
        .mob-card:hover { border-color: var(--primary); box-shadow: 0 2px 8px rgba(0,86,179,.08); }
        .mob-card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
        }
        .mob-card-ref { font-size: 12px; font-weight: 600; color: var(--primary); font-family: monospace; }
        .mob-card-subject {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 10px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .mob-card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 16px;
            font-size: 12px;
            color: var(--text-muted);
            align-items: center;
        }
        .mob-card-meta i { margin-right: 4px; font-size: 11px; }
        .mob-card-arrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 6px;
            color: #94a3b8;
            font-size: 12px;
            flex-shrink: 0;
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

        @media (max-width: 900px) {
            .dash-wrapper{padding:20px 16px 40px}
            .top-bar{flex-direction:column;align-items:flex-start;gap:14px}
            .greeting-section h1{font-size:20px}
            .live-clock{width:100%}
            .stats{grid-template-columns:1fr 1fr}
            .dtable-wrap { display: none; }
            .mob-cards { display: block; }
            .drawer { width: 100%; max-width: 100%; }
            .drawer-meta { grid-template-columns: 1fr; }
            .dm-item { border-right: none; }
            .site-footer{flex-direction:column;gap:6px;text-align:center;padding:16px 5%}
        }

        @media (max-width: 400px) {
            .greeting-section h1 { font-size: 18px; }
        }

        /* Badge colors for office docs */
        .badge-submitted{background:#eff6ff;color:#2563eb}
        .badge-received{background:#f0fdf4;color:#16a34a}
        .badge-in_review{background:#fffbeb;color:#d97706}

        /* ─── Receive strip (office style) ─── */
        .receive-strip{background:#fff;border:1px solid var(--border);border-radius:0;padding:22px 24px}
        .receive-strip h2{font-size:20px;font-weight:700;color:var(--text-dark);margin:0 0 6px}
        .receive-strip p.rs-sub{font-size:13px;color:var(--text-muted);margin:0 0 18px}
        .rs-main{width:100%;display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:0}
        .ref-boxes-row{display:flex;align-items:center;gap:7px;flex:0 1 auto;flex-wrap:nowrap}
        .ref-box{width:76px;height:60px;text-align:center;font-size:24px;font-weight:700;font-family:'Poppins',sans-serif;border:1.5px solid #e2e8f0;border-radius:8px;outline:none;text-transform:uppercase;background:#f8fafc;transition:border-color .2s,box-shadow .2s,background .2s;color:#1e293b;padding:0;caret-color:#16a34a}
        .ref-box:focus{border-color:#16a34a;box-shadow:0 0 0 3px rgba(22,163,74,.13);background:#fff}
        .ref-box.filled{background:#fff;border-color:#94a3b8}
        .ref-sep{font-size:18px;color:#cbd5e1;user-select:none;padding:0 2px}
        .btn-clear-x{width:36px;height:36px;border:1.5px solid #e2e8f0;border-radius:50%;background:#f8fafc;color:#94a3b8;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;flex-shrink:0;padding:0}
        .rs-center{width:fit-content;margin:0 auto}
        .rs-btn-wrap{display:flex;justify-content:center;margin-top:18px}
        .btn-receive{width:auto;min-width:600px;height:60px;padding:0 32px;border:none;border-radius:8px;background:#16a34a;color:#fff;font-family:'Poppins',sans-serif;font-size:14px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;transition:background .2s}
        .btn-receive:hover{background:#15803d}
        .btn-receive:active{background:#166534}
        .btn-receive:disabled{opacity:.5;cursor:not-allowed}
        .receive-alert{margin-top:12px;padding:8px 12px;border-radius:7px;font-size:12px;display:none;align-items:center;gap:8px;animation:rcvFadeIn .2s ease-out;width:100%}
        .receive-alert.show{display:flex}
        .receive-alert.err{background:#fef2f2;border-left:3px solid #dc2626;color:#b91c1c}
        .receive-alert.ok{background:#f0fdf4;border-left:3px solid #16a34a;color:#15803d}
        .receive-alert i{font-size:13px;flex-shrink:0}
        .receive-alert span{line-height:1.4}
        @keyframes rcvFadeIn{from{opacity:0;transform:translateY(-3px)}to{opacity:1;transform:translateY(0)}}
        @media(max-width:768px){
            .receive-strip{padding:16px 18px}
            .rs-main{gap:5px}
            .ref-boxes-row{gap:4px}
            .ref-box{width:38px;height:44px;font-size:17px}
            .btn-clear-x{width:32px;height:32px;font-size:12px}
            .btn-receive{min-width:auto;width:100%;height:44px;font-size:13px}
        }
        /* ─── Tracking Drawer ─── */
        .drawer-overlay{position:fixed;inset:0;background:rgba(0,0,0,.35);z-index:400;opacity:0;pointer-events:none;transition:opacity .25s}
        .drawer-overlay.open{opacity:1;pointer-events:all}
        .drawer{position:fixed;top:0;right:0;height:100vh;width:460px;max-width:100vw;background:#fff;z-index:401;box-shadow:-4px 0 24px rgba(0,0,0,.12);display:flex;flex-direction:column;transform:translateX(100%);transition:transform .28s cubic-bezier(.4,0,.2,1)}
        .drawer.open{transform:translateX(0)}
        .drawer-head{padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:flex-start;gap:12px}
        .drawer-head-info{flex:1;min-width:0}
        .drawer-head h3{font-size:16px;font-weight:700;color:var(--text-dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:4px}
        .drawer-ref{font-size:13px;color:var(--text-muted);font-family:monospace;letter-spacing:.4px;margin-bottom:2px}
        .drawer-track{font-size:11px;color:var(--text-muted);font-family:monospace;letter-spacing:.4px;margin-bottom:4px}
        .drawer-close{width:32px;height:32px;border-radius:8px;border:1px solid var(--border);background:#f8fafc;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:14px;flex-shrink:0;transition:all .15s}
        .drawer-close:hover{background:#fee2e2;color:#dc2626;border-color:#fca5a5}
        .drawer-body{flex:1;overflow-y:auto}
        .drawer-meta{display:grid;grid-template-columns:1fr 1fr;border-bottom:1px solid var(--border)}
        .dm-item{padding:14px 20px;border-right:1px solid #f1f5f9;border-bottom:1px solid #f1f5f9}
        .dm-item:nth-child(2n){border-right:none}
        .dm-label{font-size:11px;text-transform:uppercase;letter-spacing:.6px;color:#94a3b8;font-weight:600;margin-bottom:3px}
        .dm-value{font-size:14px;color:var(--text-dark);font-weight:500}
        .drawer-tl-head{padding:14px 20px 6px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--text-muted);display:flex;align-items:center;gap:6px}
        .drawer-timeline{padding:10px 20px 24px}
        .tl{position:relative}
        .tl::before{content:'';position:absolute;left:7px;top:8px;bottom:8px;width:2px;background:var(--border);z-index:-1}
        .tl-item{position:relative;margin-bottom:20px;padding-left:24px}
        .tl-item:last-child{margin-bottom:0}
        .tl-dot{width:16px;height:16px;border-radius:50%;border:2.5px solid #fff;display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0}
        .tl-dot.c-active{background:#22c55e;box-shadow:0 0 0 2px #22c55e}
        .tl-dot.c-done{background:#22c55e;box-shadow:0 0 0 2px #22c55e}
        .tl-dot.c-warn{background:#22c55e;box-shadow:0 0 0 2px #22c55e}
        .tl-dot.c-danger{background:#22c55e;box-shadow:0 0 0 2px #22c55e}
        .tl-dot.c-latest{background:#f59e0b;box-shadow:0 0 0 2px #f59e0b}
        .tl-action{font-size:12px;font-weight:500;color:#64748b}
        .tl-meta{font-size:12px;color:#64748b;margin:2px 0}
        .tl-remarks{font-size:12px;color:#64748b;background:#f8fafc;border-left:3px solid var(--border);padding:5px 9px;border-radius:4px;margin-top:5px}
        .tl-office-hdr{display:flex;align-items:center;font-size:13px;font-weight:700;color:var(--text-dark);text-transform:none;letter-spacing:0;margin:18px 0 8px -7px;padding-left:7px;padding-bottom:6px;position:relative}
        .tl-office-hdr::after{content:'';position:absolute;left:21px;right:0;bottom:0;height:1.5px;background:var(--border)}
        .tl-office-hdr:first-child{margin-top:0}
        .drawer-loader{display:flex;align-items:center;justify-content:center;padding:48px;flex-direction:column;gap:12px;color:var(--text-muted);font-size:13px}
        .badge-forwarded{background:#f5f3ff;color:#7c3aed}
        .badge-for_pickup{background:#fff7ed;color:#c2410c}
        .badge-completed{background:#f0fdf4;color:#15803d}
    </style>
    <script src="/js/spa.js" defer></script>
    <script src="/js/form-utils.js" defer></script>
    <script src="/js/request-utils.js" defer></script>
</head>
<body>

<!-- Mobile top bar -->
<div class="mob-topbar">
    <button class="mob-hamburger" id="mobHamBtn" type="button" onclick="toggleSidebar()" aria-label="Menu"><span></span><span></span><span></span></button>
    <div class="mob-brand">
        <span class="brand-subtitle">Department of Education</span>
        <h1>Document Tracking System &mdash; <strong>DOCTRAX</strong></h1>
    </div>
</div>
<div class="mob-overlay" id="mobOverlay" onclick="closeSidebar()"></div>

<!-- ─── Sidebar ─── -->
<div class="sidebar" id="mainSidebar">
    <div class="sb-brand">
        <img src="<?php echo e(asset('images/DOCTRAXLOGO.svg')); ?>" alt="DOCTRAX Logo">
        <h2>DOCTRAX</h2>
        <small>DepEd Document Tracking System</small>
    </div>
    <nav class="sb-nav">
        <span class="nav-section">Overview</span>
        <a href="/dashboard" class="active"><i class="fas fa-th-large"></i> Dashboard</a>
        <span class="nav-section">Management</span>
        <a href="/admin/users"><i class="fas fa-users"></i> Users</a>
        <a href="/admin/offices"><i class="fas fa-building"></i> Offices</a>
        <a href="/admin/documents"><i class="fas fa-folder-open"></i> Documents</a>
        <?php if($user->isSuperAdmin()): ?>
        <a href="/records/documents"><i class="fas fa-eye"></i> Records View</a>
        <span class="nav-section">ICT Unit</span>
        <a href="/ict/documents"><i class="fas fa-network-wired"></i> ICT Documents</a>
        <?php endif; ?>
        <?php if($user->isSuperAdmin()): ?>
        <span class="nav-section">Reports</span>
        <a href="/office/search"><i class="fas fa-chart-line"></i> Reports Dashboard</a>
        <?php endif; ?>
        <span class="nav-section">My Documents</span>
        <a href="/submit"><i class="fas fa-paper-plane"></i> Submit Document</a>
        <a href="/my-documents"><i class="fas fa-folder"></i> My Documents</a>
        <a href="/track"><i class="fas fa-search"></i> Track Document</a>
        <span class="nav-section">Account</span>
        <a href="/profile"><i class="fas fa-user-cog"></i> My Profile</a>
    </nav>
    <div class="sb-footer">
        <div class="sb-user">
            <div class="sb-avatar"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></div>
            <div class="sb-user-info">
                <small><?php echo e($user->isSuperAdmin() ? 'Super Admin' : 'Admin'); ?></small>
                <span><?php echo e(explode(' ', $user->name)[0]); ?></span>
            </div>
        </div>
        <button onclick="logout()" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>
</div>

<!-- ─── Main Content ─── -->
<div class="main">

    <!-- ─── Dashboard Content ─── -->
    <div class="dash-wrapper">

        <!-- Top Bar -->
        <div class="top-bar anim">
            <div class="greeting-section">
                <h1>Admin Dashboard</h1>
                <p><?php echo e(now()->format('l, F j, Y')); ?></p>
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
                    <div class="s-num" id="stat-users"><?php echo e($stats['total_users']); ?></div>
                    <div class="s-label">Total Users</div>
                </div>
            </div>
            <div class="stat-card anim">
                <div class="s-icon purple"><i class="fas fa-file-alt"></i></div>
                <div class="s-data">
                    <div class="s-num" id="stat-docs"><?php echo e($stats['total_documents']); ?></div>
                    <div class="s-label">Total Documents</div>
                </div>
            </div>
            <div class="stat-card anim">
                <div class="s-icon orange"><i class="fas fa-clock"></i></div>
                <div class="s-data">
                    <div class="s-num" id="stat-pending"><?php echo e($stats['pending_docs']); ?></div>
                    <div class="s-label">Pending</div>
                </div>
            </div>
            <div class="stat-card anim">
                <div class="s-icon green"><i class="fas fa-check-circle"></i></div>
                <div class="s-data">
                    <div class="s-num" id="stat-completed"><?php echo e($stats['completed_docs']); ?></div>
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

                <?php if($recentDocs->count() > 0): ?>
                <div class="dtable-wrap">
                <table class="dtable">
                    <thead>
                        <tr>
                            <th>Tracking #</th>
                            <th>Reference #</th>
                            <th>Subject</th>
                            <th>Submitted By</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $recentDocs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="doc-row" style="cursor:pointer;" onclick='openDocDetail(<?php echo json_encode($doc->tracking_number, 15, 512) ?>)'>
                            <td><span class="t-num"><?php echo e($doc->tracking_number); ?></span></td>
                            <td><span class="t-num" style="color:var(--text-dark)"><?php echo e($doc->reference_number ?: 'N/A'); ?></span></td>
                            <td style="max-width:200px"><div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="<?php echo e($doc->subject); ?>"><?php echo e($doc->subject); ?></div></td>
                            <td class="t-user"><?php echo e($doc->user ? $doc->user->name : ($doc->sender_name ?? 'Guest')); ?></td>
                            <td>
                                <?php
                                    $sc = match($doc->status) {
                                        'submitted', 'received' => 'pending',
                                        'in_review', 'on_hold' => 'processing',
                                        'completed', 'for_pickup' => 'completed',
                                        default => 'other',
                                    };
                                    $statusLabel = $doc->statusLabel();
                                ?>
                                <span class="pill <?php echo e($sc); ?>">
                                    <?php echo e($statusLabel); ?>

                                </span>
                            </td>
                            <td class="t-date"><?php echo e($doc->created_at->format('M d, Y')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                </div><!-- end dtable-wrap -->

                <!-- Mobile Cards -->
                <div class="mob-cards">
                    <?php $__currentLoopData = $recentDocs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mob-card" onclick='openDocDetail(<?php echo json_encode($doc->tracking_number, 15, 512) ?>)'>
                        <div class="mob-card-top">
                            <div class="mob-card-ref"><?php echo e($doc->tracking_number); ?></div>
                        <div style="font-size:10px;color:var(--text-muted);font-family:monospace;margin-top:1px">Ref: <?php echo e($doc->reference_number ?: 'N/A'); ?></div>
                            <span class="mob-card-arrow"><i class="fas fa-chevron-right"></i></span>
                        </div>
                        <div class="mob-card-subject"><?php echo e($doc->subject); ?></div>
                        <div class="mob-card-meta">
                            <?php
                                $sc = match($doc->status) {
                                    'submitted', 'received' => 'pending',
                                    'in_review', 'on_hold' => 'processing',
                                    'completed', 'for_pickup' => 'completed',
                                    default => 'other',
                                };
                            ?>
                            <span class="pill <?php echo e($sc); ?>"><?php echo e($doc->statusLabel()); ?></span>
                            <span><i class="fas fa-user"></i><?php echo e($doc->user ? $doc->user->name : ($doc->sender_name ?? 'Guest')); ?></span>
                            <span><i class="fas fa-calendar"></i><?php echo e($doc->created_at->format('M d, Y')); ?></span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No documents submitted yet.</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Quick Actions -->
            <div class="panel anim">
                <div class="panel-head">
                    <div class="panel-title">Quick Actions</div>
                </div>
                <div class="actions-list">
                    <a href="/" class="act">
                        <div class="act-icon" style="background:rgba(0,86,179,0.08);color:var(--primary);"><i class="fas fa-home"></i></div>
                        <div class="act-body">
                            <div class="act-title">Home</div>
                            <div class="act-desc">Go to the main landing page</div>
                        </div>
                        <i class="fas fa-chevron-right act-arrow"></i>
                    </a>
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
                    <a href="/admin/offices" class="act">
                        <div class="act-icon" style="background:rgba(0,86,179,0.08);color:var(--primary);"><i class="fas fa-building"></i></div>
                        <div class="act-body">
                            <div class="act-title">Office Accounts</div>
                            <div class="act-desc">Manage internal DepEd office accounts</div>
                        </div>
                        <i class="fas fa-chevron-right act-arrow"></i>
                    </a>
                    <a href="/admin/documents?status=in_review" class="act">
                        <div class="act-icon" style="background:#f1f5f9;color:#475569;"><i class="fas fa-inbox"></i></div>
                        <div class="act-body">
                            <div class="act-title">Pending Documents</div>
                            <div class="act-desc">Documents awaiting processing</div>
                        </div>
                        <i class="fas fa-chevron-right act-arrow"></i>
                    </a>
                    <?php if($user->isSuperAdmin()): ?>
                    <a href="/records/documents" class="act">
                        <div class="act-icon" style="background:#dcfce7;color:#16a34a;"><i class="fas fa-eye"></i></div>
                        <div class="act-body">
                            <div class="act-title">Records View</div>
                            <div class="act-desc">View all incoming documents (Records Section)</div>
                        </div>
                        <i class="fas fa-chevron-right act-arrow"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>

    <!-- Tracking Drawer -->
    <div class="drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>
    <div class="drawer" id="docDrawer">
        <div class="drawer-head">
            <div class="drawer-head-info">
                <h3 id="drTitle">—</h3>
                <div class="drawer-ref" id="drRef">—</div>
                <div class="drawer-track" id="drTrack"></div>
            </div>
            <button class="drawer-close" onclick="closeDrawer()"><i class="fas fa-times"></i></button>
        </div>
        <div class="drawer-body" id="drawerBody">
            <div class="drawer-loader"><span class="loading-dots"><span></span></span>Loading details...</div>
        </div>
    </div>

    <footer class="site-footer">
        <div class="footer-left">
            <span>&copy; <?php echo e(date('Y')); ?> DepEd Document Tracking System</span>
        </div>
        <div class="footer-right">
            Developed by Raymond Bautista
        </div>
    </footer>

</div><!-- end .main -->

    <script>
    (function() {
        // ─── Clock ───
        function tick() {
            var n = new Date();
            var h = n.getHours(), m = n.getMinutes(), s = n.getSeconds();
            var p = h >= 12 ? 'PM' : 'AM';
            var h12 = h % 12 || 12;
            var el = document.getElementById('c-h'); if (!el) { clearInterval(clockInterval); return; }

            el.textContent = String(h12).padStart(2, '0');
            document.getElementById('c-m').textContent = String(m).padStart(2, '0');
            document.getElementById('c-s').textContent = String(s).padStart(2, '0');
            document.getElementById('c-p').textContent = p;

            var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
            var mos = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            document.getElementById('c-day').textContent = days[n.getDay()];
            document.getElementById('c-date').textContent = mos[n.getMonth()] + ' ' + n.getDate() + ', ' + n.getFullYear();
        }
        tick(); var clockInterval = setInterval(tick, 1000);

        // ─── Sidebar Toggle ───
        window.toggleSidebar = function() {
            var s = document.getElementById('mainSidebar');
            var o = document.getElementById('mobOverlay');
            var open = s.classList.toggle('open');
            o.classList.toggle('open', open);
            document.body.style.overflow = open ? 'hidden' : '';
            document.getElementById('mobHamBtn').classList.toggle('toggle', open);
        };
        window.closeSidebar = function() {
            document.getElementById('mainSidebar').classList.remove('open');
            document.getElementById('mobOverlay').classList.remove('open');
            document.body.style.overflow = '';
            var btn = document.getElementById('mobHamBtn'); if (btn) btn.classList.remove('toggle');
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

        // ─── Live stats (refresh every 30s) ───
        // Live stats (silent update every 30s)
        (function() {
            function refreshStats() {
                fetch('/api/admin-stats', { headers: { 'Accept': 'application/json' } })
                    .then(function(r) { return r.ok ? r.json() : null; })
                    .then(function(d) {
                        if (!d) return;
                        document.getElementById('stat-users').textContent     = d.total_users;
                        document.getElementById('stat-docs').textContent      = d.total_documents;
                        document.getElementById('stat-pending').textContent   = d.pending_docs;
                        document.getElementById('stat-completed').textContent = d.completed_docs;
                    })
                    .catch(function() {});

                // Refresh office stats too
                fetch('/api/office-stats', { headers: { 'Accept': 'application/json' } })
                    .then(function(r) { return r.ok ? r.json() : null; })
                    .then(function(d) {
                        if (!d) return;
                        var el;
                        el = document.getElementById('os-incoming');  if (el) el.textContent = d.incoming;
                        el = document.getElementById('os-review');    if (el) el.textContent = d.in_review;
                    })
                    .catch(function() {});
            }
            if (window.smartInterval) { window.smartInterval(refreshStats, 30000); }
            else { setInterval(refreshStats, 30000); }
        })();

        // ─── Office document actions (SuperAdmin with office) ───
        var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        window.acceptDoc = function(id) {
            var btn = document.querySelector('.btn-accept-office[data-id="' + id + '"]');
            if (btn) { btn.disabled = true; }

            fetch('/api/office/documents/' + id + '/accept', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
            })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (d.success) {
                    if (btn) { btn.innerHTML = '<i class="fas fa-check"></i> Accepted'; btn.style.background = '#059669'; btn.disabled = true; }
                    setTimeout(function() { location.reload(); }, 800);
                } else {
                    alert(d.message || 'Could not accept document.');
                    if (btn) { btn.disabled = false; }
                }
            })
            .catch(function() {
                alert('Network error. Please try again.');
                if (btn) { btn.disabled = false; }
            });
        };

        /* ─── Segmented Reference Box Logic ─── */
        (function(){
            var container = document.getElementById('refBoxes');
            if(!container) return;
            var boxes = container.querySelectorAll('.ref-box');
            boxes.forEach(function(box){
                box.addEventListener('input', function(){
                    this.value = this.value.replace(/[^A-Za-z0-9]/g,'').toUpperCase();
                    this.classList.toggle('filled', this.value.length > 0);
                    if(this.value.length === 1){
                        var next = container.querySelector('[data-idx="'+(parseInt(this.dataset.idx)+1)+'"]');
                        if(next) next.focus();
                    }
                });
                box.addEventListener('keydown', function(e){
                    if(e.key === 'Backspace' && !this.value){
                        var prev = container.querySelector('[data-idx="'+(parseInt(this.dataset.idx)-1)+'"]');
                        if(prev){ prev.focus(); prev.select(); }
                    }
                    if(e.key === 'Enter'){ e.preventDefault(); receiveByReference(); }
                    if(e.key === 'ArrowLeft'){
                        var p2 = container.querySelector('[data-idx="'+(parseInt(this.dataset.idx)-1)+'"]');
                        if(p2) p2.focus();
                    }
                    if(e.key === 'ArrowRight'){
                        var n2 = container.querySelector('[data-idx="'+(parseInt(this.dataset.idx)+1)+'"]');
                        if(n2) n2.focus();
                    }
                });
                box.addEventListener('paste', function(e){
                    e.preventDefault();
                    var paste = (e.clipboardData.getData('text')||'').replace(/[^A-Za-z0-9]/g,'').toUpperCase();
                    var startIdx = parseInt(this.dataset.idx);
                    for(var i=0; i<paste.length && startIdx+i<boxes.length; i++){
                        boxes[startIdx+i].value = paste[i];
                        boxes[startIdx+i].classList.add('filled');
                    }
                    var lastIdx = Math.min(startIdx+paste.length, boxes.length)-1;
                    boxes[lastIdx].focus();
                });
                box.addEventListener('focus', function(){ this.select(); });
            });
        })();

        function getRefValue(){
            var boxes = document.querySelectorAll('#refBoxes .ref-box');
            var val = '';
            boxes.forEach(function(b){ val += b.value; });
            return val.trim().toUpperCase();
        }

        function showReceiveMsg(message, kind){
            var el = document.getElementById('receiveRefMsg');
            if(!el) return;
            var icon = el.querySelector('i');
            var span = el.querySelector('span');
            if(!message){ el.classList.remove('show','ok','err'); return; }
            span.textContent = message;
            el.className = 'receive-alert show ' + (kind || '');
            if(icon) icon.className = kind === 'ok' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
        }

        function clearRefBoxes(){
            var boxes = document.querySelectorAll('#refBoxes .ref-box');
            boxes.forEach(function(b){ b.value=''; b.classList.remove('filled'); });
            if(boxes.length) boxes[0].focus();
            showReceiveMsg('', '');
        }

        async function receiveByReference(){
            var btn = document.getElementById('receiveRefBtn');
            var ref = getRefValue();
            if(ref.length < 8){
                showReceiveMsg('Please enter all 8 characters of the tracking number.', 'err');
                var boxes = document.querySelectorAll('#refBoxes .ref-box');
                for(var i=0;i<boxes.length;i++){
                    if(!boxes[i].value){ boxes[i].focus(); break; }
                }
                return;
            }
            showReceiveMsg('', '');
            btnLoading(btn);
            try{
                var res = await fetch('/api/office/documents/receive-by-reference', {
                    method: 'POST',
                    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
                    body: JSON.stringify({ reference_number: ref, tracking_number: ref })
                });
                var data = await res.json();
                if(data.success){
                    showReceiveMsg(data.message || 'Document received successfully.', 'ok');
                    setTimeout(function(){ location.reload(); }, 700);
                    return;
                }
                showReceiveMsg(data.message || 'Failed to receive document.', 'err');
            }catch(e){
                showReceiveMsg('Network error. Please try again.', 'err');
            }
            btn.disabled = false;
        }

        /* ─── Tracking Drawer ─── */
        function escapeHtml(value){
            return String(value === null || value === undefined ? '' : value)
                .replace(/&/g,'&amp;')
                .replace(/</g,'&lt;')
                .replace(/>/g,'&gt;')
                .replace(/"/g,'&quot;')
                .replace(/'/g,'&#39;');
        }
        window.openDocDetail = function(ref){
            document.getElementById('drTitle').textContent='—';
            document.getElementById('drRef').textContent=ref;
            document.getElementById('drTrack').textContent='';
            document.getElementById('drawerBody').innerHTML='<div class="drawer-loader"><span class="loading-dots"><span></span></span>Loading details...</div>';
            document.getElementById('drawerOverlay').classList.add('open');
            document.getElementById('docDrawer').classList.add('open');
            document.body.style.overflow='hidden';
            fetch('/api/track-document',{
                method:'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
                body:JSON.stringify({ reference_number: ref, tracking_number: ref })
            })
            .then(function(r){ return r.json(); })
            .then(function(data){
                if(!data.success || !data.document){
                    document.getElementById('drawerBody').innerHTML='<div class="drawer-loader">Document not found.</div>';
                    return;
                }
                renderDrawer(data.document);
            })
            .catch(function(){
                document.getElementById('drawerBody').innerHTML='<div class="drawer-loader">Something went wrong. Please try again.</div>';
            });
        };
        window.closeDrawer = function(){
            document.getElementById('drawerOverlay').classList.remove('open');
            document.getElementById('docDrawer').classList.remove('open');
            document.body.style.overflow='';
        };
        function dotClass(s){
            if(s==='cancelled' || s==='returned') return 'c-danger';
            if(s==='completed') return 'c-done';
            if(s==='forwarded') return 'c-warn';
            return 'c-active';
        }
        function renderDrawer(doc){
            var ref = doc.reference_number || doc.tracking_number || '-';
            var trackingNo = doc.tracking_number || '';
            document.getElementById('drTitle').textContent = doc.subject || '-';
            document.getElementById('drRef').textContent = 'TN · ' + ref;
            document.getElementById('drTrack').textContent = (trackingNo && trackingNo !== ref) ? ('Ref · ' + trackingNo) : '';
            var logs = Array.isArray(doc.routing_logs) ? doc.routing_logs : [];
            var tlHtml = '';
            if (!logs.length) {
                tlHtml = '<div style="color:var(--text-muted);font-size:13px;padding:4px 0">No routing history yet.</div>';
            } else {
                var prevGroupKey = null;
                logs.slice().reverse().forEach(function(log, idx) {
                    var isLatest = idx === 0;
                    var dc = isLatest ? 'c-latest' : dotClass(log.status_after);
                    var dotIcon = isLatest ? 'fa-arrow-up' : 'fa-check';
                    var groupKey = (log.action === 'submitted') ? '__pending__' :
                                   (log.action === 'forwarded' ? (log.from_office || 'Unknown') :
                                   (log.to_office || log.from_office || 'Unknown'));
                    var groupLabel = (groupKey === '__pending__') ? 'Submitted — Pending Acceptance' : groupKey;
                    if (groupKey !== prevGroupKey) {
                        prevGroupKey = groupKey;
                        tlHtml += '<div class="tl-office-hdr"><div class="tl-dot ' + dc + '" style="margin-right:5px"><i class="fas ' + dotIcon + '" style="font-size:5px"></i></div><span>' + escapeHtml(groupLabel) + '</span></div>';
                    }
                    tlHtml += '<div class="tl-item">' +
                        (log.performed_by ? '<div class="tl-action">' + escapeHtml(log.performed_by) + '</div>' : '') +
                        '<div class="tl-meta"><i class="fas fa-clock" style="margin-right:3px;font-size:10px"></i>' + escapeHtml(log.timestamp || '-') + '</div>' +
                        '<div class="tl-meta"><i class="fas fa-tasks" style="margin-right:3px;font-size:10px"></i>' + escapeHtml(log.action_label || 'Status Updated') + '</div>' +
                        (log.remarks ? '<div class="tl-remarks">' + escapeHtml(log.remarks) + '</div>' : '') +
                        '</div>';
                });
            }
            var currentOfficeText = (doc.status === 'submitted')
                ? ('Awaiting acceptance by ' + (doc.submitted_to_office || doc.current_office || 'Records Section'))
                : (doc.current_office || doc.submitted_to_office || '-');
            var currentHandlerText = doc.current_handler || 'Unassigned';
            document.getElementById('drawerBody').innerHTML =
                '<div class="drawer-tl-head"><i class="fas fa-history"></i> Routing History</div>' +
                '<div class="drawer-timeline"><div class="tl">' + tlHtml + '</div></div>';
        }
        document.addEventListener('keydown', function(e){
            if(e.key === 'Escape'){ closeDrawer(); closeSidebar(); }
        });
    })();
    </script>
</body>
</html>
<?php /**PATH C:\Users\iamra\Desktop\DepedDocumentTrackingSystem\resources\views/admin/index.blade.php ENDPATH**/ ?>