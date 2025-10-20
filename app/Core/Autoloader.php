<?php

namespace Core;

/**
 * PSR-4 Autoloader for the application
 */
class Autoloader
{
    private array $prefixes = [];

    public function __construct()
    {
        // Register namespace prefixes
        $this->addNamespace('Core\\', APP_ROOT . '/app/Core/');
        $this->addNamespace('Controllers\\', APP_ROOT . '/app/Controllers/');
        $this->addNamespace('Models\\', APP_ROOT . '/app/Models/');
    }

    /**
     * Register the autoloader
     */
    public function register(): void
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * Add a namespace prefix
     */
    public function addNamespace(string $prefix, string $baseDir): void
    {
        // Normalize namespace prefix
        $prefix = trim($prefix, '\\') . '\\';
        
        // Normalize base directory with trailing separator
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . '/';
        
        // Initialize the namespace prefix array
        if (!isset($this->prefixes[$prefix])) {
            $this->prefixes[$prefix] = [];
        }
        
        // Add the base directory
        array_push($this->prefixes[$prefix], $baseDir);
    }

    /**
     * Load the class file for a given class name
     */
    public function loadClass(string $class): bool
    {
        // Work backwards through the namespace names to find a match
        $prefix = $class;
        while (false !== $pos = strrpos($prefix, '\\')) {
            // Retain the trailing namespace separator in the prefix
            $prefix = substr($class, 0, $pos + 1);
            
            // The rest is the relative class name
            $relativeClass = substr($class, $pos + 1);
            
            // Try to load a mapped file for the prefix and relative class
            $mappedFile = $this->loadMappedFile($prefix, $relativeClass);
            if ($mappedFile) {
                return $mappedFile;
            }
            
            // Remove the trailing namespace separator for the next iteration
            $prefix = rtrim($prefix, '\\');
        }
        
        return false;
    }

    /**
     * Load the mapped file for a namespace prefix and relative class
     */
    protected function loadMappedFile(string $prefix, string $relativeClass): bool
    {
        // Are there any base directories for this namespace prefix?
        if (!isset($this->prefixes[$prefix])) {
            return false;
        }
        
        // Look through base directories for this namespace prefix
        foreach ($this->prefixes[$prefix] as $baseDir) {
            // Replace namespace separators with directory separators
            // in the relative class name, append with .php
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            
            // If the mapped file exists, require it
            if ($this->requireFile($file)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Require a file if it exists
     */
    protected function requireFile(string $file): bool
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}