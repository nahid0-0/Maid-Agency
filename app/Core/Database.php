<?php

namespace Core;

use PDO;
use PDOException;
use Exception;

/**
 * Database connection and query management using PDO
 */
class Database
{
    private static ?PDO $connection = null;
    private static ?Database $instance = null;

    private function __construct()
    {
        $this->connect();
    }

    /**
     * Get singleton instance
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get PDO connection
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            self::getInstance();
        }
        return self::$connection;
    }

    /**
     * Establish database connection
     */
    private function connect(): void
    {
        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                DB_HOST,
                DB_PORT,
                DB_NAME,
                DB_CHARSET
            );

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET,
            ];

            if (defined('DB_SSL') && DB_SSL) {
                $ca = defined('DB_SSL_CA') && !empty(DB_SSL_CA) ? DB_SSL_CA : null;
                if (!$ca) {
                    if (file_exists('/etc/ssl/certs/ca-certificates.crt')) {
                        $ca = '/etc/ssl/certs/ca-certificates.crt';
                    } elseif (file_exists('/etc/ssl/cert.pem')) {
                        $ca = '/etc/ssl/cert.pem';
                    }
                }
                if ($ca) {
                    $options[PDO::MYSQL_ATTR_SSL_CA] = $ca;
                }
                $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
            }

            self::$connection = new PDO($dsn, DB_USER, DB_PASS, $options);
            
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection failed");
        }
    }

    /**
     * Execute a prepared statement with parameters
     */
    public function query(string $sql, array $params = []): \PDOStatement
    {
        try {
            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Database query failed: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Params: " . json_encode($params));
            throw new Exception("Database query failed");
        }
    }

    /**
     * Get single row from query
     */
    public function fetch(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Get all rows from query
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Execute insert and return last insert ID
     */
    public function insert(string $sql, array $params = []): int
    {
        $this->query($sql, $params);
        return (int) self::getConnection()->lastInsertId();
    }

    /**
     * Execute update/delete and return affected rows
     */
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Begin transaction
     */
    public function beginTransaction(): bool
    {
        return self::getConnection()->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commit(): bool
    {
        return self::getConnection()->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback(): bool
    {
        return self::getConnection()->rollBack();
    }

    /**
     * Check if in transaction
     */
    public function inTransaction(): bool
    {
        return self::getConnection()->inTransaction();
    }

    /**
     * Prevent cloning
     */
    private function __clone() {}

    /**
     * Prevent unserialization
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}