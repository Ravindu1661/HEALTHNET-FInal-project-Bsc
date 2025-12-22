// Get provider type from URL parameter or default to 'doctor'
const urlParams = new URLSearchParams(window.location.search);
const providerType = urlParams.get('type') || 'doctor';

// Local Storage Keys
const STORAGE_KEY = `provider_registration_${providerType}`;
const STORAGE_STEP_KEY = `provider_registration_step_${providerType}`;

// Provider configurations
const providerConfig = {
    doctor: {
        icon: 'fa-user-md',
        title: 'Doctor Registration',
        subtitle: 'Join HealthNet as a Medical Professional',
        color: '#0f4c75',
        infoText: 'Your SLMC registration and credentials will be verified by our admin team.'
    },
    hospital: {
        icon: 'fa-hospital',
        title: 'Hospital Registration',
        subtitle: 'Register Your Healthcare Facility',
        color: '#3282b8',
        infoText: 'Government and Private hospitals are welcome. Registration documents will be verified.'
    },
    laboratory: {
        icon: 'fa-flask',
        title: 'Laboratory Registration',
        subtitle: 'Register Your Diagnostic Laboratory',
        color: '#9b59b6',
        infoText: 'Your laboratory license and accreditation will be verified by our team.'
    },
    pharmacy: {
        icon: 'fa-pills',
        title: 'Pharmacy Registration',
        subtitle: 'Register Your Pharmacy',
        color: '#e74c3c',
        infoText: 'Pharmacist license and pharmacy registration will be verified.'
    },
    medical_centre: {
        icon: 'fa-clinic-medical',
        title: 'Medical Centre Registration',
        subtitle: 'Register Your Medical Centre',
        color: '#16a085',
        infoText: 'Your medical centre license and facility details will be verified.'
    }
};

// Specialization options for doctors
const doctorSpecializations = [
    'Anesthesiology',
    'Cardiology',
    'Dermatology',
    'Emergency Medicine',
    'Endocrinology',
    'Family Medicine',
    'Gastroenterology',
    'General Surgery',
    'Gynecology',
    'Hematology',
    'Internal Medicine',
    'Nephrology',
    'Neurology',
    'Oncology',
    'Ophthalmology',
    'Orthopedics',
    'Otolaryngology (ENT)',
    'Pediatrics',
    'Psychiatry',
    'Pulmonology',
    'Radiology',
    'Rheumatology',
    'Urology'
];

// Hospital specializations
const hospitalSpecializations = [
    'Cardiology',
    'Neurology',
    'Orthopedics',
    'Pediatrics',
    'Oncology',
    'Emergency Medicine',
    'Surgery',
    'Obstetrics & Gynecology',
    'Internal Medicine',
    'Radiology',
    'Anesthesiology',
    'Dermatology',
    'Psychiatry',
    'ENT',
    'Ophthalmology'
];

// Laboratory services
const laboratoryServices = [
    'Blood Tests (CBC, ESR, etc.)',
    'Urine Analysis',
    'X-Ray',
    'MRI Scan',
    'CT Scan',
    'Ultrasound Scan',
    'ECG',
    'Echo Cardiogram',
    'Blood Sugar Tests',
    'Lipid Profile',
    'Liver Function Tests',
    'Kidney Function Tests',
    'Thyroid Function Tests',
    'COVID-19 PCR Test',
    'Pregnancy Tests',
    'Allergy Tests',
    'Hormone Tests',
    'Cancer Markers',
    'Microbiology Tests',
    'Pathology Services'
];

// Days of the week for operating hours
const weekdays = [
    { value: 'monday', label: 'Monday' },
    { value: 'tuesday', label: 'Tuesday' },
    { value: 'wednesday', label: 'Wednesday' },
    { value: 'thursday', label: 'Thursday' },
    { value: 'friday', label: 'Friday' }
];

const weekendDays = [
    { value: 'saturday', label: 'Saturday' },
    { value: 'sunday', label: 'Sunday' }
];

const allDays = [...weekdays, ...weekendDays];

