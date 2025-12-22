// ========================================================================
// ✅ Patient Signup Script - HealthNet (4 Steps with Back Button + LocalStorage)
// ========================================================================

console.log("✅ Patient signup script loaded successfully!");

// ------------------------------------------------------------------------
// 🧾 Utility: Show Alert Messages
// ------------------------------------------------------------------------
function showAlert(message, type = "danger") {
    const alertBox = document.getElementById("alertMessage");
    const alertText = document.getElementById("alertText");
    alertBox.className = `alert alert-${type}`;
    alertText.textContent = message;
    alertBox.style.display = "block";
    window.scrollTo({ top: 0, behavior: "smooth" });
    setTimeout(() => (alertBox.style.display = "none"), 4000);
}

// ------------------------------------------------------------------------
// 💾 LocalStorage: Save & Load Form Data
// ------------------------------------------------------------------------
const STORAGE_KEY = "healthnet_patient_signup";

function saveFormData() {
    const formData = {
        // Step 1
        firstName: document.getElementById("firstName")?.value || "",
        lastName: document.getElementById("lastName")?.value || "",
        email: document.getElementById("email")?.value || "",
        phone: document.getElementById("phone")?.value || "",
        nic: document.getElementById("nic")?.value || "",
        
        // Step 2
        dob: document.getElementById("dob")?.value || "",
        gender: document.getElementById("gender")?.value || "",
        bloodGroup: document.getElementById("bloodGroup")?.value || "",
        address: document.getElementById("address")?.value || "",
        city: document.getElementById("city")?.value || "",
        province: document.getElementById("province")?.value || "",
        postalCode: document.getElementById("postalCode")?.value || "",
        
        // Step 3
        emergencyName: document.getElementById("emergencyName")?.value || "",
        emergencyPhone: document.getElementById("emergencyPhone")?.value || "",
        password: document.getElementById("password")?.value || "",
        confirmPassword: document.getElementById("confirmPassword")?.value || "",
        
        // Step 4
        newsletter: document.getElementById("newsletter")?.checked || false,
        
        // Current step
        currentStep: currentStep
    };
    
    localStorage.setItem(STORAGE_KEY, JSON.stringify(formData));
}

function loadFormData() {
    const savedData = localStorage.getItem(STORAGE_KEY);
    if (!savedData) return null;
    
    try {
        return JSON.parse(savedData);
    } catch (e) {
        console.error("Error parsing saved data:", e);
        return null;
    }
}

function restoreFormData(data) {
    if (!data) return;
    
    // Step 1
    if (data.firstName) document.getElementById("firstName").value = data.firstName;
    if (data.lastName) document.getElementById("lastName").value = data.lastName;
    if (data.email) document.getElementById("email").value = data.email;
    if (data.phone) document.getElementById("phone").value = data.phone;
    if (data.nic) document.getElementById("nic").value = data.nic;
    
    // Step 2
    if (data.dob) document.getElementById("dob").value = data.dob;
    if (data.gender) document.getElementById("gender").value = data.gender;
    if (data.bloodGroup) document.getElementById("bloodGroup").value = data.bloodGroup;
    if (data.address) document.getElementById("address").value = data.address;
    if (data.city) document.getElementById("city").value = data.city;
    if (data.province) document.getElementById("province").value = data.province;
    if (data.postalCode) document.getElementById("postalCode").value = data.postalCode;
    
    // Step 3
    if (data.emergencyName) document.getElementById("emergencyName").value = data.emergencyName;
    if (data.emergencyPhone) document.getElementById("emergencyPhone").value = data.emergencyPhone;
    if (data.password) document.getElementById("password").value = data.password;
    if (data.confirmPassword) document.getElementById("confirmPassword").value = data.confirmPassword;
    
    // Step 4
    if (data.newsletter) document.getElementById("newsletter").checked = data.newsletter;
}

function clearFormData() {
    localStorage.removeItem(STORAGE_KEY);
}

// ------------------------------------------------------------------------
// 📑 Step Navigation Functions with Illustration & Features Change
// ------------------------------------------------------------------------
let currentStep = 1;

function updateBackButton() {
    const backBtn = document.getElementById("backStepBtn");
    if (currentStep > 1) {
        backBtn.classList.add("show");
    } else {
        backBtn.classList.remove("show");
    }
}

function goToStep(step) {
    // Remove all active states
    for (let i = 1; i <= 4; i++) {
        document.getElementById(`section${i}`).classList.remove("active");
        document.getElementById(`stepItem${i}`).classList.remove("active");
        document.getElementById(`illustration${i}`).classList.remove("active");
        document.getElementById(`features${i}`).classList.remove("active");
        
        // Mark completed steps
        if (i < step) {
            document.getElementById(`stepItem${i}`).classList.add("completed");
        } else {
            document.getElementById(`stepItem${i}`).classList.remove("completed");
        }
    }
    
    // Activate target step
    document.getElementById(`section${step}`).classList.add("active");
    document.getElementById(`stepItem${step}`).classList.add("active");
    document.getElementById(`illustration${step}`).classList.add("active");
    document.getElementById(`features${step}`).classList.add("active");
    
    currentStep = step;
    updateBackButton();
    saveFormData();
    window.scrollTo({ top: 0, behavior: "smooth" });
}

