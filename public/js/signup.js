// Current step tracker
let currentStep = 1;

// Password Toggle for Password Field
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');

if (togglePassword) {
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
}

// Password Toggle for Confirm Password Field
const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
const confirmPasswordInput = document.getElementById('confirmPassword');

if (toggleConfirmPassword) {
    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);
        
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
}

// Password Strength Checker
if (passwordInput) {
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strengthIndicator = document.getElementById('passwordStrength');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        
        if (password.length > 0) {
            strengthIndicator.style.display = 'block';
            
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            
            // Complexity checks
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            
            // Update strength bar
            strengthBar.className = 'strength-bar-fill';
            
            if (strength <= 2) {
                strengthBar.classList.add('weak');
                strengthText.textContent = 'Weak password';
                strengthText.style.color = '#dc3545';
            } else if (strength <= 4) {
                strengthBar.classList.add('medium');
                strengthText.textContent = 'Medium strength';
                strengthText.style.color = '#ffc107';
            } else {
                strengthBar.classList.add('strong');
                strengthText.textContent = 'Strong password';
                strengthText.style.color = '#28a745';
            }
        } else {
            strengthIndicator.style.display = 'none';
        }
    });
}

// Next Step Function
function nextStep(step) {
    // Validate current step
    if (!validateStep(step)) {
        return;
    }
    
    // Hide current section
    document.getElementById(`section${step}`).classList.remove('active');
    document.getElementById(`step${step}`).classList.remove('active');
    document.getElementById(`step${step}`).classList.add('completed');
    
    // Show next section
    const nextStep = step + 1;
    document.getElementById(`section${nextStep}`).classList.add('active');
    document.getElementById(`step${nextStep}`).classList.add('active');
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    currentStep = nextStep;
}

// Previous Step Function
function prevStep(step) {
    // Hide current section
    document.getElementById(`section${step}`).classList.remove('active');
    document.getElementById(`step${step}`).classList.remove('active');
    
    // Show previous section
    const prevStep = step - 1;
    document.getElementById(`section${prevStep}`).classList.add('active');
    document.getElementById(`step${prevStep}`).classList.add('active');
    document.getElementById(`step${prevStep}`).classList.remove('completed');
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    currentStep = prevStep;
}

// Validate Step Function
function validateStep(step) {
    let isValid = true;
    let errorMessage = '';
    
    if (step === 1) {
        // Validate Step 1: Basic Information
        const firstName = document.getElementById('firstName').value.trim();
        const lastName = document.getElementById('lastName').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        
        if (!firstName || !lastName) {
            errorMessage = 'Please enter your full name';
            isValid = false;
        } else if (!email) {
            errorMessage = 'Please enter your email address';
            isValid = false;
        } else if (!validateEmail(email)) {
            errorMessage = 'Please enter a valid email address';
            isValid = false;
        } else if (!phone) {
            errorMessage = 'Please enter your phone number';
            isValid = false;
        } else if (!validatePhone(phone)) {
            errorMessage = 'Please enter a valid phone number (e.g., +94 XX XXX XXXX)';
            isValid = false;
        }
    } else if (step === 2) {
        // Validate Step 2: Personal Details
        const dob = document.getElementById('dob').value;
        const gender = document.getElementById('gender').value;
        const nic = document.getElementById('nic').value.trim();
        const address = document.getElementById('address').value.trim();
        const city = document.getElementById('city').value.trim();
        
        if (!dob) {
            errorMessage = 'Please select your date of birth';
            isValid = false;
        } else if (!gender) {
            errorMessage = 'Please select your gender';
            isValid = false;
        } else if (!nic) {
            errorMessage = 'Please enter your NIC number';
            isValid = false;
        } else if (!validateNIC(nic)) {
            errorMessage = 'Please enter a valid NIC number';
            isValid = false;
        } else if (!address) {
            errorMessage = 'Please enter your address';
            isValid = false;
        } else if (!city) {
            errorMessage = 'Please enter your city';
            isValid = false;
        }
    }
    
    if (!isValid) {
        showAlert(errorMessage, 'danger');
    }
    
    return isValid;
}

// Email Validation
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Phone Validation (Sri Lankan format)
function validatePhone(phone) {
    const phoneRegex = /^(\+94|0)?[0-9]{9,10}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
}

