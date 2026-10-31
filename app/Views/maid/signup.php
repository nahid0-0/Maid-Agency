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
                            <linearGradient id="maidRegLogoGrad" x1="4" y1="4" x2="44" y2="44" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#4F46E5"/>
                                <stop offset="1" stop-color="#2563EB"/>
                            </linearGradient>
                        </defs>
                        <rect x="4" y="4" width="40" height="40" rx="12" fill="url(#maidRegLogoGrad)" />
                        <path d="M14 31V21L24 13L34 21V31" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19 31V23L24 27L29 23V31" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="brand-text">Maid<span class="brand-accent">Mate</span></span>
                </a>
                <div class="role-pill maid-pill">Helper Registration</div>
                <h1 class="auth-title">Join as a Professional Helper</h1>
                <p class="auth-subtitle">Create your helper profile and start receiving job matches</p>
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

            <form id="signupForm" class="auth-form" action="/maid/register" method="POST">
                <input type="hidden" name="csrf_token" value="<?= (new Core\Session())->getCsrfToken() ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="full_name">Full name *</label>
                        <input type="text" id="full_name" name="full_name" placeholder="As shown in passport" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address *</label>
                        <input type="email" id="email" name="email" placeholder="name@example.com" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" required minlength="6" placeholder="Min. 6 characters">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm password *</label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="6" placeholder="Repeat password">
                    </div>

                    <div class="form-group">
                        <label for="nationality">Nationality *</label>
                        <select id="nationality" name="nationality" required>
                            <option value="">Select nationality</option>
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
                        <label for="date_of_birth">Date of birth *</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" required>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender *</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select gender</option>
                            <option value="Female">Female</option>
                            <option value="Male">Male</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact number</label>
                        <input type="tel" id="contact_number" name="contact_number" placeholder="+65 9123 4567">
                    </div>

                    <div class="form-group form-col-span">
                        <label for="experience_level">Experience level *</label>
                        <select id="experience_level" name="experience_level" required>
                            <option value="">Select experience level</option>
                            <option value="Beginner (0-2 years)">Beginner (0-2 years)</option>
                            <option value="Intermediate (2-5 years)">Intermediate (2-5 years)</option>
                            <option value="Experienced (5+ years)">Experienced (5+ years)</option>
                        </select>
                    </div>

                    <div class="form-group form-col-span">
                        <label for="emergency_contact">Emergency contact</label>
                        <input type="text" id="emergency_contact" name="emergency_contact" placeholder="Contact name, relationship, and phone number">
                    </div>
                </div>

                <button type="submit" class="btn-primary" style="margin-top: 10px;">Submit Helper Application</button>
                
                <div class="auth-footer">
                    <p class="signup-prompt">Already registered? <a href="/maid/login">Helper sign in</a></p>
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
$title = 'Helper Registration';
$hideNavbar = true;
$fullWidth = true;
include APP_ROOT . '/app/Views/layouts/main.php';
?>
