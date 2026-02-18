<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>DepEd Document Tracking System</title>
    <!-- Preconnect for faster font loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
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
        <div class="nav-actions">
           <!-- Placeholder for login or user profile in future -->
        </div>
    </nav>

    <div class="main-wrapper">
        <!-- Main Content -->
        <main class="main-content">
            <div class="greeting">
                <h2>Hello, Guest!<br>Choose your transaction.</h2>
                <p>Welcome to the official document portal.</p>
            </div>

            <!-- Action Buttons -->
            <div class="button-group">
                <a href="/track" class="btn btn-primary">
                    <i class="fas fa-search icon"></i>
                    <span>TRACK<br>DOCUMENT</span>
                </a>
                <a href="/submit" class="btn btn-primary">
                    <i class="fas fa-file-upload icon"></i>
                    <span>SUBMIT<br>DOCUMENT</span>
                </a>
            </div>

            <!-- Login Button -->
            <?php if(auth()->guard()->check()): ?>
                <a href="/dashboard" class="btn btn-login">
                    Go to Dashboard
                </a>
            <?php else: ?>
                <a href="/login" class="btn btn-login">
                    SignUp / Login
                </a>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
<?php /**PATH C:\Users\iamra\Desktop\DepedDocumentTrackingSystem\resources\views/welcome.blade.php ENDPATH**/ ?>