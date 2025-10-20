<?php
ob_start();
?>

<div class="signup-page">
    <div class="signup-container">
        <div class="signup-card">
            <div class="signup-header">
                <h1>🧹 Maid Registration</h1>
                <p>Create your account to get started</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="error-message" style="display: block;">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
                <div class="success-message" style="display: block;">
                    <?= htmlspecialchars($_GET['success']) ?>
                </div>
            <?php endif; ?>

            <form id="signupForm" class="signup-form" action="/maid/register" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="full_name">Full Name *</label>
                        <input type="text" id="full_name" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="password" name="password" required minlength="6">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <span class="toggle-icon">👁️</span>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password *</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                <span class="toggle-icon">👁️</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nationality">Nationality *</label>
                        <select id="nationality" name="nationality" required>
                            <option value="">Select Nationality</option>
                            <option value="Philippines">Philippines</option>
                            <option value="Indonesia">Indonesia</option>
                            <option value="Myanmar">Myanmar</option>
                            <option value="India">India</option>
                            <option value="Sri Lanka">Sri Lanka</option>
                            <option value="Bangladesh">Bangladesh</option>
                            <option value="Nepal">Nepal</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth *</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="gender">Gender *</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Female">Female</option>
                            <option value="Male">Male</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="tel" id="contact_number" name="contact_number" placeholder="+65 XXXX XXXX">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="experience_level">Experience Level *</label>
                    <select id="experience_level" name="experience_level" required>
                        <option value="">Select Experience Level</option>
                        <option value="Beginner (0-2 years)">Beginner (0-2 years)</option>
                        <option value="Intermediate (2-5 years)">Intermediate (2-5 years)</option>
                        <option value="Experienced (5+ years)">Experienced (5+ years)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="emergency_contact">Emergency Contact</label>
                    <textarea id="emergency_contact" name="emergency_contact" rows="2" placeholder="Emergency contact name and phone number"></textarea>
                </div>
                
                <button type="submit" class="btn-signup">Create Account</button>
                
                <div class="form-footer">
                    <p>Already have an account? <a href="/maid/login">Login here</a></p>
                    <p><a href="/">← Back to role selection</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.signup-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
}

.signup-container {
    width: 100%;
    max-width: 600px;
}

.signup-card {
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
    max-height: 90vh;
    overflow-y: auto;
}

.signup-header {
    text-align: center;
    margin-bottom: 30px;
}

.signup-header h1 {
    font-size: 24px;
    color: #1f2937;
    margin-bottom: 8px;
}

.signup-header p {
    color: #6b7280;
    font-size: 14px;
}

.signup-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-group {
    display: flex;
    flex-direction: column;
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
    min-height: 60px;
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
    font-size: 16px;
    color: #6b7280;
    padding: 4px;
    border-radius: 4px;
    transition: color 0.2s ease;
}

.password-toggle:hover {
    color: #374151;
}

.password-toggle:focus {
    outline: none;
    color: #667eea;
}

.btn-signup {
    background: #667eea;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
    margin-top: 10px;
}

.btn-signup:hover {
    background: #5a6fd8;
}

.btn-signup:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.form-footer {
    text-align: center;
    margin-top: 20px;
}

.form-footer p {
    margin: 8px 0;
    font-size: 14px;
    color: #6b7280;
}

.form-footer a {
    color: #667eea;
    text-decoration: none;
}

.form-footer a:hover {
    text-decoration: underline;
}

.error-message {
    background: #fee2e2;
    color: #dc2626;
    padding: 12px;
    border-radius: 6px;
    font-size: 14px;
    margin-bottom: 20px;
    display: none;
}

.success-message {
    background: #dcfce7;
    color: #166534;
    padding: 12px;
    border-radius: 6px;
    font-size: 14px;
    margin-bottom: 20px;
    display: none;
}

.loading {
    opacity: 0.6;
    pointer-events: none;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .signup-card {
        padding: 30px 20px;
    }
    
    .signup-header h1 {
        font-size: 20px;
    }
}
</style>

<script>

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

function validateAge(dateOfBirth) {
    const today = new Date();
    const birthDate = new Date(dateOfBirth);
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    return age;
}

document.getElementById('signupForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('.btn-signup');
    
    
    const dateOfBirth = formData.get('date_of_birth');
    if (dateOfBirth) {
        const age = validateAge(dateOfBirth);
        if (age < 25 || age > 55) {
            showError('Age must be between 25 and 55 years to register');
            return;
        }
    }
    
    
    const password = formData.get('password');
    const confirmPassword = formData.get('confirm_password');
    
    if (password !== confirmPassword) {
        showError('Passwords do not match');
        return;
    }
    
    
    submitBtn.textContent = 'Creating Account...';
    submitBtn.disabled = true;
    form.classList.add('loading');
    
    hideMessages();
    
    fetch('/maid/register', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Account created successfully! Redirecting to login...');
            setTimeout(() => {
                window.location.href = '/maid/login';
            }, 2000);
        } else {
            showError(data.error || 'Registration failed. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Network error. Please try again.');
    })
    .finally(() => {
        
        submitBtn.textContent = 'Create Account';
        submitBtn.disabled = false;
        form.classList.remove('loading');
    });
});

function showError(message) {
    let errorDiv = document.querySelector('.error-message');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        document.querySelector('.signup-form').insertBefore(errorDiv, document.querySelector('.form-row'));
    }
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
}

function showSuccess(message) {
    let successDiv = document.querySelector('.success-message');
    if (!successDiv) {
        successDiv = document.createElement('div');
        successDiv.className = 'success-message';
        document.querySelector('.signup-form').insertBefore(successDiv, document.querySelector('.form-row'));
    }
    successDiv.textContent = message;
    successDiv.style.display = 'block';
}

function hideMessages() {
    const errorDiv = document.querySelector('.error-message');
    const successDiv = document.querySelector('.success-message');
    if (errorDiv) errorDiv.style.display = 'none';
    if (successDiv) successDiv.style.display = 'none';
}
</script>

<?php
$content = ob_get_clean();
$title = 'Maid Registration';
$hideNavbar = true;
include APP_ROOT . '/app/Views/layouts/main.php';
?>
