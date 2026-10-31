<?php
/**
 * Database Creation Script
 * This script will create the database and tables
 */

// Define application root
define('APP_ROOT', dirname(__DIR__));

echo "Maid Agency Management System - Database Setup\n";
echo "=============================================\n\n";

// Load environment variables if available
if (file_exists(APP_ROOT . '/app/Core/EnvLoader.php')) {
    require_once APP_ROOT . '/app/Core/EnvLoader.php';
    EnvLoader::load();
}

$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'root';

// First, try to connect to MySQL without specifying a database
try {
    try {
        $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $tcpEx) {
        // Fallback to XAMPP socket if TCP fails
        $dsn = 'mysql:unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock;charset=utf8mb4';
        $pdo = new PDO($dsn, 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    
    echo "✅ Connected to MySQL server\n";
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS maid_agency CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database 'maid_agency' created/verified\n";
    
    // Switch to the database
    $pdo->exec("USE maid_agency");
    echo "✅ Using database 'maid_agency'\n\n";
    
    // Read and execute the schema SQL
    $schemaSql = file_get_contents(__DIR__ . '/setup-database.sql');
    if (!$schemaSql) {
        throw new Exception("Could not read setup-database.sql file");
    }
    
    // Split SQL into individual statements and execute
    $statements = array_filter(array_map('trim', explode(';', $schemaSql)));
    
    echo "Creating database schema...\n";
    foreach ($statements as $statement) {
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        try {
            $pdo->exec($statement);
        } catch (PDOException $e) {
            // Skip if table already exists or other non-critical errors
            if (strpos($e->getMessage(), 'already exists') === false) {
                echo "Warning: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "✅ Database schema created successfully\n\n";
    
    // Check if we should add sample data
    $result = $pdo->query("SELECT COUNT(*) as count FROM helpers")->fetch();
    if ($result['count'] == 0) {
        echo "Adding sample data...\n";
        
        // Read and execute sample data SQL
        $sampleSql = file_get_contents(__DIR__ . '/sample-data.sql');
        if ($sampleSql) {
            $statements = array_filter(array_map('trim', explode(';', $sampleSql)));
            
            foreach ($statements as $statement) {
                if (empty($statement) || strpos($statement, '--') === 0) {
                    continue;
                }
                
                try {
                    $pdo->exec($statement);
                } catch (PDOException $e) {
                    echo "Warning: " . $e->getMessage() . "\n";
                }
            }
            
            echo "✅ Sample data added successfully\n";
        }
    } else {
        echo "ℹ️  Sample data already exists, skipping...\n";
    }
    
    echo "\n🎉 Database setup completed successfully!\n";
    echo "\nYou can now:\n";
    echo "1. Access the application at: http://localhost/\n";
    echo "2. View the database in MySQL Workbench\n";
    echo "3. Login with: admin / admin123\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n\n";
    echo "Please ensure:\n";
    echo "1. XAMPP MySQL server is running\n";
    echo "2. MySQL is accessible on localhost:3306\n";
    echo "3. Root user has no password (default XAMPP setup)\n";
    exit(1);
}
?>
