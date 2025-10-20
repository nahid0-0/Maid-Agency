<?php
/**
 * Migration: Add/Update Profile Picture Columns in Helpers Table
 * 
 * This script:
 * 1. Renames photo_path to profile_picture_url
 * 2. Increases the size to varchar(500) to match document tables
 * 3. Adds profile_picture_key column for S3 key storage
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
    
    echo "Starting profile picture columns migration...\n\n";
    
    // Check if photo_path column exists
    $columns = $db->fetchAll("DESCRIBE helpers");
    $hasPhotoPath = false;
    $hasProfilePictureUrl = false;
    $hasProfilePictureKey = false;
    
    foreach ($columns as $column) {
        if ($column['Field'] === 'photo_path') {
            $hasPhotoPath = true;
        }
        if ($column['Field'] === 'profile_picture_url') {
            $hasProfilePictureUrl = true;
        }
        if ($column['Field'] === 'profile_picture_key') {
            $hasProfilePictureKey = true;
        }
    }
    
    echo "Current state:\n";
    echo "- photo_path exists: " . ($hasPhotoPath ? 'YES' : 'NO') . "\n";
    echo "- profile_picture_url exists: " . ($hasProfilePictureUrl ? 'YES' : 'NO') . "\n";
    echo "- profile_picture_key exists: " . ($hasProfilePictureKey ? 'YES' : 'NO') . "\n\n";
    
    // Step 1: If we have photo_path but not profile_picture_url, rename and resize it
    if ($hasPhotoPath && !$hasProfilePictureUrl) {
        echo "Step 1: Renaming photo_path to profile_picture_url and increasing size...\n";
        $sql = "ALTER TABLE helpers 
                CHANGE COLUMN photo_path profile_picture_url VARCHAR(500) NULL 
                COMMENT 'S3 URL for profile picture'";
        $db->execute($sql);
        echo "✓ Renamed photo_path to profile_picture_url (varchar(500))\n\n";
    } elseif (!$hasProfilePictureUrl) {
        echo "Step 1: Adding profile_picture_url column...\n";
        $sql = "ALTER TABLE helpers 
                ADD COLUMN profile_picture_url VARCHAR(500) NULL 
                COMMENT 'S3 URL for profile picture'";
        $db->execute($sql);
        echo "✓ Added profile_picture_url column (varchar(500))\n\n";
    } else {
        echo "Step 1: profile_picture_url already exists, checking size...\n";
        $columnInfo = $db->fetch("SHOW COLUMNS FROM helpers WHERE Field = 'profile_picture_url'");
        if (strpos($columnInfo['Type'], 'varchar(255)') !== false) {
            echo "Resizing profile_picture_url from varchar(255) to varchar(500)...\n";
            $sql = "ALTER TABLE helpers 
                    MODIFY COLUMN profile_picture_url VARCHAR(500) NULL 
                    COMMENT 'S3 URL for profile picture'";
            $db->execute($sql);
            echo "✓ Resized profile_picture_url to varchar(500)\n\n";
        } else {
            echo "✓ profile_picture_url size is already appropriate\n\n";
        }
    }
    
    // Step 2: Add profile_picture_key column if it doesn't exist
    if (!$hasProfilePictureKey) {
        echo "Step 2: Adding profile_picture_key column...\n";
        $sql = "ALTER TABLE helpers 
                ADD COLUMN profile_picture_key VARCHAR(255) NULL 
                COMMENT 'S3 key for profile picture (for deletion)'";
        $db->execute($sql);
        echo "✓ Added profile_picture_key column (varchar(255))\n\n";
    } else {
        echo "Step 2: profile_picture_key already exists\n\n";
    }
    
    // Step 3: Show final table structure for profile picture columns
    echo "Final profile picture columns in helpers table:\n";
    $result = $db->query("SHOW COLUMNS FROM helpers WHERE Field IN ('profile_picture_url', 'profile_picture_key')");
    while ($row = $result->fetch()) {
        echo "- " . $row['Field'] . ": " . $row['Type'] . " (" . $row['Null'] . ")\n";
    }
    
    echo "\n✅ Migration completed successfully!\n";
    echo "\nNext steps:\n";
    echo "1. Update Helper model to include new column names\n";
    echo "2. Update MaidController to use profile_picture_url and profile_picture_key\n";
    echo "3. Update profile view to use new column name\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
