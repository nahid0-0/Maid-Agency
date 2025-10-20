<?php

class EnvLoader {
    private static $loaded = false;
    
    public static function load($envFile = '.env') {
        if (self::$loaded) {
            return;
        }
        
        // Try multiple possible locations for the .env file
        $possiblePaths = [
            __DIR__ . '/../../' . $envFile,  // Project root
            $_SERVER['DOCUMENT_ROOT'] . '/' . $envFile,  // Document root
            getcwd() . '/' . $envFile,  // Current working directory
        ];
        
        $envPath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $envPath = $path;
                break;
            }
        }
        
        if (!$envPath) {
            throw new Exception(".env file not found. Searched in: " . implode(', ', $possiblePaths));
        }
        
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // Parse key=value pairs
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remove quotes if present
                if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                    (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                    $value = substr($value, 1, -1);
                }
                
                // Set environment variable
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
        
        self::$loaded = true;
    }
    
    public static function get($key, $default = null) {
        $value = $_ENV[$key] ?? getenv($key) ?: $default;

        // Debug logging for AWS credentials (in development only)
        if (strpos($key, 'AWS_') === 0 && defined('APP_DEBUG') && APP_DEBUG) {
            if (empty($value) && $default === null) {
                error_log("EnvLoader: Missing environment variable '{$key}'");
            }
        }

        return $value;
    }

    /**
     * Check if all required AWS credentials are present
     */
    public static function hasAwsCredentials() {
        $required = ['AWS_ACCESS_KEY_ID', 'AWS_SECRET_ACCESS_KEY', 'S3_BUCKET'];

        foreach ($required as $key) {
            $value = self::get($key);
            if (empty($value)) {
                return false;
            }
        }

        return true;
    }
}