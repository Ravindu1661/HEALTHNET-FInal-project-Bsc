<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - HEALTHNET</title>
    
    <!-- Favicon Links -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Android Chrome Icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('android-chrome-512x512.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .forgot-password-container {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 3rem 2.5rem;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }

        .header-section h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .header-section p {
            color: #718096;
            font-size: 0.95rem;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #a0aec0;
            transition: all 0.3s ease;
        }

        .step.active {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }

        .form-control {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 3rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f7fafc;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
            background: white;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #2563eb;
        }

        .otp-inputs {
            display: flex;
            gap: 0.8rem;
            justify-content: center;
        }

        .otp-input {
            width: 50px;
            height: 55px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: #f7fafc;
            transition: all 0.3s ease;
        }

        .otp-input:focus {
            border-color: #2563eb;
            background: white;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-primary {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        }

        .btn-primary:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
            transform: none;
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border: none;
        }

        .alert-success {
            background: #f0fff4;
            color: #22543d;
        }

        .alert-danger {
            background: #fff5f5;
            color: #742a2a;
        }

        .back-to-login {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-to-login a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-to-login a:hover {
            color: #1e40af;
        }

        .resend-otp {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #718096;
        }

        .resend-link {
            color: #2563eb;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
        }

        .resend-link:hover {
            text-decoration: underline;
        }

        .hidden {
            display: none;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <div class="header-section">
            <div class="header-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h2>Password Reset</h2>
            <p id="stepDescription">Enter your email</p>
        </div>

        <div class="step-indicator">
            <div class="step active" id="step1">1</div>
            <div class="step" id="step2">2</div>
            <div class="step" id="step3">3</div>
        </div>

        <div id="alertContainer"></div>

        <!-- Step 1: Enter Email -->
        <form id="emailForm" class="step-form">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="Enter your email" required>
                </div>
            </div>
            <button type="submit" class="btn-primary" id="sendOtpBtn">
                <span class="btn-text">Send OTP</span>
            </button>
        </form>

        <!-- Step 2: Verify OTP -->
        <form id="otpForm" class="step-form hidden">
            @csrf
            <div class="form-group">
                <label class="form-label">Enter 6-Digit OTP</label>
                <div class="otp-inputs">
                    <input type="text" class="otp-input" maxlength="1" data-index="0">
                    <input type="text" class="otp-input" maxlength="1" data-index="1">
                    <input type="text" class="otp-input" maxlength="1" data-index="2">
                    <input type="text" class="otp-input" maxlength="1" data-index="3">
                    <input type="text" class="otp-input" maxlength="1" data-index="4">
                    <input type="text" class="otp-input" maxlength="1" data-index="5">
                </div>
            </div>
            <div class="resend-otp">
                Didn’t receive the OTP? <a class="resend-link" id="resendOtp">Resend</a>
            </div>
            <button type="submit" class="btn-primary" id="verifyOtpBtn">
                <span class="btn-text">Verify OTP</span>
            </button>
        </form>

        <!-- Step 3: Reset Password -->
        <form id="resetForm" class="step-form hidden">
            @csrf
            <div class="form-group">
                <label class="form-label">New Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control" id="password" 
                           name="password" placeholder="Enter new password" required>
                    <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control" id="password_confirmation" 
                           name="password_confirmation" placeholder="Confirm new password" required>
                    <i class="fas fa-eye password-toggle" id="toggleConfirmPassword"></i>
                </div>
            </div>
            <button type="submit" class="btn-primary" id="resetPasswordBtn">
                <span class="btn-text">Reset Password</span>
            </button>
        </form>

        <div class="back-to-login">
            <a href="{{ route('login') }}">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentEmail = '';
        let currentOtp = '';

        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Show Alert
        function showAlert(message, type = 'danger') {
            const alertHtml = `
                <div class="alert alert-${type}">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    ${message}
                </div>
            `;
            document.getElementById('alertContainer').innerHTML = alertHtml;
            
            setTimeout(() => {
                document.getElementById('alertContainer').innerHTML = '';
            }, 5000);
        }

        // Show Loading State
        function showLoading(button, show = true) {
            const btn = document.getElementById(button);
            const btnText = btn.querySelector('.btn-text');
            
            if (show) {
                btn.disabled = true;
                btnText.innerHTML = '<span class="loading-spinner"></span> Loading...';
            } else {
                btn.disabled = false;
                if (button === 'sendOtpBtn') btnText.textContent = 'Send OTP';
                else if (button === 'verifyOtpBtn') btnText.textContent = 'Verify OTP';
                else if (button === 'resetPasswordBtn') btnText.textContent = 'Reset Password';
            }
        }

        // Navigate to Step
        function goToStep(stepNumber) {
            document.querySelectorAll('.step-form').forEach(form => form.classList.add('hidden'));
            
            if (stepNumber === 1) {
                document.getElementById('emailForm').classList.remove('hidden');
                document.getElementById('stepDescription').textContent = 'Enter your email';
            } else if (stepNumber === 2) {
                document.getElementById('otpForm').classList.remove('hidden');
                document.getElementById('stepDescription').textContent = 'Enter the OTP';
            } else if (stepNumber === 3) {
                document.getElementById('resetForm').classList.remove('hidden');
                document.getElementById('stepDescription').textContent = 'Enter your new password';
            }

            document.querySelectorAll('.step').forEach((step, index) => {
                if (index < stepNumber) {
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                }
            });
        }

        // Step 1: Send OTP
        document.getElementById('emailForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            currentEmail = email;
            
            showLoading('sendOtpBtn', true);

            try {
                const response = await fetch('{{ route("password.sendOtp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ email })
                });

                const data = await response.json();

                if (data.success) {
                    showAlert(data.message, 'success');
                    setTimeout(() => goToStep(2), 1500);
                } else {
                    showAlert(data.message, 'danger');
                }
            } catch (error) {
                showAlert('An error occurred. Please try again.', 'danger');
            } finally {
                showLoading('sendOtpBtn', false);
            }
        });

        // OTP Input Auto-Focus
        const otpInputs = document.querySelectorAll('.otp-input');
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                if (this.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        // Step 2: Verify OTP
        document.getElementById('otpForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const otp = Array.from(otpInputs).map(input => input.value).join('');
            
            if (otp.length !== 6) {
                showAlert('Please enter the complete OTP.', 'danger');
                return;
            }

            currentOtp = otp;
            showLoading('verifyOtpBtn', true);

            try {
                const response = await fetch('{{ route("password.verifyOtp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ email: currentEmail, otp })
                });

                const data = await response.json();

                if (data.success) {
                    showAlert(data.message, 'success');
                    setTimeout(() => goToStep(3), 1500);
                } else {
                    showAlert(data.message, 'danger');
                    otpInputs.forEach(input => input.value = '');
                    otpInputs[0].focus();
                }
            } catch (error) {
                showAlert('An error occurred.', 'danger');
            } finally {
                showLoading('verifyOtpBtn', false);
            }
        });

        // Resend OTP
        document.getElementById('resendOtp').addEventListener('click', async function() {
            showLoading('verifyOtpBtn', true);

            try {
                const response = await fetch('{{ route("password.sendOtp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ email: currentEmail })
                });

                const data = await response.json();
                showAlert(data.message, data.success ? 'success' : 'danger');
                
                if (data.success) {
                    otpInputs.forEach(input => input.value = '');
                    otpInputs[0].focus();
                }
            } catch (error) {
                showAlert('Error occurred while resending OTP.', 'danger');
            } finally {
                showLoading('verifyOtpBtn', false);
            }
        });

        // Step 3: Reset Password
        document.getElementById('resetForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;

            if (password !== password_confirmation) {
                showAlert('Passwords do not match.', 'danger');
                return;
            }

            if (password.length < 8) {
                showAlert('Password must be at least 8 characters long.', 'danger');
                return;
            }

            showLoading('resetPasswordBtn', true);

            try {
                const response = await fetch('{{ route("password.reset") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ 
                        email: currentEmail, 
                        otp: currentOtp,
                        password,
                        password_confirmation
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showAlert(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    showAlert(data.message, 'danger');
                }
            } catch (error) {
                showAlert('An error occurred while resetting the password.', 'danger');
            } finally {
                showLoading('resetPasswordBtn', false);
            }
        });

        // Password Toggle
        ['togglePassword', 'toggleConfirmPassword'].forEach(id => {
            document.getElementById(id).addEventListener('click', function() {
                const input = this.previousElementSibling;
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
    </script>

</body>
</html>