function nextStep(step) {
    if (!validateStep(step)) return;
    
    // Remove active states
    document.getElementById(`section${step}`).classList.remove("active");
    document.getElementById(`stepItem${step}`).classList.remove("active");
    document.getElementById(`stepItem${step}`).classList.add("completed");
    document.getElementById(`illustration${step}`).classList.remove("active");
    document.getElementById(`features${step}`).classList.remove("active");
    
    // Activate next step
    const next = step + 1;
    document.getElementById(`section${next}`).classList.add("active");
    document.getElementById(`stepItem${next}`).classList.add("active");
    document.getElementById(`illustration${next}`).classList.add("active");
    document.getElementById(`features${next}`).classList.add("active");
    
    window.scrollTo({ top: 0, behavior: "smooth" });
    currentStep = next;
    updateBackButton();
    saveFormData();
}

function prevStep(step) {
    // Remove active states
    document.getElementById(`section${step}`).classList.remove("active");
    document.getElementById(`stepItem${step}`).classList.remove("active");
    document.getElementById(`illustration${step}`).classList.remove("active");
    document.getElementById(`features${step}`).classList.remove("active");
    
    // Activate previous step
    const prev = step - 1;
    document.getElementById(`section${prev}`).classList.add("active");
    document.getElementById(`stepItem${prev}`).classList.add("active");
    document.getElementById(`stepItem${prev}`).classList.remove("completed");
    document.getElementById(`illustration${prev}`).classList.add("active");
    document.getElementById(`features${prev}`).classList.add("active");
    
    window.scrollTo({ top: 0, behavior: "smooth" });
    currentStep = prev;
    updateBackButton();
    saveFormData();
}

function backStepQuick() {
    if (currentStep > 1) {
        prevStep(currentStep);
    }
}

// ------------------------------------------------------------------------
// ✅ Step Validation
// ------------------------------------------------------------------------
function validateStep(step) {
    let isValid = true, error = "";
    
    if (step === 1) {
        const first = document.getElementById("firstName").value.trim();
        const last = document.getElementById("lastName").value.trim();
        const email = document.getElementById("email").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const nic = document.getElementById("nic").value.trim();
        
        if (!first || !last) error = "Please enter your full name";
        else if (!email) error = "Please enter your email address";
        else if (!validateEmail(email)) error = "Please enter a valid email address";
        else if (!phone) error = "Please enter your phone number";
        else if (!validatePhone(phone)) error = "Invalid phone number (use +94 or 0)";
        else if (!nic) error = "Please enter your NIC number";
        else if (!validateNIC(nic)) error = "Invalid NIC number format";
    } else if (step === 2) {
        const dob = document.getElementById("dob").value;
        const gender = document.getElementById("gender").value;
        const address = document.getElementById("address").value.trim();
        const city = document.getElementById("city").value.trim();
        const province = document.getElementById("province").value;
        
        if (!dob) error = "Please select your date of birth";
        else if (!validateAge(dob)) error = "You must be at least 18 years old";
        else if (!gender) error = "Please select your gender";
        else if (!address) error = "Please enter your address";
        else if (!city) error = "Please enter your city";
        else if (!province) error = "Please select your province";
    } else if (step === 3) {
        const password = document.getElementById("password").value;
        const confirm = document.getElementById("confirmPassword").value;
        
        if (!password) error = "Please enter a password";
        else if (password.length < 8) error = "Password must be at least 8 characters long";
        else if (!confirm) error = "Please confirm your password";
        else if (password !== confirm) error = "Passwords do not match";
    }
    
    if (error) {
        showAlert(error);
        isValid = false;
    }
    return isValid;
}

// ------------------------------------------------------------------------
// 🧮 Validation Helper Functions
// ------------------------------------------------------------------------
function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function validatePhone(phone) {
    return /^(\+94|0)?[0-9]{9,10}$/.test(phone.replace(/\s/g, ""));
}

function validateNIC(nic) {
    return /^[0-9]{9}[vVxX]$/.test(nic) || /^[0-9]{12}$/.test(nic);
}

function validateAge(dob) {
    const birth = new Date(dob);
    const today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    const m = today.getMonth() - birth.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
    return age >= 18;
}

