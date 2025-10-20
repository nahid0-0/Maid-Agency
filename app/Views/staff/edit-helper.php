<?php

?>

<div class="edit-helper-page">
    <div class="page-header">
        <div class="header-content">
            <a href="/staff/helper-management" class="back-link">← Back to Helper Management</a>
            <h1>✏️ Edit Helper - <?= htmlspecialchars($helper['name']) ?></h1>
            <p>Update helper information and status</p>
        </div>
    </div>

    <div class="form-container">
        <form id="editHelperForm" class="helper-form">
            <input type="hidden" id="helperId" value="<?= $helper['id'] ?>">
            
            <div class="form-sections">
                
                <div class="form-section">
                    <h3>👤 Personal Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($helper['name']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($helper['email'] ?? '') ?>">
                            <small class="form-help">If provided, helper can login to manage their profile</small>
                        </div>

                        <div class="form-group">
                            <label for="nationality">Nationality *</label>
                            <select id="nationality" name="nationality" required>
                                <option value="">Select Nationality</option>
                                <?php foreach ($nationalities as $nationality): ?>
                                    <option value="<?= htmlspecialchars($nationality) ?>" <?= $helper['nationality'] === $nationality ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($nationality) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth *</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="<?= htmlspecialchars($helper['date_of_birth']) ?>" required>
                            <small class="form-help">Age must be between 25-55 years</small>
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender *</label>
                            <select id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Female" <?= $helper['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                                <option value="Male" <?= $helper['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="tel" id="contact_number" name="contact_number" value="<?= htmlspecialchars($helper['contact_number'] ?? '') ?>" placeholder="+65 9123 4567">
                        </div>
                    </div>
                </div>

                
                <div class="form-section">
                    <h3>🚨 Emergency Contact</h3>
                    <div class="form-group">
                        <label for="emergency_contact">Emergency Contact Details</label>
                        <textarea id="emergency_contact" name="emergency_contact" rows="2" 
                                  placeholder="Name, relationship, and phone number"><?= htmlspecialchars($helper['emergency_contact'] ?? '') ?></textarea>
                    </div>
                </div>

                
                <div class="form-section">
                    <h3>💼 Experience & Skills</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="experience_level">Experience Level *</label>
                            <select id="experience_level" name="experience_level" required>
                                <option value="">Select Experience Level</option>
                                <?php foreach ($experience_levels as $level): ?>
                                    <option value="<?= htmlspecialchars($level) ?>" <?= $helper['experience_level'] === $level ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($level) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" required>
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?= htmlspecialchars($status) ?>" <?= $helper['status'] === $status ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($status) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="previous_experience">Previous Experience</label>
                        <textarea id="previous_experience" name="previous_experience" rows="3" 
                                  placeholder="Describe previous work experience, countries worked in, etc."><?= htmlspecialchars($helper['previous_experience'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="special_skills">Special Skills</label>
                        <textarea id="special_skills" name="special_skills" rows="2" 
                                  placeholder="e.g., Elderly Care, Child Care, Pet Care, Cooking, etc."><?= htmlspecialchars($helper['special_skills'] ?? '') ?></textarea>
                    </div>
                </div>

                
                <div class="form-section">
                    <h3>💰 Salary & Preferences</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="salary_min">Minimum Salary (SGD)</label>
                            <input type="number" id="salary_min" name="salary_min" min="0" step="50" 
                                   value="<?= $helper['salary_min'] ? number_format($helper['salary_min'], 0, '', '') : '' ?>" placeholder="600">
                        </div>

                        <div class="form-group">
                            <label for="salary_max">Maximum Salary (SGD)</label>
                            <input type="number" id="salary_max" name="salary_max" min="0" step="50" 
                                   value="<?= $helper['salary_max'] ? number_format($helper['salary_max'], 0, '', '') : '' ?>" placeholder="1200">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="dietary_restrictions">Dietary Restrictions</label>
                        <textarea id="dietary_restrictions" name="dietary_restrictions" rows="2" 
                                  placeholder="Any dietary restrictions or preferences"><?= htmlspecialchars($helper['dietary_restrictions'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="medical_conditions">Medical Conditions</label>
                        <textarea id="medical_conditions" name="medical_conditions" rows="2" 
                                  placeholder="Any medical conditions or medications (confidential)"><?= htmlspecialchars($helper['medical_conditions'] ?? '') ?></textarea>
                    </div>
                </div>

                
                <div class="form-section">
                    <h3>🔐 Change Password (Optional)</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="new_password" name="new_password" minlength="6">
                                <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                    <span class="toggle-icon">👁️</span>
                                </button>
                            </div>
                            <small class="form-help">Leave blank to keep current password</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_new_password">Confirm New Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="confirm_new_password" name="confirm_new_password">
                                <button type="button" class="password-toggle" onclick="togglePassword('confirm_new_password')">
                                    <span class="toggle-icon">👁️</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="form-section">
                    <h3>⚙️ Account Settings</h3>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="is_active" name="is_active" <?= $helper['is_active'] ? 'checked' : '' ?>>
                            <span class="checkmark"></span>
                            Account is active
                        </label>
                        <small class="form-help">Inactive accounts cannot login to the system</small>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="window.location.href='/staff/helper-management'">
                    Cancel
                </button>
                <button type="submit" class="btn-primary">
                    Update Helper
                </button>
            </div>
        </form>
    </div>
</div>

<style>

* {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}

.edit-helper-page {
    padding: 20px;
    max-width: 1000px;
    margin: 0 auto;
    background-color: #f8f9fa;
    min-height: 100vh;
}

.page-header {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e5e7eb;
}

.back-link {
    color: #3b82f6;
    text-decoration: none;
    font-weight: 500;
    margin-bottom: 10px;
    display: inline-block;
    transition: color 0.3s ease;
}

.back-link:hover {
    color: #1d4ed8;
}

.page-header h1 {
    margin: 10px 0 5px 0;
    color: #1f2937;
    font-size: 2rem;
}

.page-header p {
    color: #6b7280;
    margin: 0;
}

.form-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    padding: 30px;
}

.form-section {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #e5e7eb;
}

.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.form-section h3 {
    margin: 0 0 20px 0;
    color: #1f2937;
    font-size: 1.2rem;
    font-weight: 600;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #374151;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    transition: border-color 0.3s ease;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-group textarea {
    resize: vertical;
}

.form-help {
    display: block;
    margin-top: 5px;
    font-size: 12px;
    color: #6b7280;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    font-weight: 500;
    color: #374151;
}

.checkbox-label input[type="checkbox"] {
    width: auto;
    margin: 0;
}

.password-input-wrapper {
    position: relative;
}

.password-input-wrapper input {
    padding-right: 50px;
}

.password-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #9ca3af;
    font-size: 16px;
}

.password-toggle:hover {
    color: #374151;
}

.password-toggle:focus {
    outline: none;
    color: #3b82f6;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

.btn-primary, .btn-secondary {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border-color: #e5e7eb;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

.age-valid {
    border-color: #10b981 !important;
}

.age-invalid {
    border-color: #ef4444 !important;
}

.age-message {
    margin-top: 5px;
    font-size: 12px;
    font-weight: 500;
}

.age-message.valid {
    color: #10b981;
}

.age-message.invalid {
    color: #ef4444;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-primary, .btn-secondary {
        width: 100%;
    }
}
</style>

<script>

function validateAge(dateOfBirth) {
    if (!dateOfBirth) return null;
    
    const dob = new Date(dateOfBirth);
    const today = new Date();
    const age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
    
    return age;
}

function updateAgeValidation() {
    const dobInput = document.getElementById('date_of_birth');
    const age = validateAge(dobInput.value);
    
    
    const existingMessage = dobInput.parentElement.querySelector('.age-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    if (age !== null) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'age-message';
        
        if (age >= 25 && age <= 55) {
            dobInput.classList.remove('age-invalid');
            dobInput.classList.add('age-valid');
            messageDiv.classList.add('valid');
            messageDiv.textContent = `Age: ${age} years (Valid)`;
        } else {
            dobInput.classList.remove('age-valid');
            dobInput.classList.add('age-invalid');
            messageDiv.classList.add('invalid');
            messageDiv.textContent = `Age: ${age} years (Must be 25-55 years)`;
        }
        
        dobInput.parentElement.appendChild(messageDiv);
    } else {
        dobInput.classList.remove('age-valid', 'age-invalid');
    }
}

document.getElementById('date_of_birth').addEventListener('change', updateAgeValidation);

updateAgeValidation();

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const toggleIcon = input.parentElement.querySelector('.toggle-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        toggleIcon.textContent = '🙈';
    } else {
        input.type = 'password';
        toggleIcon.textContent = '👁️';
    }
}

document.getElementById('editHelperForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const helperId = document.getElementById('helperId').value;
    
    
    const newPassword = formData.get('new_password');
    const confirmNewPassword = formData.get('confirm_new_password');
    
    if (newPassword || confirmNewPassword) {
        if (newPassword !== confirmNewPassword) {
            showMessage('Error', 'New passwords do not match', 'error');
            return;
        }
        
        if (newPassword.length < 6) {
            showMessage('Error', 'New password must be at least 6 characters long', 'error');
            return;
        }
    }
    
    
    const age = validateAge(formData.get('date_of_birth'));
    if (age < 25 || age > 55) {
        showMessage('Error', 'Age must be between 25 and 55 years', 'error');
        return;
    }
    
    
    const salaryMin = parseFloat(formData.get('salary_min') || 0);
    const salaryMax = parseFloat(formData.get('salary_max') || 0);
    
    if (salaryMin > 0 && salaryMax > 0 && salaryMin > salaryMax) {
        showMessage('Error', 'Minimum salary cannot be greater than maximum salary', 'error');
        return;
    }
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Updating...';
    submitBtn.disabled = true;
    
    fetch(`/staff/helper-management/update/${helperId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Success', 'Helper updated successfully!', 'success');
            setTimeout(() => {
                window.location.href = '/staff/helper-management';
            }, 1500);
        } else {
            showMessage('Error', data.error || 'Failed to update helper', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error', 'Network error occurred. Please try again.', 'error');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});

function showMessage(title, message, type) {
    
    alert(`${title}: ${message}`);
}
</script>
