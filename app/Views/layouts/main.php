<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? APP_NAME) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
        }

        .navbar-brand {
            font-size: 1.8em;
            font-weight: 800;
            text-decoration: none;
            color: white;
            padding: 18px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.5px;
        }

        .navbar-brand:hover {
            opacity: 0.9;
        }

        .brand-logo {
            font-size: 1.2em;
        }
        
        .navbar-nav {
            display: flex;
            list-style: none;
            gap: 0;
        }
        
        .nav-item {
            position: relative;
        }
        
        .nav-link {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        
        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            text-decoration: none;
            color: white;
        }
        
        .dropdown {
            position: relative;
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            min-width: 200px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 4px;
            display: none;
            z-index: 1000;
        }
        
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        
        .dropdown-item {
            display: block;
            padding: 10px 20px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
            text-decoration: none;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .flash-messages {
            margin-bottom: 20px;
        }
        
        .flash-message {
            padding: 12px 20px;
            margin-bottom: 10px;
            border-radius: 4px;
            border-left: 4px solid;
        }
        
        .flash-error {
            background-color: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }
        
        .flash-success {
            background-color: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }
        
        .flash-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border-left-color: #17a2b8;
        }
        
        .flash-warning {
            background-color: #fff3cd;
            color: #856404;
            border-left-color: #ffc107;
        }

        
        .navbar-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5em;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .navbar-toggle {
                display: block;
            }
            
            .navbar-nav {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                flex-direction: column;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            
            .navbar-nav.show {
                display: flex;
            }
            
            .dropdown-menu {
                position: static;
                display: block;
                box-shadow: none;
                background: rgba(255,255,255,0.1);
                margin-left: 20px;
            }
            
            .dropdown-item {
                color: white;
                padding-left: 40px;
            }
            
            .dropdown-item:hover {
                background-color: rgba(255,255,255,0.1);
                color: white;
            }
            
            .container {
                padding: 10px;
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
                <span class="brand-logo">🏠</span>
                <?= htmlspecialchars(APP_NAME) ?>
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
                        <a href="/" class="nav-link">🏠 Home</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <?php endif; ?>

    <?php
    
    $useFullWidth = isset($fullWidth) && $fullWidth === true;
    $containerClass = $useFullWidth ? '' : 'container';
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