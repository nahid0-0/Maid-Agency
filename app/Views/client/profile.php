<?php
ob_start();
?>

<div class="profile-page">
    <a href="/client" class="back-link">← Back to Dashboard</a>
    
    <div class="page-header">
        <h1>👤 My Profile</h1>
        <p>Manage your personal information and preferences</p>
    </div>
    
    <div class="profile-container">
        
        <div class="profile-photo-section">
            <div class="photo-container">
                <div class="photo-placeholder">
                    <?php if (!empty($client['profile_picture_url'])): ?>
                        <img src="<?= htmlspecialchars($client['profile_picture_url']) ?>" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    <?php else: ?>
                        <span class="photo-icon">📷</span>
                        <p>Profile Photo</p>
                    <?php endif; ?>
                </div>
                <button class="btn-upload-photo" id="uploadPhotoBtn">
                    <?= !empty($client['profile_picture_url']) ? 'Change Photo' : 'Upload Photo' ?>
                </button>
                <input type="file" id="photoInput" accept="image/jpeg,image/jpg,image/png,image/gif" style="display: none;">
            </div>
        </div>

        <!-- Profile Form Section -->
        <div class="profile-form-section">
            <!-- Tab Navigation -->
            <div class="form-tabs">
                <button class="tab-btn active" data-tab="personal">Personal Information</button>
                <button class="tab-btn" data-tab="security">Security Settings</button>
            </div>

            <!-- Personal Information Tab -->
            <div id="personal-tab" class="tab-content active">
                <h3>Personal Information</h3>

                <div class="id-highlight-section">
                    <div class="id-badge">
                        <span class="id-icon">🆔</span>
                        <div class="id-content">
                            <label>Client ID</label>
                            <div class="id-value"><?= htmlspecialchars($client['id']) ?></div>
                        </div>
                    </div>
                </div>

                <form id="profileForm" method="POST">
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($client['name']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="tel" id="contact_number" name="contact_number" value="<?= htmlspecialchars($client['contact_number'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea id="address" name="address" rows="3"><?= htmlspecialchars($client['address'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="<?= htmlspecialchars($client['date_of_birth'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="preferred_language">Preferred Language</label>
                            <select id="preferred_language" name="preferred_language">
                                <option value="english" <?= ($client['preferred_language'] ?? '') === 'english' ? 'selected' : '' ?>>English</option>
                                <option value="arabic" <?= ($client['preferred_language'] ?? '') === 'arabic' ? 'selected' : '' ?>>Arabic</option>
                                <option value="filipino" <?= ($client['preferred_language'] ?? '') === 'filipino' ? 'selected' : '' ?>>Filipino</option>
                                <option value="hindi" <?= ($client['preferred_language'] ?? '') === 'hindi' ? 'selected' : '' ?>>Hindi</option>
                            </select>
                        </div>

                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="window.location.href='/client'">Cancel</button>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>

            <!-- Security Settings Tab -->
            <div id="security-tab" class="tab-content">
                <h3>Security Settings</h3>
                
                <form id="passwordForm" method="POST">
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="current_password">Current Password *</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="current_password" name="current_password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('current_password')">👁️</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="new_password">New Password *</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="new_password" name="new_password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('new_password')">👁️</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm_new_password">Confirm New Password *</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="confirm_new_password" name="confirm_new_password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('confirm_new_password')">👁️</button>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <button type="button" class="btn-change-password" onclick="changePassword()">🔐 Change Password</button>
                        </div>
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

.password-input-wrapper {
    position: relative;
}

.password-input-wrapper input {
    padding-right: 45px;
}

.password-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    font-size: 16px;
    color: #6b7280;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.password-toggle:hover {
    color: #374151;
}

.btn-change-password {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
}

.btn-change-password:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
}

.btn-change-password:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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
    
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });

    
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    
    document.getElementById(tabName + '-tab').classList.add('active');

    
    if (clickedButton) {
        clickedButton.classList.add('active');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    
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

    
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            showTab(tabName, this);
        });
    });
});

// Handle all forms with a generic handler
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

        fetch('/client/update-profile', {
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

// Initialize form handler
handleFormSubmit('profileForm');

function resetForm() {
    if (confirm('Are you sure you want to reset all changes?')) {
        document.getElementById('profileForm').reset();
    }
}

function uploadPhoto(input) {
    if (!input.files || input.files.length === 0) return;
    
    const file = input.files[0];
    const formData = new FormData();
    formData.append('photo', file);
    
    const btn = document.querySelector('.btn-upload-photo');
    const originalText = btn.textContent;
    btn.textContent = 'Uploading...';
    btn.disabled = true;
    
    fetch('/client/upload-photo', {
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

function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const toggleButton = passwordField.parentNode.querySelector('.password-toggle');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleButton.textContent = '🙈';
    } else {
        passwordField.type = 'password';
        toggleButton.textContent = '👁️';
    }
}

function changePassword() {
    const currentPassword = document.getElementById('current_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_new_password').value;
    
    
    if (!currentPassword || !newPassword || !confirmPassword) {
        showMessage('Error', 'Please fill in all password fields.', 'error');
        return;
    }
    
    if (newPassword.length < 6) {
        showMessage('Error', 'New password must be at least 6 characters long.', 'error');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        showMessage('Error', 'New passwords do not match.', 'error');
        return;
    }
    
    if (currentPassword === newPassword) {
        showMessage('Error', 'New password must be different from current password.', 'error');
        return;
    }
    
    const changeBtn = document.querySelector('.btn-change-password');
    const originalText = changeBtn.textContent;
    changeBtn.textContent = 'Changing...';
    changeBtn.disabled = true;
    
    
    const formData = new FormData();
    formData.append('current_password', currentPassword);
    formData.append('new_password', newPassword);
    formData.append('confirm_password', confirmPassword);
    
    fetch('/client/change-password', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Success', 'Password changed successfully!', 'success');
            
            document.getElementById('current_password').value = '';
            document.getElementById('new_password').value = '';
            document.getElementById('confirm_new_password').value = '';
        } else {
            showMessage('Error', data.error || 'Failed to change password. Please try again.', 'error');
        }
    })
    .catch(error => {
        console.error('Password change error:', error);
        showMessage('Error', 'Failed to change password. Please try again.', 'error');
    })
    .finally(() => {
        changeBtn.textContent = originalText;
        changeBtn.disabled = false;
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
$title = 'My Profile - Client Dashboard';
require_once __DIR__ . '/../layouts/main.php';
?>