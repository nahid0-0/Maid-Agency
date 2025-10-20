<?php

namespace Controllers;

use Core\Database;
use Core\S3Service;
use Core\Security;
use Models\Helper;
use Models\Client;
use Models\Request;

/**
 * Client dashboard controller
 */
class ClientDashboardController extends BaseController
{
    private Database $db;
    private S3Service $s3Service;
    private Helper $helperModel;
    private Client $clientModel;
    private Request $requestModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->db = Database::getInstance();
        $this->s3Service = new S3Service();
        $this->helperModel = new Helper();
        $this->clientModel = new Client();
        $this->requestModel = new Request();
    }
    
    /**
     * Check if client is authenticated
     */
    private function requireAuth(): ?int
    {
        $clientId = $this->session->get('client_id');
        if (!$clientId) {
            $this->redirect('/login');
            return null;
        }
        return $clientId;
    }
    
    /**
     * Display client home page
     */
    public function index(): void
    {
        $clientId = $this->requireAuth();
        if (!$clientId) return;
        
        try {
            // Get client with statistics
            $client = $this->clientModel->getWithStats($clientId);
            if (!$client) {
                $this->redirect('/login?error=' . urlencode('Client not found'));
                return;
            }
            
            // Get recent requests
            $recentRequests = $this->requestModel->getByClientId($clientId);
            $recentRequests = array_slice($recentRequests, 0, 5); // Last 5 requests
            
            // Set role in session for navbar
            $this->session->set('role', 'client');
            
            $data = [
                'title' => 'Client Dashboard',
                'client' => $client,
                'recent_requests' => $recentRequests
            ];
            
            $this->view('client.client_index', $data);
        } catch (\Exception $e) {
            $this->redirect('/login?error=' . urlencode('Error loading dashboard'));
        }
    }
    
    /**
     * Display browse helpers page
     */
    public function browseHelpers(): void
    {
        $clientId = $this->requireAuth();
        if (!$clientId) return;
        
        try {
            // Get search filters from query parameters
            $filters = [
                'nationality' => $_GET['nationality'] ?? '',
                'experience_level' => $_GET['experience_level'] ?? '',
                'min_salary' => $_GET['min_salary'] ?? '',
                'max_salary' => $_GET['max_salary'] ?? '',
                'gender' => $_GET['gender'] ?? ''
            ];
            
            // Get available helpers
            $helpers = $this->getAvailableHelpers($filters);
            
            // Get unique nationalities for filter dropdown
            $nationalities = $this->helperModel->getUniqueValues('nationality');
            
            $data = [
                'title' => 'Browse Helpers',
                'helpers' => $helpers,
                'nationalities' => $nationalities,
                'filters' => $filters
            ];
            
            $this->view('client.browse_helpers', $data);
        } catch (\Exception $e) {
            $this->redirect('/client?error=' . urlencode('Error loading helpers'));
        }
    }
    
    /**
     * Display my requests page
     */
    public function myRequests(): void
    {
        $clientId = $this->requireAuth();
        if (!$clientId) return;
        
        try {
            // Get client requests
            $requests = $this->requestModel->getByClientId($clientId);

            // Get available helpers for the dropdown
            $availableHelpers = $this->getAvailableHelpers();
            
            $data = [
                'title' => 'My Requests',
                'requests' => $requests,
                'available_helpers' => $availableHelpers
            ];
            
            $this->view('client.my_requests', $data);
        } catch (\Exception $e) {
            $this->redirect('/client?error=' . urlencode('Error loading requests'));
        }
    }
    
    /**
     * Submit a new helper request
     */
    public function submitRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }
        
        $clientId = $this->requireAuth();
        if (!$clientId) {
            $this->json(['success' => false, 'error' => 'Authentication required']);
            return;
        }
        
        try {
            // Validate CSRF token
            if (!Security::validateCsrfFromPost()) {
                throw new \Exception('Invalid request. Please try again.');
            }

            // Validate required fields
            $requiredFields = ['request_type', 'urgency', 'requirements'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new \Exception(ucfirst(str_replace('_', ' ', $field)) . ' is required');
                }
            }
            
            $data = [
                'client_id' => $clientId,
                'request_type' => $_POST['request_type'],
                'urgency' => $_POST['urgency'],
                'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
                'helper_id' => !empty($_POST['helper_id']) ? (int)$_POST['helper_id'] : null,
                'requirements' => trim($_POST['requirements']),
                'notes' => !empty($_POST['notes']) ? trim($_POST['notes']) : null,
                'status' => 'Pending'
            ];
            
            $requestId = $this->requestModel->createRequest($data);

            if ($requestId) {
                $this->json([
                    'success' => true,
                    'message' => 'Request submitted successfully',
                    'request_id' => $requestId
                ]);
            } else {
                throw new \Exception('Failed to create request');
            }
            
        } catch (\Exception $e) {
            error_log('Request submission error: ' . $e->getMessage());
            $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    /**
     * Get available helpers with filters
     */
    private function getAvailableHelpers(array $filters = []): array
    {
        $sql = "SELECT h.*,
                CASE WHEN h.profile_picture_url IS NOT NULL THEN h.profile_picture_url
                     ELSE NULL END as avatar_url,
                YEAR(CURDATE()) - YEAR(h.date_of_birth) -
                (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(h.date_of_birth, '%m%d')) as age
                FROM helpers h
                LEFT JOIN requests r ON h.id = r.helper_id
                    AND r.status IN ('Pending', 'In Progress', 'Approved', 'Active')
                WHERE h.is_active = 1
                AND r.id IS NULL";
        
        $params = [];
        
        // Filter by status (default to Available and Training)
        if (!empty($filters['status'])) {
            $sql .= " AND h.status = ?";
            $params[] = $filters['status'];
        } else {
            $sql .= " AND h.status IN ('Available', 'Training')";
        }
        
        if (!empty($filters['nationality'])) {
            $sql .= " AND h.nationality = ?";
            $params[] = $filters['nationality'];
        }
        
        if (!empty($filters['experience_level'])) {
            $sql .= " AND h.experience_level = ?";
            $params[] = $filters['experience_level'];
        }
        
        if (!empty($filters['gender'])) {
            $sql .= " AND h.gender = ?";
            $params[] = $filters['gender'];
        }
        
        if (!empty($filters['min_salary']) && is_numeric($filters['min_salary'])) {
            $sql .= " AND h.salary_max >= ?";
            $params[] = (float)$filters['min_salary'];
        }
        
        if (!empty($filters['max_salary']) && is_numeric($filters['max_salary'])) {
            $sql .= " AND h.salary_min <= ?";
            $params[] = (float)$filters['max_salary'];
        }
        
        $sql .= " ORDER BY h.status ASC, h.created_at DESC";
        
        try {
            return $this->db->fetchAll($sql, $params);
        } catch (\Exception $e) {
            error_log("Error fetching helpers: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Display client login page
     */
    public function login(): void
    {
        $data = [
            'title' => 'Client Login',
            'hideNavbar' => true
        ];
        
        $this->view('client.login', $data);
    }
    
    /**
     * Display client registration page
     */
    public function signup(): void
    {
        $data = [
            'title' => 'Client Registration',
            'hideNavbar' => true
        ];
        
        $this->view('client.signup', $data);
    }
    
    /**
     * Handle client registration
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/client/signup?error=' . urlencode('Invalid request method'));
            return;
        }

        try {
            // Validate CSRF token
            if (!Security::validateCsrfFromPost()) {
                throw new \Exception('Invalid request. Please try again.');
            }

            // Rate limiting for registration
            $clientIP = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            if (!Security::checkRateLimit('client_register_' . $clientIP, 3, 900)) {
                throw new \Exception('Too many registration attempts. Please try again later.');
            }

            // Validate input
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Sanitize inputs
            $name = Security::sanitizeString($name, 100);
            $email = Security::sanitizeString($email, 255);
            $phone = Security::sanitizeString($phone, 20);
            
            if (empty($name) || empty($email) || empty($phone) || empty($password)) {
                throw new \Exception('Name, email, phone, and password are required');
            }

            // Validate email format
            if (!Security::validateEmail($email)) {
                throw new \Exception('Invalid email format');
            }

            // Validate phone format
            if (!Security::validatePhone($phone)) {
                throw new \Exception('Invalid phone number format');
            }

            // Validate password strength
            $passwordValidation = Security::validatePassword($password);
            if (!$passwordValidation['valid']) {
                throw new \Exception('Password requirements: ' . implode(', ', $passwordValidation['errors']));
            }

            if ($password !== $confirmPassword) {
                throw new \Exception('Passwords do not match');
            }
            
            // Check if email already exists
            $existingClient = $this->clientModel->findByEmail($email);
            if ($existingClient) {
                throw new \Exception('Email already registered');
            }
            
            // Create client account
            $clientData = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => trim($_POST['address'] ?? ''),
                'family_size' => !empty($_POST['family_size']) ? (int)$_POST['family_size'] : null,
                'children_ages' => trim($_POST['children_ages'] ?? ''),
                'special_requirements' => trim($_POST['special_requirements'] ?? ''),
                'budget_min' => !empty($_POST['budget_min']) ? (float)$_POST['budget_min'] : null,
                'budget_max' => !empty($_POST['budget_max']) ? (float)$_POST['budget_max'] : null,
                'preferred_nationality' => trim($_POST['preferred_nationality'] ?? ''),
                'password_hash' => Security::hashPassword($password),
                'status' => 'Active',
                'is_active' => true
            ];
            
            $clientId = $this->clientModel->createClient($clientData);

            if ($clientId) {
                // Log the registration event
                Security::logSecurityEvent('client_registration_success', [
                    'client_id' => $clientId,
                    'email' => $email
                ]);

                $this->redirect('/client/login?success=' . urlencode('Registration successful! Please login to continue.'));
            } else {
                throw new \Exception('Failed to create account');
            }

        } catch (\Exception $e) {
            // Log the failed registration attempt
            Security::logSecurityEvent('client_registration_failed', [
                'email' => $email ?? 'unknown',
                'error' => $e->getMessage()
            ]);

            $this->redirect('/client/signup?error=' . urlencode($e->getMessage()));
        }
    }
    
    /**
     * Handle client authentication
     */
    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/client/login');
            return;
        }

        try {
            // Validate CSRF token
            if (!Security::validateCsrfFromPost()) {
                throw new \Exception('Invalid request. Please try again.');
            }

            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                throw new \Exception('Email and password are required');
            }

            // Rate limiting
            if (!Security::checkRateLimit('client_login_' . $email, 5, 900)) {
                throw new \Exception('Too many login attempts. Please try again later.');
            }
            
            // Authenticate client
            $client = $this->clientModel->authenticate($email, $password);
            if (!$client) {
                throw new \Exception('Invalid email or password');
            }
            
            if ($client['status'] !== 'Active') {
                throw new \Exception('Account is inactive. Please contact support.');
            }
            
            // Set session
            $this->session->set('client_id', $client['id']);
            $this->session->set('client_name', $client['name']);
            $this->session->set('role', 'client');
            
            $this->redirect('/client?success=' . urlencode('Login successful!'));
            
        } catch (\Exception $e) {
            $this->redirect('/client/login?error=' . urlencode($e->getMessage()));
        }
    }
    
    /**
     * Handle client logout
     */
    public function logout(): void
    {
        $this->session->destroy();
        $this->redirect('/?message=' . urlencode('You have been logged out successfully'));
    }
    
    /**
     * Display client profile page
     */
    public function profile(): void
    {
        $clientId = $this->requireAuth();
        if (!$clientId) return;
        
        try {
            $client = $this->clientModel->find($clientId);
            if (!$client) {
                $this->redirect('/client?error=' . urlencode('Client not found'));
                return;
            }
            
            $data = [
                'title' => 'My Profile',
                'client' => $client
            ];
            
            $this->view('client.profile', $data);
        } catch (\Exception $e) {
            $this->redirect('/client?error=' . urlencode('Error loading profile'));
        }
    }
    
    /**
     * Handle profile update
     */
    public function updateProfile(): void
    {
        $clientId = $this->requireAuth();
        if (!$clientId) return;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/client/profile');
            return;
        }
        
        try {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
                'family_size' => (int)($_POST['family_size'] ?? 0),
                'children_ages' => trim($_POST['children_ages'] ?? ''),
                'special_requirements' => trim($_POST['special_requirements'] ?? ''),
                'budget_min' => (float)($_POST['budget_min'] ?? 0),
                'budget_max' => (float)($_POST['budget_max'] ?? 0),
                'preferred_nationality' => trim($_POST['preferred_nationality'] ?? '')
            ];
            
            if (empty($data['name'])) {
                throw new \Exception('Name is required');
            }
            
            $success = $this->clientModel->updateProfile($clientId, $data);
            
            if ($success) {
                $this->session->set('client_name', $data['name']);
                $this->redirect('/client/profile?success=' . urlencode('Profile updated successfully'));
            } else {
                throw new \Exception('Failed to update profile');
            }
            
        } catch (\Exception $e) {
            $this->redirect('/client/profile?error=' . urlencode($e->getMessage()));
        }
    }
    
    /**
     * Handle profile picture upload
     */
    public function uploadPhoto(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $clientId = $this->requireAuth();
        if (!$clientId) {
            $this->json(['success' => false, 'error' => 'Authentication required']);
            return;
        }

        // Add debug logging
        error_log("Photo upload attempt for client ID: " . $clientId);
        error_log("FILES data: " . print_r($_FILES, true));

        try {
            if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception('Please select a valid photo file');
            }
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
                throw new \Exception('Invalid file type. Please upload a JPEG, PNG, or GIF image.');
            }
            
            // Validate file size (max 5MB)
            $maxSize = 5 * 1024 * 1024; // 5MB
            if ($_FILES['photo']['size'] > $maxSize) {
                throw new \Exception('File size too large. Maximum size is 5MB.');
            }
            
            // Get current client to check for existing profile picture
            $currentClient = $this->clientModel->find($clientId);
            $oldPictureKey = $currentClient['profile_picture_key'] ?? null;
            
            // Upload to S3
            $uploadResult = $this->s3Service->uploadDocument($_FILES['photo'], 'client-profiles', $clientId);
            
            if (!$uploadResult['success']) {
                throw new \Exception($uploadResult['error']);
            }
            
            // Update client record with photo URL and key
            $updateData = [
                'profile_picture_url' => $uploadResult['url'],
                'profile_picture_key' => $uploadResult['key']
            ];
            
            $success = $this->clientModel->update($clientId, $updateData);
            
            if ($success) {
                // Delete old profile picture if it exists
                if ($oldPictureKey) {
                    $this->s3Service->deleteDocument($oldPictureKey);
                }
                
                $this->json([
                    'success' => true,
                    'message' => 'Profile picture updated successfully',
                    'url' => $uploadResult['url']
                ]);
            } else {
                // If database update failed, clean up the uploaded file
                $this->s3Service->deleteDocument($uploadResult['key']);
                throw new \Exception('Failed to update profile picture in database');
            }
            
        } catch (\Exception $e) {
            $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    /**
     * Change client password
     */
    public function changePassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }
        
        $clientId = $this->requireAuth();
        if (!$clientId) {
            $this->json(['success' => false, 'error' => 'Authentication required']);
            return;
        }
        
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        try {
            // Validation
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                throw new \Exception('All password fields are required');
            }
            
            if (strlen($newPassword) < 6) {
                throw new \Exception('New password must be at least 6 characters long');
            }
            
            if ($newPassword !== $confirmPassword) {
                throw new \Exception('New passwords do not match');
            }
            
            if ($currentPassword === $newPassword) {
                throw new \Exception('New password must be different from current password');
            }
            
            // Get current client data
            $client = $this->clientModel->find($clientId);
            if (!$client) {
                throw new \Exception('Client not found');
            }
            
            // Verify current password
            if (!password_verify($currentPassword, $client['password_hash'])) {
                throw new \Exception('Current password is incorrect');
            }
            
            // Hash new password
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Update password in database
            $success = $this->clientModel->update($clientId, ['password_hash' => $newPasswordHash]);
            
            if ($success) {
                $this->json([
                    'success' => true,
                    'message' => 'Password changed successfully'
                ]);
            } else {
                throw new \Exception('Failed to update password');
            }
            
        } catch (\Exception $e) {
            $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    /**
     * Get helper details for modal display
     */
    public function helperDetails(): void
    {
        $clientId = $this->requireAuth();
        if (!$clientId) {
            $this->json(['success' => false, 'error' => 'Authentication required']);
            return;
        }
        
        // Get helper ID from route parameters
        $helperId = $this->params['id'] ?? null;
        if (!$helperId || !is_numeric($helperId)) {
            $this->json(['success' => false, 'error' => 'Invalid helper ID']);
            return;
        }
        
        try {
            // Get helper details with calculated age
            $sql = "SELECT h.*, 
                    YEAR(CURDATE()) - YEAR(h.date_of_birth) - 
                    (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(h.date_of_birth, '%m%d')) as age
                    FROM helpers h 
                    WHERE h.id = ? AND h.is_active = 1";
            
            $helper = $this->db->fetch($sql, [(int)$helperId]);
            
            if (!$helper) {
                $this->json(['success' => false, 'error' => 'Helper not found']);
                return;
            }
            
            $this->json([
                'success' => true,
                'helper' => $helper
            ]);
            
        } catch (\Exception $e) {
            error_log('Helper details error: ' . $e->getMessage());
            $this->json(['success' => false, 'error' => 'Failed to load helper details']);
        }
    }
    
    /**
     * Get request details for modal display
     */
    public function requestDetails(): void
    {
        $clientId = $this->requireAuth();
        if (!$clientId) {
            $this->json(['success' => false, 'error' => 'Authentication required']);
            return;
        }
        
        $requestId = $this->params['id'] ?? null;
        if (!$requestId || !is_numeric($requestId)) {
            $this->json(['success' => false, 'error' => 'Invalid request ID']);
            return;
        }
        
        try {
            // Get request details and ensure it belongs to the current client
            $sql = "SELECT r.*, h.name as helper_name 
                    FROM requests r 
                    LEFT JOIN helpers h ON r.helper_id = h.id 
                    WHERE r.id = ? AND r.client_id = ?";
            
            $request = $this->db->fetch($sql, [(int)$requestId, $clientId]);
            
            if (!$request) {
                $this->json(['success' => false, 'error' => 'Request not found']);
                return;
            }
            
            $this->json([
                'success' => true,
                'request' => $request
            ]);
            
        } catch (\Exception $e) {
            error_log('Request details error: ' . $e->getMessage());
            $this->json(['success' => false, 'error' => 'Failed to load request details']);
        }
    }
    
    /**
     * Cancel a request
     */
    public function cancelRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }
        
        $clientId = $this->requireAuth();
        if (!$clientId) {
            $this->json(['success' => false, 'error' => 'Authentication required']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $requestId = $input['request_id'] ?? null;
        
        if (!$requestId || !is_numeric($requestId)) {
            $this->json(['success' => false, 'error' => 'Invalid request ID']);
            return;
        }
        
        try {
            // Check if request belongs to client and is still pending
            $sql = "SELECT id, status FROM requests WHERE id = ? AND client_id = ?";
            $request = $this->db->fetch($sql, [(int)$requestId, $clientId]);
            
            if (!$request) {
                $this->json(['success' => false, 'error' => 'Request not found']);
                return;
            }
            
            if ($request['status'] !== 'Pending') {
                $this->json(['success' => false, 'error' => 'Only pending requests can be cancelled']);
                return;
            }
            
            // Update request status to cancelled
            $success = $this->requestModel->updateStatus((int)$requestId, 'Cancelled', 'Cancelled by client');
            
            if ($success) {
                $this->json([
                    'success' => true,
                    'message' => 'Request cancelled successfully'
                ]);
            } else {
                throw new \Exception('Failed to cancel request');
            }
            
        } catch (\Exception $e) {
            error_log('Request cancellation error: ' . $e->getMessage());
            $this->json(['success' => false, 'error' => 'Failed to cancel request']);
        }
    }
}
