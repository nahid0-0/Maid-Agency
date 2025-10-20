<?php
ob_start();
?>

<div class="profile-page">
    <a href="/maid" class="back-link">← Back to Dashboard</a>
    
    <div class="page-header">
        <h1>👤 My Profile</h1>
        <p>Manage your personal information and preferences</p>
    </div>
    
    <div class="profile-container">
        
        <div class="profile-photo-section">
            <div class="photo-container">
                <div class="photo-placeholder">
                    <?php if (!empty($helper['profile_picture_url'])): ?>
                        <img src="<?= htmlspecialchars($helper['profile_picture_url']) ?>" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    <?php else: ?>
                        <span class="photo-icon">📷</span>
                        <p>Profile Photo</p>
                    <?php endif; ?>
                </div>
                <button class="btn-upload-photo" id="uploadPhotoBtn">
                    <?= !empty($helper['profile_picture_url']) ? 'Change Photo' : 'Upload Photo' ?>
                </button>
                <input type="file" id="photoInput" accept="image/jpeg,image/jpg,image/png,image/gif" style="display: none;">
            </div>
        </div>

        <!-- Profile Form Section -->
        <div class="profile-form-section">
            <!-- Tab Navigation -->
            <div class="form-tabs">
                <button class="tab-btn active" data-tab="personal">Personal Information</button>
                <button class="tab-btn" data-tab="professional">Professional Details</button>
                <button class="tab-btn" data-tab="preferences">Preferences</button>
            </div>

            <!-- Personal Information Tab -->
            <div id="personal-tab" class="tab-content active">
                <h3>Personal Information</h3>

                <div class="id-highlight-section">
                    <div class="id-badge">
                        <span class="id-icon">🆔</span>
                        <div class="id-content">
                            <label>Helper ID</label>
                            <div class="id-value"><?= htmlspecialchars($helper['id']) ?></div>
                        </div>
                    </div>
                </div>

                <form id="profileForm" method="POST">
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($helper['name']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($helper['email']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="tel" id="contact_number" name="contact_number" value="<?= htmlspecialchars($helper['contact_number'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="nationality">Nationality *</label>
                            <input type="text" id="nationality" name="nationality" value="<?= htmlspecialchars($helper['nationality']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth *</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="<?= htmlspecialchars($helper['date_of_birth']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" id="age" name="age" value="<?= htmlspecialchars($helper['age'] ?? '') ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender *</label>
                            <select id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Female" <?= ($helper['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                                <option value="Male" <?= ($helper['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Other" <?= ($helper['gender'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label for="emergency_contact">Emergency Contact</label>
                            <input type="text" id="emergency_contact" name="emergency_contact" value="<?= htmlspecialchars($helper['emergency_contact'] ?? '') ?>">
                            <small>Name and phone number of emergency contact person</small>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="window.location.href='/maid'">Cancel</button>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>

            <!-- Professional Details Tab -->
            <div id="professional-tab" class="tab-content">
                <h3>Professional Details</h3>

                <form id="professionalForm" method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="experience_level">Experience Level *</label>
                            <select id="experience_level" name="experience_level" required>
                                <option value="">Select Level</option>
                                <option value="Entry Level" <?= ($helper['experience_level'] ?? '') === 'Entry Level' ? 'selected' : '' ?>>Entry Level (0-2 years)</option>
                                <option value="Intermediate" <?= ($helper['experience_level'] ?? '') === 'Intermediate' ? 'selected' : '' ?>>Intermediate (3-5 years)</option>
                                <option value="Experienced" <?= ($helper['experience_level'] ?? '') === 'Experienced' ? 'selected' : '' ?>>Experienced (6-10 years)</option>
                                <option value="Expert" <?= ($helper['experience_level'] ?? '') === 'Expert' ? 'selected' : '' ?>>Expert (10+ years)</option>
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label for="previous_experience">Previous Experience</label>
                            <textarea id="previous_experience" name="previous_experience" rows="4"><?= htmlspecialchars($helper['previous_experience'] ?? '') ?></textarea>
                            <small>Describe your work history and previous positions</small>
                        </div>

                        <div class="form-group full-width">
                            <label for="special_skills">Special Skills</label>
                            <textarea id="special_skills" name="special_skills" rows="3"><?= htmlspecialchars($helper['special_skills'] ?? '') ?></textarea>
                            <small>List any special skills (e.g., cooking, childcare, elderly care, languages)</small>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="window.location.href='/maid'">Cancel</button>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>

            <!-- Preferences Tab -->
            <div id="preferences-tab" class="tab-content">
                <h3>Preferences & Requirements</h3>

                <form id="preferencesForm" method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="salary_min">Minimum Expected Salary (£)</label>
                            <input type="number" id="salary_min" name="salary_min" value="<?= htmlspecialchars($helper['salary_min'] ?? '') ?>" min="0" step="100">
                        </div>

                        <div class="form-group">
                            <label for="salary_max">Maximum Expected Salary (£)</label>
                            <input type="number" id="salary_max" name="salary_max" value="<?= htmlspecialchars($helper['salary_max'] ?? '') ?>" min="0" step="100">
                        </div>

                        <div class="form-group full-width">
                            <label for="dietary_restrictions">Dietary Restrictions</label>
                            <textarea id="dietary_restrictions" name="dietary_restrictions" rows="3"><?= htmlspecialchars($helper['dietary_restrictions'] ?? '') ?></textarea>
                            <small>Any dietary requirements or restrictions</small>
                        </div>

                        <div class="form-group full-width">
                            <label for="medical_conditions">Medical Conditions</label>
                            <textarea id="medical_conditions" name="medical_conditions" rows="3"><?= htmlspecialchars($helper['medical_conditions'] ?? '') ?></textarea>
                            <small>Any medical conditions or allergies that employers should be aware of</small>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="window.location.href='/maid'">Cancel</button>
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
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn-upload-photo:hover {
    background: #5a6fd8;
}

.profile-form-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.form-tabs {
    display: flex;
    border-bottom: 1px solid #e5e7eb;
}

.tab-btn {
    flex: 1;
    padding: 16px 20px;
    border: none;
    background: #f9fafb;
    color: #6b7280;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.tab-btn:hover {
    background: #f3f4f6;
    color: #374151;
}

.tab-btn.active {
    background: white;
    color: #667eea;
    border-bottom: 2px solid #667eea;
}

.tab-content {
    display: none;
    padding: 30px;
}

.tab-content.active {
    display: block;
}

.tab-content h3 {
    margin: 0 0 24px 0;
    font-size: 20px;
    color: #1f2937;
    font-weight: 600;
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
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 30px;
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
    font-size: 14px;
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
        flex-direction: column;
    }
    
    .tab-content {
        padding: 20px;
    }
    
    .form-actions {
        flex-direction: column;
        padding: 20px;
    }
    
    .btn-primary,
    .btn-secondary {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .profile-page {
        padding: 10px;
    }
    
    .photo-placeholder {
        width: 150px;
        height: 150px;
    }
    
    .photo-icon {
        font-size: 36px;
    }
}
</style>

<script<?= defined('CSP_NONCE') ? ' nonce="' . CSP_NONCE . '"' : '' ?>>

function showTab(tabName, clickedButton) {
    
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => content.classList.remove('active'));

    
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(btn => btn.classList.remove('active'));

    
    document.getElementById(tabName + '-tab').classList.add('active');

    
    if (clickedButton) {
        clickedButton.classList.add('active');
    }
}

document.getElementById('date_of_birth').addEventListener('change', function() {
    const dob = new Date(this.value);
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const monthDiff = today.getMonth() - dob.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
        age--;
    }
    
    document.getElementById('age').value = age >= 0 ? age : '';
});

document.addEventListener('DOMContentLoaded', function() {
    
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            showTab(tabName, this);
        });
    });

    
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
});

// Handle all three forms with a generic handler
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

        fetch('/maid/update-profile', {
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

// Initialize all forms
handleFormSubmit('profileForm');
handleFormSubmit('professionalForm');
handleFormSubmit('preferencesForm');

function uploadPhoto(input) {
    if (!input.files || input.files.length === 0) return;
    
    const file = input.files[0];
    const formData = new FormData();
    formData.append('photo', file);
    
    
    const btn = document.querySelector('.btn-upload-photo');
    const originalText = btn.textContent;
    btn.textContent = 'Uploading...';
    btn.disabled = true;
    
    fetch('/maid/upload-photo', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            
            if (data.photo_url) {
                const placeholder = document.querySelector('.photo-placeholder');
                placeholder.innerHTML = `<img src="${data.photo_url}" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
            }
            showMessage('Success', 'Profile photo updated successfully!', 'success');
        } else {
            showMessage('Error', data.error || 'Failed to upload photo. Please try again.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error', 'Network error occurred. Please try again.', 'error');
    })
    .finally(() => {
        
        btn.textContent = originalText;
        btn.disabled = false;
        
        input.value = '';
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
</script>

<?php
$content = ob_get_clean();
$title = 'My Profile';
include APP_ROOT . '/app/Views/layouts/main.php';
?>

