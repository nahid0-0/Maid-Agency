<?php
ob_start();
?>

<header class="landing-header">
    <div class="header-container">
        <a href="/" class="header-brand">
            <svg width="34" height="34" viewBox="0 0 48 48" fill="none">
                <defs>
                    <linearGradient id="homeBrandGrad" x1="4" y1="4" x2="44" y2="44" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#4F46E5"/>
                        <stop offset="1" stop-color="#2563EB"/>
                    </linearGradient>
                </defs>
                <rect x="4" y="4" width="40" height="40" rx="12" fill="url(#homeBrandGrad)" />
                <path d="M14 31V21L24 13L34 21V31" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M19 31V23L24 27L29 23V31" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="brand-name">Maid<span class="brand-name-accent">Mate</span></span>
        </a>
        <nav class="header-nav">
            <a href="#about" class="nav-link-header">About</a>
            <a href="#portals" class="nav-link-header">Portals</a>
            <a href="#contact" class="nav-link-header">Contact</a>
            <a href="/client/login" class="nav-cta-header">Client Portal →</a>
        </nav>
    </div>
</header>

<div class="hero-section">
    <div class="hero-content">
        <div class="brand-section">
            <div class="hero-badge">
                <span class="badge-dot"></span>
                Reliable Domestic Agency Management
            </div>
            <h1 class="hero-headline">Welcome to <span class="text-gradient">MaidMate</span></h1>
            <p class="tagline">Connecting caring households with verified, professional domestic helpers through a secure, transparent platform.</p>
        </div>

        <div id="portals" class="role-cards">
            <div class="card" data-role="client">
                <div class="card-icon client-bg">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                        <path d="M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </div>
                <h3>Client Portal</h3>
                <p>Browse pre-screened helpers, submit hiring requests, and track your placements.</p>
                <div class="card-action">
                    <span>Enter portal →</span>
                </div>
            </div>

            <div class="card" data-role="maid">
                <div class="card-icon maid-bg">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <h3>Helper Portal</h3>
                <p>Manage your profile, upload training & medical certificates, and view work schedules.</p>
                <div class="card-action">
                    <span>Enter portal →</span>
                </div>
            </div>

            <div class="card" data-role="staff">
                <div class="card-icon staff-bg">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <h3>Staff Console</h3>
                <p>Administer agency workflows, verify records, and manage deployment operations.</p>
                <div class="card-action">
                    <span>Enter console →</span>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="landing-footer" id="about">
    <div class="footer-container-landing">
        <div class="footer-content-landing">
            <div class="footer-section-landing">
                <div class="footer-brand">
                    <svg width="28" height="28" viewBox="0 0 48 48" fill="none">
                        <rect x="4" y="4" width="40" height="40" rx="12" fill="#4F46E5" />
                        <path d="M14 31V21L24 13L34 21V31" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19 31V23L24 27L29 23V31" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Maid<span style="color:#818CF8;">Mate</span></span>
                </div>
                <p>Providing seamless, trusted domestic helper agency management and placements with high standards of compliance and care.</p>
            </div>

            <div class="footer-section-landing">
                <h3>For Households</h3>
                <ul class="footer-links-landing">
                    <li><a href="/client/signup">Register as Client</a></li>
                    <li><a href="/client/login">Client Sign In</a></li>
                    <li><a href="/client">Browse Available Helpers</a></li>
                </ul>
            </div>

            <div class="footer-section-landing">
                <h3>For Helpers</h3>
                <ul class="footer-links-landing">
                    <li><a href="/maid/signup">Apply as Domestic Helper</a></li>
                    <li><a href="/maid/login">Helper Sign In</a></li>
                </ul>
            </div>

            <div class="footer-section-landing" id="contact">
                <h3>Contact & Office</h3>
                <div style="font-size: 0.875rem; color: #94a3b8; line-height: 1.8;">
                    <p style="color: #ffffff; font-weight: 600; margin-bottom: 4px;"><?= htmlspecialchars(defined('APP_CONTACT_PERSON') ? APP_CONTACT_PERSON : 'Rofidul Hasan Ovik') ?></p>
                    <p style="margin-bottom: 4px;">📍 <?= htmlspecialchars(defined('APP_CONTACT_ADDRESS') ? APP_CONTACT_ADDRESS : 'Mirpur 1, Uttor Tularbag, Dhaka') ?></p>
                    <p style="margin-bottom: 4px;">📞 <a href="tel:01902440284" style="color: #cbd5e1; text-decoration: none;">01902440284</a></p>
                    <p style="margin-bottom: 4px;">✉️ <a href="mailto:rofidul15-14160@diu.edu.bd" style="color: #cbd5e1; text-decoration: none;">rofidul15-14160@diu.edu.bd</a></p>
                </div>
            </div>
        </div>

        <div class="footer-bottom-landing">
            <p>&copy; <?= date('Y') ?> MaidMate. All rights reserved.</p>
        </div>
    </div>
