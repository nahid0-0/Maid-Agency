<?php
/**
 * Application Configuration
 */

// Define APP_ROOT if not already defined
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

// Load Composer autoloader
if (file_exists(APP_ROOT . '/vendor/autoload.php')) {
    require_once APP_ROOT . '/vendor/autoload.php';
}

// Load environment variables
require_once __DIR__ . '/Core/EnvLoader.php';
EnvLoader::load();

// Database configuration
define('DB_HOST', EnvLoader::get('DB_HOST', '127.0.0.1'));
define('DB_PORT', EnvLoader::get('DB_PORT', '3306'));
define('DB_NAME', EnvLoader::get('DB_NAME', 'maid_agency'));
define('DB_USER', EnvLoader::get('DB_USER', 'maid_user'));
define('DB_PASS', EnvLoader::get('DB_PASS', 'maid_pass'));
define('DB_CHARSET', 'utf8mb4');
define('DB_SSL', EnvLoader::get('DB_SSL', (strpos(DB_HOST, 'tidbcloud.com') !== false ? 'true' : 'false')) === 'true');
define('DB_SSL_CA', EnvLoader::get('DB_SSL_CA', ''));

// Application settings
define('APP_NAME', EnvLoader::get('APP_NAME', 'MaidMate'));
define('APP_COMPANY', EnvLoader::get('APP_COMPANY', 'MaidMate'));
define('APP_TAGLINE', EnvLoader::get('APP_TAGLINE', 'Professional Home Care & Domestic Services'));
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

// Cloudinary configuration
define('CLOUDINARY_CLOUD_NAME', EnvLoader::get('CLOUDINARY_CLOUD_NAME', ''));
define('CLOUDINARY_API_KEY',    EnvLoader::get('CLOUDINARY_API_KEY',    ''));
define('CLOUDINARY_API_SECRET', EnvLoader::get('CLOUDINARY_API_SECRET', ''));
define('CLOUDINARY_UPLOAD_PRESET', EnvLoader::get('CLOUDINARY_UPLOAD_PRESET', ''));