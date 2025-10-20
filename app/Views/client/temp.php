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
                <input type="file" id="photoInput" accept="image
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
    grid-template-columns: 1fr 1fr;
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
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    font-family: inherit;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 80px;
}

.form-group input[readonly] {
    background-color: #f9fafb;
    color: #6b7280;
    cursor: not-allowed;
}

.form-group input.system-field {
    background-color: #f3f4f6;
    color: #9ca3af;
    border-color: #e5e7eb;
    font-family: 'Courier New', monospace;
    font-size: 14px;
    cursor: not-allowed;
}

.form-group small {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
    font-style: italic;
}

.password-section,
.account-status-section,
.system-info-section {
    margin-bottom: 30px;
}

.password-section h4,
.account-status-section h4,
.system-info-section h4 {
    color: #374151;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: 600;
}

.section-divider {
    border: none;
    border-top: 1px solid #e5e7eb;
    margin: 30px 0;
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
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.2s ease;
    font-size: 14px;
}

.btn-change-password:hover {
    opacity: 0.9;
}

.btn-change-password:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.form-actions {
    margin-top: 40px;
    display: flex;
    gap: 16px;
    justify-content: flex-end;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.2s ease;
}

.btn-primary:hover {
    opacity: 0.9;
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

@media (max-width: 768px) {
    .profile-container {
        grid-template-columns: 1fr;
        gap: 30px;
        padding: 20px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .photo-placeholder {
        width: 150px;
        height: 150px;
    }
    
    .form-tabs {
        flex-wrap: wrap;
    }
    
    .tab-btn {
        flex: 1;
        min-width: 120px;
    }
}

@media (max-width: 480px) {
    .profile-page {
        padding: 10px;
    }
    
    .page-header h1 {
        font-size: 24px;
    }
    
    .form-actions {
        flex-direction: column;
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

document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('.btn-primary');
    const originalText = submitBtn.textContent;
    
    submitBtn.textContent = 'Saving...';
    submitBtn.disabled = true;
    
    fetch('/client/update-profile', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (response.ok) {
            
            showMessage('Profile updated successfully!', 'success');
        } else {
            throw new Error('Failed to update profile');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Failed to update profile. Please try again.', 'error');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});

function resetForm() {
    if (confirm('Are you sure you want to reset all changes?')) {
        document.getElementById('profileForm').reset();
    }
}

function uploadPhoto(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        
        if (!file.type.startsWith('image/')) {
            showMessage('Please select a valid image file.', 'error');
            return;
        }
        
        
        if (file.size > 5 * 1024 * 1024) {
            showMessage('Image size must be less than 5MB.', 'error');
            return;
        }
        
        const formData = new FormData();
        formData.append('photo', file);
        
        const uploadBtn = document.querySelector('.btn-upload-photo');
        const originalText = uploadBtn.textContent;
        uploadBtn.textContent = 'Uploading...';
        uploadBtn.disabled = true;
        
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
                
                const photoPlaceholder = document.querySelector('.photo-placeholder');
                photoPlaceholder.innerHTML = `<img src="${data.url}" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
                
                
                uploadBtn.textContent = 'Change Photo';
                
                showMessage('Profile photo updated successfully!', 'success');
            } else {
                showMessage(data.error || 'Failed to upload photo. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Photo upload error:', error);
            showMessage('Failed to upload photo. Please try again.', 'error');
        })
        .finally(() => {
            uploadBtn.disabled = false;
            if (uploadBtn.textContent === 'Uploading...') {
                uploadBtn.textContent = originalText;
            }
        });
    }
}

function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const toggleButton = passwordField.nextElementSibling.querySelector('.toggle-icon');
    
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
        showMessage('Please fill in all password fields.', 'error');
        return;
    }
    
    if (newPassword.length < 6) {
        showMessage('New password must be at least 6 characters long.', 'error');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        showMessage('New passwords do not match.', 'error');
        return;
    }
    
    if (currentPassword === newPassword) {
        showMessage('New password must be different from current password.', 'error');
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
            showMessage('Password changed successfully!', 'success');
            
            document.getElementById('current_password').value = '';
            document.getElementById('new_password').value = '';
            document.getElementById('confirm_new_password').value = '';
        } else {
            showMessage(data.error || 'Failed to change password. Please try again.', 'error');
        }
    })
    .catch(error => {
        console.error('Password change error:', error);
        showMessage('Failed to change password. Please try again.', 'error');
    })
    .finally(() => {
        changeBtn.textContent = originalText;
        changeBtn.disabled = false;
    });
}

function showMessage(message, type) {
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;
    messageDiv.textContent = message;
    messageDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 500;
        z-index: 1000;
        transition: opacity 0.3s ease;
        ${type === 'success' ? 'background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;' : 'background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;'}
    `;
    
    document.body.appendChild(messageDiv);
    
    
    setTimeout(() => {
        messageDiv.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(messageDiv);
        }, 300);
    }, 3000);
}
</script>

<?php
$content = ob_get_clean();
$title = 'My Profile - Client Dashboard';
require_once __DIR__ . '/../layouts/main.php';
?>