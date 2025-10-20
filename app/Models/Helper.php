<?php

namespace Models;

use PDO;

/**
 * Helper Model
 * Handles maid/helper authentication and profile management
 */
class Helper extends BaseModel
{
    protected string $table = 'helpers';
    
    protected array $fillable = [
        'name', 'email', 'password_hash', 'nationality', 'date_of_birth', 'age', 'gender', 
        'contact_number', 'emergency_contact', 'experience_level', 'previous_experience',
        'status', 'is_active', 'special_skills', 'salary_min', 'salary_max', 'dietary_restrictions',
        'medical_conditions', 'profile_picture_url', 'profile_picture_key'
    ];
    
    /**
     * Find helper by email
     */
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
        return $this->db->fetch($sql, [$email]);
    }
    
    /**
     * Create new helper with authentication
     */
    public function createWithAuth(array $data): int
    {
        // Calculate age from date of birth
        if (isset($data['date_of_birth'])) {
            $dob = new \DateTime($data['date_of_birth']);
            $today = new \DateTime();
            $data['age'] = $today->diff($dob)->y;
        }
        
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']); // Remove plain password
        }
        
        $sql = "INSERT INTO {$this->table} 
                (name, email, password_hash, nationality, date_of_birth, age, gender, 
                 contact_number, emergency_contact, experience_level, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        return $this->db->insert($sql, [
            $data['name'],
            $data['email'],
            $data['password_hash'],
            $data['nationality'],
            $data['date_of_birth'],
            $data['age'],
            $data['gender'],
            $data['contact_number'] ?? null,
            $data['emergency_contact'] ?? null,
            $data['experience_level'],
            $data['is_active'] ?? true
        ]);
    }
    
    /**
     * Update last login time
     */
    public function updateLastLogin(int $id): bool
    {
        $sql = "UPDATE {$this->table} SET last_login = NOW() WHERE id = ?";
        return $this->db->execute($sql, [$id]) > 0;
    }
    
    /**
     * Check if email exists
     */
    public function emailExists(string $email): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = ?";
        $result = $this->db->fetch($sql, [$email]);
        return $result['count'] > 0;
    }
    
    /**
     * Authenticate helper
     */
    public function authenticate(string $email, string $password): ?array
    {
        $helper = $this->findByEmail($email);

        if (!$helper) {
            return null;
        }

        if (!$helper['is_active']) {
            throw new \Exception('Account is inactive. Please contact support.');
        }

        // Check if password hash exists
        if (empty($helper['password_hash'])) {
            return null;
        }

        if (!password_verify($password, $helper['password_hash'])) {
            return null;
        }

        // Update last login
        $this->updateLastLogin($helper['id']);

        return $helper;
    }
    
    /**
     * Get unique values for a specific column
     */
    public function getUniqueValues(string $column): array
    {
        $sql = "SELECT DISTINCT {$column} as {$column} FROM {$this->table} 
                WHERE {$column} IS NOT NULL AND {$column} != '' 
                ORDER BY {$column}";
        
        try {
            return $this->db->fetchAll($sql);
        } catch (\Exception $e) {
            error_log("Error fetching unique values for {$column}: " . $e->getMessage());
            return [];
        }
    }
}
