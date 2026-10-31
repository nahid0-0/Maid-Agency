<?php
ob_start();
?>

<div class="auth-page auth-page-wide">
    <div class="auth-container-wide">
        <div class="auth-card">
            <div class="auth-header">
                <a href="/" class="auth-brand">
                    <svg width="38" height="38" viewBox="0 0 48 48" fill="none">
                        <defs>
                            <linearGradient id="clientLogoGrad" x1="4" y1="4" x2="44" y2="44" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#4F46E5"/>
                                <stop offset="1" stop-color="#2563EB"/>
                            </linearGradient>
                        </defs>
                        <rect x="4" y="4" width="40" height="40" rx="12" fill="url(#clientLogoGrad)" />
                        <path d="M14 31V21L24 13L34 21V31" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19 31V23L24 27L29 23V31" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="brand-text">Maid<span class="brand-accent">Mate</span></span>
                </a>
                <div class="role-pill">Client Registration</div>
                <h1 class="auth-title">Create your client account</h1>
                <p class="auth-subtitle">Connect with thoroughly vetted domestic helpers</p>
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
            <div class="success-message" id="asyncSuccess" style="display: none;"></div>

            <form id="signupForm" class="auth-form" action="/client/register" method="POST">
                <input type="hidden" name="csrf_token" value="<?= (new Core\Session())->getCsrfToken() ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Full name *</label>
                        <input type="text" id="name" name="name" placeholder="John Doe" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email address *</label>
                        <input type="email" id="email" name="email" placeholder="john@example.com" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone number *</label>
                        <input type="tel" id="phone" name="phone" required placeholder="+65 9123 4567">
                    </div>

                    <div class="form-group">
                        <label for="preferred_nationality">Preferred nationality</label>
                        <select id="preferred_nationality" name="preferred_nationality">
                            <option value="">No Preference</option>
                            <option value="Philippines">Philippines</option>
                            <option value="Indonesia">Indonesia</option>
                            <option value="Myanmar">Myanmar</option>
                            <option value="India">India</option>
                            <option value="Sri Lanka">Sri Lanka</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group form-col-span">
                        <label for="address">Residential address</label>
                        <input type="text" id="address" name="address" placeholder="e.g. 123 Orchard Rd, Singapore">
                    </div>
                    
                    <div class="form-group">
                        <label for="family_size">Family size</label>
                        <select id="family_size" name="family_size">
                            <option value="">Select family size</option>
                            <option value="1">1 person</option>
                            <option value="2">2 people</option>
                            <option value="3">3 people</option>
                            <option value="4">4 people</option>
                            <option value="5">5 people</option>
                            <option value="6">6+ people</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="children_ages">Children ages (if applicable)</label>
                        <input type="text" id="children_ages" name="children_ages" placeholder="e.g. 3, 7">
                    </div>

                    <div class="form-group">
                        <label for="budget_min">Min budget (SGD)</label>
                        <input type="number" id="budget_min" name="budget_min" min="0" step="50" placeholder="700">
                    </div>

                    <div class="form-group">
                        <label for="budget_max">Max budget (SGD)</label>
                        <input type="number" id="budget_max" name="budget_max" min="0" step="50" placeholder="1200">
                    </div>

                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" required minlength="6" placeholder="Min. 6 characters">
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm password *</label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="6" placeholder="Repeat password">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 4px;">
                    <label for="special_requirements">Special requirements or duties</label>
                    <textarea id="special_requirements" name="special_requirements" rows="2" placeholder="e.g. Elderly care, infant handling, specific cooking preference..."></textarea>
                </div>
                
                <button type="submit" class="btn-primary" style="margin-top: 8px;">Create Client Account</button>
                
                <div class="auth-footer">
                    <p class="signup-prompt">Already have an account? <a href="/client/login">Sign in here</a></p>
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
    padding: 48px 16px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.auth-container-wide {
    width: 100%;
    max-width: 660px;
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
    background: #eff6ff;
    color: #2563eb;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 16px;
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
    gap: 16px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-col-span {
    grid-column: span 2;
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

.form-group input, 
.form-group select, 
.form-group textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 0.95rem;
    color: #0f172a;
    background: #ffffff;
    transition: all 0.15s ease;
    font-family: inherit;
}

.form-group input:focus, 
.form-group select:focus, 
.form-group textarea:focus {
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
}

.btn-primary:hover {
    background: #1e293b;
}

.auth-footer {
    text-align: center;
    margin-top: 20px;
    padding-top: 18px;
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

.auth-alert, .error-message, .success-message {
    padding: 11px 14px;
    border-radius: 8px;
    font-size: 0.875rem;
    margin-bottom: 18px;
    text-align: left;
}

.alert-error, .error-message {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.alert-success, .success-message {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

@media (max-width: 600px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    .form-col-span {
        grid-column: span 1;
    }
    .auth-card {
        padding: 28px 20px;
    }
}
</style>

<script<?= defined('CSP_NONCE') ? ' nonce="' . CSP_NONCE . '"' : '' ?>>
document.getElementById('signupForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        showError('Passwords do not match');
        return;
    }
    
    const minBudget = parseFloat(document.getElementById('budget_min').value) || 0;
    const maxBudget = parseFloat(document.getElementById('budget_max').value) || 0;
    
    if (minBudget > 0 && maxBudget > 0 && minBudget > maxBudget) {
        e.preventDefault();
        showError('Minimum budget cannot be greater than maximum budget');
        return;
    }
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
$title = 'Client Registration';
$hideNavbar = true;
$fullWidth = true;
include APP_ROOT . '/app/Views/layouts/main.php';
?>