// Form field configurations based on Laravel validation rules
const formFields = {
    doctor: {
        basic: [
            { name: 'slmc_number', label: 'SLMC Registration Number', type: 'text', required: true, placeholder: 'e.g., SL12345', maxlength: 50 },
            { name: 'first_name', label: 'First Name', type: 'text', required: true, placeholder: 'John', maxlength: 100 },
            { name: 'last_name', label: 'Last Name', type: 'text', required: true, placeholder: 'Doe', maxlength: 100 },
            { name: 'specialization', label: 'Specialization', type: 'select', required: true, options: doctorSpecializations }
        ],
        contact: [
            { name: 'phone', label: 'Phone Number', type: 'tel', required: true, placeholder: '+94 XX XXX XXXX', maxlength: 20 }
        ],
        professional: [
            { name: 'qualifications', label: 'Qualifications', type: 'textarea', required: true, placeholder: 'MBBS, MD, etc.' },
            { name: 'experience_years', label: 'Years of Experience', type: 'number', required: true, placeholder: '5', min: 0, max: 60 },
            { name: 'consultation_fee', label: 'Consultation Fee (LKR)', type: 'number', required: false, placeholder: '2000', min: 0, max: 999999.99, step: '0.01' },
            { name: 'bio', label: 'Professional Bio', type: 'textarea', required: false, placeholder: 'Brief description about yourself...', maxlength: 1000 }
        ],
        account: [
            { name: 'email', label: 'Email', type: 'email', required: true, placeholder: 'doctor@example.com', maxlength: 255 },
            { name: 'password', label: 'Password', type: 'password', required: true, minlength: 8, placeholder: 'Minimum 8 characters' },
            { name: 'password_confirmation', label: 'Confirm Password', type: 'password', required: true, placeholder: 'Re-enter password' },
            { name: 'document', label: 'SLMC Certificate & ID', type: 'file', required: true, accept: '.pdf,.jpg,.jpeg,.png' },
            { name: 'profile_image', label: 'Profile Photo', type: 'file', required: false, accept: '.jpg,.jpeg,.png' }
        ]
    },
    hospital: {
        basic: [
            { name: 'name', label: 'Hospital Name', type: 'text', required: true, placeholder: 'General Hospital', maxlength: 255 },
            { name: 'registration_number', label: 'Registration Number', type: 'text', required: true, placeholder: 'HRN123456', maxlength: 100 },
            { name: 'type', label: 'Hospital Type', type: 'select', required: true, options: ['government', 'private'] }
        ],
        contact: [
            { name: 'phone', label: 'Phone Number', type: 'tel', required: true, placeholder: '+94 XX XXX XXXX', maxlength: 20 },
            { name: 'address', label: 'Address', type: 'textarea', required: true, placeholder: 'Street address' },
            { name: 'city', label: 'City', type: 'text', required: true, placeholder: 'Colombo', maxlength: 100 },
            { name: 'province', label: 'Province', type: 'select', required: true, options: [
                'Western', 'Central', 'Southern', 'Northern', 'Eastern', 'North Western', 'North Central', 'Uva', 'Sabaragamuwa'
            ]},
            { name: 'postal_code', label: 'Postal Code', type: 'text', required: false, placeholder: '00100', maxlength: 10 }
        ],
        professional: [
            { name: 'specializations', label: 'Medical Specializations', type: 'multiselect', required: true, options: hospitalSpecializations },
            { name: 'facilities', label: 'Available Facilities', type: 'textarea', required: true, placeholder: 'ICU, Emergency Unit, Operating Theatres (comma separated)' },
            { name: 'operating_hours', label: 'Operating Hours', type: 'schedule', required: false },
            { name: 'description', label: 'Hospital Description', type: 'textarea', required: false, placeholder: 'Brief description...' },
            { name: 'website', label: 'Website URL', type: 'url', required: false, placeholder: 'example.com or https://example.com', maxlength: 255 }
        ],
        account: [
            { name: 'email', label: 'Email', type: 'email', required: true, placeholder: 'admin@hospital.lk', maxlength: 255 },
            { name: 'password', label: 'Password', type: 'password', required: true, minlength: 8, placeholder: 'Minimum 8 characters' },
            { name: 'password_confirmation', label: 'Confirm Password', type: 'password', required: true, placeholder: 'Re-enter password' },
            { name: 'document', label: 'Hospital Registration Certificate', type: 'file', required: true, accept: '.pdf,.jpg,.jpeg,.png' },
            { name: 'profile_image', label: 'Hospital Photo', type: 'file', required: false, accept: '.jpg,.jpeg,.png' }
        ]
    },
    laboratory: {
        basic: [
            { name: 'name', label: 'Laboratory Name', type: 'text', required: true, placeholder: 'Medical Diagnostics Lab', maxlength: 255 },
            { name: 'registration_number', label: 'Registration Number', type: 'text', required: true, placeholder: 'LRN123456', maxlength: 100 }
        ],
        contact: [
            { name: 'phone', label: 'Phone Number', type: 'tel', required: true, placeholder: '+94 XX XXX XXXX', maxlength: 20 },
            { name: 'address', label: 'Address', type: 'textarea', required: true, placeholder: 'Street address' },
            { name: 'city', label: 'City', type: 'text', required: true, placeholder: 'Colombo', maxlength: 100 },
            { name: 'province', label: 'Province', type: 'select', required: true, options: [
                'Western', 'Central', 'Southern', 'Northern', 'Eastern', 'North Western', 'North Central', 'Uva', 'Sabaragamuwa'
            ]},
            { name: 'postal_code', label: 'Postal Code', type: 'text', required: false, placeholder: '00100', maxlength: 10 }
        ],
        professional: [
            { name: 'services', label: 'Laboratory Services', type: 'multiselect', required: true, options: laboratoryServices },
            { name: 'operating_hours', label: 'Operating Hours', type: 'schedule', required: false },
            { name: 'description', label: 'Laboratory Description', type: 'textarea', required: false, placeholder: 'Brief description...' }
        ],
        account: [
            { name: 'email', label: 'Email', type: 'email', required: true, placeholder: 'admin@lab.lk', maxlength: 255 },
            { name: 'password', label: 'Password', type: 'password', required: true, minlength: 8, placeholder: 'Minimum 8 characters' },
            { name: 'password_confirmation', label: 'Confirm Password', type: 'password', required: true, placeholder: 'Re-enter password' },
            { name: 'document', label: 'Laboratory License', type: 'file', required: true, accept: '.pdf,.jpg,.jpeg,.png' },
            { name: 'profile_image', label: 'Laboratory Photo', type: 'file', required: false, accept: '.jpg,.jpeg,.png' }
        ]
    },
    pharmacy: {
        basic: [
            { name: 'name', label: 'Pharmacy Name', type: 'text', required: true, placeholder: 'City Pharmacy', maxlength: 255 },
            { name: 'registration_number', label: 'Registration Number', type: 'text', required: true, placeholder: 'PRN123456', maxlength: 100 },
            { name: 'pharmacist_name', label: 'Pharmacist Name', type: 'text', required: true, placeholder: 'John Doe', maxlength: 100 },
            { name: 'pharmacist_license', label: 'Pharmacist License Number', type: 'text', required: true, placeholder: 'PL123456', maxlength: 100 }
        ],
        contact: [
            { name: 'phone', label: 'Phone Number', type: 'tel', required: true, placeholder: '+94 XX XXX XXXX', maxlength: 20 },
            { name: 'address', label: 'Address', type: 'textarea', required: true, placeholder: 'Street address' },
            { name: 'city', label: 'City', type: 'text', required: true, placeholder: 'Colombo', maxlength: 100 },
            { name: 'province', label: 'Province', type: 'select', required: true, options: [
                'Western', 'Central', 'Southern', 'Northern', 'Eastern', 'North Western', 'North Central', 'Uva', 'Sabaragamuwa'
            ]},
            { name: 'postal_code', label: 'Postal Code', type: 'text', required: false, placeholder: '00100', maxlength: 10 }
        ],
        professional: [
            { name: 'operating_hours', label: 'Operating Hours', type: 'schedule', required: false },
            { name: 'delivery_available', label: 'Home Delivery Available', type: 'checkbox', required: false }
        ],
        account: [
            { name: 'email', label: 'Email', type: 'email', required: true, placeholder: 'admin@pharmacy.lk', maxlength: 255 },
            { name: 'password', label: 'Password', type: 'password', required: true, minlength: 8, placeholder: 'Minimum 8 characters' },
            { name: 'password_confirmation', label: 'Confirm Password', type: 'password', required: true, placeholder: 'Re-enter password' },
            { name: 'document', label: 'Pharmacy License & Pharmacist License', type: 'file', required: true, accept: '.pdf,.jpg,.jpeg,.png' },
            { name: 'profile_image', label: 'Pharmacy Photo', type: 'file', required: false, accept: '.jpg,.jpeg,.png' }
        ]
    },
    medical_centre: {
        basic: [
            { name: 'name', label: 'Medical Centre Name', type: 'text', required: true, placeholder: 'City Medical Centre', maxlength: 255 },
            { name: 'registration_number', label: 'Registration Number', type: 'text', required: true, placeholder: 'MCN123456', maxlength: 100 }
        ],
        contact: [
            { name: 'phone', label: 'Phone Number', type: 'tel', required: true, placeholder: '+94 XX XXX XXXX', maxlength: 20 },
            { name: 'address', label: 'Address', type: 'textarea', required: true, placeholder: 'Street address' },
            { name: 'city', label: 'City', type: 'text', required: true, placeholder: 'Colombo', maxlength: 100 },
            { name: 'province', label: 'Province', type: 'select', required: true, options: [
                'Western', 'Central', 'Southern', 'Northern', 'Eastern', 'North Western', 'North Central', 'Uva', 'Sabaragamuwa'
            ]},
            { name: 'postal_code', label: 'Postal Code', type: 'text', required: false, placeholder: '00100', maxlength: 10 }
        ],
        professional: [
            { name: 'specializations', label: 'Medical Specializations', type: 'multiselect', required: true, options: hospitalSpecializations },
            { name: 'facilities', label: 'Available Facilities', type: 'textarea', required: true, placeholder: 'Consultation rooms, Lab facilities (comma separated)' },
            { name: 'operating_hours', label: 'Operating Hours', type: 'schedule', required: false },
            { name: 'description', label: 'Centre Description', type: 'textarea', required: false, placeholder: 'Brief description...' }
        ],
        account: [
            { name: 'email', label: 'Email', type: 'email', required: true, placeholder: 'admin@medicalcentre.lk', maxlength: 255 },
            { name: 'password', label: 'Password', type: 'password', required: true, minlength: 8, placeholder: 'Minimum 8 characters' },
            { name: 'password_confirmation', label: 'Confirm Password', type: 'password', required: true, placeholder: 'Re-enter password' },
            { name: 'document', label: 'Medical Centre License', type: 'file', required: true, accept: '.pdf,.jpg,.jpeg,.png' },
            { name: 'profile_image', label: 'Centre Photo', type: 'file', required: false, accept: '.jpg,.jpeg,.png' }
        ]
    }
};

