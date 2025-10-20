<?php
ob_start();
?>

<header class="landing-header">
    <div class="header-container">
        <div class="header-brand">
            <span class="brand-icon-small">🏠</span>
            <span class="brand-name"><?= htmlspecialchars(APP_NAME) ?></span>
        </div>
        <nav class="header-nav">
            <a href="#about" class="nav-link-header">About</a>
            <a href="#services" class="nav-link-header">Services</a>
            <a href="#contact" class="nav-link-header">Contact</a>
            <a href="#faq" class="nav-link-header">FAQ</a>
        </nav>
    </div>
</header>

<div class="hero-section">
    <div class="hero-content">
        <div class="brand-section">
            <div class="brand-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9,22 9,12 15,12 15,22"/>
                </svg>
            </div>
            <h1><?= htmlspecialchars(APP_COMPANY) ?></h1>
            <p class="tagline"><?= htmlspecialchars(APP_TAGLINE) ?></p>
        </div>

        <div class="role-cards">
            <div class="card" data-role="maid">
                <div class="card-icon maid-bg">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <h3>Maid Portal</h3>
                <p>Manage your profile, documents, and work schedule</p>
                <div class="card-action">
                    <span>Sign in →</span>
                </div>
            </div>

            <div class="card" data-role="client">
                <div class="card-icon client-bg">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                        <path d="M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </div>
                <h3>Client Portal</h3>
                <p>Book services, browse helpers, and manage bookings</p>
                <div class="card-action">
                    <span>Sign in →</span>
                </div>
            </div>

            <div class="card" data-role="staff">
                <div class="card-icon staff-bg">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <h3>Staff Console</h3>
                <p>Manage operations, users, and business analytics</p>
                <div class="card-action">
                    <span>Sign in →</span>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="landing-footer">
    <div class="footer-container-landing">
        <div class="footer-content-landing">
            
            <div class="footer-section-landing">
                <h3>About <?= htmlspecialchars(APP_NAME) ?></h3>
                <p>We connect families with trusted domestic helpers, providing a reliable and transparent platform for employment services.</p>
                <div class="social-links-landing">
                    <a href="#" title="Facebook" class="social-icon">📘</a>
                    <a href="#" title="Twitter" class="social-icon">🐦</a>
                    <a href="#" title="LinkedIn" class="social-icon">💼</a>
                    <a href="#" title="Instagram" class="social-icon">📷</a>
                </div>
            </div>

            
            <div class="footer-section-landing">
                <h3>Quick Links</h3>
                <ul class="footer-links-landing">
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#services">Our Services</a></li>
                    <li><a href="#how-it-works">How It Works</a></li>
                    <li><a href="#faq">FAQ</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                </ul>
            </div>

            
            <div class="footer-section-landing">
                <h3>For Clients</h3>
                <ul class="footer-links-landing">
                    <li><a href="/client/signup">Register as Client</a></li>
                    <li><a href="/client/login">Client Login</a></li>
                    <li><a href="#pricing">Pricing</a></li>
                    <li><a href="#testimonials">Testimonials</a></li>
                </ul>
            </div>

            
            <div class="footer-section-landing">
                <h3>For Helpers</h3>
                <ul class="footer-links-landing">
                    <li><a href="/maid/signup">Apply as Helper</a></li>
                    <li><a href="/maid/login">Helper Login</a></li>
                    <li><a href="#training">Training Programs</a></li>
                    <li><a href="#support">Helper Support</a></li>
                </ul>
            </div>

            
            <div class="footer-section-landing">
                <h3>Contact Us</h3>
                <p class="contact-item">📍 123 Agency Street<br>Singapore 123456</p>
                <p class="contact-item">📞 +65 1234 5678</p>
                <p class="contact-item">✉️ info@cleancare.sg</p>
                <p class="contact-item">🕒 Mon-Fri: 9AM-6PM</p>
            </div>
        </div>

        
        <div class="footer-bottom-landing">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars(APP_NAME) ?>. All rights reserved.</p>
            <p class="footer-links-inline">
                <a href="#privacy">Privacy Policy</a> |
                <a href="#terms">Terms of Service</a> |
                <a href="#cookies">Cookie Policy</a>
            </p>
        </div>
    </div>
</footer>

<style>

.landing-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.header-container {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 2rem;
}

.header-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
}

.brand-icon-small {
    font-size: 1.8rem;
}

.header-nav {
    display: flex;
    gap: 2rem;
}

.nav-link-header {
    color: white;
    text-decoration: none;
    font-weight: 500;
    font-size: 1rem;
    transition: all 0.3s ease;
    padding: 0.5rem 0;
    border-bottom: 2px solid transparent;
}

