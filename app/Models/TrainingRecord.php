<?php

namespace Models;

/**
 * Training Record model for helper training tracking
 */
class TrainingRecord extends BaseModel
{
    protected string $table = 'helper_training_documents';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'helper_id', 'document_type', 'course_name', 'training_provider', 'certificate_number',
        'issue_date', 'expiry_date', 's3_url', 's3_key', 'original_filename',
        'file_size', 'mime_type', 'status', 'reviewed_by', 'reviewed_at', 'review_notes', 'notes'
    ];

    /**
     * Get training records for a helper
     */
    public function getByHelperId(int $helperId): array
    {
        $records = $this->db->fetchAll(
            'SELECT tr.*, h.name as helper_name,
             tr.issue_date as start_date,
             tr.expiry_date as certificate_expiry
             FROM helper_training_documents tr
             JOIN helpers h ON tr.helper_id = h.id
             WHERE tr.helper_id = ?
             ORDER BY tr.issue_date DESC',
            [$helperId]
        );
        return $records ?: [];
    }

    /**
     * Get all training records with helper info
     */
    public function getAllWithHelperInfo(): array
    {
        $records = $this->db->fetchAll(
            'SELECT tr.*, h.name as helper_name, h.nationality,
             tr.issue_date as start_date,
             tr.expiry_date as certificate_expiry
             FROM helper_training_documents tr
             JOIN helpers h ON tr.helper_id = h.id
             ORDER BY tr.issue_date DESC'
        );
        return $records ?: [];
    }

    /**
     * Get certificates expiring soon
     */
    public function getExpiringCertificates(int $days = 30): array
    {
        $records = $this->db->fetchAll(
            'SELECT tr.*, h.name as helper_name,
             tr.expiry_date as certificate_expiry
             FROM helper_training_documents tr
             JOIN helpers h ON tr.helper_id = h.id
             WHERE tr.expiry_date IS NOT NULL
             AND tr.expiry_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
             AND tr.expiry_date >= CURDATE()
             ORDER BY tr.expiry_date ASC',
            [$days]
        );
        return $records ?: [];
    }

    /**
     * Get training statistics
     */
    public function getStatistics(): array
    {
        $stats = $this->db->fetch(
            'SELECT
                COUNT(*) as total_records,
                SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) as completed_count,
                SUM(CASE WHEN status = "Pending Review" THEN 1 ELSE 0 END) as in_progress_count,
                SUM(CASE WHEN status = "Pending Review" THEN 1 ELSE 0 END) as enrolled_count,
                SUM(CASE WHEN expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND expiry_date >= CURDATE() THEN 1 ELSE 0 END) as expiring_soon
             FROM helper_training_documents'
        );
        return $stats ?: [];
    }

    /**
     * Get training records by course name
     */
    public function getByCourse(string $courseName): array
    {
        $records = $this->db->fetchAll(
            'SELECT tr.*, h.name as helper_name,
             tr.issue_date as start_date
             FROM helper_training_documents tr
             JOIN helpers h ON tr.helper_id = h.id
             WHERE tr.course_name LIKE ?
             ORDER BY tr.issue_date DESC',
            ["%$courseName%"]
        );
        return $records ?: [];
    }

    /**
     * Get active training records for helper
     */
    public function getActiveByHelperId(int $helperId): array
    {
        $records = $this->db->fetchAll(
            'SELECT *, issue_date as start_date
             FROM helper_training_documents
             WHERE helper_id = ?
             AND status IN ("Pending Review", "Approved")
             ORDER BY issue_date DESC',
            [$helperId]
        );
        return $records ?: [];
    }
}
