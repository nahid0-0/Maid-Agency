<?php
/**
 * Front Controller - Root Level
 * This is a simplified version for direct access
 */

// Start output buffering
ob_start();

// Define application root
define('APP_ROOT', __DIR__);

// Load configuration and autoloader
require_once APP_ROOT . '/app/config.php';

// Set error reporting based on environment
if (APP_DEBUG && APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', APP_ROOT . '/logs/error.log');
}
require_once APP_ROOT . '/app/Core/Autoloader.php';

// Initialize autoloader
$autoloader = new Core\Autoloader();
$autoloader->register();

// Apply security configuration
Core\SecurityConfig::apply();
Core\SecurityConfig::sanitizeInputs();

// Validate request for security
if (!Core\SecurityConfig::validateRequest()) {
    http_response_code(400);
    die('Bad Request');
}

// Initialize core components
try {
    $database = Core\Database::getInstance();
} catch (Exception $e) {
    // Database not required for web pages demo
    $database = null;
}

$session = new Core\Session();
$router = new Core\Router();

// Start session
$session->start();

// Set comprehensive security headers
Core\SecurityConfig::setHeaders();

// Content Security Policy with nonce
$nonce = base64_encode(random_bytes(16));
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self'; connect-src 'self';");

// Set nonce for inline scripts
define('CSP_NONCE', $nonce);

// Route the request
try {
    $router->dispatch();
} catch (Exception $e) {
    // Log error and show generic error page
    error_log($e->getMessage());
    http_response_code(500);
    echo "An error occurred: " . $e->getMessage();
}

// End output buffering
ob_end_flush();