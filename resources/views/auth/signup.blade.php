<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Sign Up - HealthNet</title>

    <!-- Favicon Links -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('android-chrome-512x512.png') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 0;
            position: relative;
            overflow-x: hidden;
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

        .back-home {
            position: fixed;
            top: 1rem;
            left: 1rem;
            color: white;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            z-index: 1000;
            font-size: 0.8rem;
            background: rgba(255, 255, 255, 0.15);
            padding: 0.5rem 1rem;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .back-home:hover {
            color: white;
            background: rgba(255, 255, 255, 0.25);
            transform: translateX(-3px);
        }

        .signup-container {
            width: 100%;
            max-width: 1000px;
            padding: 0 1rem;
            position: relative;
            z-index: 1;
        }

        .signup-wrapper {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 80px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            display: flex;
            animation: slideUp 0.6s ease-out;
            max-height: 90vh;
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
            flex: 0 0 45%;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            padding: 1.5rem 1.2rem;
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

        .logo-section {
            position: absolute;
            top: 1.2rem;
            left: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            z-index: 10;
        }

        .logo-section i {
            font-size: 1.3rem;
            animation: heartbeat 1.5s ease-in-out infinite;
        }

        @keyframes heartbeat {
            0%, 100% {
                transform: scale(1);
            }
            25% {
                transform: scale(1.1);
            }
            50% {
                transform: scale(1);
            }
        }

        .logo-section span {
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .illustration-container {
            width: 100%;
            max-width: 280px;
            position: relative;
            z-index: 2;
            transition: opacity 0.5s ease;
            text-align: center;
        }

        .illustration-img {
            width: 100%;
            height: auto;
            filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.2));
            display: none;
        }

        .illustration-img.active {
            display: block;
            animation: fadeInScale 0.5s ease;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .illustration-features {
            margin-top: 1.2rem;
            display: none;
        }

        .illustration-features.active {
            display: block;
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: white;
            margin-bottom: 0.7rem;
            font-size: 0.75rem;
        }

        .feature-item i {
            width: 26px;
            height: 26px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        /* Right Side - Form */
        .form-section {
            flex: 1;
            padding: 1.5rem 1.8rem;
            overflow-y: auto;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }

        .form-header {
            text-align: center;
            margin-bottom: 0.1rem;
        }

        .form-header h2 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.2rem;
        }

        .form-header p {
            color: #718096;
            font-size: 0.6rem;
        }

        /* Progress Steps */
        .progress-steps {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.3rem;
            margin-bottom: 1rem;
            position: relative;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
            position: relative;
        }

        .step-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            color: #999;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .step-circle i {
            font-size: 0.85rem;
        }

        .step-item.active .step-circle {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
            transform: scale(1.05);
        }

        .step-item.completed .step-circle {
            background: #10b981;
            color: white;
        }

        .step-label {
            font-size: 0.6rem;
            color: #999;
            font-weight: 500;
            text-align: center;
        }

        .step-item.active .step-label {
            color: #2563eb;
            font-weight: 600;
        }

        .step-connector {
            width: 28px;
            height: 2px;
            background: #e9ecef;
            margin: 0 0.15rem;
            align-self: center;
            margin-bottom: 1rem;
        }

        .step-item.completed + .step-connector {
            background: #10b981;
        }

        /* Back Step Button */
        .back-step-btn {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 28px;
            height: 28px;
            background: rgba(37, 99, 235, 0.1);
            border: none;
            border-radius: 50%;
            color: #2563eb;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 0.75rem;
        }

        .back-step-btn.show {
            display: flex;
        }

        .back-step-btn:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-50%) scale(1.1);
        }

        /* Alert Messages */
        .alert {
            padding: 0.6rem;
            border-radius: 8px;
            margin-bottom: 0.8rem;
            font-size: 0.7rem;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .alert-danger {
            background: #fee;
            color: #c33;
            border-left: 3px solid #c33;
        }

        .alert-success {
            background: #f0fff4;
            color: #22543d;
            border-left: 3px solid #22543d;
        }

        /* Form Sections */
        .form-step {
            display: none;
            flex: 1;
        }

        .form-step.active {
            display: block;
            animation: fadeInRight 0.4s ease;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .step-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .step-title i {
            width: 23px;
            height: 23px;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.75rem;
        }

        .form-group {
            margin-bottom: 0.7rem;
        }

        .form-label {
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 0.25rem;
            font-size: 0.7rem;
            display: block;
        }

        .required {
            color: #dc3545;
        }

        .optional {
            color: #999;
            font-weight: 400;
            font-size: 0.65rem;
            margin-left: 0.25rem;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 0.55rem 0.7rem;
            border: 2px solid #e2e8f0;
            border-radius: 7px;
            font-size: 0.7rem;
            transition: all 0.3s ease;
            background: #f7fafc;
        }

        .form-control:focus, .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
            background: white;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 55px;
        }

        .password-toggle {
            position: absolute;
            right: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
            transition: color 0.3s ease;
            font-size: 0.8rem;
        }

        .password-toggle:hover {
            color: #2563eb;
        }

        /* Checkbox */
        .form-check {
            display: flex;
            align-items: flex-start;
            gap: 0.4rem;
            margin-bottom: 0.7rem;
        }

        .form-check-input {
            width: 15px;
            height: 15px;
            cursor: pointer;
            margin-top: 0.1rem;
            flex-shrink: 0;
            border: 2px solid #e2e8f0;
        }

        .form-check-input:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .form-check-label {
            font-size: 0.68rem;
            color: #4a5568;
            line-height: 1.4;
            cursor: pointer;
        }

        .form-check-label a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .form-check-label a:hover {
            text-decoration: underline;
        }

        /* Buttons */
        .btn-group-custom {
            display: flex;
            gap: 0.6rem;
            margin-top: 1rem;
        }

        .btn-custom {
            flex: 1;
            padding: 0.65rem;
            border: none;
            border-radius: 7px;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }

        .btn-prev {
            background: #e9ecef;
            color: #666;
        }

        .btn-prev:hover {
            background: #dee2e6;
            transform: translateY(-2px);
        }

        .btn-next, .btn-submit {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
        }

        .btn-next:hover, .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .spinner-border-sm {
            width: 13px;
            height: 13px;
            border-width: 2px;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 0.8rem 0 0.7rem;
            color: #a0aec0;
            font-size: 0.65rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            padding: 0 0.6rem;
        }

        /* Social Login */
        .social-signup {
            display: flex;
            gap: 0.6rem;
            margin-bottom: 0.7rem;
        }

        .social-btn {
            flex: 1;
            padding: 0.55rem;
            border: 2px solid #e2e8f0;
            border-radius: 7px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            font-weight: 500;
            font-size: 0.7rem;
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
            font-size: 0.9rem;
        }

        .google-btn i {
            color: #db4437;
        }

        .facebook-btn i {
            color: #4267B2;
        }

        .login-link {
            text-align: center;
            color: #718096;
            font-size: 0.7rem;
            margin-top: 0.5rem;
        }

        .login-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .illustration-section {
                display: none;
            }

            .signup-wrapper {
                max-width: 600px;
                margin: 0 auto;
            }

            .form-section {
                max-height: none;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 0.8rem 0;
            }

            .back-home {
                top: 0.8rem;
                left: 0.8rem;
                font-size: 0.75rem;
                padding: 0.45rem 0.8rem;
            }

            .signup-wrapper {
                border-radius: 15px;
            }

            .form-section {
                padding: 1.3rem 1rem;
            }

            .form-header h2 {
                font-size: 1.2rem;
            }

            .step-circle {
                width: 32px;
                height: 32px;
                font-size: 0.75rem;
            }

            .step-connector {
                width: 25px;
            }

            .btn-group-custom {
                flex-direction: column;
            }

            .social-signup {
                flex-direction: column;
            }
        }

        /* Scrollbar */
        .form-section::-webkit-scrollbar {
            width: 4px;
        }

        .form-section::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .form-section::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }

        .form-section::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }
    </style>
