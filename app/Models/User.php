<?php

namespace Models;

use Core\Database;

/**
 * User model for staff authentication
 */
class User extends BaseModel
{
    protected string $table = 'users';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'username', 'email', 'password_hash', 'full_name', 'role', 'is_active'
    ];

    /**
     * Authenticate user with email and password
     */
    public function authenticate(string $email, string $password): ?array
    {
        $user = $this->db->fetch(
            'SELECT * FROM users WHERE email = ? AND is_active = 1', 
            [$email]
        );

        if ($user && password_verify($password, $user['password_hash'])) {
            // Remove password hash from returned data
            unset($user['password_hash']);
            return $user;
        }

        return null;
    }

    /**
     * Create new user account
     */
    public function createUser(array $userData): int
    {
        // Hash password
        if (isset($userData['password'])) {
            $userData['password_hash'] = password_hash($userData['password'], PASSWORD_DEFAULT);
            unset($userData['password']);
        }

        // Set default role if not provided
        if (!isset($userData['role'])) {
            $userData['role'] = 'staff';
        }

        // Set active by default
        if (!isset($userData['is_active'])) {
            $userData['is_active'] = true;
        }

        return $this->create($userData);
    }

    /**
     * Check if email exists
     */
    public function emailExists(string $email): bool
    {
        $result = $this->db->fetch('SELECT id FROM users WHERE email = ?', [$email]);
        return $result !== null;
    }

    /**
     * Check if username exists
     */
    public function usernameExists(string $username): bool
    {
        $result = $this->db->fetch('SELECT id FROM users WHERE username = ?', [$username]);
        return $result !== null;
    }

    /**
     * Get user by ID
     */
    public function getUserById(int $id): ?array
    {
        $user = $this->find($id);
        if ($user) {
            unset($user['password_hash']);
        }
        return $user;
    }

    /**
     * Update user password
     */
    public function updatePassword(int $userId, string $newPassword): bool
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($userId, ['password_hash' => $hashedPassword]);
    }

    /**
     * Get users by role
     */
    public function getUsersByRole(string $role): array
    {
        $users = $this->db->fetchAll(
            'SELECT id, username, email, full_name, role, is_active, created_at FROM users WHERE role = ? ORDER BY full_name',
            [$role]
        );
        return $users ?: [];
    }

    /**
     * Toggle user active status
     */
    public function toggleActiveStatus(int $userId): bool
    {
        $user = $this->find($userId);
        if (!$user) {
            return false;
        }

        $newStatus = !$user['is_active'];
        return $this->update($userId, ['is_active' => $newStatus]);
    }
}
