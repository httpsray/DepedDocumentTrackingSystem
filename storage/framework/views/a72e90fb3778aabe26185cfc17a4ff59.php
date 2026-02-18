<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Document - DepEd DTS</title>
    <!-- Preconnect for faster font loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/auth.css"> <!-- Reusing form styles -->
    <script src="/js/spa.js" defer></script>
    <style>
        .container { background: transparent; box-shadow: none; animation: none; }
        
        .main-wrapper {
            justify-content: center; /* Center horizontally */
            padding-top: 40px;
            width: 100%;
        }

        /* Tracking Card */
        .tracking-container {
             width: 100%;
             max-width: 600px;
             background: white;
             padding: 40px;
             border-radius: 12px;
             box-shadow: var(--shadow-lg);
             animation: fadeIn 0.5s ease-out;
             font-family: 'Poppins', sans-serif;
        }

        /* Search Box */
        .search-box {
            position: relative;
            margin-bottom: 30px;
            margin-top: 30px;
        }

        .search-input {
            width: 100%;
            padding: 16px 60px 16px 24px;
            font-size: 16px;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
            background: #f8fafc;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 86, 179, 0.1);
        }

        .search-btn {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary-color);
            color: white;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .search-btn:hover {
            background: #004494;
            transform: translateY(-50%) scale(1.05);
        }

        /* Timeline / Results */
        .tracker-result {
            margin-top: 40px;
            border-top: 1px solid #f1f5f9;
            padding-top: 30px;
            display: none; /* Hidden by default */
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            background: #dbeafe;
            color: #1e40af;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .timeline {
             position: relative;
             padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 35px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -30px;
            top: 5px;
            width: 14px;
            height: 14px;
            background: #cbd5e1;
            border: 3px solid #fff;
            border-radius: 50%;
            z-index: 2;
            box-shadow: 0 0 0 1px #cbd5e1;
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            left: -24px;
            top: 22px;
            width: 2px;
            height: calc(100% - 15px);
            background: #e2e8f0;
            z-index: 1;
        }

        .timeline-item:last-child::after {
            display: none;
        }

        .timeline-item.active::before {
             background: var(--primary-color);
             box-shadow: 0 0 0 4px rgba(0, 86, 179, 0.2);
             border-color: #fff;
        }
        
        .timeline-item.active h4 {
            color: var(--primary-color);
        }

        .timeline-content h4 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
            color: var(--text-dark);
        }

        .timeline-content .time {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 4px;
            display: block;
        }

        .timeline-content .details {
            font-size: 13px;
            color: #334155;
            background: #f8fafc;
            padding: 8px 12px;
            border-radius: 6px;
            display: inline-block;
        }

        /* Mobile Responsive */
        @media (max-width: 600px) {
            .tracking-container {
                padding: 20px 16px;
                border-radius: 10px;
            }
            .search-input {
                padding: 12px 50px 12px 16px;
                font-size: 14px;
            }
            .search-btn {
                width: 38px;
                height: 38px;
                font-size: 16px;
            }
            .main-wrapper {
                padding-top: 20px;
                padding-left: 10px;
                padding-right: 10px;
            }
            .timeline-content h4 {
                font-size: 14px;
            }
            .timeline-content .details {
                font-size: 12px;
            }
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
        <div class="nav-actions">
        </div>
    </nav>
    
    <div class="main-wrapper">
        <div class="tracking-container">
            <a href="<?php echo e(auth()->check() ? '/dashboard' : '/'); ?>" style="display: inline-flex; align-items: center; gap: 6px; color: #64748b; text-decoration: none; font-size: 13px; font-weight: 500; margin-bottom: 16px; transition: color 0.2s;" onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#64748b'">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                <?php echo e(auth()->check() ? 'Back to Dashboard' : 'Back to Home'); ?>

            </a>
            <div class="auth-header" style="text-align: center; margin-bottom: 20px;">
                 <div style="background: #eff6ff; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: var(--primary-color);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-file-description"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 2l.117 .007a1 1 0 0 1 .876 .876l.007 .117v4l.005 .15a2 2 0 0 0 1.838 1.844l.157 .006h4l.117 .007a1 1 0 0 1 .876 .876l.007 .117v9a3 3 0 0 1 -2.824 2.995l-.176 .005h-10a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-14a3 3 0 0 1 2.824 -2.995l.176 -.005zm3 14h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2m0 -4h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2" /><path d="M19 7h-4l-.001 -4.001z" /></svg>
                 </div>
                 <h2>Track Document</h2>
                 <p>Enter your tracking number to check status.</p>
            </div>
            
            <div class="search-box">
                <div style="position: relative;">
                    <input type="text" class="search-input" placeholder="Enter Tracking Number (e.g., CSJDM-2026-000001)" id="trackingInput" autocomplete="off">
                    <button class="search-btn" onclick="searchDocument()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div id="errorMsg" style="color: #dc2626; font-size: 13px; margin-top: 8px; margin-left: 12px; display: none; font-weight: 500;">
                    <i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i> <span></span>
                </div>
            </div>

            <!-- Empty State -->
            <div id="initialState" style="text-align: center; opacity: 0.5; margin-top: 40px; margin-bottom: 20px;">
                <p style="font-size: 13px; font-style: italic;">Enter a valid tracking ID to see results</p>
            </div>
            
             <!-- Result Area (Sample Design) -->
            <div id="resultsArea" class="tracker-result">
                 <div style="margin-bottom: 25px;">
                     <span class="status-badge">In Progress</span>
                     <h3 style="font-size: 20px; margin-bottom: 5px;">Tracking #: <span style="font-family: monospace; color: var(--text-dark);">2024-ABC-123</span></h3>
                     <p style="font-size: 14px; color: #64748b;">Subject: Request for CCTV Installation</p>
                 </div>
                 
                 <div class="timeline">
                    <!-- Step 1 (Latest) -->
                    <div class="timeline-item active">
                        <div class="timeline-content">
                            <h4>Received at Division Office</h4>
                            <span class="time">Oct 24, 2023 &bull; 10:30 AM</span>
                            <div class="details">
                                <i class="fas fa-user-circle" style="margin-right: 5px;"></i>
                                Received by: Juan Dela Cruz (Records)
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 2 -->
                     <div class="timeline-item">
                        <div class="timeline-content">
                            <h4 style="color: #64748b;">Forwarded to ASDS Office</h4>
                            <span class="time">Oct 23, 2023 &bull; 04:15 PM</span>
                            <div class="details">
                                Action: For Approval
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 (Initial) -->
                     <div class="timeline-item">
                        <div class="timeline-content">
                            <h4 style="color: #64748b;">Document Submitted</h4>
                            <span class="time">Oct 23, 2023 &bull; 02:00 PM</span>
                            <div class="details">
                                Origin: School ID 123456
                            </div>
                        </div>
                    </div>
                 </div>
            </div>

        </div>
    </div>

    <script>
        async function searchDocument() {
            const inputEl = document.getElementById('trackingInput');
            const results = document.getElementById('resultsArea');
            const initial = document.getElementById('initialState');
            const errorMsg = document.getElementById('errorMsg');
            
            const val = inputEl.value.trim();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Reset States
            inputEl.style.borderColor = '#e2e8f0';
            errorMsg.style.display = 'none';
            results.style.display = 'none';

            if (val === '') {
                inputEl.style.borderColor = '#dc2626';
                errorMsg.querySelector('span').innerText = 'Please enter a tracking number';
                errorMsg.style.display = 'block';
                inputEl.focus();
                initial.style.display = 'block';
                return;
            }

            try {
                // Call Backend API
                const response = await fetch('/api/track-document', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ tracking_number: val })
                });

                const data = await response.json();

                if (data.success) {
                    // Success: Show Data
                    initial.style.display = 'none';
                    results.style.display = 'block';
                    results.style.animation = 'fadeIn 0.5s ease-out';
                    
                    // Update DOM with Real Data
                    results.querySelector('h3 span').innerText = data.data.tracking_number;
                    results.querySelector('p').innerText = 'Subject: ' + data.data.subject;
                    results.querySelector('.status-badge').innerText = data.data.status.toUpperCase();
                    
                    // You can also dynamically populate the timeline here based on data.data.created_at etc.

                } else {
                    // Not Found / Error
                    inputEl.style.borderColor = '#dc2626';
                    errorMsg.querySelector('span').innerText = data.message || 'Reference number not found';
                    errorMsg.style.display = 'block';
                    initial.style.display = 'block';
                }
            } catch (error) {
                console.error('Error:', error);
                inputEl.style.borderColor = '#dc2626';
                errorMsg.querySelector('span').innerText = 'System error occurred. Please try again.';
                errorMsg.style.display = 'block';
            }
        }
        
        // Allow Enter key to search
        document.getElementById('trackingInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                searchDocument();
            }
        });
        
        // Remove error on input
        document.getElementById('trackingInput').addEventListener('input', function() {
             this.style.borderColor = '#e2e8f0';
             document.getElementById('errorMsg').style.display = 'none';
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\iamra\Desktop\DepedDocumentTrackingSystem\resources\views/track/index.blade.php ENDPATH**/ ?>