// Current step tracker
let currentStep = 1;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    initializeProvider();
    loadFormFields();
    restoreFormData();
    setupAutoSave();
});

// Initialize provider configuration
function initializeProvider() {
    const config = providerConfig[providerType];
    
    document.getElementById('providerType').value = providerType;
    document.getElementById('headerTitle').textContent = config.title;
    document.getElementById('headerSubtitle').textContent = config.subtitle;
    document.getElementById('infoText').textContent = config.infoText;
    
    // Update animation
    updateAnimationByProviderType(providerType);
}

// Load form fields dynamically
function loadFormFields() {
    const fields = formFields[providerType];
    
    document.getElementById('basicInfoFields').innerHTML = generateFields(fields.basic);
    document.getElementById('contactFields').innerHTML = generateFields(fields.contact);
    document.getElementById('professionalFields').innerHTML = generateFields(fields.professional);
    document.getElementById('accountFields').innerHTML = generateFields(fields.account);
}

// Generate HTML for fields
function generateFields(fieldsList) {
    let html = '';
    
    fieldsList.forEach(field => {
        if (field.type === 'checkbox') {
            html += `
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="${field.name}" name="${field.name}" 
                           ${field.required ? 'required' : ''} onchange="saveFormData()">
                    <label class="form-check-label" for="${field.name}">
                        ${field.label}
                    </label>
                </div>
            `;
        } else if (field.type === 'file') {
            html += `
                <div class="form-group">
                    <label class="form-label">
                        ${field.label} ${field.required ? '<span class="required">*</span>' : '<span class="optional">(Optional)</span>'}
                    </label>
                    <div class="file-upload-wrapper">
                        <input type="file" class="file-upload-input" id="${field.name}" name="${field.name}" 
                               accept="${field.accept || '*'}" ${field.required ? 'required' : ''} 
                               onchange="handleFileSelect(this)">
                        <label for="${field.name}" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <div class="file-info">
                                <h6>Choose File</h6>
                                <p>PDF, JPG, JPEG or PNG (Max 5MB)</p>
                            </div>
                        </label>
                    </div>
                    <div class="file-selected" id="${field.name}_selected">
                        <div class="file-selected-info">
                            <i class="fas fa-file-alt"></i>
                            <span class="file-selected-name" id="${field.name}_filename"></span>
                        </div>
                        <button type="button" class="file-remove-btn" onclick="removeFile('${field.name}')">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                    <div class="error-message" id="${field.name}_error"></div>
                </div>
            `;
        } else if (field.type === 'select') {
            const optionsHtml = Array.isArray(field.options) 
                ? field.options.map(opt => {
                    const value = typeof opt === 'string' ? opt.toLowerCase() : opt;
                    const label = typeof opt === 'string' ? opt : opt;
                    return `<option value="${value}">${label}</option>`;
                }).join('')
                : '';
            
            html += `
                <div class="form-group">
                    <label class="form-label">
                        ${field.label} ${field.required ? '<span class="required">*</span>' : '<span class="optional">(Optional)</span>'}
                    </label>
                    <select class="form-select" id="${field.name}" name="${field.name}" 
                            ${field.required ? 'required' : ''} onchange="saveFormData()">
                        <option value="">Select ${field.label}</option>
                        ${optionsHtml}
                    </select>
                    <div class="error-message" id="${field.name}_error"></div>
                </div>
            `;
        } else if (field.type === 'multiselect') {
            html += `
                <div class="form-group">
                    <label class="form-label">
                        ${field.label} ${field.required ? '<span class="required">*</span>' : '<span class="optional">(Optional)</span>'}
                    </label>
                    <div class="multiselect-wrapper" id="${field.name}_wrapper">
                        <div class="multiselect-display" onclick="toggleMultiselect('${field.name}')">
                            <span class="multiselect-placeholder">Select ${field.label}</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="multiselect-dropdown" id="${field.name}_dropdown">
                            ${field.options.map(opt => `
                                <label class="multiselect-option">
                                    <input type="checkbox" value="${opt}" onchange="updateMultiselect('${field.name}')">
                                    <span>${opt}</span>
                                </label>
                            `).join('')}
                        </div>
                    </div>
                    <input type="hidden" id="${field.name}" name="${field.name}" ${field.required ? 'required' : ''}>
                    <div class="selected-items" id="${field.name}_selected_items"></div>
                    <div class="error-message" id="${field.name}_error"></div>
                </div>
            `;
        } else if (field.type === 'schedule') {
            html += `
                <div class="form-group">
                    <label class="form-label">
                        ${field.label} ${field.required ? '<span class="required">*</span>' : '<span class="optional">(Optional)</span>'}
                    </label>
                    
                    <!-- Schedule Type Selection -->
                    <div class="schedule-type-selection" id="${field.name}_type_selection">
                        <label class="schedule-type-option">
                            <input type="radio" name="${field.name}_type" value="24/7" 
                                   onchange="handleScheduleTypeChange('${field.name}', '24/7')">
                            <span><i class="fas fa-clock"></i> 24/7 (Open All Day)</span>
                        </label>
                        <label class="schedule-type-option">
                            <input type="radio" name="${field.name}_type" value="weekdays" 
                                   onchange="handleScheduleTypeChange('${field.name}', 'weekdays')">
                            <span><i class="fas fa-business-time"></i> Weekdays (Mon-Fri)</span>
                        </label>
                        <label class="schedule-type-option">
                            <input type="radio" name="${field.name}_type" value="weekend" 
                                   onchange="handleScheduleTypeChange('${field.name}', 'weekend')">
                            <span><i class="fas fa-calendar-weekend"></i> Weekend (Sat-Sun)</span>
                        </label>
                        <label class="schedule-type-option">
                            <input type="radio" name="${field.name}_type" value="custom" 
                                   onchange="handleScheduleTypeChange('${field.name}', 'custom')">
                            <span><i class="fas fa-calendar-alt"></i> Custom Schedule</span>
                        </label>
                    </div>
                    
                    <!-- Day Schedule Details (Hidden by default) -->
                    <div class="schedule-wrapper" id="${field.name}_schedule" style="display: none; margin-top: 1rem;">
                        ${allDays.map(day => `
                            <div class="day-schedule" data-day="${day.value}">
                                <div class="day-header">
                                    <span class="day-name">${day.label}</span>
                                    <div class="day-toggle">
                                        <input type="checkbox" id="${field.name}_${day.value}_open" 
                                               onchange="toggleDaySchedule('${field.name}', '${day.value}')">
                                        <label for="${field.name}_${day.value}_open">Open</label>
                                    </div>
                                </div>
                                <div class="time-inputs disabled" id="${field.name}_${day.value}_times">
                                    <div class="time-input-wrapper">
                                        <label>From:</label>
                                        <input type="time" id="${field.name}_${day.value}_from" value="08:00"
                                               onchange="updateSchedule('${field.name}')">
                                    </div>
                                    <div class="time-input-wrapper">
                                        <label>To:</label>
                                        <input type="time" id="${field.name}_${day.value}_to" value="17:00"
                                               onchange="updateSchedule('${field.name}')">
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    
                    <input type="hidden" id="${field.name}" name="${field.name}">
                    <div class="error-message" id="${field.name}_error"></div>
                </div>
            `;
        } else if (field.type === 'textarea') {
            html += `
                <div class="form-group">
                    <label class="form-label">
                        ${field.label} ${field.required ? '<span class="required">*</span>' : '<span class="optional">(Optional)</span>'}
                    </label>
                    <textarea class="form-control" id="${field.name}" name="${field.name}" 
                              rows="3" placeholder="${field.placeholder || ''}" 
                              ${field.maxlength ? `maxlength="${field.maxlength}"` : ''}
                              ${field.required ? 'required' : ''} oninput="saveFormData()"></textarea>
                    <div class="error-message" id="${field.name}_error"></div>
                </div>
            `;
        } else {
            html += `
                <div class="form-group">
                    <label class="form-label">
                        ${field.label} ${field.required ? '<span class="required">*</span>' : '<span class="optional">(Optional)</span>'}
                    </label>
                    <input type="${field.type}" class="form-control" id="${field.name}" name="${field.name}" 
                           placeholder="${field.placeholder || ''}" ${field.required ? 'required' : ''}
                           ${field.min !== undefined ? `min="${field.min}"` : ''}
                           ${field.max !== undefined ? `max="${field.max}"` : ''}
                           ${field.step ? `step="${field.step}"` : ''}
                           ${field.minlength ? `minlength="${field.minlength}"` : ''}
                           ${field.maxlength ? `maxlength="${field.maxlength}"` : ''}
                           oninput="saveFormData()">
                    <div class="error-message" id="${field.name}_error"></div>
                </div>
            `;
        }
    });
    
    return html;
}

// Handle schedule type change (24/7, Weekdays, Weekend, Custom)
function handleScheduleTypeChange(fieldName, scheduleType) {
    const scheduleWrapper = document.getElementById(`${fieldName}_schedule`);
    const allDaySchedules = scheduleWrapper.querySelectorAll('.day-schedule');
    
    // Reset all days first
    allDaySchedules.forEach(daySchedule => {
        const day = daySchedule.getAttribute('data-day');
        const checkbox = document.getElementById(`${fieldName}_${day}_open`);
        const timesDiv = document.getElementById(`${fieldName}_${day}_times`);
        
        checkbox.checked = false;
        timesDiv.classList.add('disabled');
        daySchedule.style.display = 'none';
    });
    
    if (scheduleType === '24/7') {
        // Hide all day schedules and set value to "24/7"
        scheduleWrapper.style.display = 'none';
        document.getElementById(fieldName).value = '24/7';
        
    } else if (scheduleType === 'weekdays') {
        // Show only weekdays (Monday - Friday)
        scheduleWrapper.style.display = 'block';
        weekdays.forEach(day => {
            const daySchedule = scheduleWrapper.querySelector(`[data-day="${day.value}"]`);
            const checkbox = document.getElementById(`${fieldName}_${day.value}_open`);
            const timesDiv = document.getElementById(`${fieldName}_${day.value}_times`);
            
            daySchedule.style.display = 'block';
            checkbox.checked = true;
            timesDiv.classList.remove('disabled');
            
            // Set default times (8:00 AM - 5:00 PM)
            document.getElementById(`${fieldName}_${day.value}_from`).value = '08:00';
            document.getElementById(`${fieldName}_${day.value}_to`).value = '17:00';
        });
        
        updateSchedule(fieldName);
        
    } else if (scheduleType === 'weekend') {
        // Show only weekend (Saturday - Sunday)
        scheduleWrapper.style.display = 'block';
        weekendDays.forEach(day => {
            const daySchedule = scheduleWrapper.querySelector(`[data-day="${day.value}"]`);
            const checkbox = document.getElementById(`${fieldName}_${day.value}_open`);
            const timesDiv = document.getElementById(`${fieldName}_${day.value}_times`);
            
            daySchedule.style.display = 'block';
            checkbox.checked = true;
            timesDiv.classList.remove('disabled');
            
            // Set default times (8:00 AM - 5:00 PM)
            document.getElementById(`${fieldName}_${day.value}_from`).value = '08:00';
            document.getElementById(`${fieldName}_${day.value}_to`).value = '17:00';
        });
        
        updateSchedule(fieldName);
        
    } else if (scheduleType === 'custom') {
        // Show all days and let user customize
        scheduleWrapper.style.display = 'block';
        allDaySchedules.forEach(daySchedule => {
            daySchedule.style.display = 'block';
        });
    }
    
    saveFormData();
}

// Toggle multiselect dropdown
function toggleMultiselect(fieldName) {
    const dropdown = document.getElementById(`${fieldName}_dropdown`);
    dropdown.classList.toggle('show');
    
    // Close other dropdowns
    document.querySelectorAll('.multiselect-dropdown').forEach(dd => {
        if (dd.id !== `${fieldName}_dropdown`) {
            dd.classList.remove('show');
        }
    });
}

// Close multiselect when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.multiselect-wrapper')) {
        document.querySelectorAll('.multiselect-dropdown').forEach(dd => {
            dd.classList.remove('show');
        });
    }
});

// Update multiselect values
function updateMultiselect(fieldName) {
    const dropdown = document.getElementById(`${fieldName}_dropdown`);
    const hiddenInput = document.getElementById(fieldName);
    const selectedItemsDiv = document.getElementById(`${fieldName}_selected_items`);
    const placeholder = document.querySelector(`#${fieldName}_wrapper .multiselect-placeholder`);
    
    const checkboxes = dropdown.querySelectorAll('input[type="checkbox"]:checked');
    const selectedValues = Array.from(checkboxes).map(cb => cb.value);
    
    // Update hidden input with comma-separated values
    hiddenInput.value = selectedValues.join(', ');
    
    // Update display
    if (selectedValues.length > 0) {
        placeholder.textContent = `${selectedValues.length} selected`;
        selectedItemsDiv.innerHTML = selectedValues.map(val => `
            <span class="selected-tag">
                ${val}
                <i class="fas fa-times" onclick="removeMultiselectItem('${fieldName}', '${val}')"></i>
            </span>
        `).join('');
    } else {
        placeholder.textContent = `Select ${fieldName.replace('_', ' ')}`;
        selectedItemsDiv.innerHTML = '';
    }
    
    clearFieldError(fieldName);
    saveFormData();
}

// Remove item from multiselect
function removeMultiselectItem(fieldName, value) {
    const dropdown = document.getElementById(`${fieldName}_dropdown`);
    const checkbox = Array.from(dropdown.querySelectorAll('input[type="checkbox"]'))
        .find(cb => cb.value === value);
    
    if (checkbox) {
        checkbox.checked = false;
        updateMultiselect(fieldName);
    }
}

// Toggle day schedule
function toggleDaySchedule(fieldName, day) {
    const checkbox = document.getElementById(`${fieldName}_${day}_open`);
    const timesDiv = document.getElementById(`${fieldName}_${day}_times`);
    
    if (checkbox.checked) {
        timesDiv.classList.remove('disabled');
    } else {
        timesDiv.classList.add('disabled');
    }
    
    updateSchedule(fieldName);
}

// Update schedule hidden field
function updateSchedule(fieldName) {
    const scheduleData = {};
    
    allDays.forEach(day => {
        const checkbox = document.getElementById(`${fieldName}_${day.value}_open`);
        if (checkbox && checkbox.checked) {
            const fromTime = document.getElementById(`${fieldName}_${day.value}_from`).value;
            const toTime = document.getElementById(`${fieldName}_${day.value}_to`).value;
            
            if (fromTime && toTime) {
                scheduleData[day.value] = `${fromTime}-${toTime}`;
            }
        }
    });
    
    // Convert to string format for database
    const scheduleString = Object.entries(scheduleData)
        .map(([day, time]) => `${day.charAt(0).toUpperCase() + day.slice(1)}: ${time}`)
        .join(', ');
    
    document.getElementById(fieldName).value = scheduleString;
    saveFormData();
}

// Save form data to localStorage
function saveFormData() {
    const formData = {};
    const form = document.getElementById('providerSignupForm');
    
    const elements = form.querySelectorAll('input:not([type="file"]):not([type="password"]), select, textarea');
    
    elements.forEach(element => {
        if (element.type === 'checkbox') {
            formData[element.id || element.name] = element.checked;
        } else if (element.type === 'radio') {
            if (element.checked) {
                formData[element.name] = element.value;
            }
        } else if (element.name && element.value) {
            formData[element.name] = element.value;
        }
    });
    
    localStorage.setItem(STORAGE_KEY, JSON.stringify(formData));
}

// Restore form data from localStorage
function restoreFormData() {
    const savedData = localStorage.getItem(STORAGE_KEY);
    const savedStep = localStorage.getItem(STORAGE_STEP_KEY);
    
    if (savedData) {
        try {
            const formData = JSON.parse(savedData);
            
            showAlert('📋 Previous form data restored! Continue where you left off.', 'info');
            
            Object.keys(formData).forEach(key => {
                const element = document.getElementById(key) || document.querySelector(`[name="${key}"]`);
                
                if (element) {
                    if (element.type === 'checkbox') {
                        element.checked = formData[key];
                        
                        // Trigger schedule toggle if it's a day checkbox
                        if (key.includes('_open')) {
                            const parts = key.split('_');
                            const fieldName = parts.slice(0, -2).join('_');
                            const day = parts[parts.length - 2];
                            toggleDaySchedule(fieldName, day);
                        }
                    } else if (element.type === 'radio') {
                        if (element.value === formData[key]) {
                            element.checked = true;
                            
                            // Trigger schedule type change
                            if (key.includes('_type')) {
                                const fieldName = key.replace('_type', '');
                                handleScheduleTypeChange(fieldName, formData[key]);
                            }
                        }
                    } else {
                        element.value = formData[key];
                        
                        // Update multiselect display if applicable
                        if (element.type === 'hidden' && document.getElementById(`${key}_dropdown`)) {
                            const values = formData[key].split(', ');
                            const dropdown = document.getElementById(`${key}_dropdown`);
                            values.forEach(val => {
                                const checkbox = Array.from(dropdown.querySelectorAll('input[type="checkbox"]'))
                                    .find(cb => cb.value === val.trim());
                                if (checkbox) checkbox.checked = true;
                            });
                            updateMultiselect(key);
                        }
                    }
                }
            });
            
            if (savedStep) {
                const step = parseInt(savedStep);
                if (step > 1 && step <= 4) {
                    setTimeout(() => {
                        navigateToStep(step);
                    }, 500);
                }
            }
        } catch (error) {
            console.error('Error restoring form data:', error);
        }
    }
}

// Navigate to specific step
function navigateToStep(targetStep) {
    for (let i = 1; i <= 4; i++) {
        document.getElementById(`section${i}`).classList.remove('active');
        document.getElementById(`stepItem${i}`).classList.remove('active');
        
        if (i < targetStep) {
            document.getElementById(`stepItem${i}`).classList.add('completed');
        } else {
            document.getElementById(`stepItem${i}`).classList.remove('completed');
        }
    }
    
    document.getElementById(`section${targetStep}`).classList.add('active');
    document.getElementById(`stepItem${targetStep}`).classList.add('active');
    
    currentStep = targetStep;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Setup auto-save on input change
function setupAutoSave() {
    const form = document.getElementById('providerSignupForm');
    form.addEventListener('input', saveFormData);
    form.addEventListener('change', saveFormData);
}

// Clear saved data after successful submission
function clearSavedData() {
    localStorage.removeItem(STORAGE_KEY);
    localStorage.removeItem(STORAGE_STEP_KEY);
}

// Handle file selection
function handleFileSelect(input) {
    const file = input.files[0];
    const selectedDiv = document.getElementById(`${input.id}_selected`);
    const filenameSpan = document.getElementById(`${input.id}_filename`);
    
    clearFieldError(input.id);
    
    if (file) {
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            showFieldError(input.id, 'File size must not exceed 5MB');
            input.value = '';
            return;
        }
        
        const allowedTypes = input.accept.split(',').map(t => t.trim());
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        
        if (!allowedTypes.includes(fileExtension)) {
            showFieldError(input.id, 'Invalid file type. Allowed: ' + input.accept);
            input.value = '';
            return;
        }
        
        filenameSpan.textContent = file.name;
        selectedDiv.classList.add('show');
    }
}

// Remove file
function removeFile(fieldName) {
    const input = document.getElementById(fieldName);
    const selectedDiv = document.getElementById(`${fieldName}_selected`);
    
    input.value = '';
    selectedDiv.classList.remove('show');
    clearFieldError(fieldName);
}

// Show field error
function showFieldError(fieldName, message) {
    const input = document.getElementById(fieldName);
    const errorDiv = document.getElementById(`${fieldName}_error`);
    
    if (input) {
        input.classList.add('error');
    }
    
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.classList.add('show');
    }
}

// Clear field error
function clearFieldError(fieldName) {
    const input = document.getElementById(fieldName);
    const errorDiv = document.getElementById(`${fieldName}_error`);
    
    if (input) {
        input.classList.remove('error');
    }
    
    if (errorDiv) {
        errorDiv.textContent = '';
        errorDiv.classList.remove('show');
    }
}

// Next step function
function nextStep(step) {
    if (!validateStep(step)) {
        return;
    }
    
    document.getElementById(`section${step}`).classList.remove('active');
    document.getElementById(`stepItem${step}`).classList.remove('active');
    document.getElementById(`stepItem${step}`).classList.add('completed');
    
    const nextStepNum = step + 1;
    document.getElementById(`section${nextStepNum}`).classList.add('active');
    document.getElementById(`stepItem${nextStepNum}`).classList.add('active');
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
    currentStep = nextStepNum;
    
    localStorage.setItem(STORAGE_STEP_KEY, currentStep.toString());
}

// Previous step function
function prevStep(step) {
    document.getElementById(`section${step}`).classList.remove('active');
    document.getElementById(`stepItem${step}`).classList.remove('active');
    
    const prevStepNum = step - 1;
    document.getElementById(`section${prevStepNum}`).classList.add('active');
    document.getElementById(`stepItem${prevStepNum}`).classList.add('active');
    document.getElementById(`stepItem${prevStepNum}`).classList.remove('completed');
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
    currentStep = prevStepNum;
    
    localStorage.setItem(STORAGE_STEP_KEY, currentStep.toString());
}

// Validate step
function validateStep(step) {
    const section = document.getElementById(`section${step}`);
    const inputs = section.querySelectorAll('input[required], select[required], textarea[required]');
    
    let isValid = true;
    let firstErrorField = null;
    
    for (let input of inputs) {
        if (input.disabled) continue;
        
        clearFieldError(input.id);
        
        // Skip file validation during step navigation
        if (input.type === 'file') {
            if (!input.files || input.files.length === 0) {
                showFieldError(input.id, 'Please upload the required document');
                if (!firstErrorField) firstErrorField = input;
                isValid = false;
            }
            continue;
        }
        
        if (!input.value || input.value.trim() === '') {
            showFieldError(input.id, 'This field is required');
            if (!firstErrorField) firstErrorField = input;
            isValid = false;
            continue;
        }
        
        // Email validation
        if (input.type === 'email' && !validateEmail(input.value)) {
            showFieldError(input.id, 'Please enter a valid email address');
            if (!firstErrorField) firstErrorField = input;
            isValid = false;
            continue;
        }
        
        // Phone validation
        if (input.name === 'phone' && !validatePhone(input.value)) {
            showFieldError(input.id, 'Please enter a valid phone number (+94XXXXXXXXX)');
            if (!firstErrorField) firstErrorField = input;
            isValid = false;
            continue;
        }
        
        // Password validation
        if (input.name === 'password') {
            if (input.value.length < 8) {
                showFieldError(input.id, 'Password must be at least 8 characters long');
                if (!firstErrorField) firstErrorField = input;
                isValid = false;
                continue;
            }
        }
        
        // Password confirmation validation
        if (input.name === 'password_confirmation') {
            const password = document.getElementById('password');
            if (password && input.value !== password.value) {
                showFieldError(input.id, 'Passwords do not match');
                if (!firstErrorField) firstErrorField = input;
                isValid = false;
                continue;
            }
        }
        
        // Number validation
        if (input.type === 'number') {
            const value = parseFloat(input.value);
            if (input.min && value < parseFloat(input.min)) {
                showFieldError(input.id, `Value must be at least ${input.min}`);
                if (!firstErrorField) firstErrorField = input;
                isValid = false;
                continue;
            }
            if (input.max && value > parseFloat(input.max)) {
                showFieldError(input.id, `Value must not exceed ${input.max}`);
                if (!firstErrorField) firstErrorField = input;
                isValid = false;
                continue;
            }
        }
    }
    
    if (!isValid) {
        showAlert('Please correct the errors in this section', 'danger');
        if (firstErrorField) {
            firstErrorField.focus();
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    
    return isValid;
}

// Email validation
function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

// Phone validation
function validatePhone(phone) {
    return /^(\+94|0)?[0-9]{9,10}$/.test(phone.replace(/\s/g, ''));
}

// Show alert
function showAlert(message, type) {
    const alertMessage = document.getElementById('alertMessage');
    const alertText = document.getElementById('alertText');
    
    alertText.textContent = message;
    alertMessage.className = `alert alert-${type}`;
    alertMessage.style.display = 'flex';
    
    alertMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    
    setTimeout(() => {
        alertMessage.style.display = 'none';
    }, 6000);
}

// Normalize URL input
function normalizeURL(url) {
    if (!url) return '';
    url = url.trim();
    if (!/^https?:\/\//i.test(url)) {
        url = 'https://' + url;
    }
    return url;
}

// Form submission
document.getElementById('providerSignupForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!validateStep(4)) {
        return;
    }
    
    const terms = document.getElementById('terms');
    if (!terms || !terms.checked) {
        showAlert('Please accept the Terms & Conditions to continue', 'danger');
        terms.focus();
        return;
    }
    
    const formData = new FormData(this);
    formData.set('provider_type', providerType);
    
    // Normalize website URL if exists
    const websiteInput = document.getElementById('website');
    if (websiteInput && websiteInput.value) {
        formData.set('website', normalizeURL(websiteInput.value));
    }
    
    // Ensure delivery_available is set properly for pharmacy
    if (providerType === 'pharmacy') {
        const deliveryCheckbox = document.getElementById('delivery_available');
        if (deliveryCheckbox) {
            formData.delete('delivery_available');
            if (deliveryCheckbox.checked) {
                formData.set('delivery_available', '1');
            } else {
                formData.set('delivery_available', '0');
            }
        }
    }
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Submitting...';
    
    try {
        const response = await fetch('/signup/provider', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (response.ok && data.ok) {
            clearSavedData();
            
            showAlert('✅ Registration successful! Redirecting to your dashboard...', 'success');
            
            if (data.welcome_message) {
                sessionStorage.setItem('login_welcome', data.welcome_message);
            }
            if (data.user_name) {
                sessionStorage.setItem('user_name', data.user_name);
            }
            if (data.user_email) {
                sessionStorage.setItem('user_email', data.user_email);
            }
            
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1500);
            
        } else {
            if (data.errors) {
                handleServerErrors(data.errors);
                const errorMessages = Object.values(data.errors).flat();
                showAlert('❌ ' + errorMessages[0], 'danger');
            } else {
                showAlert('❌ ' + (data.message || 'Registration failed. Please try again.'), 'danger');
            }
            
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
        
    } catch (error) {
        console.error('Registration Error:', error);
        showAlert('❌ Network error occurred. Please check your connection and try again.', 'danger');
        
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Handle server-side validation errors
function handleServerErrors(errors) {
    for (const [fieldName, messages] of Object.entries(errors)) {
        const message = Array.isArray(messages) ? messages[0] : messages;
        showFieldError(fieldName, message);
        
        const fieldElement = document.getElementById(fieldName);
        if (fieldElement) {
            const section = fieldElement.closest('.form-step');
            if (section) {
                const sectionId = section.id;
                const stepNum = parseInt(sectionId.replace('section', ''));
                
                if (stepNum !== currentStep) {
                    navigateToStep(stepNum);
                }
            }
            
            fieldElement.focus();
            fieldElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            break;
        }
    }
}

console.log(`✅ Provider Registration initialized for: ${providerType}`);
