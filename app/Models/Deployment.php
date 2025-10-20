<?php

namespace Models;

/**
 * Deployment model for helper-client deployments
 */
class Deployment extends BaseModel
{
    protected string $table = 'deployments';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'contract_id', 'helper_id', 'client_id', 'deployment_date', 'expected_end_date',
        'actual_end_date', 'status', 'performance_rating', 'client_feedback',
        'helper_feedback', 'notes'
    ];

    /**
     * Get all deployments with helper and client info
     */
    public function getAllWithInfo(): array
    {
        $deployments = $this->db->fetchAll(
            'SELECT d.*, 
                    h.name as helper_name, h.nationality, h.experience_level,
                    c.company_name, c.contact_person, c.contact_email
             FROM deployments d
             LEFT JOIN helpers h ON d.helper_id = h.id
             LEFT JOIN clients c ON d.client_id = c.id
             ORDER BY d.deployment_date DESC'
        );
        return $deployments ?: [];
    }

    /**
     * Get deployments by helper ID
     */
    public function getByHelperId(int $helperId): array
    {
        $deployments = $this->db->fetchAll(
            'SELECT d.*, c.company_name, c.contact_person
             FROM deployments d
             LEFT JOIN clients c ON d.client_id = c.id
             WHERE d.helper_id = ?
             ORDER BY d.deployment_date DESC',
            [$helperId]
        );
        return $deployments ?: [];
    }

    /**
     * Get deployments by client ID
     */
    public function getByClientId(int $clientId): array
    {
        $deployments = $this->db->fetchAll(
            'SELECT d.*, h.name as helper_name, h.nationality, h.experience_level
             FROM deployments d
             LEFT JOIN helpers h ON d.helper_id = h.id
             WHERE d.client_id = ?
             ORDER BY d.deployment_date DESC',
            [$clientId]
        );
        return $deployments ?: [];
    }

    /**
     * Get active deployments
     */
    public function getActive(): array
    {
        $deployments = $this->db->fetchAll(
            'SELECT d.*, 
                    h.name as helper_name, h.nationality,
                    c.company_name, c.contact_person
             FROM deployments d
             LEFT JOIN helpers h ON d.helper_id = h.id
             LEFT JOIN clients c ON d.client_id = c.id
             WHERE d.status = "Active"
             ORDER BY d.deployment_date DESC'
        );
        return $deployments ?: [];
    }

    /**
     * Get deployment statistics
     */
    public function getStatistics(): array
    {
        $stats = $this->db->fetch(
            'SELECT 
                COUNT(*) as total_deployments,
                SUM(CASE WHEN status = "Active" THEN 1 ELSE 0 END) as active_count,
                SUM(CASE WHEN status = "Planned" THEN 1 ELSE 0 END) as planned_count,
                SUM(CASE WHEN status = "Completed" THEN 1 ELSE 0 END) as completed_count,
                SUM(CASE WHEN status = "Terminated" THEN 1 ELSE 0 END) as terminated_count,
                AVG(performance_rating) as avg_rating
             FROM deployments'
        );
        return $stats ?: [];
    }

    /**
     * Get deployments ending soon
     */
    public function getEndingSoon(int $days = 30): array
    {
        $deployments = $this->db->fetchAll(
            'SELECT d.*, 
                    h.name as helper_name, h.contact_number,
                    c.company_name, c.contact_person, c.contact_email
             FROM deployments d
             LEFT JOIN helpers h ON d.helper_id = h.id
             LEFT JOIN clients c ON d.client_id = c.id
             WHERE d.expected_end_date IS NOT NULL 
             AND d.expected_end_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
             AND d.expected_end_date >= CURDATE()
             AND d.status = "Active"
             ORDER BY d.expected_end_date ASC',
            [$days]
        );
        return $deployments ?: [];
    }

    /**
     * Get current deployment for helper
     */
    public function getCurrentByHelperId(int $helperId): ?array
    {
        return $this->db->fetch(
            'SELECT d.*, c.company_name, c.contact_person, c.contact_email
             FROM deployments d
             LEFT JOIN clients c ON d.client_id = c.id
             WHERE d.helper_id = ? 
             AND d.status IN ("Active", "Planned")
             ORDER BY d.deployment_date DESC
             LIMIT 1',
            [$helperId]
        );
    }

    /**
     * Create deployment with helper status update
     */
    public function createWithHelperUpdate(array $data): int
    {
        $this->db->beginTransaction();
        
        try {
            // Create deployment
            $deploymentId = $this->create($data);
            
            // Update helper status
            $helperStatus = $data['status'] === 'Active' ? 'Deployed' : 'Reserved';
            $this->db->execute(
                'UPDATE helpers SET status = ? WHERE id = ?',
                [$helperStatus, $data['helper_id']]
            );
            
            $this->db->commit();
            return $deploymentId;
        } catch (\Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    /**
     * Update deployment with helper status update
     */
    public function updateWithHelperStatus(int $id, array $data): bool
    {
        $this->db->beginTransaction();
        
        try {
            // Get current deployment
            $deployment = $this->find($id);
            if (!$deployment) {
                throw new \Exception('Deployment not found');
            }
            
            // Update deployment
            $updated = $this->update($id, $data);
            
            // Update helper status if deployment status changed
            if (isset($data['status'])) {
                $helperStatus = match($data['status']) {
                    'Active' => 'Deployed',
                    'Completed', 'Terminated' => 'Available',
                    'Planned' => 'Reserved',
                    default => null
                };
                
                if ($helperStatus) {
                    $this->db->execute(
                        'UPDATE helpers SET status = ? WHERE id = ?',
                        [$helperStatus, $deployment['helper_id']]
                    );
                }
            }
            
            $this->db->commit();
            return $updated;
        } catch (\Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
}
