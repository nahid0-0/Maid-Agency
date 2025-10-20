<?php
/**
 * Migration: Add Profile Picture Columns to Clients Table
 * 
 * This script adds profile picture support to the clients table:
 * 1. Adds profile_picture_url column (varchar(500)) for S3 URL storage
 * 2. Adds profile_picture_key column (varchar(255)) for S3 key storage
 */

// Define application root
define('APP_ROOT', dirname(__DIR__));

// Load configuration and autoloader
require_once APP_ROOT . '/app/config.php';
require_once APP_ROOT . '/app/Core/Autoloader.php';

// Initialize autoloader
$autoloader = new Core\Autoloader();
$autoloader->register();

use Core\Database;

try {
    $db = Database::getInstance();
    
    echo "Starting client profile picture columns migration...\n\n";
    
    // Check if columns already exist
    $columns = $db->fetchAll("DESCRIBE clients");
    $hasProfilePictureUrl = false;
    $hasProfilePictureKey = false;
    
    foreach ($columns as $column) {
        if ($column['Field'] === 'profile_picture_url') {
            $hasProfilePictureUrl = true;
        }
        if ($column['Field'] === 'profile_picture_key') {
            $hasProfilePictureKey = true;
        }
    }
    
    echo "Current state:\n";
    echo "- profile_picture_url exists: " . ($hasProfilePictureUrl ? 'YES' : 'NO') . "\n";
    echo "- profile_picture_key exists: " . ($hasProfilePictureKey ? 'YES' : 'NO') . "\n\n";
    
    // Step 1: Add profile_picture_url column if it doesn't exist
    if (!$hasProfilePictureUrl) {
        echo "Step 1: Adding profile_picture_url column...\n";
        $sql = "ALTER TABLE clients 
                ADD COLUMN profile_picture_url VARCHAR(500) NULL 
                COMMENT 'S3 URL for client profile picture'";
        $db->execute($sql);
        echo "✓ Added profile_picture_url column (varchar(500))\n\n";
    } else {
        echo "Step 1: profile_picture_url already exists\n\n";
    }
    
    // Step 2: Add profile_picture_key column if it doesn't exist
    if (!$hasProfilePictureKey) {
        echo "Step 2: Adding profile_picture_key column...\n";
        $sql = "ALTER TABLE clients 
                ADD COLUMN profile_picture_key VARCHAR(255) NULL 
                COMMENT 'S3 key for client profile picture (for deletion)'";
        $db->execute($sql);
        echo "✓ Added profile_picture_key column (varchar(255))\n\n";
    } else {
        echo "Step 2: profile_picture_key already exists\n\n";
    }
    
    echo "Migration completed successfully!\n";
    echo "Clients table now supports profile pictures with S3 integration.\n";
    
} catch (Exception $e) {
    echo "Error during migration: " . $e->getMessage() . "\n";
    echo "Please check your database connection and try again.\n";
}

