<?php

namespace Models;

use Core\Database;

/**
 * Staff model for staff management
 */
class Staff extends BaseModel
{
    protected string $table = 'staff';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'name', 'email', 'password_hash', 'username', 'role', 'department', 'position',
        'date_of_birth', 'age', 'gender', 'contact_number', 'emergency_contact', 'address',
        'profile_picture_url', 'profile_picture_key', 'is_active', 'email_verified',
        'verification_token', 'last_login', 'hire_date', 'salary'
    ];

    /**
     * Authenticate staff with email and password
     */
    public function authenticate(string $email, string $password): ?array
    {
        $staff = $this->db->fetch(
            'SELECT * FROM staff WHERE email = ? AND is_active = 1',
            [$email]
        );

        if (!$staff) {
            return null;
        }

        // Check if password hash exists
        if (empty($staff['password_hash'])) {
            return null;
        }

        if (password_verify($password, $staff['password_hash'])) {
            // Update last login
            $this->update($staff['id'], ['last_login' => date('Y-m-d H:i:s')]);

            // Remove password hash from returned data
            unset($staff['password_hash']);
            return $staff;
        }

        return null;
    }

    /**
     * Create new staff account
     */
    public function createStaff(array $staffData): int
    {
        // Hash password
        if (isset($staffData['password'])) {
            $staffData['password_hash'] = password_hash($staffData['password'], PASSWORD_DEFAULT);
            unset($staffData['password']);
        }

        // Set default role if not provided
        if (!isset($staffData['role'])) {
            $staffData['role'] = 'staff';
        }

        // Set active by default
        if (!isset($staffData['is_active'])) {
            $staffData['is_active'] = true;
        }

        // Calculate age if date of birth is provided
        if (isset($staffData['date_of_birth']) && !empty($staffData['date_of_birth'])) {
            $dob = new \DateTime($staffData['date_of_birth']);
            $today = new \DateTime();
            $staffData['age'] = $today->diff($dob)->y;
        }

        return $this->create($staffData);
    }

    /**
     * Check if email exists
     */
    public function emailExists(string $email): bool
    {
        $result = $this->db->fetch('SELECT id FROM staff WHERE email = ?', [$email]);
        return $result !== null;
    }

    /**
     * Check if username exists
     */
    public function usernameExists(string $username): bool
    {
        $result = $this->db->fetch('SELECT id FROM staff WHERE username = ?', [$username]);
        return $result !== null;
    }

    /**
     * Get staff by ID
     */
    public function getStaffById(int $id): ?array
    {
        $staff = $this->find($id);
        if ($staff) {
            unset($staff['password_hash']);
        }
        return $staff;
    }

    /**
     * Update staff password
     */
    public function updatePassword(int $staffId, string $newPassword): bool
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($staffId, ['password_hash' => $hashedPassword]);
    }

    /**
     * Get staff by role
     */
    public function getStaffByRole(string $role): array
    {
        $staff = $this->db->fetchAll(
            'SELECT id, username, email, name, role, department, position, is_active, created_at FROM staff WHERE role = ? ORDER BY name',
            [$role]
        );
        return $staff ?: [];
    }

    /**
     * Get all active staff
     */
    public function getActiveStaff(): array
    {
        $staff = $this->db->fetchAll(
            'SELECT id, username, email, name, role, department, position, created_at FROM staff WHERE is_active = 1 ORDER BY name'
        );
        return $staff ?: [];
    }

    /**
     * Toggle staff active status
     */
    public function toggleActiveStatus(int $staffId): bool
    {
        $staff = $this->find($staffId);
        if (!$staff) {
            return false;
        }

        $newStatus = !$staff['is_active'];
        return $this->update($staffId, ['is_active' => $newStatus]);
    }

    /**
     * Search staff by name or email
     */
    public function searchStaff(string $query): array
    {
        $staff = $this->db->fetchAll(
            'SELECT id, username, email, name, role, department, position, is_active FROM staff 
             WHERE (name LIKE ? OR email LIKE ? OR username LIKE ?) AND is_active = 1 
             ORDER BY name',
            ["%$query%", "%$query%", "%$query%"]
        );
        return $staff ?: [];
    }
}
