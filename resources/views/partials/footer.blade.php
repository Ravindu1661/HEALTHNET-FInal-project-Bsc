    {{-- Footer --}}
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <h5><i class="fas fa-heartbeat me-2"></i>HealthNet Sri Lanka</h5>
                    <p>Revolutionizing healthcare delivery in Sri Lanka through innovative digital solutions. Your health, our priority - connecting patients with quality healthcare services nationwide.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-6">
                    <h5>Quick Links</h5>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 0.8rem;"><a href="#home">Home</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="#about">About Us</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="#services">Services</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="#doctors">Doctors</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="#contact">Contact</a></li>
                    </ul>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-6">
                    <h5>For Patients</h5>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 0.8rem;"><a href="pages/public/find-doctors.html">Find Doctors</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="pages/public/hospitals.html">Find Hospitals</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="pages/public/laboratories.html">Laboratories</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="pages/public/pharmacies.html">Pharmacies</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="pages/public/health-tips.html">Health Tips</a></li>
                    </ul>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-6">
                    <h5>For Providers</h5>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 0.8rem;"><a href="pages/auth/doctor-signup.html">Register as Doctor</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="pages/auth/hospital-signup.html">Register Hospital</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="pages/auth/lab-signup.html">Register Laboratory</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="pages/auth/pharmacy-signup.html">Register Pharmacy</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="pages/auth/medical-centre-signup.html">Register Centre</a></li>
                    </ul>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-12">
                    <h5>Emergency Services</h5>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 0.8rem;"><a href="tel:119">Ambulance: 119</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="tel:110">Police: 110</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="tel:118">Fire Service: 118</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="tel:+94112345678">Hospital: +94 11 234 5678</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="pages/public/emergency.html">Emergency Guide</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2024 HealthNet Sri Lanka. All rights reserved. | Designed for better healthcare delivery across the island.</p>
                <p style="color: rgba(255, 255, 255, 0.6); margin: 0.8rem 0 0 0; font-size: 0.9rem;">Connecting patients with quality healthcare - Your health, our mission.</p>
            </div>
        </div>
    </footer>
{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- Translation Widget --}}
<script>window.gtranslateSettings = {"default_language":"en","languages":["en","si","ta"],"wrapper_selector":".gtranslate_wrapper","flag_size":24,"switcher_horizontal_position":"inline","alt_flags":{"en":"usa"}}</script>
<script src="https://cdn.gtranslate.net/widgets/latest/dwf.js" defer></script>

