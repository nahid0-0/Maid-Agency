<?php

namespace Models;

use Core\Database;
use PDO;

/**
 * Helper Training Document Model
 * Handles training certificates, skill certifications, and course completions
 */
class HelperTrainingDocument extends BaseModel
{
    protected string $table = 'helper_training_documents';
    
    /**
     * Create a new training document record
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} 
                (helper_id, document_type, course_name, training_provider, certificate_number, 
                 issue_date, expiry_date, s3_url, s3_key, original_filename, file_size, mime_type, status, notes) 
                VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        return $this->db->insert($sql, [
            $data['helper_id'],
            $data['document_type'],
            $data['course_name'],
            $data['training_provider'] ?? null,
            $data['certificate_number'] ?? null,
            $data['issue_date'] ?? null,
            $data['expiry_date'] ?? null,
            $data['s3_url'],
            $data['s3_key'],
            $data['original_filename'],
            $data['file_size'],
            $data['mime_type'],
            $data['status'] ?? 'Pending Review',
            $data['notes'] ?? null
        ]);
    }
    
    /**
     * Get all training documents for a helper
     */
    public function getByHelperId(int $helperId): array
    {
        $sql = "SELECT htd.*, s.name as reviewed_by_name 
                FROM {$this->table} htd 
                LEFT JOIN staff s ON htd.reviewed_by = s.id 
                WHERE htd.helper_id = ? 
                ORDER BY htd.issue_date DESC, htd.created_at DESC";
        
        return $this->db->fetchAll($sql, [$helperId]);
    }
    
    /**
     * Get documents by status for staff review
     */
    public function getByStatus(string $status): array
    {
        $sql = "SELECT htd.*, h.name as helper_name, s.name as reviewed_by_name 
                FROM {$this->table} htd 
                JOIN helpers h ON htd.helper_id = h.id 
                LEFT JOIN staff s ON htd.reviewed_by = s.id 
                WHERE htd.status = ? 
                ORDER BY htd.created_at ASC";
        
        return $this->db->fetchAll($sql, [$status]);
    }
    
    /**
     * Get training documents by type
     */
    public function getByType(string $documentType, ?int $helperId = null): array
    {
        $sql = "SELECT htd.*, h.name as helper_name, s.name as reviewed_by_name 
                FROM {$this->table} htd 
                JOIN helpers h ON htd.helper_id = h.id 
                LEFT JOIN staff s ON htd.reviewed_by = s.id 
                WHERE htd.document_type = :document_type";
        
        $params = [':document_type' => $documentType];
        
        if ($helperId) {
            $sql .= " AND htd.helper_id = :helper_id";
            $params[':helper_id'] = $helperId;
        }
        
        $sql .= " ORDER BY htd.issue_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get training documents by course name
     */
    public function getByCourseName(string $courseName, ?int $helperId = null): array
    {
        $sql = "SELECT htd.*, h.name as helper_name, s.name as reviewed_by_name 
                FROM {$this->table} htd 
                JOIN helpers h ON htd.helper_id = h.id 
                LEFT JOIN staff s ON htd.reviewed_by = s.id 
                WHERE htd.course_name LIKE ?";
        
        $params = ["%{$courseName}%"];
        
        if ($helperId) {
            $sql .= " AND htd.helper_id = ?";
            $params[] = $helperId;
        }
        
        $sql .= " ORDER BY htd.issue_date DESC";
        
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
     * Get training documents expiring soon (within specified days)
     */
    public function getExpiringDocuments(int $days = 30): array
    {
        $sql = "SELECT htd.*, h.name as helper_name 
                FROM {$this->table} htd 
                JOIN helpers h ON htd.helper_id = h.id 
                WHERE htd.expiry_date IS NOT NULL 
                AND htd.expiry_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
                AND htd.expiry_date >= CURDATE()
                AND htd.status = 'Approved'
                ORDER BY htd.expiry_date ASC";
        
        return $this->db->fetchAll($sql, [$days]);
    }
    
    /**
     * Get helper's certifications summary
     */
    public function getHelperCertificationsSummary(int $helperId): array
    {
        $sql = "SELECT 
                    document_type,
                    COUNT(*) as total_certificates,
                    COUNT(CASE WHEN status = 'Approved' THEN 1 END) as approved_certificates,
                    COUNT(CASE WHEN status = 'Pending Review' THEN 1 END) as pending_certificates,
                    COUNT(CASE WHEN expiry_date IS NOT NULL AND expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 1 END) as expiring_soon
                FROM {$this->table} 
                WHERE helper_id = ? 
                GROUP BY document_type
                ORDER BY document_type";
        
        return $this->db->fetchAll($sql, [$helperId]);
    }
    
    /**
     * Check if helper has specific certification
     */
    public function hasValidCertification(int $helperId, string $courseName): bool
    {
        $sql = "SELECT id FROM {$this->table} 
                WHERE helper_id = ? 
                AND course_name LIKE ? 
                AND status = 'Approved'
                AND (expiry_date IS NULL OR expiry_date > CURDATE())
                LIMIT 1";
        
        $result = $this->db->fetch($sql, [$helperId, "%{$courseName}%"]);
        return $result !== null;
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
        $sql = "SELECT htd.*, h.name as helper_name, h.nationality, s.name as reviewed_by_name 
                FROM {$this->table} htd 
                JOIN helpers h ON htd.helper_id = h.id 
                LEFT JOIN staff s ON htd.reviewed_by = s.id 
                ORDER BY htd.created_at DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get document by ID with helper info
     */
    public function getWithHelperInfo(int $id): ?array
    {
        $sql = "SELECT htd.*, h.name as helper_name, h.nationality, s.name as reviewed_by_name 
                FROM {$this->table} htd 
                JOIN helpers h ON htd.helper_id = h.id 
                LEFT JOIN staff s ON htd.reviewed_by = s.id 
                WHERE htd.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
}
