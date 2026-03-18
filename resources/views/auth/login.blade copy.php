<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HealthNet</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        html {
            height: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin: 0;
            position: relative;
            overflow: hidden;
        }

        /* Background decoration circles */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 0;
        }

        body::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            z-index: 0;
        }

        .page-container {
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .back-home {
            position: fixed;
            top: 1.5rem;
            left: 1.5rem;
            color: white;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            z-index: 1000;
            font-size: 0.95rem;
            background: rgba(255, 255, 255, 0.15);
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .back-home:hover {
            color: white;
            background: rgba(255, 255, 255, 0.25);
            transform: translateX(-3px);
        }

        .login-wrapper {
            width: 100%;
            max-width: 900px;
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 80px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            display: flex;
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s ease-out;
            margin: auto;
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

        /* Left Side - Illustration */
        .illustration-section {
            flex: 1;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            padding: 2.5rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .illustration-section::before {
            content: '';
            position: absolute;
            top: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .illustration-section::after {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .illustration-container {
            width: 100%;
            max-width: 350px;
            position: relative;
            z-index: 2;
        }

        .illustration-container img {
            width: 100%;
            height: auto;
            filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.2));
        }

        /* Right Side - Login Form */
        .form-section {
            flex: 1;
            padding: 2.5rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
        }

        .form-header {
            margin-bottom: 1.8rem;
        }

        .form-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #718096;
            font-size: 0.9rem;
            margin: 0;
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.3rem;
            font-size: 0.88rem;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background: #fee;
            color: #c33;
            border-left: 4px solid #c33;
        }

        .alert-success {
            background: #f0fff4;
            color: #22543d;
            border-left: 4px solid #22543d;
        }

        .alert i {
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 1.3rem;
        }

        .form-label {
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            display: block;
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
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.8rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: #f7fafc;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
            background: white;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            background: #fff5f5;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 0.4rem;
            margin-left: 0.5rem;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
            transition: color 0.3s ease;
            font-size: 0.95rem;
        }

        .password-toggle:hover {
            color: #2563eb;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.3rem;
            font-size: 0.85rem;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-check-input {
            width: 17px;
            height: 17px;
            cursor: pointer;
            border: 2px solid #e2e8f0;
        }

        .form-check-input:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .form-check-label {
            color: #4a5568;
            cursor: pointer;
            margin: 0;
            font-size: 0.85rem;
        }

        .forgot-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            font-size: 0.85rem;
        }

        .forgot-link:hover {
            color: #1e40af;
        }

        .btn-login {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.3rem 0;
            color: #a0aec0;
            font-size: 0.8rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            padding: 0 1rem;
        }

        .social-login {
            display: flex;
            gap: 0.9rem;
            margin-bottom: 1.3rem;
        }

        .social-btn {
            flex: 1;
            padding: 0.8rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            font-weight: 500;
            font-size: 0.85rem;
            text-decoration: none;
            color: #4a5568;
        }

        .social-btn:hover {
            border-color: #2563eb;
            background: rgba(37, 99, 235, 0.05);
            transform: translateY(-2px);
            color: #4a5568;
        }

        .social-btn i {
            font-size: 1.1rem;
        }

        .google-btn i {
            color: #db4437;
        }

        .facebook-btn i {
            color: #4267B2;
        }

        .signup-link {
            text-align: center;
            color: #718096;
            font-size: 0.9rem;
        }

        .signup-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .signup-link a:hover {
            color: #1e40af;
        }

        /* Mobile illustration section */
        .mobile-illustration {
            display: none;
            width: 100%;
            padding: 2rem 1.5rem 1.5rem;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border-radius: 25px 25px 0 0;
            position: relative;
            overflow: hidden;
        }

        .mobile-illustration::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .mobile-illustration-img {
            width: 100%;
            max-width: 250px;
            margin: 0 auto;
            display: block;
            position: relative;
            z-index: 2;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.2));
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .illustration-section {
                display: none;
            }

            .mobile-illustration {
                display: block;
            }

            .login-wrapper {
                max-width: 500px;
                flex-direction: column;
            }

            .form-section {
                padding: 2rem 2rem;
                border-radius: 0 0 25px 25px;
            }
        }

        @media (max-width: 576px) {
            .form-section {
                padding: 1.8rem 1.5rem;
            }

            .remember-forgot {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.8rem;
            }

            .social-login {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Back to Home Link -->
    <a href="{{ route('Home') }}" class="back-home">
        <i class="fas fa-arrow-left"></i> Back to Home
    </a>

    <div class="page-container">
        <div class="login-wrapper">
            <!-- Mobile Illustration -->
            <div class="mobile-illustration">
                <img src="{{ asset('images/login-page.png') }}" alt="Login Illustration" class="mobile-illustration-img" onerror="this.src='https://cdni.iconscout.com/illustration/premium/thumb/login-page-4468581-3705811.png'">
            </div>

            <!-- Desktop Illustration -->
            <div class="illustration-section">
                <div class="illustration-container">
                    <img src="{{ asset('images/login-page.png') }}" alt="Login Illustration" onerror="this.src='https://cdni.iconscout.com/illustration/premium/thumb/login-page-4468581-3705811.png'">
                </div>
            </div>

            <!-- Login Form -->
            <div class="form-section">
                <div class="form-header">
                    <h2>Welcome Back!</h2>
                    <p>Sign in to your HealthNet Account</p>
                </div>

                <!-- Laravel Session Errors (server-side) -->
                @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                <!-- Laravel Validation Errors -->
                @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
                @endif

                <form id="loginForm" method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope input-icon"></i>
                            <input 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                id="email" 
                                name="email" 
                                placeholder="Enter your email" 
                                value="{{ old('email') }}"
                                required>
                        </div>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password" 
                                placeholder="Enter your password" 
                                required>
                            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                        </div>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="remember-forgot">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                            <label class="form-check-label" for="rememberMe">
                                Remember me
                            </label>
                        </div>
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn-login" id="loginBtn">
                        <span id="btnText">SIGN IN</span>
                    </button>
                </form>

                <div class="divider">
                    <span>OR LOGIN WITH</span>
                </div>

                <div class="social-login">
                    <a href="{{ route('oauth.redirect', ['driver' => 'google']) }}" class="social-btn google-btn">
                        <i class="fab fa-google"></i>
                        Google
                    </a>
                    <a href="{{ route('oauth.redirect', ['driver' => 'facebook']) }}" class="social-btn facebook-btn">
                        <i class="fab fa-facebook-f"></i>
                        Facebook
                    </a>
                </div>

                <div class="signup-link">
                    Don't have an account? <a href="{{ route('signup') }}">Sign Up Now</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password Toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }

        // Form Submit Loading State
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const btnText = document.getElementById('btnText');

        loginForm.addEventListener('submit', function(e) {
            // Disable button and show loading
            loginBtn.disabled = true;
            btnText.innerHTML = '<span class="spinner"></span> Signing In...';
        });

        // Client-side Validation (optional enhancement)
        const emailInput = document.getElementById('email');
        emailInput.addEventListener('blur', function() {
            if (this.value && !this.value.includes('@')) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });

        console.log('✅ Login page initialized with complete error handling');
    </script>
</body>
</html>
