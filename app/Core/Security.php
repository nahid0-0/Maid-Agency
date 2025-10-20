<?php

namespace Core;

/**
 * Security utilities for input validation and sanitization
 */
class Security
{
    /**
     * Sanitize string input
     */
    public static function sanitizeString(string $input, int $maxLength = 255): string
    {
        $sanitized = trim($input);
        $sanitized = htmlspecialchars($sanitized, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return substr($sanitized, 0, $maxLength);
    }

    /**
     * Validate email address
     */
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate phone number (basic format)
     */
    public static function validatePhone(string $phone): bool
    {
        $cleaned = preg_replace('/[^\d+\-\(\)\s]/', '', $phone);
        return strlen($cleaned) >= 8 && strlen($cleaned) <= 20;
    }

    /**
     * Validate password strength
     */
    public static function validatePassword(string $password): array
    {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter';
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter';
        }

        if (!preg_match('/\d/', $password)) {
            $errors[] = 'Password must contain at least one number';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Validate file upload
     */
    public static function validateFileUpload(array $file, array $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png'], int $maxSize = 5242880): array
    {
        $errors = [];

        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            $errors[] = 'No file uploaded';
            return ['valid' => false, 'errors' => $errors];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'File upload failed with error code: ' . $file['error'];
        }

        if ($file['size'] > $maxSize) {
            $errors[] = 'File size exceeds maximum allowed size of ' . ($maxSize / 1024 / 1024) . 'MB';
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedTypes)) {
            $errors[] = 'File type not allowed. Allowed types: ' . implode(', ', $allowedTypes);
        }

        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMimes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png'
        ];

        if (!in_array($mimeType, $allowedMimes)) {
            $errors[] = 'Invalid file content. Expected: ' . implode(', ', array_unique($allowedMimes));
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Rate limiting for login attempts
     */
    public static function checkRateLimit(string $identifier, int $maxAttempts = 5, int $timeWindow = 900): bool
    {
        $session = new Session();
        $key = 'rate_limit_' . md5($identifier);
        $attempts = $session->get($key, []);

        // Clean old attempts
        $currentTime = time();
        $attempts = array_filter($attempts, function($timestamp) use ($currentTime, $timeWindow) {
            return ($currentTime - $timestamp) < $timeWindow;
        });

        if (count($attempts) >= $maxAttempts) {
            return false;
        }

        // Record this attempt
        $attempts[] = $currentTime;
        $session->set($key, $attempts);

        return true;
    }

    /**
     * Generate secure random token
     */
    public static function generateToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Hash password securely
     */
    public static function hashPassword(string $password): string
    {
        // Use ARGON2ID if available, otherwise fall back to BCRYPT
        $algorithm = defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_BCRYPT;
        return password_hash($password, $algorithm);
    }

    /**
     * Verify password
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Validate CSRF token from form
     */
    public static function validateCsrfFromPost(): bool
    {
        $session = new Session();
        $token = $_POST['csrf_token'] ?? '';
        return $session->validateCsrfToken($token);
    }

    /**
     * Log security event
     */
    public static function logSecurityEvent(string $event, array $context = []): void
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event' => $event,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'context' => $context
        ];

        error_log('SECURITY: ' . json_encode($logEntry));
    }
}