// NIC Validation (Sri Lankan format)
function validateNIC(nic) {
    // Old NIC: 9 digits + V/X
    // New NIC: 12 digits
    const oldNICRegex = /^[0-9]{9}[vVxX]$/;
    const newNICRegex = /^[0-9]{12}$/;
    
    return oldNICRegex.test(nic) || newNICRegex.test(nic);
}

// Show Alert Function
function showAlert(message, type) {
    const alertMessage = document.getElementById('alertMessage');
    const alertText = document.getElementById('alertText');
    
    alertText.textContent = message;
    alertMessage.className = `alert alert-${type}`;
    alertMessage.style.display = 'block';
    
    // Scroll to alert
    alertMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        alertMessage.style.display = 'none';
    }, 5000);
}

// Form Submission
const signupForm = document.getElementById('signupForm');
const submitBtn = document.getElementById('submitBtn');

if (signupForm) {
    signupForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get all form values
        const formData = {
            firstName: document.getElementById('firstName').value.trim(),
            lastName: document.getElementById('lastName').value.trim(),
            email: document.getElementById('email').value.trim(),
            phone: document.getElementById('phone').value.trim(),
            dob: document.getElementById('dob').value,
            gender: document.getElementById('gender').value,
            nic: document.getElementById('nic').value.trim(),
            address: document.getElementById('address').value.trim(),
            city: document.getElementById('city').value.trim(),
            password: document.getElementById('password').value,
            confirmPassword: document.getElementById('confirmPassword').value,
            terms: document.getElementById('terms').checked,
            newsletter: document.getElementById('newsletter').checked
        };
        
        // Validate passwords match
        if (formData.password !== formData.confirmPassword) {
            showAlert('Passwords do not match', 'danger');
            return;
        }
        
        // Validate password strength
        if (formData.password.length < 8) {
            showAlert('Password must be at least 8 characters long', 'danger');
            return;
        }
        
        // Validate terms accepted
        if (!formData.terms) {
            showAlert('Please accept the Terms & Conditions', 'danger');
            return;
        }
        
        // Show loading
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Creating Account...';
        submitBtn.disabled = true;
        
        // Simulate API call (Replace with actual API call)
        setTimeout(() => {
            // Success scenario
            showAlert('Account created successfully! Redirecting...', 'success');
            
            // Log form data (for development)
            console.log('Form Data:', formData);
            
            // Redirect to login page after 2 seconds
            setTimeout(() => {
                window.location.href = 'login.html';
            }, 2000);
            
            // Reset form (optional)
            // signupForm.reset();
            // submitBtn.innerHTML = originalText;
            // submitBtn.disabled = false;
        }, 2000);
    });
}

// Social Signup
function socialSignup(provider) {
    console.log(`Signing up with ${provider}`);
    showAlert(`${provider} signup integration coming soon!`, 'success');
    
    // Add your OAuth integration here
    // Example: Redirect to OAuth provider
    // window.location.href = `your-backend-url/auth/${provider}/signup`;
}

// Phone number formatting (optional)
const phoneInput = document.getElementById('phone');
if (phoneInput) {
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        
        // Add spaces for better readability
        if (value.startsWith('+94')) {
            value = value.substring(0, 3) + ' ' + 
                    value.substring(3, 5) + ' ' + 
                    value.substring(5, 8) + ' ' + 
                    value.substring(8);
        } else if (value.startsWith('0')) {
            value = value.substring(0, 3) + ' ' + 
                    value.substring(3, 6) + ' ' + 
                    value.substring(6);
        }
        
        e.target.value = value.trim();
    });
}

// NIC formatting (optional)
const nicInput = document.getElementById('nic');
if (nicInput) {
    nicInput.addEventListener('input', function(e) {
        // Convert to uppercase for old NIC format
        e.target.value = e.target.value.toUpperCase();
    });
}

// Date of Birth validation (must be at least 18 years old)
const dobInput = document.getElementById('dob');
if (dobInput) {
    // Set max date to 18 years ago
    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
    dobInput.max = maxDate.toISOString().split('T')[0];
    
    // Set min date to 120 years ago
    const minDate = new Date(today.getFullYear() - 120, today.getMonth(), today.getDate());
    dobInput.min = minDate.toISOString().split('T')[0];
}

console.log('✅ Signup form initialized successfully!');