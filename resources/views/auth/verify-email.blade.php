<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email - HealthNet</title>
    
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
            padding: 2rem;
        }
        
        .verify-container {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 80px rgba(0, 0, 0, 0.2);
            padding: 3rem;
            text-align: center;
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
        
        .verify-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        .verify-icon i {
            font-size: 3rem;
            color: white;
        }
        
        h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1rem;
        }
        
        p {
            color: #718096;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .user-email {
            color: #2563eb;
            font-weight: 600;
        }
        
        .btn-verify {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        }
        
        .btn-verify:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .alert {
            padding: 0.8rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            border: none;
        }
        
        .alert-success {
            background: #f0fff4;
            color: #22543d;
            border-left: 3px solid #22543d;
        }
        
        .back-home {
            display: inline-block;
            margin-top: 1.5rem;
            color: #718096;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        
        .back-home:hover {
            color: #2563eb;
        }

        /* ✅ Auto-redirect countdown */
        .redirect-message {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #e0f2fe;
            border-radius: 8px;
            color: #075985;
            font-size: 0.9rem;
        }

        .countdown {
            font-weight: 700;
            font-size: 1.1rem;
            color: #2563eb;
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-icon">
            <i class="fas fa-envelope-open"></i>
        </div>
        
        <h2>Email Verification Sent!</h2>
        
        <p>
            We've sent a verification link to<br>
            <span class="user-email">{{ auth()->user()->email }}</span>
        </p>
        
        <p>
            Please check your inbox and click the verification link to activate your account.
        </p>
        
        @if (session('message'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('message') }}
            </div>
        @endif
        
        <!-- ✅ Auto-redirect message -->
        <div class="redirect-message">
            <i class="fas fa-info-circle"></i> 
            Redirecting to your dashboard in <span class="countdown" id="countdown">5</span> seconds...
        </div>
        
        <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
            @csrf
            <button type="submit" class="btn-verify" id="resendBtn">
                <i class="fas fa-paper-plane"></i>
                Resend Verification Email
            </button>
        </form>
        
        <a href="{{ route('Home') }}" class="back-home">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
    </div>
    
    <script>
        // ✅ Auto-redirect to dashboard after 5 seconds
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        
        const countdownInterval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                
                // Redirect based on user type
                @if(auth()->check())
                    @php
                        $userType = auth()->user()->user_type;
                        $dashboardRoute = match($userType) {
                            'patient' => route('patient.dashboard'),
                            'doctor' => route('doctor.dashboard'),
                            'hospital' => route('hospital.dashboard'),
                            'laboratory' => route('laboratory.dashboard'),
                            'pharmacy' => route('pharmacy.dashboard'),
                            'medicalcentre' => route('medical_centre.dashboard'),
                            'admin' => route('admin.dashboard'),
                            default => route('Home'),
                        };
                    @endphp
                    window.location.href = '{{ $dashboardRoute }}';
                @else
                    window.location.href = '{{ route('Home') }}';
                @endif
            }
        }, 1000);
        
        // Disable resend button for 60 seconds after click
        document.getElementById('resendForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('resendBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-clock"></i> Please wait...';
            
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane"></i> Resend Verification Email';
            }, 60000);
        });
    </script>
</body>
</html>
