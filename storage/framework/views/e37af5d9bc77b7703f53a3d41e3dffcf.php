<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <title>Submit Document - DepEd DTS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/auth.css">
    <script src="/js/spa.js" defer></script>
    <style>
        .container { background: transparent; box-shadow: none; animation: none; }

        .main-wrapper {
            justify-content: center;
            padding-top: 30px;
            padding-bottom: 30px;
            width: 100%;
        }

        .request-container {
            width: 100%;
            max-width: 520px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 40, 100, 0.1);
            overflow: hidden;
            animation: fadeIn 0.5s ease-out;
            font-family: 'Poppins', sans-serif;
        }

        .request-header {
            background: linear-gradient(135deg, #0056b3 0%, #004494 100%);
            padding: 24px 30px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .request-header-icon {
            width: 44px;
            height: 44px;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .request-header-text h2 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            color: #fff;
        }

        .request-header-text p {
            font-size: 12px;
            opacity: 0.8;
            margin: 2px 0 0;
        }

        .request-body {
            padding: 28px 30px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-group label .required {
            color: #dc2626;
            margin-left: 2px;
        }

        .form-group label .optional {
            color: #94a3b8;
            font-weight: 400;
            font-size: 12px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 11px 14px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            color: #1e293b;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: #0056b3;
            box-shadow: 0 0 0 3px rgba(0, 86, 179, 0.1);
            background: #fff;
        }

        .form-input.error,
        .form-select.error,
        .form-textarea.error {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.08);
        }

        .form-select {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }

        .form-textarea {
            resize: vertical;
            min-height: 90px;
        }

        /* Others input (conditional) */
        .others-input-wrapper {
            margin-top: 8px;
            display: none;
        }

        .others-input-wrapper.show {
            display: block;
        }

        .error-text {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            color: #dc2626;
            margin-top: 5px;
            display: none;
            align-items: center;
            gap: 4px;
        }

        .error-text.show {
            display: flex;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, #0056b3 0%, #004494 100%);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 6px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #004494 0%, #003370 100%);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-back {
            width: 100%;
            padding: 11px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            background: transparent;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            margin-top: 8px;
        }

        .btn-back:hover {
            background: #f1f5f9;
            color: #334155;
        }

        /* Success State */
        .success-state {
            display: none;
            padding: 40px 30px;
            text-align: center;
        }

        .success-icon {
            width: 64px;
            height: 64px;
            background: #f0fdf4;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: #16a34a;
        }

        .success-state h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .success-state .subtitle {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 20px;
        }

        .tracking-display {
            background: linear-gradient(135deg, #f0f7ff 0%, #e8f4fd 100%);
            border: 2px dashed #0056b3;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .tracking-display small {
            font-family: 'Poppins', sans-serif;
            font-size: 11px;
            font-weight: 600;
            color: #0056b3;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .tracking-display h3 {
            font-family: 'Poppins', monospace;
            font-size: 28px;
            font-weight: 700;
            color: #0056b3;
            letter-spacing: 3px;
            margin: 6px 0 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 600px) {
            .request-container {
                border-radius: 12px;
                margin: 0 8px;
            }
            .request-header {
                padding: 18px 20px;
            }
            .request-header-icon {
                width: 38px;
                height: 38px;
            }
            .request-header-text h2 {
                font-size: 16px;
            }
            .request-body {
                padding: 20px;
            }
            .tracking-display h3 {
                font-size: 22px;
                letter-spacing: 2px;
            }
            .main-wrapper {
                padding-left: 8px;
                padding-right: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
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
        <div class="request-container">
            <!-- Form State -->
            <div id="form-state">
                <div class="request-header">
                    <div class="request-header-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                    </div>
                    <div class="request-header-text">
                        <h2>Submit Document</h2>
                        <p>Fill in details to generate a tracking number</p>
                    </div>
                </div>

                <div class="request-body">
                    <a href="<?php echo e(auth()->check() ? '/dashboard' : '/'); ?>" style="display: inline-flex; align-items: center; gap: 6px; color: #64748b; text-decoration: none; font-size: 13px; font-weight: 500; margin-bottom: 16px; transition: color 0.2s;" onmouseover="this.style.color='#0056b3'" onmouseout="this.style.color='#64748b'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                        <?php echo e(auth()->check() ? 'Back to Dashboard' : 'Back to Home'); ?>

                    </a>
                    <div class="form-group">
                        <label>Document Type <span class="required">*</span></label>
                        <select class="form-select" id="docType" onchange="toggleOthers()">
                            <option value="" disabled selected>Select document type</option>
                            <option value="TOR">Transcript of Records (TOR)</option>
                            <option value="Others">Others</option>
                        </select>
                        <div class="error-text" id="docTypeError"><i class="fas fa-exclamation-circle"></i> <span>Please select a document type</span></div>
                    </div>

                    <div class="others-input-wrapper" id="othersWrapper">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label>Please specify <span class="required">*</span></label>
                            <input type="text" class="form-input" id="othersSpecify" placeholder="e.g. Certificate of Enrollment" autocomplete="off">
                            <div class="error-text" id="othersError"><i class="fas fa-exclamation-circle"></i> <span>Please specify the document type</span></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Subject <span class="required">*</span></label>
                        <input type="text" class="form-input" id="subject" placeholder="e.g. Submit for TOR — Juan Dela Cruz" autocomplete="off">
                        <div class="error-text" id="subjectError"><i class="fas fa-exclamation-circle"></i> <span>Subject is required</span></div>
                    </div>

                    <div class="form-group">
                        <label>Description <span class="optional">(Optional)</span></label>
                        <textarea class="form-textarea" id="description" placeholder="Enter additional details or remarks..."></textarea>
                    </div>

                    <button class="btn-submit" id="submitBtn" onclick="submitDocument()">
                        <i class="fas fa-paper-plane" style="margin-right: 6px;"></i>Generate Tracking Number
                    </button>
                </div>
            </div>

            <!-- Success State -->
            <div class="success-state" id="success-state">
                <div class="success-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2>Submitted!</h2>
                <p class="subtitle">Save your tracking number to monitor the status of your document.</p>

                <div class="tracking-display">
                    <small>Tracking Number</small>
                    <h3 id="generatedTracking"></h3>
                </div>

                <button class="btn-submit" onclick="window.location.reload()">
                    <i class="fas fa-plus" style="margin-right: 6px;"></i>Submit Another
                </button>
                <button class="btn-back" onclick="window.location.href='/track'">
                    <i class="fas fa-search" style="margin-right: 6px;"></i>Track This Document
                </button>
            </div>
        </div>
    </div>

    <script>
        function toggleOthers() {
            const type = document.getElementById('docType').value;
            const wrapper = document.getElementById('othersWrapper');
            if (type === 'Others') {
                wrapper.classList.add('show');
            } else {
                wrapper.classList.remove('show');
                document.getElementById('othersSpecify').value = '';
            }
        }

        function showFieldError(id) {
            const el = document.getElementById(id);
            const input = el.previousElementSibling || el.closest('.form-group').querySelector('.form-input, .form-select');
            if (input) input.classList.add('error');
            el.classList.add('show');
        }

        function clearErrors() {
            document.querySelectorAll('.error-text').forEach(e => e.classList.remove('show'));
            document.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(e => e.classList.remove('error'));
        }

        async function submitDocument() {
            clearErrors();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const btn = document.getElementById('submitBtn');
            let valid = true;

            const docType = document.getElementById('docType').value;
            const subject = document.getElementById('subject').value.trim();
            const description = document.getElementById('description').value.trim();
            const othersVal = document.getElementById('othersSpecify').value.trim();

            if (!docType) {
                document.getElementById('docType').classList.add('error');
                document.getElementById('docTypeError').classList.add('show');
                valid = false;
            }

            if (docType === 'Others' && !othersVal) {
                document.getElementById('othersSpecify').classList.add('error');
                document.getElementById('othersError').classList.add('show');
                valid = false;
            }

            if (!subject) {
                document.getElementById('subject').classList.add('error');
                document.getElementById('subjectError').classList.add('show');
                valid = false;
            }

            if (!valid) return;

            // Build final type string
            const finalType = docType === 'Others' ? 'Others: ' + othersVal : docType;

            btn.innerHTML = '<span class="loading-dots"><span></span></span>';
            btn.disabled = true;

            try {
                const response = await fetch('/api/submit-document', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        subject: subject,
                        type: finalType,
                        description: description
                    })
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('form-state').style.display = 'none';
                    document.getElementById('success-state').style.display = 'block';
                    document.getElementById('generatedTracking').innerText = data.data.tracking_number;
                } else {
                    throw new Error(data.message || 'Submission failed');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('System error occurred. Please try again.');
                btn.innerHTML = '<i class="fas fa-paper-plane" style="margin-right: 6px;"></i>Generate Tracking Number';
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>
<?php /**PATH C:\Users\iamra\Desktop\DepedDocumentTrackingSystem\resources\views/submit/index.blade.php ENDPATH**/ ?>