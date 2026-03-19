<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="<?php echo e(asset('images/DOCTRAXLOGO.svg')); ?>" type="image/svg+xml">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Contact Us - DepEd DOCTRAX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #0056b3;
            --primary-dark: #004494;
            --primary-gradient: linear-gradient(135deg, #0056b3 0%, #004494 100%);
            --text-dark: #1b263b;
            --text-muted: #64748b;
            --white: #ffffff;
            --bg: #f0f2f5;
            --border: #e2e8f0;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.06);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        html { overflow-y:scroll; }
        body { background:var(--bg); font-family:'Poppins',sans-serif; color:var(--text-dark); min-height:100vh; display:flex; flex-direction:column; }

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

        /* ─── Main ─── */
        .main{margin-left:0;flex:1;display:flex;flex-direction:column}

        /* ─── Content ─── */
        .contact-wrapper { max-width:680px; width:100%; margin:0 auto; padding:28px 24px 48px; flex:1; }
        .back-link { display:inline-flex; align-items:center; gap:6px; color:var(--text-muted); font-size:13px; text-decoration:none; margin-bottom:18px; transition:color .15s; }
        .back-link:hover { color:var(--primary); }

        .contact-hero { text-align:center; margin-bottom:36px; }
        .contact-hero img { width:clamp(80px,18vw,120px); height:auto; margin-bottom:16px; }
        .contact-hero h2 { font-size:clamp(20px,4vw,28px); font-weight:700; color:var(--text-dark); margin-bottom:6px; }
        .contact-hero p { font-size:clamp(13px,2.5vw,15px); color:#64748b; max-width:480px; margin:0 auto; line-height:1.7; }
        .contact-card { background:#fff; border-radius:16px; box-shadow:0 4px 24px rgba(0,0,0,.06); padding:32px; border:1px solid #e2e8f0; margin-bottom:24px; }
        .contact-item { display:flex; gap:16px; margin-bottom:22px; align-items:flex-start; }
        .contact-item:last-child { margin-bottom:0; }
        .contact-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
        .contact-detail h4 { font-size:14px; font-weight:700; color:var(--text-dark); margin-bottom:4px; }
        .contact-detail p { font-size:13px; color:#475569; line-height:1.7; margin:0; }
        .contact-detail a { color:var(--primary); text-decoration:none; font-weight:500; }
        .contact-detail a:hover { text-decoration:underline; }

        /* ─── Footer ─── */
        .site-footer{width:100%;background:#fff;border-top:1px solid #e2e8f0;padding:20px 5%;display:flex;justify-content:space-between;align-items:center;font-size:12px;color:#94a3b8;margin-top:auto}
        .footer-right{font-size:11px;color:#b0b8c4}

        @media(max-width:900px){
            .site-footer{flex-direction:column;gap:6px;text-align:center;padding:16px 5%}
        }
        @media(max-width:600px) {
            .contact-wrapper { padding:24px 14px 40px; }
            .contact-card { padding:22px 18px; }
        }
    </style>
    <script src="/js/spa.js" defer></script>
    <script src="/js/form-utils.js" defer></script>
    <script src="/js/request-utils.js" defer></script>
</head>
<body>

<?php
    $isRep = ($user->account_type ?? '') === 'representative';
    $navOfficeName = $isRep ? ($user->office?->name ?? 'Office') : '';
    $navRepName = $user->name;
    $navDisplayName = $isRep ? $navOfficeName : $user->name;
    $initials = collect(explode(' ', trim($user->name)))->filter()->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('');
?>

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
        <span class="nav-section">Office</span>
        <a href="/office/dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="/office/search" id="reports-nav-link" style="<?php echo e($user->hasReportsAccess() ? '' : 'display:none'); ?>"><i class="fas fa-chart-line"></i> Reports</a>
        <?php if($user->isRecords() || $user->isSuperAdmin()): ?>
        <span class="nav-section">Records Section</span>
        <a href="/records/documents"><i class="fas fa-folder-open"></i> All Documents</a>
        <?php endif; ?>
        <span class="nav-section">My Documents</span>
        <a href="/submit"><i class="fas fa-paper-plane"></i> Submit Document</a>
        <a href="/my-documents"><i class="fas fa-folder"></i> My Documents</a>
        <span class="nav-section">Account</span>
        <a href="/profile"><i class="fas fa-user-circle"></i> My Profile</a>
    </nav>
    <div class="sb-footer">
        <div class="sb-user">
            <div class="sb-avatar"><?php echo e($initials); ?></div>
            <div class="sb-user-info">
                <small><?php echo e($navOfficeName ?: 'Office'); ?></small>
                <span><?php echo e($navRepName ?: $navDisplayName); ?></span>
            </div>
        </div>
        <button onclick="logout()" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>
</div>

<!-- ─── Main Content ─── -->
<div class="main">

<div class="contact-wrapper">

    <?php if(request()->query('from') === 'profile'): ?>
    <a href="/profile" class="back-link"><i class="fas fa-arrow-left"></i> Back to Profile</a>
    <?php else: ?>
    <a href="/office/dashboard" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    <?php endif; ?>

    <div class="contact-hero">
        <img src="<?php echo e(asset('images/DOCTRAXLOGO.svg')); ?>" alt="DOCTRAX Logo">
        <h2>Contact Us</h2>
        <p>Have questions, feedback, or need technical support? The Schools Division Office of City of San Jose del Monte is here to help.</p>
    </div>

    <div class="contact-card">
        <div class="contact-item">
            <div class="contact-icon" style="background:rgba(0,86,179,.1);color:var(--primary)">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="contact-detail">
                <h4>Email</h4>
                <p>For inquiries or support, reach us at:<br><a href="mailto:arthur.francisco@deped.gov.ph">arthur.francisco@deped.gov.ph</a></p>
            </div>
        </div>
        <div class="contact-item">
            <div class="contact-icon" style="background:rgba(34,197,94,.1);color:#16a34a">
                <i class="fas fa-clock"></i>
            </div>
            <div class="contact-detail">
                <h4>Office Hours</h4>
                <p>Monday – Friday, 8:00 AM – 4:00 PM<br>We respond to messages during office hours only.</p>
            </div>
        </div>
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

</div><!-- /.main -->

<script>
(function(){
    var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    window.logout = function() {
        fetch('/api/logout', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } })
        .then(function() { window.location.href = '/login'; })
        .catch(function() { window.location.href = '/login'; });
    };
})();

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

document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeSidebar(); });
</script>
</body>
</html>
<?php /**PATH C:\Users\iamra\Desktop\DepedDocumentTrackingSystem\resources\views\office\contact.blade.php ENDPATH**/ ?>