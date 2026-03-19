<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="<?php echo e(asset('images/DOCTRAXLOGO.svg')); ?>" type="image/svg+xml">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>About Us - DepEd DOCTRAX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
    <script src="/js/spa.js" defer></script>
    <script src="/js/form-utils.js" defer></script>
    <script src="/js/request-utils.js" defer></script>
    <style>
        .about-wrapper { max-width:800px; margin:0 auto; padding:40px 20px 60px; flex:1; width:100%; }
        .about-hero { text-align:center; margin-bottom:36px; }
        .about-hero img { width:clamp(80px,18vw,120px); height:auto; margin-bottom:16px; }
        .about-hero h2 { font-size:clamp(20px,4vw,28px); font-weight:700; color:var(--text-dark); margin-bottom:6px; }
        .about-hero p { font-size:clamp(13px,2.5vw,15px); color:#64748b; max-width:520px; margin:0 auto; line-height:1.7; }
        .about-card { background:#fff; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,.06); padding:24px 28px; margin-bottom:20px; border:1px solid #e2e8f0; }
        .about-card h3 { font-size:15px; font-weight:600; color:var(--text-dark); margin-bottom:10px; }
        .about-card p, .about-card li { font-size:13px; color:#475569; line-height:1.7; }
        .about-card ul { list-style:none; padding:0; margin:0; }
        .about-card ul li { padding:5px 0; display:flex; align-items:flex-start; gap:10px; }
        .about-card ul li i { color:var(--primary-color); margin-top:3px; flex-shrink:0; font-size:12px; }
        @media(max-width:600px) {
            .about-wrapper { padding:24px 14px 40px; }
            .about-card { padding:20px 18px; }
        }
    </style>
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
            <a href="/about-us" class="nav-link active"><i class="fas fa-info-circle"></i> About Us</a>
            <a href="/contact-us" class="nav-link"><i class="fas fa-envelope"></i> Contact Us</a>
        </div>
    </nav>

    <div class="about-wrapper">
        <div class="about-hero">
            <img src="<?php echo e(asset('images/DOCTRAXLOGO.svg')); ?>" alt="DOCTRAX Logo">
            <h2>About DOCTRAX</h2>
            <p>The document tracking system of the Schools Division of City of San Jose del Monte, Bulacan.</p>
        </div>

        <div class="about-card">
            <h3>What is DOCTRAX?</h3>
            <p>DOCTRAX is a web-based system that lets you submit documents online and track them using a unique tracking number. Instead of following up in person or calling the office, you can check your document's status anytime from your phone or computer.</p>
        </div>

        <div class="about-card">
            <h3>How it works</h3>
            <ul>
                <li><i class="fas fa-check"></i> <div>Submit a document through the online form and get a tracking number.</div></li>
                <li><i class="fas fa-check"></i> <div>Use the tracking number to check your document's status anytime.</div></li>
                <li><i class="fas fa-check"></i> <div>Office staff receive, process, and update the document in the system.</div></li>
                <li><i class="fas fa-check"></i> <div>Once done, the document is marked as completed.</div></li>
            </ul>
        </div>

        <div class="about-card">
            <h3>Who can use it?</h3>
            <ul>
                <li><i class="fas fa-user"></i> <div><strong>Clients &amp; school personnel</strong> — submit and track documents.</div></li>
                <li><i class="fas fa-building"></i> <div><strong>Office representatives</strong> — receive, process, and manage documents.</div></li>
                <li><i class="fas fa-user-shield"></i> <div><strong>Administrators</strong> — manage accounts and oversee the system.</div></li>
            </ul>
        </div>

    </div>

    <footer class="dash-footer">
        <div class="footer-left">
            <span>&copy; <?php echo e(date('Y')); ?> DepEd Document Tracking System</span>
        </div>
        <div class="footer-right">
            Developed by Raymond Bautista
        </div>
    </footer>
</body>
</html>
<?php /**PATH C:\Users\iamra\Desktop\DepedDocumentTrackingSystem\resources\views\about.blade.php ENDPATH**/ ?>