<?php
ob_start();
?>

<div class="signup-page">
    <div class="signup-container">
        <div class="signup-card">
            <div class="signup-header">
                <h1>🏢 Staff Registration</h1>
                <p>Create your staff account to access the management system</p>
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

            <form id="signupForm" class="signup-form" action="/staff/register" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="full_name">Full Name *</label>
                        <input type="text" id="full_name" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text" id="username" name="username" required minlength="3">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role">
                        <option value="staff" selected>Staff</option>
                        <option value="manager">Manager</option>
                        <option value="admin">Administrator</option>
                    </select>
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
                            <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                            <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                <span class="toggle-icon">👁️</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn-signup">Create Account</button>
                
                <div class="form-footer">
                    <p>Already have an account? <a href="/staff/login">Login here</a></p>
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
    gap: 20px;
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
.form-group select {
    padding: 12px 16px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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

@media (max-width: 480px) {
    .signup-container {
        max-width: 100%;
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

document.getElementById('signupForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('.btn-signup');
    const originalText = submitBtn.textContent;
    
    
    const password = formData.get('password');
    const confirmPassword = formData.get('confirm_password');
    
    if (password !== confirmPassword) {
        showError('Passwords do not match');
        return;
    }
    
    
    if (password.length < 6) {
        showError('Password must be at least 6 characters long');
        return;
    }
    
    
    submitBtn.textContent = 'Creating Account...';
    submitBtn.disabled = true;
    form.classList.add('loading');
    
    hideMessages();
    
    fetch('/staff/register', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.message || 'Account created successfully! Redirecting to login...');
            
            setTimeout(() => {
                window.location.href = '/staff/login';
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
        
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        form.classList.remove('loading');
    });
});

function showError(message) {
    hideMessages();
    let errorDiv = document.querySelector('.error-message');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        document.querySelector('.signup-form').insertBefore(errorDiv, document.querySelector('.form-row, .form-group'));
    }
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
}

function showSuccess(message) {
    hideMessages();
    let successDiv = document.querySelector('.success-message');
    if (!successDiv) {
        successDiv = document.createElement('div');
        successDiv.className = 'success-message';
        document.querySelector('.signup-form').insertBefore(successDiv, document.querySelector('.form-row, .form-group'));
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
$title = 'Staff Registration';
$hideNavbar = true;
include APP_ROOT . '/app/Views/layouts/main.php';
?>