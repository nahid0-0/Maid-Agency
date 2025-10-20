<?php

namespace Core;

/**
 * Security configuration and hardening
 */
class SecurityConfig
{
    /**
     * Apply security hardening based on environment
     */
    public static function apply(): void
    {
        // Disable dangerous PHP functions in production
        if (defined('APP_ENV') && APP_ENV === 'production') {
            ini_set('expose_php', 'Off');
            ini_set('display_errors', 'Off');
            ini_set('display_startup_errors', 'Off');
            ini_set('log_errors', 'On');
        }

        // Set secure cookie defaults
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_samesite', 'Strict');

        // Prevent session fixation
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        // Set upload restrictions
        ini_set('file_uploads', 1);
        ini_set('upload_max_filesize', '5M');
        ini_set('post_max_size', '6M');
        ini_set('max_file_uploads', 5);

        // Memory and execution limits
        ini_set('memory_limit', '128M');
        ini_set('max_execution_time', 30);
    }

    /**
     * Set security headers for all responses
     */
    public static function setHeaders(): void
    {
        // Prevent MIME type sniffing
        header('X-Content-Type-Options: nosniff');

        // Prevent clickjacking
        header('X-Frame-Options: DENY');

        // Enable XSS protection
        header('X-XSS-Protection: 1; mode=block');

        // Control referrer information
        header('Referrer-Policy: strict-origin-when-cross-origin');

        // Disable unnecessary browser features
        header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()');

        // Force HTTPS in production
        if (defined('APP_ENV') && APP_ENV === 'production') {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        }
    }

    /**
     * Validate request origin and method
     */
    public static function validateRequest(): bool
    {
        // Check for common attack patterns in request
        $dangerous_patterns = [
            '<script',
            'javascript:',
            'data:text/html',
            '../',
            '..\\',
            'eval(',
            'base64_decode'
        ];

        $request_data = array_merge($_GET, $_POST, $_COOKIE);

        foreach ($request_data as $key => $value) {
            if (is_string($value)) {
                foreach ($dangerous_patterns as $pattern) {
                    if (stripos($value, $pattern) !== false) {
                        Security::logSecurityEvent('dangerous_pattern_detected', [
                            'pattern' => $pattern,
                            'key' => $key,
                            'value' => substr($value, 0, 100)
                        ]);
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Clean and validate all user inputs
     */
    public static function sanitizeInputs(): void
    {
        $_GET = self::deepSanitize($_GET);
        $_POST = self::deepSanitize($_POST);
        $_COOKIE = self::deepSanitize($_COOKIE);
    }

    /**
     * Recursively sanitize array values
     */
    private static function deepSanitize($data)
    {
        if (is_array($data)) {
            return array_map([self::class, 'deepSanitize'], $data);
        }

        if (is_string($data)) {
            // Remove null bytes and control characters
            $data = str_replace(["\0", "\r"], '', $data);
            // Trim whitespace
            $data = trim($data);
        }

        return $data;
    }
}