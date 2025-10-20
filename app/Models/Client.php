<?php

namespace Models;

use PDO;

/**
 * Client Model
 * Handles client profile and account management
 */
class Client extends BaseModel
{
    protected string $table = 'clients';
    
    protected array $fillable = [
        'name', 'email', 'phone', 'address', 'family_size', 'children_ages',
        'special_requirements', 'budget_min', 'budget_max', 'preferred_nationality', 'status',
        'profile_picture_url', 'profile_picture_key', 'password_hash', 'is_active'
    ];
    
    /**
     * Find client by email
     */
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
        return $this->db->fetch($sql, [$email]);
    }
    
    /**
     * Authenticate client with email and password
     */
    public function authenticate(string $email, string $password): ?array
    {
        $client = $this->db->fetch(
            'SELECT * FROM clients WHERE email = ? AND is_active = 1', 
            [$email]
        );

        if ($client && password_verify($password, $client['password_hash'])) {
            // Remove password hash from returned data
            unset($client['password_hash']);
            return $client;
        }

        return null;
    }
    
    /**
     * Create new client
     */
    public function createClient(array $data): int
    {
        $sql = "INSERT INTO {$this->table} 
                (name, email, phone, address, family_size, children_ages, 
                 special_requirements, budget_min, budget_max, preferred_nationality, 
                 status, password_hash, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        return $this->db->insert($sql, [
            $data['name'],
            $data['email'],
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $data['family_size'] ?? null,
            $data['children_ages'] ?? null,
            $data['special_requirements'] ?? null,
            $data['budget_min'] ?? null,
            $data['budget_max'] ?? null,
            $data['preferred_nationality'] ?? null,
            $data['status'] ?? 'Active',
            $data['password_hash'] ?? null,
            $data['is_active'] ?? true
        ]);
    }
    
    /**
     * Update client profile
     */
    public function updateProfile(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table} SET 
                name = ?, email = ?, phone = ?, address = ?, family_size = ?, 
                children_ages = ?, special_requirements = ?, budget_min = ?, 
                budget_max = ?, preferred_nationality = ?, status = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?";
        
        return $this->db->execute($sql, [
            $data['name'],
            $data['email'],
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $data['family_size'] ?? null,
            $data['children_ages'] ?? null,
            $data['special_requirements'] ?? null,
            $data['budget_min'] ?? null,
            $data['budget_max'] ?? null,
            $data['preferred_nationality'] ?? null,
            $data['status'] ?? 'Active',
            $id
        ]);
    }
    
    /**
     * Get client with statistics
     */
    public function getWithStats(int $id): ?array
    {
        $sql = "SELECT c.*, 
                (SELECT COUNT(*) FROM requests WHERE client_id = c.id) as total_requests,
                (SELECT COUNT(*) FROM requests WHERE client_id = c.id AND status = 'Completed') as completed_requests,
                (SELECT COUNT(*) FROM requests WHERE client_id = c.id AND status IN ('Pending', 'In Progress')) as active_requests
                FROM {$this->table} c 
                WHERE c.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Search clients by criteria
     */
    public function search(array $criteria = []): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];
        
        if (!empty($criteria['name'])) {
            $sql .= " AND name LIKE ?";
            $params[] = '%' . $criteria['name'] . '%';
        }
        
        if (!empty($criteria['status'])) {
            $sql .= " AND status = ?";
            $params[] = $criteria['status'];
        }
        
        $sql .= " ORDER BY name ASC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get fillable fields (for testing)
     */
    public function getFillable(): array
    {
        return $this->fillable;
    }
}
