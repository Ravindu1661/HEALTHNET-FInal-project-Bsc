<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HealthNet</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { height: 100%; overflow-x: hidden; }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: '';
            position: fixed;
            top: -50%; right: -10%;
            width: 500px; height: 500px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%; z-index: 0;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -30%; left: -10%;
            width: 400px; height: 400px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%; z-index: 0;
        }

        .back-home {
            position: fixed; top: 1.5rem; left: 1.5rem;
            color: white; text-decoration: none;
            font-weight: 500; font-size: 0.9rem;
            display: flex; align-items: center; gap: 0.5rem;
            background: rgba(255,255,255,0.15);
            padding: 0.55rem 1.1rem; border-radius: 10px;
            backdrop-filter: blur(10px); z-index: 1000;
            transition: all 0.3s;
        }
        .back-home:hover { color: white; background: rgba(255,255,255,0.25); transform: translateX(-3px); }

        .page-container {
            width: 100%; max-width: 980px;
            position: relative; z-index: 1;
        }

        /* ── DEMO PANEL ── */
        .demo-panel {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 18px;
            padding: 1.1rem 1.4rem;
            margin-bottom: 1rem;
            animation: slideDown 0.5s ease-out;
        }
        @keyframes slideDown {
            from { opacity:0; transform: translateY(-12px); }
            to   { opacity:1; transform: translateY(0); }
        }
        .demo-title {
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.75rem;
            display: flex; align-items: center; gap: 0.4rem;
        }
        .demo-title::before {
            content: '';
            width: 6px; height: 6px; border-radius: 50%;
            background: #4ade80;
            display: inline-block;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0%,100% { opacity:1; transform:scale(1); }
            50%      { opacity:0.6; transform:scale(1.3); }
        }
        .demo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 0.5rem;
        }
        .demo-btn {
            display: flex; flex-direction: column;
            align-items: center; gap: 0.25rem;
            padding: 0.6rem 0.5rem;
            border-radius: 10px;
            border: 1.5px solid rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.1);
            cursor: pointer; transition: all 0.2s;
            color: #fff; text-align: center;
        }
        .demo-btn:hover {
            background: rgba(255,255,255,0.22);
            border-color: rgba(255,255,255,0.5);
            transform: translateY(-2px);
        }
        .demo-btn:active { transform: translateY(0); }
        .demo-btn .d-icon {
            width: 32px; height: 32px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.95rem; margin-bottom: 0.15rem;
        }
        .demo-btn .d-role  { font-size: 0.7rem; font-weight: 700; line-height: 1.2; }
        .demo-btn .d-email { font-size: 0.6rem; opacity: 0.75; line-height: 1.3; word-break: break-all; }

        /* Icon colors */
        .ic-admin    { background: rgba(239,68,68,0.3);  }
        .ic-patient  { background: rgba(59,130,246,0.3); }
        .ic-doctor   { background: rgba(34,197,94,0.3);  }
        .ic-hospital { background: rgba(249,115,22,0.3); }
        .ic-lab      { background: rgba(168,85,247,0.3); }
        .ic-pharmacy { background: rgba(20,184,166,0.3); }
        .ic-mc       { background: rgba(234,179,8,0.3);  }

        /* ── LOGIN WRAPPER ── */
        .login-wrapper {
            background: white;
            border-radius: 22px;
            box-shadow: 0 20px 70px rgba(0,0,0,0.22);
            overflow: hidden;
            display: flex;
            animation: slideUp 0.5s ease-out 0.1s both;
        }
        @keyframes slideUp {
            from { opacity:0; transform: translateY(20px); }
            to   { opacity:1; transform: translateY(0); }
        }

        /* Illustration */
        .illustration-section {
            flex: 1;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            padding: 2.5rem 2rem;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        .illustration-section::before {
            content: ''; position: absolute;
            top: -50px; left: -50px;
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.1); border-radius: 50%;
        }
        .illustration-section::after {
            content: ''; position: absolute;
            bottom: -80px; right: -80px;
            width: 250px; height: 250px;
            background: rgba(255,255,255,0.08); border-radius: 50%;
        }
        .illustration-section img {
            width: 100%; max-width: 320px;
            position: relative; z-index: 2;
            filter: drop-shadow(0 10px 30px rgba(0,0,0,0.2));
        }
        .illus-text {
            position: relative; z-index: 2;
            text-align: center; margin-top: 1.5rem;
        }
        .illus-text h3 {
            color: #fff; font-size: 1.2rem; font-weight: 700; margin-bottom: 0.4rem;
        }
        .illus-text p {
            color: rgba(255,255,255,0.75); font-size: 0.82rem; line-height: 1.6;
        }

        /* Form section */
        .form-section {
            flex: 1; padding: 2.2rem 2.2rem;
            display: flex; flex-direction: column; justify-content: center;
        }
        .form-header { margin-bottom: 1.6rem; }
        .form-header h2 { font-size: 1.7rem; font-weight: 700; color: #2d3748; margin-bottom: 0.3rem; }
        .form-header p  { color: #718096; font-size: 0.88rem; margin: 0; }

        .alert {
            padding: 0.9rem 1rem; border-radius: 11px;
            margin-bottom: 1.2rem; font-size: 0.85rem; border: none;
            display: flex; align-items: center; gap: 0.6rem;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity:0; transform:translateY(-8px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .alert-danger  { background:#fff0f0; color:#c33; border-left:4px solid #c33; }
        .alert-success { background:#f0fff4; color:#22543d; border-left:4px solid #22543d; }

        .form-group { margin-bottom: 1.2rem; }
        .form-label { font-weight: 500; color: #4a5568; margin-bottom: 0.45rem; font-size: 0.82rem; display: block; }

        .input-wrapper { position: relative; }
        .input-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #a0aec0; font-size: 0.9rem; }
        .form-control {
            width: 100%; padding: 0.78rem 1rem 0.78rem 2.7rem;
            border: 2px solid #e2e8f0; border-radius: 11px;
            font-size: 0.88rem; font-family: 'Poppins', sans-serif;
            transition: all 0.3s; background: #f7fafc;
        }
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
            outline: none; background: white;
        }
        .form-control.is-invalid { border-color: #dc3545; background: #fff5f5; }
        .invalid-feedback { display: block; color: #dc3545; font-size: 0.78rem; margin-top: 0.35rem; margin-left: 0.4rem; }

        .password-toggle {
            position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
            cursor: pointer; color: #a0aec0; font-size: 0.9rem; transition: color 0.3s;
        }
        .password-toggle:hover { color: #2563eb; }

        .remember-forgot {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1.2rem; font-size: 0.83rem; flex-wrap: wrap; gap: 0.5rem;
        }
        .form-check { display: flex; align-items: center; gap: 0.45rem; }
        .form-check-input { width: 16px; height: 16px; cursor: pointer; }
        .form-check-input:checked { background-color: #2563eb; border-color: #2563eb; }
        .form-check-label { color: #4a5568; cursor: pointer; margin: 0; font-size: 0.83rem; }
        .forgot-link { color: #2563eb; text-decoration: none; font-weight: 500; font-size: 0.83rem; transition: color 0.3s; }
        .forgot-link:hover { color: #1e40af; }

        .btn-login {
            width: 100%; padding: 0.88rem;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border: none; border-radius: 11px;
            color: white; font-weight: 600; font-size: 0.92rem;
            font-family: 'Poppins', sans-serif;
            cursor: pointer; transition: all 0.3s; margin-bottom: 0.9rem;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .btn-login:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(37,99,235,0.3); }
        .btn-login:disabled { opacity: 0.7; cursor: not-allowed; }

        .spinner { display: inline-block; width: 15px; height: 15px; border: 2px solid rgba(255,255,255,0.3); border-radius: 50%; border-top-color: white; animation: spin 0.6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .divider { display: flex; align-items: center; margin: 0.9rem 0; color: #a0aec0; font-size: 0.78rem; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }
        .divider span { padding: 0 0.9rem; }

        .social-login { display: flex; gap: 0.8rem; margin-bottom: 1.1rem; }
        .social-btn {
            flex: 1; padding: 0.72rem; border: 2px solid #e2e8f0; border-radius: 11px;
            background: white; cursor: pointer; transition: all 0.3s;
            display: flex; align-items: center; justify-content: center; gap: 0.55rem;
            font-weight: 500; font-size: 0.83rem; text-decoration: none; color: #4a5568;
            font-family: 'Poppins', sans-serif;
        }
        .social-btn:hover { border-color: #2563eb; background: rgba(37,99,235,0.05); transform: translateY(-2px); color: #4a5568; }
        .google-btn   i { color: #db4437; }
        .facebook-btn i { color: #4267B2; }

        .signup-link { text-align: center; color: #718096; font-size: 0.87rem; }
        .signup-link a { color: #2563eb; text-decoration: none; font-weight: 600; }
        .signup-link a:hover { color: #1e40af; }

        /* Mobile */
        .mobile-illustration {
            display: none; padding: 1.8rem 1.5rem 1.3rem;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border-radius: 22px 22px 0 0; position: relative; overflow: hidden;
        }
        .mobile-illustration::before {
            content: ''; position: absolute; top:-30px; right:-30px;
            width:120px; height:120px; background:rgba(255,255,255,0.1); border-radius:50%;
        }
        .mobile-illustration img {
            width: 100%; max-width: 220px; margin: 0 auto; display: block;
            position: relative; z-index: 2;
            filter: drop-shadow(0 5px 15px rgba(0,0,0,0.2));
        }

        @media (max-width: 768px) {
            .illustration-section { display: none; }
            .mobile-illustration  { display: block; }
            .login-wrapper { flex-direction: column; }
            .form-section  { padding: 1.8rem 1.8rem; border-radius: 0 0 22px 22px; }
            .demo-grid { grid-template-columns: repeat(4, 1fr); }
        }
        @media (max-width: 500px) {
            .demo-grid { grid-template-columns: repeat(3, 1fr); }
            .social-login { flex-direction: column; }
        }
    </style>
</head>
<body>

    <a href="{{ route('Home') }}" class="back-home">
        <i class="fas fa-arrow-left"></i> Back to Home
    </a>

    <div class="page-container">

        {{-- ══ DEMO LOGIN PANEL ══ --}}
        <div class="demo-panel">
            <div class="demo-title">
                Demo Accounts — Password: <strong style="margin-left:.3rem;color:#fde68a;">healthnet2026</strong>
            </div>
            <div class="demo-grid">

                {{-- Admin --}}
                <button class="demo-btn" onclick="fillDemo('admin@healthnet.lk','healthnet2026')">
                    <div class="d-icon ic-admin"><i class="fas fa-user-shield"></i></div>
                    <div class="d-role">Admin</div>
                    <div class="d-email">admin@healthnet.lk</div>
                </button>

                {{-- Patient --}}
                <button class="demo-btn" onclick="fillDemo('nimala.perera@healthnet.lk','healthnet2026')">
                    <div class="d-icon ic-patient"><i class="fas fa-user-injured"></i></div>
                    <div class="d-role">Patient</div>
                    <div class="d-email">nimala.perera</div>
                </button>

                {{-- Doctor --}}
                <button class="demo-btn" onclick="fillDemo('dr.samantha.fernando@healthnet.lk','healthnet2026')">
                    <div class="d-icon ic-doctor"><i class="fas fa-user-md"></i></div>
                    <div class="d-role">Doctor</div>
                    <div class="d-email">dr.samantha.fernando</div>
                </button>

                {{-- Doctor 2 --}}
                <button class="demo-btn" onclick="fillDemo('dr.anura.ruwan@healthnet.lk','healthnet2026')">
                    <div class="d-icon ic-doctor"><i class="fas fa-stethoscope"></i></div>
                    <div class="d-role">Doctor 2</div>
                    <div class="d-email">dr.anura.ruwan</div>
                </button>

                {{-- Hospital --}}
                <button class="demo-btn" onclick="fillDemo('asiri.hospital@healthnet.lk','healthnet2026')">
                    <div class="d-icon ic-hospital"><i class="fas fa-hospital"></i></div>
                    <div class="d-role">Hospital</div>
                    <div class="d-email">asiri.hospital</div>
                </button>

                {{-- Lab --}}
                <button class="demo-btn" onclick="fillDemo('durdans.lab@healthnet.lk','healthnet2026')">
                    <div class="d-icon ic-lab"><i class="fas fa-flask"></i></div>
                    <div class="d-role">Laboratory</div>
                    <div class="d-email">durdans.lab</div>
                </button>

                {{-- Pharmacy --}}
                <button class="demo-btn" onclick="fillDemo('osusala.colombo@healthnet.lk','healthnet2026')">
                    <div class="d-icon ic-pharmacy"><i class="fas fa-pills"></i></div>
                    <div class="d-role">Pharmacy</div>
                    <div class="d-email">osusala.colombo</div>
                </button>

                {{-- Medical Centre --}}
                <button class="demo-btn" onclick="fillDemo('kandy.medcentre@healthnet.lk','healthnet2026')">
                    <div class="d-icon ic-mc"><i class="fas fa-clinic-medical"></i></div>
                    <div class="d-role">Med Centre</div>
                    <div class="d-email">kandy.medcentre</div>
                </button>

            </div>
        </div>

        {{-- ══ LOGIN CARD ══ --}}
        <div class="login-wrapper">

            {{-- Mobile illustration --}}
            <div class="mobile-illustration">
                <img src="{{ asset('images/login-page.png') }}" alt="HealthNet"
                     onerror="this.src='https://cdni.iconscout.com/illustration/premium/thumb/login-page-4468581-3705811.png'">
            </div>

            {{-- Desktop illustration --}}
            <div class="illustration-section">
                <img src="{{ asset('images/login-page.png') }}" alt="HealthNet"
                     onerror="this.src='https://cdni.iconscout.com/illustration/premium/thumb/login-page-4468581-3705811.png'">
                <div class="illus-text">
                    <h3>HealthNet Sri Lanka</h3>
                    <p>Connecting patients with quality<br>healthcare services nationwide.</p>
                </div>
            </div>

            {{-- Form --}}
            <div class="form-section">
                <div class="form-header">
                    <h2>Welcome Back!</h2>
                    <p>Sign in to your HealthNet account</p>
                </div>

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
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email"
                                   placeholder="Enter your email"
                                   value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password"
                                   placeholder="Enter your password" required>
                            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                        </div>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="remember-forgot">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn-login" id="loginBtn">
                        <span id="btnText"><i class="fas fa-sign-in-alt"></i> SIGN IN</span>
                    </button>
                </form>

                <div class="divider"><span>OR LOGIN WITH</span></div>

                <div class="social-login">
                    <a href="{{ route('oauth.redirect', ['driver' => 'google']) }}" class="social-btn google-btn">
                        <i class="fab fa-google"></i> Google
                    </a>
                    <a href="{{ route('oauth.redirect', ['driver' => 'facebook']) }}" class="social-btn facebook-btn">
                        <i class="fab fa-facebook-f"></i> Facebook
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
        /* ── DEMO FILL ── */
        function fillDemo(email, password) {
            const emailEl    = document.getElementById('email');
            const passwordEl = document.getElementById('password');

            // Animate fill
            emailEl.style.transition    = 'background 0.3s';
            passwordEl.style.transition = 'background 0.3s';
            emailEl.style.background    = 'rgba(37,99,235,0.08)';
            passwordEl.style.background = 'rgba(37,99,235,0.08)';

            emailEl.value    = email;
            passwordEl.value = password;

            setTimeout(() => {
                emailEl.style.background    = '';
                passwordEl.style.background = '';
            }, 600);

            // Auto-submit after short delay
            setTimeout(() => {
                document.getElementById('loginBtn').disabled = true;
                document.getElementById('btnText').innerHTML = '<span class="spinner"></span> Signing In…';
                document.getElementById('loginForm').submit();
            }, 400);
        }

        /* ── PASSWORD TOGGLE ── */
        document.getElementById('togglePassword').addEventListener('click', function() {
            const pw = document.getElementById('password');
            const isText = pw.type === 'text';
            pw.type = isText ? 'password' : 'text';
            this.classList.toggle('fa-eye',       isText);
            this.classList.toggle('fa-eye-slash', !isText);
        });

        /* ── FORM SUBMIT LOADING ── */
        document.getElementById('loginForm').addEventListener('submit', function() {
            document.getElementById('loginBtn').disabled = true;
            document.getElementById('btnText').innerHTML = '<span class="spinner"></span> Signing In…';
        });

        /* ── EMAIL VALIDATION ── */
        document.getElementById('email').addEventListener('blur', function() {
            this.classList.toggle('is-invalid', this.value && !this.value.includes('@'));
        });
    </script>
</body>
</html>
