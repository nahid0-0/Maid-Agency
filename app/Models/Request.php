<?php

namespace Models;

use PDO;

/**
 * Request Model
 * Handles client requests for domestic helpers
 */
class Request extends BaseModel
{
    protected string $table = 'requests';
    
    protected array $fillable = [
        'client_id', 'helper_id', 'request_type', 'urgency', 'start_date',
        'requirements', 'status', 'notes', 'created_by'
    ];
    
    /**
     * Create new request
     */
    public function createRequest(array $data): int
    {
        $sql = "INSERT INTO {$this->table}
                (client_id, helper_id, request_type, urgency, start_date,
                 requirements, status, notes, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $data['client_id'],
            $data['helper_id'] ?? null,
            $data['request_type'] ?? 'New Hire',
            $data['urgency'] ?? 'Medium',
            $data['start_date'] ?? null,
            $data['requirements'] ?? null,
            $data['status'] ?? 'Pending',
            $data['notes'] ?? null,
            $data['created_by'] ?? null
        ];

        try {
            return $this->db->insert($sql, $params);
        } catch (\Exception $e) {
            error_log("Request creation error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get requests by client ID
     */
    public function getByClientId(int $clientId): array
    {
        $sql = "SELECT r.*, h.name as helper_name, c.name as client_name,
                s.name as created_by_name
                FROM {$this->table} r
                LEFT JOIN helpers h ON r.helper_id = h.id
                LEFT JOIN clients c ON r.client_id = c.id
                LEFT JOIN staff s ON r.created_by = s.id
                WHERE r.client_id = ?
                AND r.status != 'Cancelled'
                ORDER BY r.created_at DESC";

        return $this->db->fetchAll($sql, [$clientId]);
    }
    
    /**
     * Get requests with full details
     */
    public function getAllWithDetails(): array
    {
        $sql = "SELECT r.*, h.name as helper_name, c.name as client_name,
                s.name as created_by_name
                FROM {$this->table} r 
                LEFT JOIN helpers h ON r.helper_id = h.id
                LEFT JOIN clients c ON r.client_id = c.id
                LEFT JOIN staff s ON r.created_by = s.id
                ORDER BY r.created_at DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Update request status
     */
    public function updateStatus(int $id, string $status, ?string $notes = null): bool
    {
        $sql = "UPDATE {$this->table} SET 
                status = ?, notes = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?";
        
        return $this->db->execute($sql, [$status, $notes, $id]);
    }
    
    /**
     * Assign helper to request
     */
    public function assignHelper(int $id, int $helperId): bool
    {
        $sql = "UPDATE {$this->table} SET 
                helper_id = ?, status = 'Matched', updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?";
        
        return $this->db->execute($sql, [$helperId, $id]);
    }
    
    /**
     * Get requests by status
     */
    public function getByStatus(string $status): array
    {
        $sql = "SELECT r.*, h.name as helper_name, c.name as client_name,
                s.name as created_by_name
                FROM {$this->table} r 
                LEFT JOIN helpers h ON r.helper_id = h.id
                LEFT JOIN clients c ON r.client_id = c.id
                LEFT JOIN staff s ON r.created_by = s.id
                WHERE r.status = ?
                ORDER BY r.created_at DESC";
        
        return $this->db->fetchAll($sql, [$status]);
    }
    
    /**
     * Search requests by criteria
     */
    public function search(array $criteria = []): array
    {
        $sql = "SELECT r.*, h.name as helper_name, c.name as client_name
                FROM {$this->table} r 
                LEFT JOIN helpers h ON r.helper_id = h.id
                LEFT JOIN clients c ON r.client_id = c.id
                WHERE 1=1";
        $params = [];
        
        if (!empty($criteria['status'])) {
            $sql .= " AND r.status = ?";
            $params[] = $criteria['status'];
        }
        
        if (!empty($criteria['urgency'])) {
            $sql .= " AND r.urgency = ?";
            $params[] = $criteria['urgency'];
        }
        
        if (!empty($criteria['client_id'])) {
            $sql .= " AND r.client_id = ?";
            $params[] = $criteria['client_id'];
        }
        
        $sql .= " ORDER BY r.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get request statistics
     */
    public function getStatistics(): array
    {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN status = 'Matched' THEN 1 ELSE 0 END) as matched,
                SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled
                FROM {$this->table}";
        
        return $this->db->fetch($sql) ?: [];
    }
}
