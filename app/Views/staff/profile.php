<?php
ob_start();
?>

<div class="profile-page">
    <a href="/staff" class="back-link">← Back to Dashboard</a>
    
    <div class="page-header">
        <h1>👤 My Profile</h1>
        <p>Manage your personal information and work details</p>
    </div>
    
    <div class="profile-container">
        
        <div class="profile-photo-section">
            <div class="photo-container">
                <div class="photo-placeholder">
                    <?php if (!empty($staff['profile_picture_url'])): ?>
                        <img src="<?= htmlspecialchars($staff['profile_picture_url']) ?>" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    <?php else: ?>
                        <span class="photo-icon">📷</span>
                        <p>Profile Photo</p>
                    <?php endif; ?>
                </div>
                <button type="button" class="btn-upload-photo" id="uploadPhotoBtn">
                    <?= !empty($staff['profile_picture_url']) ? 'Change Photo' : 'Upload Photo' ?>
                </button>
                <input type="file" id="photoInput" accept="image/jpeg,image/jpg,image/png,image/gif" style="display: none;">
            </div>
        </div>

        <!-- Profile Form Section -->
        <div class="profile-form-section">
            <!-- Tab Navigation -->
            <div class="form-tabs">
                <button class="tab-btn active" data-tab="personal" onclick="showTab('personal')">Personal Information</button>
                <button class="tab-btn" data-tab="work" onclick="showTab('work')">Work Details</button>
            </div>

            <!-- Personal Information Tab -->
            <div id="personal-tab" class="tab-content active">
                <h3>Personal Information</h3>

                <div class="id-highlight-section">
                    <div class="id-badge">
                        <span class="id-icon">🆔</span>
                        <div class="id-content">
                            <label>Staff ID</label>
                            <div class="id-value"><?= htmlspecialchars($staff['id']) ?></div>
                        </div>
                    </div>
                </div>

                <form id="profileForm" method="POST">
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($staff['name']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($staff['email']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="tel" id="contact_number" name="contact_number" value="<?= htmlspecialchars($staff['contact_number'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth *</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="<?= htmlspecialchars($staff['date_of_birth']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" id="age" name="age" value="<?= htmlspecialchars($staff['age'] ?? '') ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender *</label>
                            <select id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Female" <?= ($staff['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                                <option value="Male" <?= ($staff['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Other" <?= ($staff['gender'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label for="address">Address</label>
                            <textarea id="address" name="address" rows="3"><?= htmlspecialchars($staff['address'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="window.location.href='/staff'">Cancel</button>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>

            <!-- Work Details Tab -->
            <div id="work-tab" class="tab-content">
                <h3>Work Details</h3>

                <form id="workForm" method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="department">Department *</label>
                            <select id="department" name="department" required>
                                <option value="">Select Department</option>
                                <option value="Administration" <?= ($staff['department'] ?? '') === 'Administration' ? 'selected' : '' ?>>Administration</option>
                                <option value="Operations" <?= ($staff['department'] ?? '') === 'Operations' ? 'selected' : '' ?>>Operations</option>
                                <option value="Customer Service" <?= ($staff['department'] ?? '') === 'Customer Service' ? 'selected' : '' ?>>Customer Service</option>
                                <option value="HR" <?= ($staff['department'] ?? '') === 'HR' ? 'selected' : '' ?>>Human Resources</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="position">Position *</label>
                            <input type="text" id="position" name="position" value="<?= htmlspecialchars($staff['position'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="hire_date">Hire Date</label>
                            <input type="date" id="hire_date" name="hire_date" value="<?= htmlspecialchars($staff['hire_date'] ?? '') ?>">
                        </div>

                        <div class="form-group full-width">
                            <label for="work_notes">Work Notes</label>
                            <textarea id="work_notes" name="work_notes" rows="4"><?= htmlspecialchars($staff['work_notes'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="window.location.href='/staff'">Cancel</button>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.profile-page {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.back-link {
    display: inline-flex;
    align-items: center;
    color: #667eea;
    text-decoration: none;
    font-size: 14px;
    margin-bottom: 20px;
    transition: color 0.2s ease;
}

.back-link:hover {
    color: #5a6fd8;
}

.page-header {
    text-align: center;
    margin-bottom: 40px;
}

.page-header h1 {
    font-size: 36px;
    color: #1f2937;
    margin: 0 0 10px 0;
    font-weight: 700;
}

.page-header p {
    color: #6b7280;
    font-size: 16px;
    margin: 0;
}

.profile-container {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 30px;
    margin-bottom: 40px;
}

.profile-photo-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    height: fit-content;
}

.photo-container {
    text-align: center;
}

.photo-placeholder {
    width: 200px;
    height: 200px;
    border: 2px dashed #d1d5db;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    background: #f9fafb;
}

.photo-icon {
    font-size: 48px;
    color: #9ca3af;
    margin-bottom: 10px;
}

.photo-placeholder p {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
}

.btn-upload-photo {
    background: #667eea;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.2s ease;
    width: 100%;
}

.btn-upload-photo:hover {
    background: #5a6fd8;
}

.profile-form-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.form-tabs {
    display: flex;
    border-bottom: 2px solid #f1f5f9;
    margin-bottom: 30px;
}

.tab-btn {
    background: none;
    border: none;
    padding: 15px 25px;
    cursor: pointer;
    font-size: 16px;
    color: #64748b;
    border-bottom: 2px solid transparent;
    transition: all 0.2s ease;
}

.tab-btn.active {
    color: #667eea;
    border-bottom-color: #667eea;
}

.tab-btn:hover {
    color: #667eea;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.tab-content h3 {
    color: #1f2937;
    margin-bottom: 25px;
    font-size: 20px;
}

.id-highlight-section {
    margin-bottom: 30px;
}

.id-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: 20px 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.id-icon {
    font-size: 36px;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
}

.id-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.id-content label {
    color: rgba(255, 255, 255, 0.9);
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 0;
}

.id-value {
    color: #ffffff;
    font-size: 24px;
    font-weight: 700;
    font-family: 'Courier New', monospace;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 12px 16px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group input[readonly] {
    background: #f9fafb;
    color: #6b7280;
}

.form-group small {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 4px;
}

.form-group textarea {
    resize: vertical;
    min-height: 80px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 20px 30px;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
    margin-bottom: 30px;
}

.btn-primary,
.btn-secondary {
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a6fd8;
}

.btn-secondary {
    background: #f3f4f6;
    color: #6b7280;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover {
    background: #e5e7eb;
    color: #374151;
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 16px 24px;
    border-radius: 8px;
    font-weight: 500;
    z-index: 10000;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    animation: slideIn 0.3s ease-out;
    max-width: 400px;
}

.toast-notification.success {
    background: #d1fae5;
    color: #065f46;
    border-left: 4px solid #10b981;
}

.toast-notification.error {
    background: #fee2e2;
    color: #991b1b;
    border-left: 4px solid #ef4444;
}

@keyframes slideIn {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(400px);
        opacity: 0;
    }
}

@media (max-width: 1024px) {
    .profile-container {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .profile-photo-section {
        order: 2;
    }
    
    .profile-form-section {
        order: 1;
    }
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-tabs {
        flex-wrap: wrap;
    }
    
    .tab-btn {
        flex: 1;
        min-width: 120px;
    }
    
    .profile-page {
        padding: 10px;
    }
}
</style>

<script<?= defined('CSP_NONCE') ? ' nonce="' . CSP_NONCE . '"' : '' ?>>

document.addEventListener('DOMContentLoaded', function() {
    // Photo upload functionality
    const uploadBtn = document.getElementById('uploadPhotoBtn');
    const photoInput = document.getElementById('photoInput');

    if (uploadBtn && photoInput) {
        uploadBtn.addEventListener('click', function() {
            photoInput.click();
        });

        photoInput.addEventListener('change', function() {
            uploadPhoto(this);
        });
    }

    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            showTab(tabName, this);
        });
    });
});

function showTab(tabName, clickedButton) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => content.classList.remove('active'));
    
    // Remove active class from all tab buttons
    const tabBtns = document.querySelectorAll('.tab-btn');
    tabBtns.forEach(btn => btn.classList.remove('active'));
    
    // Show the selected tab content
    document.getElementById(tabName + '-tab').classList.add('active');
    
    // Add active class to clicked button
    if (clickedButton) {
        clickedButton.classList.add('active');
    }
}

// Age calculation
const dobField = document.getElementById('date_of_birth');
const ageField = document.getElementById('age');
if (dobField && ageField) {
    dobField.addEventListener('change', function() {
        const dob = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const monthDiff = today.getMonth() - dob.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
            age--;
        }
        
        ageField.value = age >= 0 ? age : '';
    });
}

function showMessage(title, message, type) {
    
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `<strong>${title}</strong><br>${message}`;

    
    document.body.appendChild(toast);

    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => {
            if (toast.parentNode) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

// Handle form submissions
function handleFormSubmit(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        submitBtn.textContent = 'Updating...';
        submitBtn.disabled = true;
        
        fetch('/staff/update-profile', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showMessage('Success', 'Your profile has been updated successfully!', 'success');
            } else {
                showMessage('Error', data.error || 'Failed to update profile. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Profile update error:', error);
            showMessage('Error', 'Network error occurred. Please try again.', 'error');
        })
        .finally(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
}

// Initialize form handlers
handleFormSubmit('profileForm');
handleFormSubmit('workForm');

function uploadPhoto(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        
        if (!file.type.startsWith('image/')) {
            showMessage('Error', 'Please select a valid image file.', 'error');
            return;
        }
        
        
        if (file.size > 5 * 1024 * 1024) {
            showMessage('Error', 'Image size must be less than 5MB.', 'error');
            return;
        }
        
        const formData = new FormData();
        formData.append('photo', file);
        
        const uploadBtn = document.querySelector('.btn-upload-photo');
        const originalText = uploadBtn.textContent;
        uploadBtn.textContent = 'Uploading...';
        uploadBtn.disabled = true;
        
        fetch('/staff/upload-photo', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                
                const photoPlaceholder = document.querySelector('.photo-placeholder');
                photoPlaceholder.innerHTML = `<img src="${data.photo_url}" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
                
                
                uploadBtn.textContent = 'Change Photo';
                
                showMessage('Success', 'Profile photo updated successfully!', 'success');
            } else {
                showMessage('Error', data.error || 'Failed to upload photo. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Photo upload error:', error);
            showMessage('Error', 'Network error occurred. Please try again.', 'error');
        })
        .finally(() => {
            uploadBtn.disabled = false;
            if (uploadBtn.textContent === 'Uploading...') {
                uploadBtn.textContent = originalText;
            }
        });
    }
}
</script>

<?php
$content = ob_get_clean();
$title = 'My Profile';
include APP_ROOT . '/app/Views/layouts/main.php';
?>