</footer>

<style>
.landing-header {
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.header-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 24px;
    height: 68px;
}

.header-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    font-size: 1.35rem;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.025em;
}

.brand-name-accent {
    color: #4f46e5;
}

.header-nav {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.nav-link-header {
    color: #475569;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    transition: color 0.15s ease;
}

.nav-link-header:hover {
    color: #0f172a;
}

.nav-cta-header {
    background: #0f172a;
    color: #ffffff;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 8px;
    transition: background 0.15s ease;
}

.nav-cta-header:hover {
    background: #1e293b;
}

.hero-section {
    min-height: calc(100vh - 68px);
    background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 24px;
}

.hero-content {
    max-width: 1100px;
    width: 100%;
    text-align: center;
}

.brand-section {
    margin-bottom: 48px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    background: #eff6ff;
    border: 1px solid #dbeafe;
    border-radius: 9999px;
    color: #2563eb;
    font-size: 0.8125rem;
    font-weight: 600;
    margin-bottom: 20px;
}

.badge-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #2563eb;
}

.hero-headline {
    font-size: 3.25rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.03em;
    margin: 0 0 16px 0;
    line-height: 1.15;
}

.text-gradient {
    background: linear-gradient(135deg, #4f46e5 0%, #2563eb 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.tagline {
    font-size: 1.15rem;
    color: #64748b;
    max-width: 620px;
    margin: 0 auto;
    line-height: 1.6;
}

.role-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    max-width: 1000px;
    margin: 0 auto;
}

.card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 32px 28px;
    cursor: pointer;
    text-align: left;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
}

.card:hover {
    border-color: #cbd5e1;
    transform: translateY(-3px);
    box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.08);
}

.card-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    color: #ffffff;
}

.client-bg { background: #2563eb; }
.maid-bg { background: #059669; }
.staff-bg { background: #0f172a; }

.card h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 8px 0;
    letter-spacing: -0.015em;
}

.card p {
    color: #64748b;
    font-size: 0.9rem;
    line-height: 1.55;
    margin: 0 0 24px 0;
    flex-grow: 1;
}

.card-action {
    color: #4f46e5;
    font-size: 0.875rem;
    font-weight: 600;
}

.landing-footer {
    background: #0f172a;
    color: #cbd5e1;
    padding: 60px 0 32px;
}

.footer-container-landing {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
}

.footer-content-landing {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 36px;
    margin-bottom: 40px;
}

.footer-brand {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1.2rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 14px;
}

.footer-section-landing p {
    color: #94a3b8;
    font-size: 0.875rem;
    line-height: 1.6;
    max-width: 320px;
}

.footer-section-landing h3 {
    color: #ffffff;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 16px;
    letter-spacing: 0.02em;
}

.footer-links-landing {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.footer-links-landing a {
    color: #94a3b8;
    text-decoration: none;
    font-size: 0.85rem;
    transition: color 0.15s ease;
}

.footer-links-landing a:hover {
    color: #ffffff;
}

.footer-bottom-landing {
    border-top: 1px solid #1e293b;
    padding-top: 24px;
    text-align: center;
    color: #64748b;
    font-size: 0.85rem;
}

@media (max-width: 900px) {
    .role-cards {
        grid-template-columns: 1fr;
        max-width: 440px;
    }
    .footer-content-landing {
        grid-template-columns: 1fr;
    }
    .hero-headline {
        font-size: 2.5rem;
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
$title = 'Welcome to MaidMate';
$hideNavbar = true; 
$fullWidth = true; 
include APP_ROOT . '/app/Views/layouts/main.php';
?>
