<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provider Registration - HealthNet</title>
    
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
            background: linear-gradient(135deg, #0f4c75 0%, #3282b8 100%);
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
            background: linear-gradient(135deg, #0f4c75 0%, #3282b8 100%);
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

        dotlottie-wc {
            width: 100%;
            height: 280px;
            display: none;
        }

        dotlottie-wc.active {
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
            font-size: 0.7rem;
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
            background: linear-gradient(135deg, #0f4c75 0%, #3282b8 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(15, 76, 117, 0.3);
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
            color: #0f4c75;
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

        .alert-info {
            background: #e6f3ff;
            color: #0f4c75;
            border-left: 3px solid #0f4c75;
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
            color: #0f4c75;
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .step-title i {
            width: 23px;
            height: 23px;
            background: linear-gradient(135deg, #0f4c75 0%, #3282b8 100%);
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
            border-color: #0f4c75;
            box-shadow: 0 0 0 3px rgba(15, 76, 117, 0.1);
            outline: none;
            background: white;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 55px;
        }

        /* Time Picker Styling */
        .time-picker-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
        }

        .time-input-wrapper {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .time-input-wrapper label {
            font-size: 0.65rem;
            color: #718096;
            min-width: 35px;
        }

        .time-input-wrapper input[type="time"] {
            flex: 1;
            padding: 0.45rem 0.5rem;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.65rem;
            background: #f7fafc;
        }

        .time-input-wrapper input[type="time"]:focus {
            border-color: #0f4c75;
            background: white;
        }

        .day-schedule {
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.6rem;
            margin-bottom: 0.5rem;
        }

        .day-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .day-name {
            font-weight: 600;
            font-size: 0.7rem;
            color: #2d3748;
        }

        .day-toggle {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .day-toggle input[type="checkbox"] {
            width: 15px;
            height: 15px;
            cursor: pointer;
        }

        .day-toggle label {
            font-size: 0.65rem;
            color: #718096;
            cursor: pointer;
            margin: 0;
        }

        .time-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
        }

        .time-inputs.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        /* File Upload Styling */
        .file-upload-wrapper {
            position: relative;
            display: block;
        }

        .file-upload-input {
            position: absolute;
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            z-index: -1;
        }

        .file-upload-label {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.7rem;
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            background: #f7fafc;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0;
        }

        .file-upload-label:hover {
            border-color: #0f4c75;
            background: #e6f3ff;
        }

        .file-upload-label i {
            font-size: 1.5rem;
            color: #0f4c75;
        }

        .file-info h6 {
            font-size: 0.75rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        .file-info p {
            font-size: 0.65rem;
            color: #718096;
            margin: 0;
        }

        .file-selected {
            display: none;
            align-items: center;
            justify-content: space-between;
            padding: 0.6rem;
            background: #e6f3ff;
            border: 2px solid #0f4c75;
            border-radius: 7px;
            margin-top: 0.5rem;
            font-size: 0.7rem;
        }

        .file-selected.show {
            display: flex;
        }

        .file-selected-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
        }

        .file-selected-info i {
            color: #0f4c75;
            font-size: 1rem;
        }

        .file-selected-name {
            font-weight: 500;
            color: #2d3748;
        }

        .file-remove-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.3rem 0.6rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.65rem;
            transition: all 0.3s ease;
        }

        .file-remove-btn:hover {
            background: #c82333;
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
            background-color: #0f4c75;
            border-color: #0f4c75;
        }

        .form-check-label {
            font-size: 0.68rem;
            color: #4a5568;
            line-height: 1.4;
            cursor: pointer;
        }

        .form-check-label a {
            color: #0f4c75;
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
            background: linear-gradient(135deg, #0f4c75 0%, #3282b8 100%);
            color: white;
        }

        .btn-next:hover, .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(15, 76, 117, 0.3);
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

        .login-link {
            text-align: center;
            color: #718096;
            font-size: 0.7rem;
            margin-top: 0.5rem;
        }

        .login-link a {
            color: #0f4c75;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Error State */
        .form-control.error, .form-select.error {
            border-color: #dc3545;
            background: #fff5f5;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.65rem;
            margin-top: 0.25rem;
            display: none;
        }

        .error-message.show {
            display: block;
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

            .time-picker-group {
                grid-template-columns: 1fr;
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
        /* Schedule Type Selection */
.schedule-type-selection {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 0.7rem;
}

.schedule-type-option {
    display: flex;
    align-items: center;
    padding: 0.5rem 0.6rem;
    border: 2px solid #e2e8f0;
    border-radius: 7px;
    background: #f7fafc;
    cursor: pointer;
    transition: all 0.3s ease;
    margin: 0;
}

.schedule-type-option:hover {
    border-color: #0f4c75;
    background: #e6f3ff;
}

.schedule-type-option input[type="radio"] {
    margin-right: 0.5rem;
    cursor: pointer;
    width: 14px;
    height: 14px;
}

.schedule-type-option input[type="radio"]:checked + span {
    color: #0f4c75;
    font-weight: 600;
}

.schedule-type-option span {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.68rem;
    color: #4a5568;
}

.schedule-type-option i {
    font-size: 0.75rem;
    color: #0f4c75;
}

/* Multiselect styles */
.multiselect-wrapper {
    position: relative;
}

.multiselect-display {
    padding: 0.5rem 0.65rem;
    border: 2px solid #e2e8f0;
    border-radius: 7px;
    font-size: 0.68rem;
    background: #f7fafc;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.multiselect-display:hover {
    border-color: #cbd5e0;
}

.multiselect-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    max-height: 180px;
    overflow-y: auto;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 7px;
    margin-top: 0.25rem;
    z-index: 1000;
    display: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.multiselect-dropdown.show {
    display: block;
}

.multiselect-option {
    padding: 0.45rem 0.65rem;
    display: flex;
    align-items: center;
    gap: 0.45rem;
    cursor: pointer;
    font-size: 0.68rem;
    transition: background 0.2s ease;
    margin: 0;
}

.multiselect-option:hover {
    background: #f7fafc;
}

.multiselect-option input[type="checkbox"] {
    margin: 0;
    cursor: pointer;
    width: 14px;
    height: 14px;
}

.selected-items {
    display: flex;
    flex-wrap: wrap;
    gap: 0.35rem;
    margin-top: 0.45rem;
}

.selected-tag {
    background: #e6f3ff;
    color: #0f4c75;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.62rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.selected-tag i {
    cursor: pointer;
    font-size: 0.58rem;
}

.selected-tag i:hover {
    color: #dc3545;
}

/* Day Schedule styles */
.day-schedule {
    background: #f7fafc;
    border: 2px solid #e2e8f0;
    border-radius: 7px;
    padding: 0.5rem;
    margin-bottom: 0.45rem;
}

.day-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.45rem;
}

.day-name {
    font-weight: 600;
    font-size: 0.68rem;
    color: #2d3748;
}

.day-toggle {
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.day-toggle input[type="checkbox"] {
    width: 14px;
    height: 14px;
    cursor: pointer;
}

.day-toggle label {
    font-size: 0.62rem;
    color: #718096;
    cursor: pointer;
    margin: 0;
}

.time-inputs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.45rem;
}

.time-inputs.disabled {
    opacity: 0.5;
    pointer-events: none;
}

.time-input-wrapper {
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

.time-input-wrapper label {
    font-size: 0.62rem;
    color: #718096;
    min-width: 32px;
}

.time-input-wrapper input[type="time"] {
    flex: 1;
    padding: 0.4rem 0.45rem;
    border: 2px solid #e2e8f0;
    border-radius: 6px;
    font-size: 0.62rem;
    background: #f7fafc;
}

.time-input-wrapper input[type="time"]:focus {
    border-color: #0f4c75;
    background: white;
}

    </style>
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>
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

                <!-- Illustration Container -->
                <div class="illustration-container">
                    <!-- Doctor Animation -->
                    <dotlottie-wc 
                        src="https://lottie.host/1248c82e-1212-4e61-b424-96b1252314dd/i0GyICTdLv.lottie" 
                        background="transparent" 
                        speed="1" 
                        data-provider-type="doctor"
                        id="animationDoctor" 
                        loop 
                        autoplay>
                    </dotlottie-wc>
                    
                    <!-- Hospital Animation -->
                    <dotlottie-wc 
                        src="https://lottie.host/18f7046d-73fc-44de-9fe1-bfa0aa565a02/WuGoMG4Gxu.lottie" 
                        background="transparent" 
                        speed="1" 
                        data-provider-type="hospital"
                        id="animationHospital" 
                        loop 
                        autoplay>
                    </dotlottie-wc>
                    
                    <!-- Laboratory Animation -->
                    <dotlottie-wc 
                        src="https://lottie.host/3aa5cbd5-c305-4143-940e-eb7c63fe29bc/uXeNEiNd9O.lottie" 
                        background="transparent" 
                        speed="1" 
                        data-provider-type="laboratory"
                        id="animationLaboratory" 
                        loop 
                        autoplay>
                    </dotlottie-wc>
                    
                    <!-- Pharmacy Animation -->
                    <dotlottie-wc 
                        src="https://lottie.host/7b6fe4e9-28d0-4b27-90d0-49116b3a942d/56KrfFw3nL.lottie" 
                        src="https://lottie.host/432faec8-213c-4748-b26f-5f1a4a723337/VEz88oLMdz.lottie" 
                        background="transparent" 
                        speed="1" 
                        data-provider-type="pharmacy"
                        id="animationPharmacy" 
                        loop 
                        autoplay>
                    </dotlottie-wc>

                    <!-- Medical Centre Animation -->
                    <dotlottie-wc 
                        src="https://lottie.host/ccaa0ef7-2620-4916-8287-7858d6e39012/gmUHoVfjXi.lottie" 
                        background="transparent" 
                        speed="1" 
                        data-provider-type="medical_centre"
                        id="animationMedicalCentre" 
                        loop 
                        autoplay>
                    </dotlottie-wc>

                    <!-- Features - Doctor -->
                    <div class="illustration-features" data-provider-type="doctor" id="featuresDoctor">
                        <div class="feature-item">
                            <i class="fas fa-user-md"></i>
                            <span>Join Verified Network</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-users"></i>
                            <span>Connect with Patients</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Secure & Trusted</span>
                        </div>
                    </div>

                    <!-- Features - Hospital -->
                    <div class="illustration-features" data-provider-type="hospital" id="featuresHospital">
                        <div class="feature-item">
                            <i class="fas fa-hospital"></i>
                            <span>Comprehensive Care</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-ambulance"></i>
                            <span>Emergency Services</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-bed"></i>
                            <span>Advanced Facilities</span>
                        </div>
                    </div>

                    <!-- Features - Laboratory -->
                    <div class="illustration-features" data-provider-type="laboratory" id="featuresLaboratory">
                        <div class="feature-item">
                            <i class="fas fa-flask"></i>
                            <span>Accurate Testing</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-microscope"></i>
                            <span>Advanced Equipment</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-vial"></i>
                            <span>Quick Results</span>
                        </div>
                    </div>

                    <!-- Features - Pharmacy -->
                    <div class="illustration-features" data-provider-type="pharmacy" id="featuresPharmacy">
                        <div class="feature-item">
                            <i class="fas fa-pills"></i>
                            <span>Quality Medicines</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-prescription"></i>
                            <span>Expert Consultation</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-mortar-pestle"></i>
                            <span>Trusted Service</span>
                        </div>
                    </div>

                    <!-- Features - Medical Centre -->
                    <div class="illustration-features" data-provider-type="medical_centre" id="featuresMedicalCentre">
                        <div class="feature-item">
                            <i class="fas fa-clinic-medical"></i>
                            <span>Multi-specialty Care</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-stethoscope"></i>
                            <span>Professional Team</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-heart"></i>
                            <span>Patient-Centered</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="form-section">
                <div class="form-header">
                    <h2 id="headerTitle">Provider Registration</h2>
                    <p id="headerSubtitle">Join HealthNet as a Healthcare Provider</p>
                </div>

                <!-- Progress Steps -->
                <div class="progress-steps">
                    <div class="step-item active" id="stepItem1">
                        <div class="step-circle">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="step-label">Basic</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" id="stepItem2">
                        <div class="step-circle">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="step-label">Contact</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" id="stepItem3">
                        <div class="step-circle">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <div class="step-label">Details</div>
                    </div>
                    <div class="step-connector"></div>
                    <div class="step-item" id="stepItem4">
                        <div class="step-circle">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="step-label">Complete</div>
                    </div>
                </div>

                <!-- Info Alert -->
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <span id="infoText">Your registration and credentials will be verified by our admin team.</span>
                </div>

                <!-- Alert Messages -->
                <div id="alertMessage" class="alert alert-danger" style="display: none;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="alertText"></span>
                </div>

                <!-- Registration Form -->
                <form id="providerSignupForm">
                    <!-- Hidden field for provider type -->
                    <input type="hidden" id="providerType" value="doctor">

                    <!-- Step 1: Basic Information -->
                    <div class="form-step active" id="section1">
                        <div class="step-title">
                            <i class="fas fa-info-circle"></i>
                            <span>Basic Information</span>
                        </div>

                        <div id="basicInfoFields">
                            <!-- Dynamically loaded -->
                        </div>

                        <div class="btn-group-custom">
                            <button type="button" class="btn-custom btn-next" onclick="nextStep(1)">
                                Next <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Contact & Location -->
                    <div class="form-step" id="section2">
                        <div class="step-title">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Contact & Location</span>
                        </div>

                        <div id="contactFields">
                            <!-- Dynamically loaded -->
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

                    <!-- Step 3: Professional Details -->
                    <div class="form-step" id="section3">
                        <div class="step-title">
                            <i class="fas fa-certificate"></i>
                            <span>Professional Details</span>
                        </div>

                        <div id="professionalFields">
                            <!-- Dynamically loaded -->
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

                    <!-- Step 4: Account & Documents -->
                    <div class="form-step" id="section4">
                        <div class="step-title">
                            <i class="fas fa-file-upload"></i>
                            <span>Account & Documents</span>
                        </div>

                        <div id="accountFields">
                            <!-- Dynamically loaded -->
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I certify that all information is accurate and agree to the <a href="../public/terms.html" target="_blank">Terms & Conditions</a>
                            </label>
                        </div>

                        <div class="btn-group-custom">
                            <button type="button" class="btn-custom btn-prev" onclick="prevStep(4)">
                                <i class="fas fa-arrow-left"></i> Previous
                            </button>
                            <button type="submit" class="btn-custom btn-submit" id="submitBtn">
                                <i class="fas fa-check"></i> Submit Registration
                            </button>
                        </div>

                        <!-- Login Link -->
                        <div class="login-link">
                            Already registered? <a href="{{ route('login') }}">Login Here</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/provider-signup.js') }}"></script>
    
    <script>
        // Provider type based animation loading
        document.addEventListener('DOMContentLoaded', function() {
            // Get provider type from hidden input
            const providerTypeInput = document.getElementById('providerType');
            const providerType = providerTypeInput ? providerTypeInput.value : 'doctor';
            
            // Update animation based on provider type
            updateAnimationByProviderType(providerType);
        });

        function updateAnimationByProviderType(providerType) {
            // Hide all animations
            document.querySelectorAll('dotlottie-wc').forEach(anim => {
                anim.classList.remove('active');
            });
            
            // Hide all features
            document.querySelectorAll('.illustration-features').forEach(feature => {
                feature.classList.remove('active');
            });
            
            // Show animation and features for current provider type
            const currentAnimation = document.querySelector(`dotlottie-wc[data-provider-type="${providerType}"]`);
            const currentFeatures = document.querySelector(`.illustration-features[data-provider-type="${providerType}"]`);
            
            if (currentAnimation) {
                currentAnimation.classList.add('active');
            }
            
            if (currentFeatures) {
                currentFeatures.classList.add('active');
            }
        }

        // Original animation update function kept for compatibility
        function updateAnimation(step) {
            // This function is kept for backward compatibility
            // but won't be used for provider type based animations
        }
    </script>
</body>
</html>
