<?php
ob_start();
?>

<div class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>👨‍👩‍👧‍👦 Client Login</h1>
                <p>Access your account to find domestic help</p>
            </div>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="error-message" style="display: block;">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
                <div class="success-message" style="display: block; background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; font-size: 14px; margin-bottom: 20px;">
                    <?= htmlspecialchars($_GET['success']) ?>
                </div>
            <?php endif; ?>

            <form id="loginForm" class="login-form" action="/client/authenticate" method="POST">
                <input type="hidden" name="csrf_token" value="<?= (new Core\Session())->getCsrfToken() ?>">

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-login">Login</button>
                
                <div class="form-footer">
                    <p>Don't have an account? <a href="/client/signup">Sign up here</a></p>
                    <p><a href="/">← Back to role selection</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.login-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
}

.login-container {
    width: 100%;
    max-width: 400px;
}

.login-card {
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
}

.login-header {
    text-align: center;
    margin-bottom: 30px;
}

.login-header h1 {
    font-size: 24px;
    color: #1f2937;
    margin-bottom: 8px;
}

.login-header p {
    color: #6b7280;
    font-size: 14px;
}

.login-form {
    display: flex;
    flex-direction: column;
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

.form-group input {
    padding: 12px 16px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.form-group input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-login {
    background: #667eea;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn-login:hover {
    background: #5a6fd8;
}

.btn-login:disabled {
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

.loading {
    opacity: 0.6;
    pointer-events: none;
}

@media (max-width: 480px) {
    .login-card {
        padding: 30px 20px;
    }
    
    .login-header h1 {
        font-size: 20px;
    }
}
</style>

<script<?= defined('CSP_NONCE') ? ' nonce="' . CSP_NONCE . '"' : '' ?>>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('.btn-login');
    const errorDiv = document.querySelector('.error-message');
    
    
    submitBtn.textContent = 'Logging in...';
    submitBtn.disabled = true;
    form.classList.add('loading');
    
    if (errorDiv) {
        errorDiv.style.display = 'none';
    }
    
    fetch('/client/authenticate', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            
            window.location.href = response.url || '/client';
            return null;
        }
    })
    .then(data => {
        if (data && data.success) {
            
            window.location.href = '/client';
        } else if (data && !data.success) {
            
            showError(data.error || 'Login failed. Please try again.');
        }
        
    })
    .catch(error => {
        console.error('Error:', error);
        
        if (error.message.includes('Unexpected token')) {
            window.location.href = '/client';
        } else {
            showError('Network error. Please try again.');
        }
    })
    .finally(() => {
        
        submitBtn.textContent = 'Login';
        submitBtn.disabled = false;
        form.classList.remove('loading');
    });
});

function showError(message) {
    let errorDiv = document.querySelector('.error-message');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        document.querySelector('.login-form').insertBefore(errorDiv, document.querySelector('.form-group'));
    }
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
}
</script>

<?php
$content = ob_get_clean();
$title = 'Client Login';
$hideNavbar = true;
include APP_ROOT . '/app/Views/layouts/main.php';
?>

