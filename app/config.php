<?php
/**
 * Application Configuration
 */

// Define APP_ROOT if not already defined
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

// Load environment variables
require_once __DIR__ . '/Core/EnvLoader.php';
EnvLoader::load();

// Database configuration
define('DB_HOST', EnvLoader::get('DB_HOST', 'localhost'));
define('DB_NAME', EnvLoader::get('DB_NAME', 'maid_agency'));
define('DB_USER', EnvLoader::get('DB_USER', 'root'));
define('DB_PASS', EnvLoader::get('DB_PASS', ''));
define('DB_CHARSET', 'utf8mb4');
define('DB_SOCKET', '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock');

// Application settings
define('APP_NAME', EnvLoader::get('APP_NAME', 'Maid Agency Management System'));
define('APP_COMPANY', EnvLoader::get('APP_COMPANY', 'CleanCare Agency'));
define('APP_TAGLINE', EnvLoader::get('APP_TAGLINE', 'Professional Home Care Services'));
define('APP_ENV', EnvLoader::get('APP_ENV', 'development')); // development, production
define('APP_DEBUG', EnvLoader::get('APP_DEBUG', 'true') === 'true');

// Security settings
define('SESSION_LIFETIME', EnvLoader::get('SESSION_LIFETIME', 3600)); // 1 hour
define('CSRF_TOKEN_NAME', '_token');
define('PASSWORD_MIN_LENGTH', 8);

// File upload settings
define('UPLOAD_MAX_SIZE', 2 * 1024 * 1024); // 2MB
define('UPLOAD_ALLOWED_TYPES', ['pdf', 'jpg', 'jpeg', 'png']);
define('STORAGE_PATH', APP_ROOT . '/storage');

// Ensure storage directory exists
if (!is_dir(STORAGE_PATH)) {
    mkdir(STORAGE_PATH, 0755, true);
    mkdir(STORAGE_PATH . '/medical', 0755, true);
    mkdir(STORAGE_PATH . '/contracts', 0755, true);
    mkdir(STORAGE_PATH . '/helpers', 0755, true);
    mkdir(STORAGE_PATH . '/requests', 0755, true);
}

// Set timezone
date_default_timezone_set('UTC');