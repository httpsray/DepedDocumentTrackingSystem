<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('images/DOCTRAXLOGO.svg') }}" type="image/svg+xml">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ICT Unit — DOCTRAX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root{--primary:#0056b3;--primary-dark:#004494;--bg:#f0f2f5;--border:#e2e8f0;--text-dark:#1b263b;--text-muted:#64748b}
        *{margin:0;padding:0;box-sizing:border-box}
        body{background:var(--bg);font-family:'Poppins',sans-serif;min-height:100vh;display:flex;flex-direction:column}

        /* ─── Receive Strip ─── */
        .receive-strip{background:#fff;border:1px solid var(--border);border-radius:12px;padding:20px 22px;margin-bottom:20px}
        .receive-strip h2{font-size:17px;font-weight:700;color:var(--text-dark);margin:0 0 4px}
        .receive-strip p.rs-sub{font-size:12px;color:var(--text-muted);margin:0 0 14px}
        .rs-main{width:100%;display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:0}
        .ref-boxes-row{display:flex;align-items:center;gap:7px;flex:1;min-width:0;flex-wrap:nowrap}
        .ref-box{flex:1;min-width:0;height:50px;text-align:center;font-size:20px;font-weight:700;font-family:'Poppins',sans-serif;border:1.5px solid #e2e8f0;border-radius:8px;outline:none;text-transform:uppercase;background:#f8fafc;transition:border-color .2s,box-shadow .2s,background .2s;color:#1e293b;padding:0;caret-color:#16a34a}
        .ref-box:focus{border-color:#16a34a;box-shadow:0 0 0 3px rgba(22,163,74,.13);background:#fff}
        .ref-box.filled{background:#fff;border-color:#94a3b8}
        .ref-sep{font-size:18px;color:#cbd5e1;user-select:none;padding:0 2px}
        .btn-clear-x{width:36px;height:36px;border:1.5px solid #e2e8f0;border-radius:50%;background:#f8fafc;color:#94a3b8;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;flex-shrink:0;padding:0}
        .rs-center{width:100%;margin:0 auto}
        .rs-btn-wrap{display:flex;justify-content:center;margin-top:14px}
        .btn-receive{width:100%;height:48px;padding:0 32px;border:none;border-radius:8px;background:#16a34a;color:#fff;font-family:'Poppins',sans-serif;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;transition:background .2s}
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
        @media(max-width:900px){
            .receive-strip{padding:16px 18px}
            .rs-main{gap:5px}
            .ref-boxes-row{gap:3px}
            .ref-box{height:40px;font-size:14px}
            .ref-sep{font-size:13px;padding:0 1px}
            .btn-clear-x{width:32px;height:32px;font-size:12px}
            .btn-receive{min-width:auto;width:100%;height:44px;font-size:13px}
        }

        /* Page header */
        .page-header{margin-bottom:28px;padding-bottom:20px;border-bottom:1px solid var(--border)}
        .page-header-top{display:flex;align-items:center;justify-content:space-between;gap:16px}
        .page-header h1{font-size:19px;font-weight:700;color:var(--text-dark);letter-spacing:-.2px}
        .page-header p{font-size:12.5px;color:var(--text-muted);margin-top:4px}
        .live-clock{display:flex;align-items:center;gap:14px;background:#fff;padding:10px 18px;border-radius:8px;border:1px solid var(--border);flex-shrink:0}
        .clock-time-display{font-size:18px;font-weight:600;color:var(--text-dark);font-variant-numeric:tabular-nums;line-height:1;white-space:nowrap}
        #c-h,#c-m{display:inline-block;width:2ch;text-align:center}
        .clock-time-display .seconds{font-size:14px;color:#9ca3af;font-weight:600;display:inline-block;width:2ch;text-align:center}
        .clock-time-display .period{font-size:11px;font-weight:600;color:var(--text-muted);margin-left:3px;vertical-align:top}
        .clock-sep{width:1px;height:28px;background:var(--border)}
        .clock-date-display{font-size:13px;color:var(--text-muted);font-weight:400;line-height:1.4}
        .clock-date-display .day{font-weight:600;color:var(--text-dark);display:block}

        /* ─── Panel & Office Section ─── */
        .panel{background:#fff;border-radius:10px;border:1px solid var(--border);overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06)}
        .office-section{display:block}
        .anim{animation:fadeIn .3s ease-out}
        @keyframes fadeIn{from{opacity:0;transform:translateY(4px)}to{opacity:1;transform:translateY(0)}}


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
        .btn-logout{display:flex;align-items:center;gap:7px;margin-top:8px;padding:8px 14px;background:rgba(255,255,255,.1);border:none;border-radius:8px;color:rgba(255,255,255,.8);font-size:12px;cursor:pointer;font-family:'Poppins',sans-serif;width:100%;justify-content:center;transition:background .2s}
        .btn-logout:hover{background:rgba(220,38,38,.75)}

        /* ─── Mobile topbar ─── */
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

        /* ─── Main ─── */
        .main{margin-left:0;flex:1;display:flex;flex-direction:column}
        .dash-wrapper{max-width:1200px;width:100%;margin:0 auto;padding:28px 24px 48px;flex:1}

        /* ─── Page header ─── */
        .page-header{margin-bottom:24px}
        .page-header-top{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap}
        .page-header-text h1{font-size:19px;font-weight:700;color:var(--text-dark);letter-spacing:-.2px}
        .page-header-text p{font-size:12.5px;color:var(--text-muted);margin-top:3px}
        .ict-badge{display:inline-flex;align-items:center;gap:6px;background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:600;letter-spacing:.3px;margin-top:8px}

        /* ─── Stats ─── */
        .stats-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px}
        .stat-card{background:#fff;border-radius:10px;padding:16px 18px;box-shadow:none;border:1px solid var(--border);position:relative;overflow:hidden}
        .stat-card::before{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;border-radius:3px 0 0 3px}
        .stat-card.c-blue::before{background:#3b82f6}
        .stat-card.c-amber::before{background:#f59e0b}
        .stat-card.c-green::before{background:#22c55e}
        .stat-card.c-emerald::before{background:#10b981}
        .stat-label{font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.8px;margin-bottom:10px}
        .stat-num{font-size:28px;font-weight:800;color:var(--text-dark);line-height:1;letter-spacing:-1px}
        .stat-sub{font-size:11px;color:var(--text-muted);margin-top:6px}

        /* ─── Table card ─── */
        .table-card{background:#fff;border-radius:12px;border:1px solid var(--border);overflow:hidden}
        .table-head{padding:16px 20px 14px;border-bottom:1px solid var(--border)}
        .table-head-row{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:12px}
        .table-title{font-size:14px;font-weight:700;color:var(--text-dark)}
        .table-count{font-size:12px;color:var(--text-muted)}
        .filters{display:flex;flex-direction:column;gap:8px;margin-top:0}
        .filter-row{display:flex;gap:8px;align-items:center}
        .search-wrap{position:relative;display:flex;align-items:center;flex:1;min-width:0}
        .search-wrap i{position:absolute;left:11px;color:#94a3b8;font-size:13px;pointer-events:none;z-index:1}
        .filters input{padding:8px 12px 8px 34px;font-family:'Poppins',sans-serif;font-size:13px;border:1.5px solid var(--border);border-radius:8px;outline:none;transition:border-color .2s,box-shadow .2s;width:100%;color:var(--text-dark);background:#fff}
        .filters input::placeholder{color:#94a3b8;font-size:12px}
        .filters input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(0,86,179,.1)}
        .filters select{padding:8px 32px 8px 12px;font-family:'Poppins',sans-serif;font-size:13px;border:1.5px solid var(--border);border-radius:8px;outline:none;transition:border-color .2s;color:var(--text-dark);background:#fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 10 10'%3E%3Cpath fill='%2394a3b8' d='M5 7L0 2h10z'/%3E%3C/svg%3E") no-repeat right 11px center;-webkit-appearance:none;appearance:none;cursor:pointer;flex:1;min-width:0}
        .filters select:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(0,86,179,.1)}
        .btn-clear{padding:8px 14px;border:1.5px solid var(--border);border-radius:8px;background:#f8fafc;color:var(--text-muted);font-size:12px;font-family:'Poppins',sans-serif;cursor:pointer;transition:all .2s;display:flex;align-items:center;gap:5px;white-space:nowrap}
        .btn-clear:hover{background:#e2e8f0;color:var(--text-dark)}

        /* ─── Table ─── */
        table{width:100%;border-collapse:collapse}
        th{text-align:left;padding:11px 16px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:var(--text-muted);border-bottom:1px solid var(--border);background:#f8fafc}
        td{padding:12px 16px;font-size:13px;color:var(--text-dark);border-bottom:1px solid #f1f5f9;vertical-align:middle}
        tr:last-child td{border-bottom:none}
        tr:hover td{background:#fafbff}
        .t-num{font-size:12px;font-weight:600;font-family:monospace;letter-spacing:.4px;color:var(--primary)}
        .t-num-sub{font-size:10px;color:var(--text-muted);display:block;font-family:monospace;margin-top:1px}
        .t-date{font-size:12px;color:var(--text-muted)}
        .badge{padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.4px}
        .badge-submitted{background:#eff6ff;color:#2563eb}
        .badge-received{background:#ecfdf5;color:#059669}
        .badge-in_review{background:#fffbeb;color:#d97706}
        .badge-on_hold{background:#fff7ed;color:#ea580c}
        .badge-forwarded{background:#f5f3ff;color:#7c3aed}
        .badge-completed{background:#f0fdf4;color:#15803d}
        .badge-for_pickup{background:#fff7ed;color:#c2410c}
        .badge-returned{background:#fef2f2;color:#dc2626}
        .badge-cancelled{background:#f8fafc;color:#64748b}
        .badge-archived{background:#f1f5f9;color:#9ca3af}
        .td-action{width:140px;text-align:center}
        .btn-view{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;background:var(--primary);color:#fff;border:none;border-radius:7px;font-size:11px;font-weight:600;cursor:pointer;font-family:'Poppins',sans-serif;text-decoration:none;transition:background .2s}
        .btn-view:hover{background:var(--primary-dark)}
        .btn-accept{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;background:#16a34a;color:#fff;border:none;border-radius:7px;font-size:11px;font-weight:600;cursor:pointer;font-family:'Poppins',sans-serif;transition:background .2s;margin-bottom:3px}
        .btn-accept:hover{background:#15803d}
        .btn-manage{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;background:#334155;color:#fff;border:none;border-radius:7px;font-size:11px;font-weight:600;cursor:pointer;font-family:'Poppins',sans-serif;text-decoration:none;transition:background .2s}
        .btn-manage:hover{background:#1e293b}
        tr.doc-row{cursor:pointer}

        /* ─── Drawer ─── */
        .drawer-overlay{position:fixed;inset:0;background:rgba(0,0,0,.35);z-index:998;display:none;backdrop-filter:blur(2px)}
        .drawer-overlay.open{display:block}
        .doc-drawer{position:fixed;top:0;right:-480px;width:480px;max-width:100vw;height:100vh;background:#fff;z-index:999;box-shadow:-4px 0 32px rgba(0,0,0,.14);transition:right .28s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column}
        .doc-drawer.open{right:0}
        .drawer-head{padding:18px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px;flex-shrink:0}
        .drawer-head h3{font-size:15px;font-weight:700;color:var(--text-dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .drawer-close{background:none;border:none;cursor:pointer;font-size:18px;color:var(--text-muted);padding:4px;line-height:1;transition:color .15s}
        .drawer-close:hover{color:#dc2626}
        .drawer-body{padding:20px;flex:1;overflow-y:auto}
        .d-section{margin-bottom:18px}
        .d-section-title{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;margin-bottom:8px;display:flex;align-items:center;gap:6px}
        .d-row{display:flex;gap:12px;margin-bottom:8px;align-items:flex-start}
        .d-label{font-size:11px;font-weight:600;color:var(--text-muted);width:110px;flex-shrink:0;padding-top:1px}
        .d-val{font-size:12.5px;color:var(--text-dark);flex:1;word-break:break-word}
        .d-log-item{border-left:2px solid var(--border);padding:6px 0 6px 12px;margin-bottom:6px}
        .d-log-action{font-size:12px;font-weight:600;color:var(--text-dark)}
        .d-log-meta{font-size:11px;color:var(--text-muted);margin-top:2px}

        /* ─── Modal ─── */
        .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1000;display:none;align-items:center;justify-content:center}
        .modal-overlay.open{display:flex}
        .modal{background:#fff;border-radius:14px;padding:28px;width:340px;max-width:92vw;box-shadow:0 8px 40px rgba(0,0,0,.18)}
        .modal h3{font-size:15px;font-weight:700;color:var(--text-dark);margin-bottom:8px}
        .modal p{font-size:13px;color:var(--text-muted);margin-bottom:20px;line-height:1.6}
        .modal-btns{display:flex;gap:10px;justify-content:flex-end}
        .modal-btns button{padding:9px 20px;border-radius:9px;font-size:13px;font-weight:600;font-family:'Poppins',sans-serif;cursor:pointer;border:none;transition:background .2s}
        .btn-cancel-modal{background:#f1f5f9;color:var(--text-dark)}
        .btn-cancel-modal:hover{background:#e2e8f0}
        .btn-confirm-modal{background:#16a34a;color:#fff}
        .btn-confirm-modal:hover{background:#15803d}

        /* ─── Pagination ─── */
        .pagination-bar{padding:14px 20px;display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;border-top:1px solid var(--border);font-size:12px;color:var(--text-muted)}
        .pagination-links{display:flex;gap:4px}
        .page-btn{display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;padding:0 8px;border-radius:7px;font-size:12px;font-weight:600;text-decoration:none;border:1.5px solid var(--border);color:var(--text-dark);background:#fff;transition:all .15s;cursor:pointer}
        .page-btn:hover{background:var(--primary);color:#fff;border-color:var(--primary)}
        .page-btn.active{background:var(--primary);color:#fff;border-color:var(--primary)}
        .page-btn.disabled{opacity:.4;pointer-events:none}

        /* ─── Empty ─── */
        .empty-state{padding:56px 20px;text-align:center;color:var(--text-muted)}
        .empty-state i{font-size:40px;color:#cbd5e1;margin-bottom:12px;display:block}
        .empty-state h3{font-size:15px;font-weight:600;color:#94a3b8;margin-bottom:6px}
        .empty-state p{font-size:12px}

        /* ─── Footer ─── */
        .site-footer{margin-left:0;width:100%;background:#fff;border-top:1px solid #e2e8f0;padding:20px 28px;display:flex;justify-content:space-between;align-items:center;font-size:12px;color:#94a3b8}
        .site-footer .footer-left{display:flex;align-items:center;gap:6px}
        .site-footer .footer-right{font-size:11px;color:#b0b8c4}

        /* ─── Responsive ─── */
        @media(max-width:900px){
            .dash-wrapper{padding:20px 16px 40px}
            .stats-grid{grid-template-columns:1fr 1fr}
            .page-header-top{flex-direction:column;align-items:flex-start;gap:10px}
            .live-clock{display:none}
            .receive-strip{padding:14px 16px}
            .receive-strip h2{font-size:15px}
            .rs-main{gap:5px}
            .ref-boxes-row{gap:3px}
            .ref-box{height:40px;font-size:14px}
            .ref-sep{font-size:13px;padding:0 1px}
            .btn-clear-x{width:32px;height:32px;font-size:12px}
            .btn-receive{min-width:auto;width:100%;height:42px;font-size:12px}
            .stat-card{padding:14px 14px}
            .stat-num{font-size:24px}
            .stat-label{font-size:10px;margin-bottom:6px}
            .site-footer{flex-direction:column;gap:6px;text-align:center;padding:16px 5%}
            /* Table head */
            .table-head{padding:12px 14px 12px}
            .filters input{font-size:12px;padding:8px 10px 8px 32px}
            .filters select{font-size:12px;padding:8px 28px 8px 10px}
            .btn-clear{font-size:11px;padding:7px 12px}
            /* Hide Type(3), Sender(4), Date(6), arrow(8) on mobile */
            th:nth-child(3),td:nth-child(3),
            th:nth-child(4),td:nth-child(4),
            th:nth-child(6),td:nth-child(6),
            th:nth-child(8),td:nth-child(8){display:none}
            td{padding:10px 12px;font-size:12px}
            th{padding:9px 12px;font-size:9px}
            .table-card{border-radius:10px}
            .btn-accept,.btn-manage{font-size:10px;padding:4px 8px}
        }
        @media(max-width:480px){
            .stats-grid{grid-template-columns:1fr}
        }
    </style>
    <script src="/js/auth-guard.js"></script>
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
        <img src="{{ asset('images/DOCTRAXLOGO.svg') }}" alt="DOCTRAX Logo">
        <h2>DOCTRAX</h2>
        <small>DepEd Document Tracking System</small>
    </div>
    <nav class="sb-nav">
        <span class="nav-section">Overview</span>
        <a href="/dashboard"><i class="fas fa-th-large"></i> Dashboard</a>
        <span class="nav-section">Management</span>
        <a href="/admin/users"><i class="fas fa-users"></i> Users</a>
        <a href="/admin/offices"><i class="fas fa-building"></i> Offices</a>
        <a href="/admin/documents"><i class="fas fa-folder-open"></i> Documents</a>
        @if($user->isSuperAdmin())
        <a href="/records/documents"><i class="fas fa-eye"></i> Records View</a>
        <span class="nav-section">ICT Unit</span>
        <a href="/ict/documents" class="active"><i class="fas fa-network-wired"></i> ICT Documents</a>
        @endif
        @if($user->isSuperAdmin())
        <span class="nav-section">Reports</span>
        <a href="/office/search"><i class="fas fa-chart-line"></i> Reports Dashboard</a>
        @endif
        <span class="nav-section">My Documents</span>
        <a href="/submit"><i class="fas fa-paper-plane"></i> Submit Document</a>
        <a href="/my-documents"><i class="fas fa-folder"></i> My Documents</a>
        <a href="/track"><i class="fas fa-search"></i> Track Document</a>
        <span class="nav-section">Account</span>
        <a href="/profile"><i class="fas fa-user-cog"></i> My Profile</a>
    </nav>
    <div class="sb-footer">
        <div class="sb-user">
            <div class="sb-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div class="sb-user-info">
                <small>{{ $user->isSuperAdmin() ? 'Super Admin' : 'Admin' }}</small>
                <span>{{ explode(' ', $user->name)[0] }}</span>
            </div>
        </div>
        <button onclick="logout()" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>
</div>

<!-- ─── Main ─── -->
<div class="main">
<div class="dash-wrapper">

    <div class="page-header">
        <div class="page-header-top">
            <div>
                <h1>Welcome back, {{ explode(' ', $user->name)[0] }}!</h1>
                <p>Information and Communications Technology Unit &mdash; here's your document queue.</p>
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
    </div>

    <div class="receive-strip">
        <h2>Receive Document</h2>
        <p class="rs-sub">Enter the 8-character tracking number</p>
        <div class="rs-center">
            <div class="rs-main">
                <div class="ref-boxes-row" id="refBoxes">
                    <input type="text" maxlength="1" class="ref-box" data-idx="0" data-no-clearable data-no-capitalize autocomplete="off">
                    <input type="text" maxlength="1" class="ref-box" data-idx="1" data-no-clearable data-no-capitalize autocomplete="off">
                    <input type="text" maxlength="1" class="ref-box" data-idx="2" data-no-clearable data-no-capitalize autocomplete="off">
                    <input type="text" maxlength="1" class="ref-box" data-idx="3" data-no-clearable data-no-capitalize autocomplete="off">
                    <span class="ref-sep">&mdash;</span>
                    <input type="text" maxlength="1" class="ref-box" data-idx="4" data-no-clearable data-no-capitalize autocomplete="off">
                    <input type="text" maxlength="1" class="ref-box" data-idx="5" data-no-clearable data-no-capitalize autocomplete="off">
                    <input type="text" maxlength="1" class="ref-box" data-idx="6" data-no-clearable data-no-capitalize autocomplete="off">
                    <input type="text" maxlength="1" class="ref-box" data-idx="7" data-no-clearable data-no-capitalize autocomplete="off">
                </div>
                <button type="button" class="btn-clear-x" onclick="clearRefBoxes()" title="Clear">&#10005;</button>
            </div>
            <div class="receive-alert" id="receiveRefMsg"><i class="fas fa-exclamation-circle"></i><span></span></div>
        </div>
        <div class="rs-btn-wrap">
            <button class="btn-receive" id="receiveRefBtn" onclick="receiveByReference()"><i class="fas fa-check"></i> Receive</button>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card c-blue">
            <div class="stat-label">Active</div>
            <div class="stat-num" id="stat-active">{{ $stats['active'] }}</div>
            <div class="stat-sub">All non-closed documents</div>
        </div>
        <div class="stat-card c-amber">
            <div class="stat-label">Processing</div>
            <div class="stat-num" id="stat-in-review">{{ $stats['in_review'] }}</div>
            <div class="stat-sub">Being processed</div>
        </div>
        <div class="stat-card c-emerald">
            <div class="stat-label">Completed</div>
            <div class="stat-num" id="stat-completed">{{ $stats['completed'] }}</div>
            <div class="stat-sub">Resolved documents</div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-head">
            <div class="table-head-row">
                <div>
                    <div class="table-title">ICT Unit Documents</div>
                    <div class="table-count">{{ $documents->total() }} document{{ $documents->total() !== 1 ? 's' : '' }} assigned to you</div>
                </div>
            </div>
            <div class="filters">
                <div class="filter-row">
                    <div class="search-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text" id="tblSearch" placeholder="Search reference, subject, sender, type…" oninput="filterTable()">
                    </div>
                </div>
                <div class="filter-row">
                    <select id="tblStatus" onchange="filterTable()">
                        <option value="">All Statuses</option>
                        @foreach(\App\Models\Document::STATUSES as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="btn-clear" onclick="document.getElementById('tblSearch').value='';document.getElementById('tblStatus').value='';filterTable()"><i class="fas fa-rotate-left"></i> Clear</button>
                </div>
            </div>
        </div>

        @if($documents->count())
        <table id="docTable">
            <thead>
                <tr>
                    <th>Reference #</th>
                    <th>Subject</th>
                    <th>Type</th>
                    <th>Submitted By</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="td-action">Actions</th>
                    <th style="width:24px"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                @php $sender = $doc->user ? $doc->user->name : ($doc->sender_name ?? 'Guest'); @endphp
                <tr class="doc-row" data-search="{{ strtolower(($doc->reference_number ?: $doc->tracking_number).' '.$doc->subject.' '.$sender.' '.($doc->type ?? '')) }}" data-status="{{ $doc->status }}" onclick='openDocDetail("{{ $doc->tracking_number }}")'>
                    <td>
                        <span class="t-num">{{ $doc->reference_number ?: $doc->tracking_number }}</span>
                        @if($doc->reference_number && $doc->reference_number !== $doc->tracking_number)
                            <span class="t-num-sub">{{ $doc->tracking_number }}</span>
                        @endif
                    </td>
                    <td style="max-width:200px"><div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="{{ $doc->subject }}">{{ $doc->subject }}</div></td>
                    <td style="font-size:12px;color:var(--text-muted)">{{ $doc->type ?? 'No type specified' }}</td>
                    <td style="font-size:12px">{{ $sender }}</td>
                    <td>
                        <span class="badge badge-{{ $doc->status }}">{{ $doc->statusLabel() }}</span>
                    </td>
                    <td class="t-date">{{ $doc->created_at->format('M d, Y') }}</td>
                    <td class="td-action" onclick="event.stopPropagation()">
                        @if($doc->status === 'submitted')
                        <div style="margin-bottom:3px">
                            <button class="btn-accept" onclick="quickAccept({{ $doc->id }}, this)"><i class="fas fa-check"></i> Accept</button>
                        </div>
                        @endif
                        <a href="/records/documents/{{ $doc->id }}" class="btn-manage" onclick="event.stopPropagation()"><i class="fas fa-folder-open"></i> Manage</a>
                    </td>
                    <td style="color:#cbd5e1;font-size:11px"><i class="fas fa-chevron-right"></i></td>
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

        <div class="empty-state" id="noResults" style="display:none">
            <i class="fas fa-search"></i>
            <h3>No results</h3>
            <p>No documents match your filter.</p>
        </div>

        @else
        <div class="empty-state" id="emptyState">
            <i class="fas fa-network-wired"></i>
            <h3>No documents found</h3>
            <p>No documents are currently assigned to you as ICT.</p>
        </div>
        @endif
    </div>

</div><!-- end dash-wrapper -->

<!-- ─── Document Drawer ─── -->
<div class="drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>
<div class="doc-drawer" id="docDrawer">
    <div class="drawer-head">
        <h3 id="drawerTitle">Document Details</h3>
        <button class="drawer-close" onclick="closeDrawer()"><i class="fas fa-times"></i></button>
    </div>
    <div class="drawer-body" id="drawerBody">
        <div style="text-align:center;padding:40px 0;color:var(--text-muted)"><i class="fas fa-circle-notch fa-spin" style="font-size:24px"></i></div>
    </div>
</div>

<!-- ─── Accept Modal ─── -->
<div class="modal-overlay" id="acceptModal">
    <div class="modal">
        <h3><i class="fas fa-check-circle" style="color:#16a34a;margin-right:6px"></i>Accept Document</h3>
        <p>Accept this document and tag it under ICT Unit? This will update the routing log.</p>
        <div class="modal-btns">
            <button class="btn-cancel-modal" onclick="document.getElementById('acceptModal').classList.remove('open')">Cancel</button>
            <button class="btn-confirm-modal" onclick="confirmAccept()"><i class="fas fa-check"></i> Confirm</button>
        </div>
    </div>
</div>

</div><!-- end .main -->

    <footer class="site-footer">
        <div class="footer-left">
            <span>&copy; {{ date('Y') }} DepEd Document Tracking System</span>
        </div>
        <div class="footer-right">
            Developed by Raymond Bautista
        </div>
    </footer>

<script>
var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var _acceptDocId = null;

    // ─── Sidebar ───
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
    window.logout = window.performLogout || function() {
        window.location.replace('/login');
    };

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
                if(e.key === 'Enter'){
                    e.preventDefault();
                    receiveByReference();
                }
                if(e.key === 'ArrowLeft'){
                    var prev2 = container.querySelector('[data-idx="'+(parseInt(this.dataset.idx)-1)+'"]');
                    if(prev2) prev2.focus();
                }
                if(e.key === 'ArrowRight'){
                    var next2 = container.querySelector('[data-idx="'+(parseInt(this.dataset.idx)+1)+'"]');
                    if(next2) next2.focus();
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
            showReceiveMsg('Please enter all 8 characters of the reference number.', 'err');
            var boxes = document.querySelectorAll('#refBoxes .ref-box');
            for(var i=0;i<boxes.length;i++){
                if(!boxes[i].value){ boxes[i].focus(); break; }
            }
            return;
        }

        showReceiveMsg('', '');
        btn.disabled = true;

        try{
            var res = await fetch('/api/ict/receive-by-reference', {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
                body: JSON.stringify({
                    reference_number: ref,
                    tracking_number: ref
                })
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

    // ─── Accept Document ───
    window.quickAccept = function(docId, btn) {
        _acceptDocId = docId;
        document.getElementById('acceptModal').classList.add('open');
    };
    window.confirmAccept = function() {
        if (!_acceptDocId) return;
        document.getElementById('acceptModal').classList.remove('open');
        fetch('/api/ict/documents/' + _acceptDocId + '/accept', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
            body: JSON.stringify({})
        }).then(function(r){return r.json();}).then(function(d) {
            if (d.success) setTimeout(function(){ location.reload(); }, 600);
            else alert(d.message || 'Could not accept document.');
        }).catch(function() { alert('Network error.'); });
    };

    // ─── Client-side Filter ───
    window.filterTable = function() {
        var q = (document.getElementById('tblSearch').value || '').toLowerCase();
        var s = document.getElementById('tblStatus').value || '';
        var rows = document.querySelectorAll('#docTable tbody tr.doc-row');
        var visible = 0;
        rows.forEach(function(tr) {
            var ds = (tr.getAttribute('data-search') || '').toLowerCase();
            var dstat = tr.getAttribute('data-status') || '';
            var show = (!q || ds.indexOf(q) > -1) && (!s || dstat === s);
            tr.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        var nr = document.getElementById('noResults');
        if (nr) nr.style.display = (visible === 0 && rows.length > 0) ? 'block' : 'none';
    };

    // ─── Document Drawer ───
    window.openDocDetail = function(trackingNumber) {
        document.getElementById('drawerTitle').textContent = trackingNumber;
        document.getElementById('drawerBody').innerHTML = '<div style="text-align:center;padding:40px 0;color:var(--text-muted)"><i class="fas fa-circle-notch fa-spin" style="font-size:24px"></i></div>';
        document.getElementById('docDrawer').classList.add('open');
        document.getElementById('drawerOverlay').classList.add('open');
        document.body.style.overflow = 'hidden';
        fetch('/api/track-document?ref=' + encodeURIComponent(trackingNumber), {
            headers:{'Accept':'application/json','X-CSRF-TOKEN':csrf}
        }).then(function(r){return r.json();}).then(function(d) {
            if(d.found) renderDrawer(d);
            else document.getElementById('drawerBody').innerHTML = '<div class="empty-state"><i class="fas fa-exclamation-circle"></i><h3>Not found</h3><p>'+( d.message || 'Document not found.')+'</p></div>';
        }).catch(function(){
            document.getElementById('drawerBody').innerHTML = '<div class="empty-state"><i class="fas fa-wifi"></i><h3>Network error</h3><p>Could not load document.</p></div>';
        });
    };
    window.closeDrawer = function() {
        document.getElementById('docDrawer').classList.remove('open');
        document.getElementById('drawerOverlay').classList.remove('open');
        document.body.style.overflow = '';
    };
    function escapeHtml(s) {
        if (!s) return '';
        var d = document.createElement('div');
        d.appendChild(document.createTextNode(s));
        return d.innerHTML;
    }
    function renderDrawer(d) {
        var doc = d.document;
        var logs = d.logs || [];
        var logsHtml = logs.length ? logs.map(function(l){
            return '<div class="d-log-item"><div class="d-log-action">' + escapeHtml(l.action_label) + '</div><div class="d-log-meta">' + escapeHtml(l.performed_by_name || 'System') + ' &mdash; ' + escapeHtml(l.created_at) + '</div>' + (l.remarks ? '<div style="font-size:11px;color:var(--text-muted);margin-top:2px">' + escapeHtml(l.remarks) + '</div>' : '') + '</div>';
        }).join('') : '<p style="font-size:12px;color:var(--text-muted)">No routing history yet.</p>';
        document.getElementById('drawerBody').innerHTML =
            '<div class="d-section">' +
                '<div class="d-section-title"><i class="fas fa-file-alt"></i> Document Info</div>' +
                '<div class="d-row"><span class="d-label">Tracking #</span><span class="d-val t-num">' + escapeHtml(doc.tracking_number || 'N/A') + '</span></div>' +
                '<div class="d-row"><span class="d-label">Reference #</span><span class="d-val">' + escapeHtml(doc.reference_number || 'N/A') + '</span></div>' +
                '<div class="d-row"><span class="d-label">Subject</span><span class="d-val">' + escapeHtml(doc.subject || 'No subject') + '</span></div>' +
                '<div class="d-row"><span class="d-label">Type</span><span class="d-val">' + escapeHtml(doc.type || 'No type specified') + '</span></div>' +
                '<div class="d-row"><span class="d-label">Status</span><span class="d-val"><span class="badge badge-' + escapeHtml(doc.status) + '">' + escapeHtml(doc.status_label || doc.status) + '</span></span></div>' +
                '<div class="d-row"><span class="d-label">Description</span><span class="d-val">' + escapeHtml(doc.description || 'No description provided') + '</span></div>' +
            '</div>' +
            '<div class="d-section">' +
                '<div class="d-section-title"><i class="fas fa-route"></i> Routing History</div>' +
                logsHtml +
            '</div>' +
            '<div style="padding-top:8px">' +
                '<a href="/records/documents/' + parseInt(doc.id) + '" class="btn-view" style="width:100%;justify-content:center"><i class="fas fa-folder-open"></i> Full Record</a>' +
            '</div>';
    }

    // ─── Live Stats (silent update every 30s) ───
    var _ictStatKeys = ['active','in_review','completed'];

    function refreshStats() {
        fetch('/api/ict-stats', {headers:{'Accept':'application/json','X-CSRF-TOKEN':csrf}})
            .then(function(r){return r.json();})
            .then(function(d) {
                _ictStatKeys.forEach(function(k) {
                    var el = document.getElementById('stat-' + k.replace('_','-'));
                    if (el && d[k] !== undefined) el.textContent = d[k];
                });
            }).catch(function(){});
    }
    if (window.smartInterval) { window.smartInterval(refreshStats, 30000); }
    else { setInterval(refreshStats, 30000); }

// ─── Live clock ───
(function(){
    var clockInterval;
    function tick(){
        var n=new Date();
        var h=n.getHours(),m=n.getMinutes(),s=n.getSeconds();
        var p=h>=12?'PM':'AM',h12=h%12||12;
        var el=document.getElementById('c-h');if(!el){clearInterval(clockInterval);return;}
        el.textContent=String(h12).padStart(2,'0');
        document.getElementById('c-m').textContent=String(m).padStart(2,'0');
        document.getElementById('c-s').textContent=String(s).padStart(2,'0');
        document.getElementById('c-p').textContent=p;
        var days=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        var mos=['January','February','March','April','May','June','July','August','September','October','November','December'];
        document.getElementById('c-day').textContent=days[n.getDay()];
        document.getElementById('c-date').textContent=mos[n.getMonth()]+' '+n.getDate()+', '+n.getFullYear();
    }
    tick();clockInterval=setInterval(tick,1000);
})();
</script>
</body>
</html>