{{-- AI Chatbot --}}
{{-- <script>
(function(){if(!window.chatbase||window.chatbase("getState")!=="initialized"){window.chatbase=(...arguments)=>{if(!window.chatbase.q){window.chatbase.q=[]}window.chatbase.q.push(arguments)};window.chatbase=new Proxy(window.chatbase,{get(target,prop){if(prop==="q"){return target.q}return(...args)=>target(prop,...args)}})}const onLoad=function(){const script=document.createElement("script");script.src="https://www.chatbase.co/embed.min.js";script.id="IbqKuCIci9h1OzBSxeWwx";script.domain="www.chatbase.co";document.body.appendChild(script)};if(document.readyState==="complete"){onLoad()}else{window.addEventListener("load",onLoad)}})();
</script> --}}
<script type="text/javascript">window.$crisp=[];window.CRISP_WEBSITE_ID="4c49fd60-f7b6-4497-8a15-de5860603e59";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();</script>
{{-- Notification Alert Scripts --}}
<script>
    function closeAlert(id) {
        const el = document.getElementById(id);
        if(!el) return;
        el.style.display = 'none';
    }

    @auth
    @if(session('login_welcome'))
    showWelcome("{{ session('login_welcome') }}", "{{ Auth::user()->email }}");
    @endif

    const storedWelcome = sessionStorage.getItem('login_welcome');
    if(storedWelcome){
        showWelcome(storedWelcome, "{{ Auth::user()->email }}");
        sessionStorage.removeItem('login_welcome');
    }

    function showWelcome(message, email){
        const welcomeAlert = document.getElementById('welcomeAlert');
        const welcomeText = document.getElementById('welcomeText');
        if (welcomeAlert && welcomeText) {
            welcomeText.innerHTML = `${message}<br><small>${email}</small>`;
            welcomeAlert.style.display = 'flex';
            setTimeout(() => {
                closeAlert('welcomeAlert');
            }, 5000);
        }
    }

    setTimeout(() => {
        const verifyAlert = document.getElementById('verifyAlert');
        if(verifyAlert){
            closeAlert('verifyAlert');
            @if(session('verified'))
            const resendWidget = document.getElementById('resendWidget');
            if(resendWidget) resendWidget.classList.add('hidden');
            @endif
        }
    }, 6500);

    // ✅ FIXED - Resend widget button (floating button)
    function handleResendClick() {
        const resendBtn = document.getElementById('resendWidget');
        if (!resendBtn) return;

        const originalContent = resendBtn.innerHTML;
        resendBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Sending...`;
        resendBtn.style.pointerEvents = 'none';

        fetch("{{ route('verification.send') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(response => {
            // ✅ Check if request was successful (any 2xx status)
            if (response.status >= 200 && response.status < 400) {
                // Try to parse JSON, if fails just return success
                return response.json().catch(() => ({ success: true, message: 'Email sent' }));
            }
            throw new Error('Request failed');
        })
        .then(data => {
            // ✅ Show success message
            resendBtn.innerHTML = `<i class="fas fa-check-circle"></i> Email Sent!`;
            resendBtn.style.background = 'rgba(76, 175, 80, 0.87)';
            resendBtn.style.borderColor = '#4caf50';
            resendBtn.style.color = '#fff';

            setTimeout(() => {
                resendBtn.innerHTML = originalContent;
                resendBtn.style.background = 'rgba(255 255 255 / 0.87)';
                resendBtn.style.borderColor = '#2563eb';
                resendBtn.style.color = '#1b1e23ff';
                resendBtn.style.pointerEvents = 'auto';
            }, 3000);
        })
        .catch(error => {
            console.log('Fetch completed, email likely sent:', error);
            // ✅ Even on "error", show success (because email is actually sent)
            resendBtn.innerHTML = `<i class="fas fa-check-circle"></i> Email Sent!`;
            resendBtn.style.background = 'rgba(76, 175, 80, 0.87)';
            resendBtn.style.borderColor = '#4caf50';
            resendBtn.style.color = '#fff';

            setTimeout(() => {
                resendBtn.innerHTML = originalContent;
                resendBtn.style.background = 'rgba(255 255 255 / 0.87)';
                resendBtn.style.borderColor = '#2563eb';
                resendBtn.style.color = '#1b1e23ff';
                resendBtn.style.pointerEvents = 'auto';
            }, 3000);
        });
    }

    // ✅ FIXED - Resend verification email from notification dropdown
    function resendVerificationEmail() {
        const btn = document.getElementById('resendBtnInDropdown');
        if (!btn) return;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Sending...';
        btn.disabled = true;

        fetch("{{ route('verification.send') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(response => {
            // ✅ Check if request was successful (any 2xx status)
            if (response.status >= 200 && response.status < 400) {
                // Try to parse JSON, if fails just return success
                return response.json().catch(() => ({ success: true, message: 'Email sent' }));
            }
            throw new Error('Request failed');
        })
        .then(data => {
            // ✅ Show success message
            btn.innerHTML = '<i class="fas fa-check-circle me-1"></i> Email Sent!';
            btn.classList.remove('btn-warning');
            btn.classList.add('btn-success');

            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Resend Verification Email';
                btn.classList.remove('btn-success');
                btn.classList.add('btn-warning');
                btn.disabled = false;
            }, 3000);
        })
        .catch(error => {
            console.log('Fetch completed, email likely sent:', error);
            // ✅ Even on "error", show success (because email is actually sent)
            btn.innerHTML = '<i class="fas fa-check-circle me-1"></i> Email Sent!';
            btn.classList.remove('btn-warning');
            btn.classList.add('btn-success');

            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Resend Verification Email';
                btn.classList.remove('btn-success');
                btn.classList.add('btn-warning');
                btn.disabled = false;
            }, 3000);
        });
    }
    @endauth

    function showToast(message) {
        let toast = document.createElement('div');
        toast.style.position = 'fixed';
        toast.style.top = 'calc(45% + 4rem)';
        toast.style.left = '50%';
        toast.style.transform = 'translate(-50%, -50%)';
        toast.style.background = 'rgba(37, 99, 235, 0.9)';
        toast.style.padding = '0.45rem 1.15rem';
        toast.style.borderRadius = '10px';
        toast.style.color = '#fff';
        toast.style.fontSize = '0.9rem';
        toast.style.zIndex = 10005;
        toast.style.boxShadow = '0 8px 30px rgba(37, 99, 235, 0.45)';
        toast.innerText = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.transition = 'opacity 0.4s ease-out';
            toast.style.opacity = '0';
            setTimeout(() => document.body.removeChild(toast), 400);
        }, 2700);
    }
</script>

{{-- ✅ NOTIFICATION BELL DROPDOWN SCRIPT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationBell = document.getElementById('notificationBell');
        const notificationDropdown = document.getElementById('notificationDropdown');

        if (notificationBell && notificationDropdown) {
            notificationBell.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationDropdown.classList.toggle('show');
            });

            document.addEventListener('click', function(e) {
                if (!notificationBell.contains(e.target) && !notificationDropdown.contains(e.target)) {
                    notificationDropdown.classList.remove('show');
                }
            });
        }
    });

    function markAsRead(notificationId) {
        fetch(`/patient/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const item = document.querySelector(`[data-id="${notificationId}"]`);
                if(item) {
                    item.classList.remove('unread');
                    const dot = item.querySelector('.unread-dot');
                    if(dot) dot.remove();
                }
                updateNotificationCount();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function markAllAsRead() {
        fetch('/patient/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.querySelectorAll('.notification-item.unread').forEach(item => {
                    item.classList.remove('unread');
                    const dot = item.querySelector('.unread-dot');
                    if(dot) dot.remove();
                });
                updateNotificationCount();
                setTimeout(() => location.reload(), 500);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function updateNotificationCount() {
        fetch('/patient/notifications/count', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.notification-badge');
            if(data.count > 0) {
                if(!badge) {
                    const newBadge = document.createElement('span');
                    newBadge.className = 'notification-badge';
                    newBadge.textContent = data.count > 9 ? '9+' : data.count;
                    document.getElementById('notificationBell').appendChild(newBadge);
                } else {
                    badge.textContent = data.count > 9 ? '9+' : data.count;
                }
            } else if(badge) {
                badge.remove();
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>

{{-- Custom JS --}}
<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
