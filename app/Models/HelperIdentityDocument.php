<?php

namespace Models;

use Core\Database;
use PDO;

/**
 * Helper Identity Document Model
 * Handles passport, ID, visa, and other identity documents
 */
class HelperIdentityDocument extends BaseModel
{
    protected string $table = 'helper_identity_documents';
    
    /**
     * Create a new identity document record
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} 
                (helper_id, document_type, document_number, issuing_country, issue_date, 
                 expiry_date, s3_url, s3_key, original_filename, file_size, mime_type, status) 
                VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        return $this->db->insert($sql, [
            $data['helper_id'],
            $data['document_type'],
            $data['document_number'] ?? null,
            $data['issuing_country'] ?? null,
            $data['issue_date'] ?? null,
            $data['expiry_date'] ?? null,
            $data['s3_url'],
            $data['s3_key'],
            $data['original_filename'],
            $data['file_size'],
            $data['mime_type'],
            $data['status'] ?? 'Pending Review'
        ]);
    }
    
    /**
     * Get all identity documents for a helper
     */
    public function getByHelperId(int $helperId): array
    {
        $sql = "SELECT hid.*, s.name as reviewed_by_name 
                FROM {$this->table} hid 
                LEFT JOIN staff s ON hid.reviewed_by = s.id 
                WHERE hid.helper_id = ? 
                ORDER BY hid.created_at DESC";
        
        return $this->db->fetchAll($sql, [$helperId]);
    }
    
    /**
     * Get documents by status for staff review
     */
    public function getByStatus(string $status): array
    {
        $sql = "SELECT hid.*, h.name as helper_name, s.name as reviewed_by_name 
                FROM {$this->table} hid 
                JOIN helpers h ON hid.helper_id = h.id 
                LEFT JOIN staff s ON hid.reviewed_by = s.id 
                WHERE hid.status = ? 
                ORDER BY hid.created_at ASC";
        
        return $this->db->fetchAll($sql, [$status]);
    }
    
    /**
     * Get all documents with helper information
     */
    public function getAllWithHelperInfo(): array
    {
        $sql = "SELECT hid.*, h.name as helper_name, h.nationality, s.name as reviewed_by_name 
                FROM {$this->table} hid 
                JOIN helpers h ON hid.helper_id = h.id 
                LEFT JOIN staff s ON hid.reviewed_by = s.id 
                ORDER BY hid.created_at DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Update document review status
     */
    public function updateReviewStatus(int $id, string $status, int $reviewedBy, ?string $notes = null): bool
    {
        $sql = "UPDATE {$this->table} 
                SET status = ?, reviewed_by = ?, reviewed_at = NOW(), review_notes = ? 
                WHERE id = ?";
        
        return $this->db->execute($sql, [$status, $reviewedBy, $notes, $id]) > 0;
    }
    
    /**
     * Get documents expiring soon (within 30 days)
     */
    public function getExpiringDocuments(int $days = 30): array
    {
        $sql = "SELECT hid.*, h.name as helper_name 
                FROM {$this->table} hid 
                JOIN helpers h ON hid.helper_id = h.id 
                WHERE hid.expiry_date IS NOT NULL 
                AND hid.expiry_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
                AND hid.expiry_date >= CURDATE()
                AND hid.status = 'Approved'
                ORDER BY hid.expiry_date ASC";
        
        return $this->db->fetchAll($sql, [$days]);
    }
    
    /**
     * Delete a document record
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->execute($sql, [$id]) > 0;
    }
    
    /**
     * Get document by ID with helper info
     */
    public function getWithHelperInfo(int $id): ?array
    {
        $sql = "SELECT hid.*, h.name as helper_name, h.nationality, s.name as reviewed_by_name 
                FROM {$this->table} hid 
                JOIN helpers h ON hid.helper_id = h.id 
                LEFT JOIN staff s ON hid.reviewed_by = s.id 
                WHERE hid.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
}