.nav-link-header:hover {
    border-bottom-color: white;
    opacity: 0.9;
}

.landing-footer {
    background: #1f2937;
    color: #d1d5db;
    margin-top: 4rem;
    padding: 4rem 0 1.5rem;
}

.footer-container-landing {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
}

.footer-content-landing {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 3rem;
    margin-bottom: 3rem;
}

.footer-section-landing h3 {
    color: white;
    font-size: 1.1rem;
    margin-bottom: 1.25rem;
    font-weight: 600;
}

.footer-section-landing p {
    margin-bottom: 0.75rem;
    line-height: 1.8;
    color: #9ca3af;
    font-size: 0.95rem;
}

.contact-item {
    margin-bottom: 0.5rem !important;
}

.footer-links-landing {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links-landing li {
    margin-bottom: 0.75rem;
}

.footer-links-landing a {
    color: #d1d5db;
    text-decoration: none;
    transition: color 0.3s ease, transform 0.2s ease;
    display: inline-block;
    font-size: 0.95rem;
}

.footer-links-landing a:hover {
    color: #667eea;
    transform: translateX(3px);
}

.social-links-landing {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.social-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    color: white;
    text-decoration: none;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.social-icon:hover {
    background: #667eea;
    transform: translateY(-3px);
}

.footer-bottom-landing {
    border-top: 1px solid #374151;
    padding-top: 2rem;
    text-align: center;
    color: #9ca3af;
    font-size: 0.9rem;
}

.footer-bottom-landing p {
    margin: 0.5rem 0;
}

.footer-links-inline a {
    color: #667eea;
    text-decoration: none;
    margin: 0 0.5rem;
}

.footer-links-inline a:hover {
    text-decoration: underline;
}

.hero-section {
    min-height: calc(100vh - 80px);
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4rem 2rem;
}

.hero-content {
    max-width: 1000px;
    width: 100%;
    text-align: center;
}

.brand-section {
    margin-bottom: 3rem;
}

.brand-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    background: #2563eb;
    color: white;
    border-radius: 20px;
    margin-bottom: 1.5rem;
}

.brand-section h1 {
    font-size: 3rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 0.5rem 0;
}

.tagline {
    font-size: 1.25rem;
    color: #6b7280;
    margin: 0;
}

.role-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    max-width: 900px;
    margin: 0 auto;
}

.card {
    background: white;
    border: 2px solid #f3f4f6;
    border-radius: 16px;
    padding: 2rem;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: left;
}

.card:hover {
    border-color: #2563eb;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.card-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    color: white;
}

.maid-bg { background: #2563eb; }
.client-bg { background: #059669; }
.staff-bg { background: #dc2626; }

.card h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.5rem 0;
}

.card p {
    color: #6b7280;
    font-size: 1rem;
    margin: 0 0 1.5rem 0;
    line-height: 1.5;
}

.card-action {
    color: #2563eb;
    font-weight: 500;
}

@media (max-width: 768px) {
    .header-container {
        padding: 1rem;
        flex-direction: column;
        gap: 1rem;
    }

    .header-nav {
        gap: 1.5rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .nav-link-header {
        font-size: 0.9rem;
    }

    .hero-section {
        padding: 2rem 1rem;
        min-height: auto;
        padding-top: 3rem;
        padding-bottom: 3rem;
    }

    .brand-section h1 {
        font-size: 2.5rem;
    }

    .role-cards {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .card {
        padding: 1.5rem;
    }

    .footer-content-landing {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .footer-container-landing {
        padding: 0 1rem;
    }

    .landing-footer {
        margin-top: 2rem;
        padding: 2.5rem 0 1rem;
    }

    .social-links-landing {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .brand-section h1 {
        font-size: 2rem;
    }

    .tagline {
        font-size: 1.1rem;
    }

    .brand-icon {
        width: 60px;
        height: 60px;
    }
}
</style>

<script<?= defined('CSP_NONCE') ? ' nonce="' . CSP_NONCE . '"' : '' ?>>
document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('click', () => {
        const role = card.getAttribute('data-role');
        window.location.href = '/' + role;
    });

    card.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            const role = card.getAttribute('data-role');
            window.location.href = '/' + role;
        }
    });

    
    card.setAttribute('tabindex', '0');
});
</script>

<?php
$content = ob_get_clean();
$title = 'Login';
$hideNavbar = true; 
$fullWidth = true; 
include APP_ROOT . '/app/Views/layouts/main.php';
?>

