<?php

namespace Core;

/**
 * Secure session management
 */
class Session
{
    private bool $started = false;

    /**
     * Start secure session
     */
    public function start(): void
    {
        if ($this->started || session_status() === PHP_SESSION_ACTIVE) {
            $this->started = true;
            return;
        }

        // Configure secure session settings only if session is not active
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            // Use secure cookies in production, allow insecure for development
            $isProduction = ($_ENV['APP_ENV'] ?? 'development') === 'production';
            ini_set('session.cookie_secure', $isProduction ? '1' : '0');
            ini_set('session.use_strict_mode', '1');
            ini_set('session.cookie_samesite', 'Strict');
            ini_set('session.gc_maxlifetime', SESSION_LIFETIME);

            // Set session name
            session_name('MAID_AGENCY_SESSION');
        }

        // Start session
        if (session_status() === PHP_SESSION_NONE && session_start()) {
            $this->started = true;
            
            // Regenerate session ID periodically for security
            if (!isset($_SESSION['last_regeneration'])) {
                $this->regenerate();
            } elseif (time() - $_SESSION['last_regeneration'] > 300) { // 5 minutes
                $this->regenerate();
            }
        } elseif (session_status() === PHP_SESSION_ACTIVE) {
            $this->started = true;
        }
    }

    /**
     * Regenerate session ID
     */
    public function regenerate(): void
    {
        if ($this->started) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }

    /**
     * Destroy session
     */
    public function destroy(): void
    {
        // Start session if not already started
        if (!$this->started && session_status() === PHP_SESSION_NONE) {
            $this->start();
        }
        
        // Clear session data
        $_SESSION = [];
        
        // Delete session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy the session
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        
        $this->started = false;
    }

    /**
     * Set session value
     */
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get session value
     */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if session key exists
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove session key
     */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Get CSRF token
     */
    public function getCsrfToken(): string
    {
        if (!$this->has('csrf_token')) {
            $this->set('csrf_token', bin2hex(random_bytes(32)));
        }
        return $this->get('csrf_token');
    }

    /**
     * Validate CSRF token
     */
    public function validateCsrfToken(string $token): bool
    {
        $sessionToken = $this->get('csrf_token');
        return $sessionToken && hash_equals($sessionToken, $token);
    }

    /**
     * Set flash message
     */
    public function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Get and clear flash message
     */
    public function getFlash(string $type): ?string
    {
        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        return $message;
    }

    /**
     * Check if session is started
     */
    public function isStarted(): bool
    {
        return $this->started;
    }
}