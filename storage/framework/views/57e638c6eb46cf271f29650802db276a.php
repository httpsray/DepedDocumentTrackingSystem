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
    <link rel="stylesheet" href="/css/styles.css">
    <script src="/js/spa.js" defer></script>
    <script src="/js/form-utils.js" defer></script>
    <script src="/js/request-utils.js" defer></script>
    <style>
        .contact-wrapper { max-width:680px; margin:0 auto; padding:40px 20px 60px; flex:1; width:100%; }
        .contact-hero { text-align:center; margin-bottom:36px; }
        .contact-hero img { width:clamp(80px,18vw,120px); height:auto; margin-bottom:16px; }
        .contact-hero h2 { font-size:clamp(20px,4vw,28px); font-weight:700; color:var(--text-dark); margin-bottom:6px; }
        .contact-hero p { font-size:clamp(13px,2.5vw,15px); color:#64748b; max-width:480px; margin:0 auto; line-height:1.7; }
        .contact-card { background:#fff; border-radius:16px; box-shadow:0 4px 24px rgba(0,0,0,.06); padding:28px 32px; margin-bottom:24px; border:1px solid #e2e8f0; }
        .contact-card h3 { font-size:16px; font-weight:700; color:var(--text-dark); margin-bottom:16px; display:flex; align-items:center; gap:10px; }
        .contact-card h3 i { color:var(--primary-color); font-size:18px; }
        .contact-item { display:flex; align-items:flex-start; gap:14px; padding:14px 0; border-bottom:1px solid #f1f5f9; }
        .contact-item:last-child { border-bottom:none; }
        .contact-icon { width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:17px; flex-shrink:0; }
        .contact-detail { flex:1; }
        .contact-detail .label { font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:2px; }
        .contact-detail .value { font-size:14px; font-weight:600; color:var(--text-dark); }
        .contact-detail .value a { color:var(--primary-color); text-decoration:none; }
        .contact-detail .value a:hover { text-decoration:underline; }
        .contact-detail .sub { font-size:12px; color:#64748b; margin-top:2px; }
        @media(max-width:600px) {
            .contact-wrapper { padding:24px 14px 40px; }
            .contact-card { padding:20px 18px; }
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
            <a href="/about-us" class="nav-link"><i class="fas fa-info-circle"></i> About Us</a>
            <a href="/contact-us" class="nav-link active"><i class="fas fa-envelope"></i> Contact Us</a>
        </div>
    </nav>

    <div class="contact-wrapper">
        <div class="contact-hero">
            <img src="<?php echo e(asset('images/DOCTRAXLOGO.svg')); ?>" alt="DOCTRAX Logo">
            <h2>Contact Us</h2>
            <p>Have a question or need help with your document? Reach out to us through any of the channels below.</p>
        </div>

        <div class="contact-card">
            <h3><i class="fas fa-headset"></i> Get in Touch</h3>

            <div class="contact-item">
                <div class="contact-icon" style="background:rgba(0,86,179,.1);color:var(--primary-color)">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="contact-detail">
                    <div class="label">Email Address</div>
                    <div class="value"><a href="mailto:arthur.francisco@deped.gov.ph">arthur.francisco@deped.gov.ph</a></div>
                    <div class="sub">For inquiries regarding document status and submissions</div>
                </div>
            </div>

            <div class="contact-item">
                <div class="contact-icon" style="background:rgba(34,197,94,.1);color:#16a34a">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="contact-detail">
                    <div class="label">Office Hours</div>
                    <div class="value">Monday – Friday, 8:00 AM – 4:00 PM</div>
                    <div class="sub">Closed on weekends and national holidays</div>
                </div>
            </div>
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
<?php /**PATH C:\Users\iamra\Desktop\DepedDocumentTrackingSystem\resources\views/contact.blade.php ENDPATH**/ ?>