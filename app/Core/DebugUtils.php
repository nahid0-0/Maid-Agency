<?php

/**
 * Debug and Development Utilities
 * Only functions in development/testing environments
 */
class DebugUtils
{
    /**
     * Check if application is in debug mode
     */
    public static function isDebugMode(): bool
    {
        return ($_ENV['APP_DEBUG'] ?? false) === 'true' && 
               in_array($_ENV['APP_ENV'] ?? 'production', ['development', 'testing']);
    }
    
    /**
     * Check if application is in development environment
     */
    public static function isDevelopment(): bool
    {
        return ($_ENV['APP_ENV'] ?? 'production') === 'development';
    }
    
    /**
     * Safe debug output - only shows in development
     */
    public static function dump($data, $die = false): void
    {
        if (!self::isDebugMode()) {
            return;
        }
        
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        
        if ($die) {
            die();
        }
    }
    
    /**
     * Safe print_r - only shows in development
     */
    public static function printR($data, $return = false): string
    {
        if (!self::isDebugMode()) {
            return '';
        }
        
        $output = '<pre>' . print_r($data, true) . '</pre>';
        
        if ($return) {
            return $output;
        }
        
        echo $output;
        return '';
    }
    
    /**
     * Log debug information safely
     */
    public static function log($message, $data = null): void
    {
        if (!self::isDebugMode()) {
            return;
        }
        
        $logFile = APP_ROOT . '/logs/debug.log';
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] {$message}";
        
        if ($data !== null) {
            $logEntry .= " | Data: " . json_encode($data, JSON_PRETTY_PRINT);
        }
        
        $logEntry .= "\n";
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Block access to debug/test functionality in production
     */
    public static function requireDebugMode(): void
    {
        if (!self::isDebugMode()) {
            http_response_code(404);
            die('Page not found');
        }
    }
}