// ------------------------------------------------------------------------
// 🔒 Password Visibility Toggle
// ------------------------------------------------------------------------
function setupPasswordToggles() {
    const togglePassword = document.getElementById("togglePassword");
    const toggleConfirm = document.getElementById("toggleConfirmPassword");
    const password = document.getElementById("password");
    const confirm = document.getElementById("confirmPassword");
    
    if (togglePassword && password) {
        togglePassword.addEventListener("click", () => {
            const type = password.type === "password" ? "text" : "password";
            password.type = type;
            togglePassword.classList.toggle("fa-eye");
            togglePassword.classList.toggle("fa-eye-slash");
        });
    }
    
    if (toggleConfirm && confirm) {
        toggleConfirm.addEventListener("click", () => {
            const type = confirm.type === "password" ? "text" : "password";
            confirm.type = type;
            toggleConfirm.classList.toggle("fa-eye");
            toggleConfirm.classList.toggle("fa-eye-slash");
        });
    }
}

// ------------------------------------------------------------------------
// 📤 Main Form Submission (Backend Integration)
// ------------------------------------------------------------------------
const signupForm = document.getElementById("signupForm");
const submitBtn = document.getElementById("submitBtn");

if (signupForm) {
    signupForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        
        const terms = document.getElementById("terms");
        if (!terms.checked) return showAlert("Please accept Terms & Conditions");
        
        // Disable button while submitting
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Creating Account...';
        
        const formData = new FormData(signupForm);
        
        try {
            const res = await fetch("/signup/patient", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: formData
            });
            
            const contentType = res.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error("Server returned non-JSON response. Please check your routes.");
            }
            
            const data = await res.json();
            console.log("📨 Server Response:", data);
            
            if (res.ok && data.ok) {
                showAlert("✅ Account created successfully! Redirecting...", "success");
                
                // Clear saved form data on successful submission
                clearFormData();
                
                if (data.welcome_message) {
                    sessionStorage.setItem('login_welcome', data.welcome_message);
                }
                
                setTimeout(() => {
                    window.location.href = data.redirect || "/login-home";
                }, 1500);
            } else {
                if (data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join("<br>");
                    showAlert(errorMessages);
                } else {
                    showAlert(data.message || "Registration failed. Please try again.");
                }
            }
        } catch (error) {
            console.error("❌ Error:", error);
            showAlert("An unexpected error occurred: " + error.message);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
}

// ------------------------------------------------------------------------
// 📅 Date & NIC Formatting
// ------------------------------------------------------------------------
const nicInput = document.getElementById("nic");
if (nicInput) {
    nicInput.addEventListener("input", (e) => {
        e.target.value = e.target.value.toUpperCase();
        saveFormData();
    });
}

const dobInput = document.getElementById("dob");
if (dobInput) {
    const today = new Date();
    const max = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
    const min = new Date(today.getFullYear() - 120, today.getMonth(), today.getDate());
    dobInput.max = max.toISOString().split("T")[0];
    dobInput.min = min.toISOString().split("T")[0];
}

// ------------------------------------------------------------------------
// 📞 Phone Formatting
// ------------------------------------------------------------------------
const phoneInputs = ["phone", "emergencyPhone"];
phoneInputs.forEach((id) => {
    const input = document.getElementById(id);
    if (input) {
        input.addEventListener("input", (e) => {
            let val = e.target.value.replace(/\s/g, "");
            if (val.startsWith("+94")) {
                val = `${val.slice(0, 3)} ${val.slice(3, 5)} ${val.slice(5, 8)} ${val.slice(8)}`;
            } else if (val.startsWith("0")) {
                val = `${val.slice(0, 3)} ${val.slice(3, 6)} ${val.slice(6)}`;
            }
            e.target.value = val.trim();
            saveFormData();
        });
    }
});

// ------------------------------------------------------------------------
// 💾 Auto-save on input change
// ------------------------------------------------------------------------
function setupAutoSave() {
    const allInputs = document.querySelectorAll('#signupForm input, #signupForm select, #signupForm textarea');
    allInputs.forEach(input => {
        input.addEventListener('input', saveFormData);
        input.addEventListener('change', saveFormData);
    });
}

// ------------------------------------------------------------------------
// 🚀 Initialize on Page Load
// ------------------------------------------------------------------------
document.addEventListener("DOMContentLoaded", () => {
    console.log("🔄 Initializing form...");
    
    // Load saved data
    const savedData = loadFormData();
    
    if (savedData) {
        console.log("✅ Found saved data, restoring...");
        
        // Restore form fields
        restoreFormData(savedData);
        
        // Restore current step
        if (savedData.currentStep && savedData.currentStep > 1) {
            goToStep(savedData.currentStep);
        }
    }
    
    // Setup auto-save
    setupAutoSave();
    
    // Setup password toggles
    setupPasswordToggles();
    
    // Initialize back button state
    updateBackButton();
    
    console.log("✅ All signup form features initialized successfully!");
});

console.log("✅ Patient signup script loaded successfully!");