</head>
<body>
    <!-- Back to Home Link -->
    <a href="{{ route('Home') }}" class="back-home">
        <i class="fas fa-arrow-left"></i> Back to Home
    </a>

    <div class="signup-container">
        <div class="signup-wrapper">
            <!-- Left Side - Illustration -->
            <div class="illustration-section">
                <!-- Logo -->
                <div class="logo-section">
                    <i class="fas fa-heartbeat"></i>
                    <span>HealthNet</span>
                </div>

                <!-- Illustration Images (will change per step) -->
                <div class="illustration-container">
                    <img src="images/signup-image/1-slide.png" 
                         alt="Step 1" 
                         class="illustration-img active" 
                         id="illustration1"
                         onerror="this.src='https://img.freepik.com/free-vector/doctors-concept-illustration_114360-1515.jpg'">
                    
                    <img src="images/signup-image/2-slide.png" 
                         alt="Step 2" 
                         class="illustration-img" 
                         id="illustration2"
                         onerror="this.src='https://img.freepik.com/free-vector/personal-data-concept-illustration_114360-4587.jpg'">
                    
                    <img src="images/signup-image/3-slide.png" 
                         alt="Step 3" 
                         class="illustration-img" 
                         id="illustration3"
                         onerror="this.src='https://img.freepik.com/free-vector/mobile-login-concept-illustration_114360-83.jpg'">

                    <img src="images/signup-image/4-slide.png" 
                         alt="Step 4" 
                         class="illustration-img" 
                         id="illustration4"
                         onerror="this.src='https://img.freepik.com/free-vector/health-professional-team_52683-36023.jpg'">

                    <!-- Features under illustration - Step 1 -->
                    <div class="illustration-features active" id="features1">
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Quick & Easy Registration</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Secure Data Protection</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-user-md"></i>
                            <span>Access Top Doctors</span>
                        </div>
                    </div>

                    <!-- Features under illustration - Step 2 -->
                    <div class="illustration-features" id="features2">
                        <div class="feature-item">
                            <i class="fas fa-id-card"></i>
                            <span>Complete Your Profile</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-location-dot"></i>
                            <span>Personalized Experience</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-clock"></i>
                            <span>Save Time on Visits</span>
                        </div>
                    </div>

                    <!-- Features under illustration - Step 3 -->
                    <div class="illustration-features" id="features3">
                        <div class="feature-item">
                            <i class="fas fa-lock"></i>
                            <span>256-bit Encryption</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-hospital"></i>
                            <span>Emergency Contacts Ready</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Almost Done!</span>
                        </div>
                    </div>

                    <!-- Features under illustration - Step 4 -->
                    <div class="illustration-features" id="features4">
                        <div class="feature-item">
                            <i class="fas fa-star"></i>
                            <span>Join 10,000+ Patients</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-heart"></i>
                            <span>Quality Healthcare Access</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-bolt"></i>
                            <span>Instant Appointments</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="form-section">
                <div class="form-header">
                    <h2>Create Your Account</h2>
                    <p>Join HealthNet for better healthcare access</p>
                </div>

                <!-- Progress Steps -->
                <div class="progress-steps">
                    <button type="button" class="back-step-btn" id="backStepBtn" onclick="backStepQuick()">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    
                    <div class="step-item active" id="stepItem1">
                        <div class="step-circle" id="step1">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="step-label">Basic</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" id="stepItem2">
                        <div class="step-circle" id="step2">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div class="step-label">Personal</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" id="stepItem3">
                        <div class="step-circle" id="step3">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="step-label">Security</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" id="stepItem4">
                        <div class="step-circle" id="step4">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="step-label">Complete</div>
                    </div>
                </div>

                <!-- Alert Messages -->
                <div id="alertMessage" class="alert alert-danger" style="display: none;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="alertText"></span>
                </div>

                <!-- Signup Form -->
                <form id="signupForm">
                    <!-- Step 1: Basic Information -->
                    <div class="form-step active" id="section1">
                        <div class="step-title">
                            <i class="fas fa-user"></i>
                            <span>Basic Information</span>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        First Name <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="firstName" name="first_name" placeholder="John" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        Last Name <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="lastName" name="last_name" placeholder="Doe" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Email Address <span class="required">*</span>
                            </label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="john.doe@example.com" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Phone Number <span class="required">*</span>
                            </label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="+94 XX XXX XXXX" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                NIC Number <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="nic" name="nic" placeholder="XXXXXXXXXV or XXXXXXXXXXXX" required>
                        </div>

                        <div class="btn-group-custom">
                            <button type="button" class="btn-custom btn-next" onclick="nextStep(1)">
                                Next <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>

                        <!-- Divider -->
                        <div class="divider">
                            <span>OR SIGN UP WITH</span>
                        </div>

                        <!-- Social Signup -->
                        <div class="social-signup">
                            <a href="{{ route('oauth.redirect', ['driver' => 'google']) }}" class="social-btn google-btn">
                                <i class="fab fa-google"></i> Google
                            </a>
                            <a href="{{ route('oauth.redirect', ['driver' => 'facebook']) }}" class="social-btn facebook-btn">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                        </div>

                        <!-- Login Link -->
                        <div class="login-link">
                            Already have an account? <a href="{{ route('login') }}">Login Here</a>
                        </div>
                    </div>

                    <!-- Step 2: Personal Details -->
                    <div class="form-step" id="section2">
                        <div class="step-title">
                            <i class="fas fa-id-card"></i>
                            <span>Personal Details</span>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        Date of Birth <span class="required">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="dob" name="date_of_birth" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        Gender <span class="required">*</span>
                                    </label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Blood Group <span class="optional">(Optional)</span>
                            </label>
                            <select class="form-select" id="bloodGroup" name="blood_group">
                                <option value="">Select Blood Group</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Address <span class="required">*</span>
                            </label>
                            <textarea class="form-control" id="address" name="address" rows="2" placeholder="Enter your full address" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        City <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="Colombo" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        Province <span class="required">*</span>
                                    </label>
                                    <select class="form-select" id="province" name="province" required>
                                        <option value="">Select Province</option>
                                        <option value="Western">Western</option>
                                        <option value="Central">Central</option>
                                        <option value="Southern">Southern</option>
                                        <option value="Northern">Northern</option>
                                        <option value="Eastern">Eastern</option>
                                        <option value="North Western">North Western</option>
                                        <option value="North Central">North Central</option>
                                        <option value="Uva">Uva</option>
                                        <option value="Sabaragamuwa">Sabaragamuwa</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Postal Code <span class="optional">(Optional)</span>
                            </label>
                            <input type="text" class="form-control" id="postalCode" name="postal_code" placeholder="00100">
                        </div>

                        <div class="btn-group-custom">
                            <button type="button" class="btn-custom btn-prev" onclick="prevStep(2)">
                                <i class="fas fa-arrow-left"></i> Previous
                            </button>
                            <button type="button" class="btn-custom btn-next" onclick="nextStep(2)">
                                Next <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Emergency Contact & Security -->
                    <div class="form-step" id="section3">
                        <div class="step-title">
                            <i class="fas fa-shield-alt"></i>
                            <span>Emergency Contact & Security</span>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                Emergency Contact Name <span class="optional">(Optional)</span>
                            </label>
                            <input type="text" class="form-control" id="emergencyName" name="emergency_contact_name" placeholder="Contact person name">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Emergency Contact Phone <span class="optional">(Optional)</span>
                            </label>
                            <input type="tel" class="form-control" id="emergencyPhone" name="emergency_contact_phone" placeholder="+94 XX XXX XXXX">
                        </div>

                        <hr style="margin: 0.8rem 0; border-color: #e2e8f0;">

                        <div class="form-group">
                            <label class="form-label">
                                Password <span class="required">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Create a strong password" required>
                                <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Confirm Password <span class="required">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" placeholder="Re-enter your password" required>
                                <i class="fas fa-eye password-toggle" id="toggleConfirmPassword"></i>
                            </div>
                        </div>

                        <div class="btn-group-custom">
                            <button type="button" class="btn-custom btn-prev" onclick="prevStep(3)">
                                <i class="fas fa-arrow-left"></i> Previous
                            </button>
                            <button type="button" class="btn-custom btn-next" onclick="nextStep(3)">
                                Next <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 4: Terms & Complete -->
                    <div class="form-step" id="section4">
                        <div class="step-title">
                            <i class="fas fa-check"></i>
                            <span>Complete Registration</span>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="/terms.html" target="_blank">Terms & Conditions</a> and <a href="/privacy.html" target="_blank">Privacy Policy</a>
                            </label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="newsletter" name="newsletter">
                            <label class="form-check-label" for="newsletter">
                                Send me health tips, updates, and promotional emails
                            </label>
                        </div>

                        <div class="btn-group-custom">
                            <button type="button" class="btn-custom btn-prev" onclick="prevStep(4)">
                                <i class="fas fa-arrow-left"></i> Previous
                            </button>
                            <button type="submit" class="btn-custom btn-submit" id="submitBtn">
                                <i class="fas fa-check"></i> Create Account
                            </button>
                        </div>

                        <!-- Divider -->
                        <div class="divider">
                            <span>OR SIGN UP WITH</span>
                        </div>

                        <!-- Social Signup -->
                        <div class="social-signup">
                            <a href="{{ route('oauth.redirect', ['driver' => 'google']) }}" class="social-btn google-btn">
                                <i class="fab fa-google"></i> Google
                            </a>
                            <a href="{{ route('oauth.redirect', ['driver' => 'facebook']) }}" class="social-btn facebook-btn">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                        </div>

                        <!-- Login Link -->
                        <div class="login-link">
                            Already have an account? <a href="{{ route('login') }}">Login Here</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="{{ asset('js/patient-signup.js') }}"></script>
</body>
</html>
