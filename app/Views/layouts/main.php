<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? APP_NAME) ?> | MaidMate</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="/public/assets/icon.svg">
    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .navbar {
            background: #ffffff;
            color: #0f172a;
            padding: 0;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-container {
            max-width: 1300px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 24px;
            height: 64px;
        }

        .navbar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            text-decoration: none;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.025em;
            transition: opacity 0.15s ease;
        }

        .navbar-brand:hover {
            opacity: 0.85;
        }

        .brand-logo-svg {
            width: 32px;
            height: 32px;
            display: block;
        }

        .brand-text-accent {
            color: #4f46e5;
        }
        
        .navbar-nav {
            display: flex;
            align-items: center;
            list-style: none;
            gap: 8px;
        }
        
        .nav-item {
            position: relative;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            color: #475569;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.15s ease;
        }
        
        .nav-link:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }
        
        .dropdown {
            position: relative;
        }
        
        .dropdown-menu {
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            background: #ffffff;
            min-width: 180px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            display: none;
            z-index: 1000;
            padding: 6px;
        }
        
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            color: #334155;
            font-size: 0.875rem;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.15s ease;
        }
        
        .dropdown-item:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 20px;
        }

        .fullwidth-container {
            width: 100%;
            margin: 0;
            padding: 0;
        }
        
        .flash-messages {
            margin-bottom: 24px;
        }
        
        .flash-message {
            padding: 12px 18px;
            margin-bottom: 12px;
            border-radius: 10px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid transparent;
        }
        
        .flash-error {
            background-color: #fef2f2;
            color: #991b1b;
            border-color: #fecaca;
        }
        
        .flash-success {
            background-color: #f0fdf4;
            color: #166534;
            border-color: #bbf7d0;
        }
        
        .flash-info {
            background-color: #f0f9ff;
            color: #075985;
            border-color: #bae6fd;
        }
        
        .flash-warning {
            background-color: #fffbeb;
            color: #92400e;
            border-color: #fde68a;
        }

        .navbar-toggle {
            display: none;
            background: none;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #0f172a;
            padding: 6px 10px;
            font-size: 1.1rem;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .navbar-toggle {
                display: block;
            }
            
            .navbar-nav {
                display: none;
                position: absolute;
                top: 64px;
                left: 0;
                right: 0;
                background: #ffffff;
                flex-direction: column;
                align-items: stretch;
                padding: 12px 16px;
                border-bottom: 1px solid #e2e8f0;
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            }
            
            .navbar-nav.show {
                display: flex;
            }
            
            .dropdown-menu {
                position: static;
                display: block;
                box-shadow: none;
                background: #f8fafc;
                margin-top: 4px;
                border: none;
            }
            
            .dropdown-item {
                padding-left: 20px;
            }
            
            .container {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <?php
        $session = $session ?? new Core\Session();
        $currentRole = $session->get('role'); 
        $hideBar = isset($hideNavbar) && $hideNavbar === true;
        
        if (!$hideBar):
    ?>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="/" class="navbar-brand">
                <svg class="brand-logo-svg" viewBox="0 0 48 48" fill="none">
                    <defs>
                        <linearGradient id="navMMGrad" x1="4" y1="4" x2="44" y2="44" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#4F46E5"/>
                            <stop offset="1" stop-color="#2563EB"/>
                        </linearGradient>
                    </defs>
                    <rect x="4" y="4" width="40" height="40" rx="12" fill="url(#navMMGrad)" />
                    <path d="M14 31V21L24 13L34 21V31" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M19 31V23L24 27L29 23V31" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Maid<span class="brand-text-accent">Mate</span></span>
            </a>

            <button class="navbar-toggle" onclick="toggleNavbar()">☰</button>

            <ul class="navbar-nav" id="navbarNav">
                <?php if ($currentRole === 'maid'): ?>
                    <li class="nav-item">
                        <a href="/maid" class="nav-link">📊 Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link">👤 <?= htmlspecialchars($session->get('maid_name', 'Profile')) ?></a>
                        <div class="dropdown-menu">
                            <a href="/maid/logout" class="dropdown-item">🚪 Logout</a>
                        </div>
                    </li>
                <?php elseif ($currentRole === 'client'): ?>
                    <li class="nav-item">
                        <a href="/client" class="nav-link">📊 Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link">👤 <?= htmlspecialchars($session->get('client_name', 'Profile')) ?></a>
                        <div class="dropdown-menu">
                            <a href="/client/logout" class="dropdown-item">🚪 Logout</a>
                        </div>
                    </li>
                <?php elseif ($currentRole === 'staff'): ?>
                    <li class="nav-item">
                        <a href="/staff" class="nav-link">📊 Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link">👤 <?= htmlspecialchars($session->get('staff_name', 'Profile')) ?></a>
                        <div class="dropdown-menu">
                            <a href="/staff/logout" class="dropdown-item">🚪 Logout</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="/" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="/client/login" class="nav-link">Client Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="/maid/login" class="nav-link">Helper Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="/staff/login" class="nav-link">Staff Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <?php endif; ?>

    <?php
    $isFull = (isset($fullWidth) && $fullWidth === true) || (isset($hideNavbar) && $hideNavbar === true);
    $containerClass = $isFull ? 'fullwidth-container' : 'container';
    ?>

    <div class="<?= $containerClass ?>">
        <?php
        $session = new Core\Session();
        $hasMessages = false;
        foreach (['error', 'success', 'info', 'warning'] as $type) {
            $message = $session->getFlash($type);
            if ($message) {
                if (!$hasMessages) {
                    echo '<div class="flash-messages">';
                    $hasMessages = true;
                }
                echo "<div class='flash-message flash-{$type}'>" . htmlspecialchars($message) . "</div>";
            }
        }
        if ($hasMessages) {
            echo '</div>';
        }
        ?>

        <div class="content">
            <?= $content ?? '' ?>
        </div>
    </div>

    <script<?= defined('CSP_NONCE') ? ' nonce="' . CSP_NONCE . '"' : '' ?>>
        function toggleNavbar() {
            const nav = document.getElementById('navbarNav');
            nav.classList.toggle('show');
        }

        document.addEventListener('click', function(event) {
            const nav = document.getElementById('navbarNav');
            const toggle = document.querySelector('.navbar-toggle');

            if (!nav.contains(event.target) && !toggle.contains(event.target)) {
                nav.classList.remove('show');
            }
        });

        function getCsrfToken() {
            <?php
            $session = $session ?? new Core\Session();
            echo "return '" . $session->getCsrfToken() . "';";
            ?>
        }

        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form:not([data-no-csrf])');
            forms.forEach(form => {
                if (!form.querySelector('input[name="csrf_token"]')) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = 'csrf_token';
                    csrfInput.value = getCsrfToken();
                    form.appendChild(csrfInput);
                }
            });
        });
    </script>
</body>
</html>