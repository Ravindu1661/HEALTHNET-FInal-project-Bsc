<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Dashboard - HealthNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3282b8;
            --accent-color: #5dade2;
        }
        body { background: #f5f7fa; font-family: 'Inter', sans-serif; }
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        .stat-card h4 { color: var(--primary-color); font-size: 2rem; font-weight: 700; margin: 0; }
        .stat-card p { color: #666; margin: 0; }
        .welcome-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 1000;
            animation: slideIn 0.5s ease;
        }
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div id="welcomeBox"></div>
    
    <div class="container py-5">
        <div class="dashboard-header">
            <h1><i class="fas fa-hospital me-2"></i>Hospital Dashboard</h1>
            <p class="mb-0">Welcome, {{ Auth::user()->email }}</p>
        </div>
        
        <div class="alert alert-warning">
            <i class="fas fa-clock me-2"></i>
            Your hospital registration is pending approval. You'll receive an email once verified.
        </div>
        
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <i class="fas fa-user-md fa-2x text-primary mb-2"></i>
                    <h4>0</h4>
                    <p>Doctors</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <i class="fas fa-procedures fa-2x text-success mb-2"></i>
                    <h4>0</h4>
                    <p>Beds Available</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <i class="fas fa-calendar-alt fa-2x text-warning mb-2"></i>
                    <h4>0</h4>
                    <p>Appointments</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <i class="fas fa-star fa-2x text-info mb-2"></i>
                    <h4>0.0</h4>
                    <p>Rating</p>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('logout') }}" class="btn btn-danger">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </a>
        </div>
    </div>
    @if(session('verified') || !auth()->user()->hasVerifiedEmail())
<div id="emailVerificationAlert" style="
    position: fixed;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    color: white;
    padding: 1.5rem 2rem;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(37, 99, 235, 0.3);
    z-index: 9999;
    max-width: 400px;
    animation: slideIn 0.5s ease-out;
">
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
        <i class="fas fa-envelope-circle-check" style="font-size: 2rem;"></i>
        <div>
            <h4 style="margin: 0; font-size: 1.1rem; font-weight: 600;">
                {{ session('verified') ? 'Email Verified!' : 'Verification Email Sent!' }}
            </h4>
            <p style="margin: 0.3rem 0 0 0; font-size: 0.85rem; opacity: 0.9;">
                {{ session('verified') ? 'Your account is now active.' : 'Check your inbox: ' . auth()->user()->email }}
            </p>
        </div>
    </div>
</div>

<style>
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(100px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideOut {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100px);
    }
}
</style>

<script>
// Auto-hide after 5 seconds
setTimeout(() => {
    const alert = document.getElementById('emailVerificationAlert');
    if (alert) {
        alert.style.animation = 'slideOut 0.5s ease-out';
        setTimeout(() => {
            alert.remove();
        }, 500);
    }
}, 5000);
</script>
@endif

    <script>
        const msg = sessionStorage.getItem('login_welcome');
        if (msg) {
            showWelcome(msg, '{{ Auth::user()->email }}');
            sessionStorage.removeItem('login_welcome');
        }
        
        function showWelcome(message, email) {
            document.getElementById('welcomeBox').innerHTML = `
                <div class="welcome-message" id="loginWelcomeMsg">
                    <h6><i class="fas fa-check-circle me-2"></i>${message}</h6>
                    <p class="mb-0">Hello, <strong>${email}</strong>!</p>
                </div>
            `;
            setTimeout(hideWelcomeMsg, 4000);
        }
        
        function hideWelcomeMsg() {
            const box = document.getElementById('loginWelcomeMsg');
            if (box) box.style.display = 'none';
        }
    </script>
</body>
</html>
