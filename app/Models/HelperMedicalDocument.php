<?php

namespace Models;

use Core\Database;
use PDO;

/**
 * Helper Medical Document Model
 * Handles medical certificates, health records, and vaccination documents
 */
class HelperMedicalDocument extends BaseModel
{
    protected string $table = 'helper_medical_documents';
    
    /**
     * Create a new medical document record
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} 
                (helper_id, document_type, record_date, doctor_name, clinic_hospital, 
                 s3_url, s3_key, original_filename, file_size, mime_type, status, expiry_date, notes) 
                VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        return $this->db->insert($sql, [
            $data['helper_id'],
            $data['document_type'],
            $data['record_date'],
            $data['doctor_name'] ?? null,
            $data['clinic_hospital'] ?? null,
            $data['s3_url'],
            $data['s3_key'],
            $data['original_filename'],
            $data['file_size'],
            $data['mime_type'],
            $data['status'] ?? 'Pending Review',
            $data['expiry_date'] ?? null,
            $data['notes'] ?? null
        ]);
    }
    
    /**
     * Get all medical documents for a helper
     */
    public function getByHelperId(int $helperId): array
    {
        $sql = "SELECT hmd.*, s.name as reviewed_by_name 
                FROM {$this->table} hmd 
                LEFT JOIN staff s ON hmd.reviewed_by = s.id 
                WHERE hmd.helper_id = ? 
                ORDER BY hmd.record_date DESC, hmd.created_at DESC";
        
        return $this->db->fetchAll($sql, [$helperId]);
    }
    
    /**
     * Get documents by status for staff review
     */
    public function getByStatus(string $status): array
    {
        $sql = "SELECT hmd.*, h.name as helper_name, s.name as reviewed_by_name 
                FROM {$this->table} hmd 
                JOIN helpers h ON hmd.helper_id = h.id 
                LEFT JOIN staff s ON hmd.reviewed_by = s.id 
                WHERE hmd.status = ? 
                ORDER BY hmd.created_at ASC";
        
        return $this->db->fetchAll($sql, [$status]);
    }
    
    /**
     * Get medical documents by type
     */
    public function getByType(string $documentType, ?int $helperId = null): array
    {
        $sql = "SELECT hmd.*, h.name as helper_name, s.name as reviewed_by_name 
                FROM {$this->table} hmd 
                JOIN helpers h ON hmd.helper_id = h.id 
                LEFT JOIN staff s ON hmd.reviewed_by = s.id 
                WHERE hmd.document_type = ?";
        
        $params = [$documentType];
        
        if ($helperId) {
            $sql .= " AND hmd.helper_id = ?";
            $params[] = $helperId;
        }
        
        $sql .= " ORDER BY hmd.record_date DESC";
        
        return $this->db->fetchAll($sql, $params);
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
     * Get medical documents expiring soon (within specified days)
     */
    public function getExpiringDocuments(int $days = 30): array
    {
        $sql = "SELECT hmd.*, h.name as helper_name 
                FROM {$this->table} hmd 
                JOIN helpers h ON hmd.helper_id = h.id 
                WHERE hmd.expiry_date IS NOT NULL 
                AND hmd.expiry_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
                AND hmd.expiry_date >= CURDATE()
                AND hmd.status = 'Approved'
                ORDER BY hmd.expiry_date ASC";
        
        return $this->db->fetchAll($sql, [$days]);
    }
    
    /**
     * Get latest medical clearance for a helper
     */
    public function getLatestMedicalClearance(int $helperId): ?array
    {
        $sql = "SELECT hmd.*, s.name as reviewed_by_name 
                FROM {$this->table} hmd 
                LEFT JOIN staff s ON hmd.reviewed_by = s.id 
                WHERE hmd.helper_id = ? 
                AND hmd.document_type = 'Medical Clearance' 
                AND hmd.status = 'Approved'
                ORDER BY hmd.record_date DESC 
                LIMIT 1";
        
        return $this->db->fetch($sql, [$helperId]);
    }
    
    /**
     * Check if helper has valid medical clearance
     */
    public function hasValidMedicalClearance(int $helperId): bool
    {
        $clearance = $this->getLatestMedicalClearance($helperId);
        
        if (!$clearance) {
            return false;
        }
        
        // Check if not expired (if expiry date is set)
        if ($clearance['expiry_date']) {
            return strtotime($clearance['expiry_date']) > time();
        }
        
        // If no expiry date, consider valid if approved within last year
        return strtotime($clearance['record_date']) > strtotime('-1 year');
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
     * Get all documents with helper information
     */
    public function getAllWithHelperInfo(): array
    {
        $sql = "SELECT hmd.*, h.name as helper_name, h.nationality, s.name as reviewed_by_name 
                FROM {$this->table} hmd 
                JOIN helpers h ON hmd.helper_id = h.id 
                LEFT JOIN staff s ON hmd.reviewed_by = s.id 
                ORDER BY hmd.created_at DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get document by ID with helper info
     */
    public function getWithHelperInfo(int $id): ?array
    {
        $sql = "SELECT hmd.*, h.name as helper_name, h.nationality, s.name as reviewed_by_name 
                FROM {$this->table} hmd 
                JOIN helpers h ON hmd.helper_id = h.id 
                LEFT JOIN staff s ON hmd.reviewed_by = s.id 
                WHERE hmd.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
}
