<?php
ob_start();
?>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <a href="/" class="auth-brand">
                    <svg width="38" height="38" viewBox="0 0 48 48" fill="none">
                        <defs>
                            <linearGradient id="maidLogoGrad" x1="4" y1="4" x2="44" y2="44" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#4F46E5"/>
                                <stop offset="1" stop-color="#2563EB"/>
                            </linearGradient>
                        </defs>
                        <rect x="4" y="4" width="40" height="40" rx="12" fill="url(#maidLogoGrad)" />
                        <path d="M14 31V21L24 13L34 21V31" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19 31V23L24 27L29 23V31" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="brand-text">Maid<span class="brand-accent">Mate</span></span>
                </a>
                <div class="role-pill maid-pill">Helper Portal</div>
                <h1 class="auth-title">Helper Sign In</h1>
                <p class="auth-subtitle">Access your schedule, profile, and documents</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="auth-alert alert-error">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
                <div class="auth-alert alert-success">
                    <?= htmlspecialchars($_GET['success']) ?>
                </div>
            <?php endif; ?>

            <div class="error-message" id="asyncError" style="display: none;"></div>

            <form id="loginForm" class="auth-form" action="/maid/authenticate" method="POST">
                <input type="hidden" name="csrf_token" value="<?= (new Core\Session())->getCsrfToken() ?>">

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" placeholder="name@example.com" required autocomplete="email">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                </div>
                
                <button type="submit" class="btn-primary">Sign in</button>
                
                <div class="auth-footer">
                    <p class="signup-prompt">New helper? <a href="/maid/signup">Apply now</a></p>
                    <div class="role-switcher">
                        <span>Looking for help?</span>
                        <a href="/client/login">Client sign in</a>
                        <span class="dot">•</span>
                        <a href="/staff/login">Staff sign in</a>
                    </div>
                    <a href="/" class="back-home">← Back to home</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.auth-page {
    min-height: 100vh;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    padding: 32px 16px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.auth-container {
    width: 100%;
    max-width: 420px;
}

.auth-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 40px 36px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
}

.auth-header {
    text-align: center;
    margin-bottom: 28px;
}

.auth-brand {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    margin-bottom: 14px;
}

.brand-text {
    font-size: 1.35rem;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.025em;
}

.brand-accent {
    color: #4f46e5;
}

.role-pill {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 16px;
}

.maid-pill {
    background: #f0fdf4;
    color: #166534;
}

.auth-title {
    font-size: 1.45rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 6px;
    letter-spacing: -0.02em;
}

.auth-subtitle {
    color: #64748b;
    font-size: 0.875rem;
}

.auth-form {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    text-align: left;
}

.form-group label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #334155;
}

.form-group input {
    width: 100%;
    padding: 11px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 0.95rem;
    color: #0f172a;
    background: #ffffff;
    transition: all 0.15s ease;
    font-family: inherit;
}

.form-group input:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
}

.btn-primary {
    width: 100%;
    background: #0f172a;
    color: #ffffff;
    border: none;
    padding: 12px;
    border-radius: 8px;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
    font-family: inherit;
    margin-top: 6px;
}

.btn-primary:hover {
    background: #1e293b;
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.auth-footer {
    text-align: center;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid #f1f5f9;
}

.signup-prompt {
    font-size: 0.875rem;
    color: #475569;
    margin-bottom: 12px;
}

.signup-prompt a {
    color: #4f46e5;
    font-weight: 600;
    text-decoration: none;
}

.signup-prompt a:hover {
    text-decoration: underline;
}

.role-switcher {
    font-size: 0.8125rem;
    color: #94a3b8;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;
}

.role-switcher a {
    color: #64748b;
    text-decoration: none;
    font-weight: 500;
}

.role-switcher a:hover {
    color: #0f172a;
    text-decoration: underline;
}

.dot {
    color: #cbd5e1;
}

.back-home {
    display: inline-block;
    font-size: 0.8125rem;
    color: #94a3b8;
    text-decoration: none;
    transition: color 0.15s ease;
}

.back-home:hover {
    color: #0f172a;
}

.auth-alert {
    padding: 11px 14px;
    border-radius: 8px;
    font-size: 0.875rem;
    margin-bottom: 18px;
    text-align: left;
}

.alert-error {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.alert-success {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.error-message {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
    padding: 11px 14px;
    border-radius: 8px;
    font-size: 0.875rem;
    margin-bottom: 18px;
    text-align: left;
}

@media (max-width: 480px) {
    .auth-card {
        padding: 32px 24px;
    }
}
</style>

<script<?= defined('CSP_NONCE') ? ' nonce="' . CSP_NONCE . '"' : '' ?>>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('.btn-primary');
    const errorDiv = document.getElementById('asyncError');
    
    submitBtn.textContent = 'Signing in...';
    submitBtn.disabled = true;
    
    if (errorDiv) {
        errorDiv.style.display = 'none';
    }
    
    fetch('/maid/authenticate', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data && data.success) {
            window.location.href = '/maid';
        } else {
            showError(data.error || 'Login failed. Please check your credentials.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Network error. Please try again.');
    })
    .finally(() => {
        submitBtn.textContent = 'Sign in';
        submitBtn.disabled = false;
    });
});

function showError(message) {
    const errorDiv = document.getElementById('asyncError');
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }
}
</script>

<?php
$content = ob_get_clean();
$title = 'Helper Login';
$hideNavbar = true;
$fullWidth = true;
include APP_ROOT . '/app/Views/layouts/main.php';
?>
