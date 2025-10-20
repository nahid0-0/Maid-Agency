<?php

namespace Models;

/**
 * Medical Record model for helper medical tracking
 */
class MedicalRecord extends BaseModel
{
    protected string $table = 'helper_medical_documents';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'helper_id', 'document_type', 'record_date', 'doctor_name', 'clinic_hospital',
        'status', 'notes', 'expiry_date', 's3_url', 's3_key', 'original_filename',
        'file_size', 'mime_type', 'reviewed_by', 'reviewed_at', 'review_notes'
    ];

    /**
     * Get medical records for a helper
     */
    public function getByHelperId(int $helperId): array
    {
        $records = $this->db->fetchAll(
            'SELECT mr.*, h.name as helper_name
             FROM helper_medical_documents mr
             JOIN helpers h ON mr.helper_id = h.id
             WHERE mr.helper_id = ?
             ORDER BY mr.record_date DESC',
            [$helperId]
        );
        return $records ?: [];
    }

    /**
     * Get all medical records with helper info
     */
    public function getAllWithHelperInfo(): array
    {
        $records = $this->db->fetchAll(
            'SELECT mr.*, h.name as helper_name, h.nationality,
             mr.document_type as record_type
             FROM helper_medical_documents mr
             JOIN helpers h ON mr.helper_id = h.id
             ORDER BY mr.record_date DESC'
        );
        return $records ?: [];
    }

    /**
     * Get records expiring soon
     */
    public function getExpiringRecords(int $days = 30): array
    {
        $records = $this->db->fetchAll(
            'SELECT mr.*, h.name as helper_name,
             mr.document_type as record_type
             FROM helper_medical_documents mr
             JOIN helpers h ON mr.helper_id = h.id
             WHERE mr.expiry_date IS NOT NULL
             AND mr.expiry_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
             AND mr.expiry_date >= CURDATE()
             ORDER BY mr.expiry_date ASC',
            [$days]
        );
        return $records ?: [];
    }

    /**
     * Get medical record statistics
     */
    public function getStatistics(): array
    {
        $stats = $this->db->fetch(
            'SELECT
                COUNT(*) as total_records,
                SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) as fit_count,
                SUM(CASE WHEN status = "Rejected" THEN 1 ELSE 0 END) as unfit_count,
                SUM(CASE WHEN status = "Pending Review" THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND expiry_date >= CURDATE() THEN 1 ELSE 0 END) as expiring_soon
             FROM helper_medical_documents'
        );
        return $stats ?: [];
    }

    /**
     * Get latest medical record for helper
     */
    public function getLatestByHelperId(int $helperId): ?array
    {
        return $this->db->fetch(
            'SELECT * FROM helper_medical_documents
             WHERE helper_id = ?
             ORDER BY record_date DESC
             LIMIT 1',
            [$helperId]
        );
    }
}
