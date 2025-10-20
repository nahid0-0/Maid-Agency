<?php

namespace Controllers;

use Core\S3Service;
use Models\HelperIdentityDocument;
use Models\HelperMedicalDocument;
use Models\HelperTrainingDocument;
use Models\Staff;
use Models\Helper;
use Models\MedicalRecord;
use Models\TrainingRecord;
use Models\Deployment;
use Models\Request;

/**
 * Staff controller
 */
class StaffController extends BaseController
{
    private S3Service $s3Service;
    private HelperIdentityDocument $identityModel;
    private HelperMedicalDocument $medicalModel;
    private HelperTrainingDocument $trainingModel;
    private Staff $staffModel;
    private Helper $helperModel;
    private MedicalRecord $medicalRecordModel;
    private TrainingRecord $trainingRecordModel;
    private Deployment $deploymentModel;
    private Request $requestModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->s3Service = new S3Service();
        $this->identityModel = new HelperIdentityDocument();
        $this->medicalModel = new HelperMedicalDocument();
        $this->trainingModel = new HelperTrainingDocument();
        $this->staffModel = new Staff();
        $this->helperModel = new Helper();
        $this->medicalRecordModel = new MedicalRecord();
        $this->trainingRecordModel = new TrainingRecord();
        $this->deploymentModel = new Deployment();
        $this->requestModel = new Request();
    }
    
    /**
     * Check if staff is authenticated
     */
    private function requireAuth(): ?int
    {
        $staffId = $this->session->get('staff_id');
        if (!$staffId) {
            $this->redirect('/staff/login');
            return null;
        }
        return $staffId;
    }
    
    /**
     * Show staff login page
     */
    public function login(): void
    {
        // If already logged in, redirect to dashboard
        if ($this->session->get('staff_id')) {
            $this->redirect('/staff');
            return;
        }
        
        $this->view('staff.login');
    }
    
    /**
     * Show staff signup page
     */
    public function signup(): void
    {
        // If already logged in, redirect to dashboard
        if ($this->session->get('staff_id')) {
            $this->redirect('/staff');
            return;
        }
        
        $this->view('staff.signup');
    }
    
    /**
     * Handle staff registration
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/staff/signup?error=' . urlencode('Invalid request method'));
            return;
        }

        // Determine if this is an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        try {
            // Validate required fields
            $requiredFields = ['username', 'email', 'password', 'confirm_password', 'full_name'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new \Exception("$field is required");
                }
            }

            // Validate password confirmation
            if ($_POST['password'] !== $_POST['confirm_password']) {
                throw new \Exception('Passwords do not match');
            }

            // Validate password strength
            if (strlen($_POST['password']) < 6) {
                throw new \Exception('Password must be at least 6 characters long');
            }

            // Check if email already exists
            if ($this->staffModel->emailExists($_POST['email'])) {
                throw new \Exception('Email address is already registered');
            }

            // Check if username already exists
            if ($this->staffModel->usernameExists($_POST['username'])) {
                throw new \Exception('Username is already taken');
            }

            // Create staff account
            $staffData = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => $_POST['password'],
                'name' => trim($_POST['full_name']),
                'role' => $_POST['role'] ?? 'staff',
                'is_active' => true
            ];

            $staffId = $this->staffModel->createStaff($staffData);

            if ($isAjax) {
                $this->json([
                    'success' => true,
                    'message' => 'Registration successful! You can now login.',
                    'staff_id' => $staffId
                ]);
            } else {
                $this->redirect('/staff/login?success=' . urlencode('Registration successful! You can now login.'));
            }

        } catch (\Exception $e) {
            if ($isAjax) {
                $this->json([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
            } else {
                $this->redirect('/staff/signup?error=' . urlencode($e->getMessage()));
            }
        }
    }
    
    /**
     * Handle staff authentication
     */
    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/staff/login?error=' . urlencode('Invalid request method'));
            return;
        }

        // Determine if this is an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        try {
            // Start session if not already started
            if (!$this->session->isStarted()) {
                $this->session->start();
            }

            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                throw new \Exception('Email and password are required');
            }

            // Authenticate staff
            $staff = $this->staffModel->authenticate($email, $password);

            if (!$staff) {
                throw new \Exception('Invalid email or password');
            }

            // Set session
            $this->session->set('staff_id', $staff['id']);
            $this->session->set('staff_name', $staff['name']);
            $this->session->set('staff_role', $staff['role']);
            $this->session->set('role', 'staff');

            if ($isAjax) {
                $this->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'redirect' => '/staff'
                ]);
            } else {
                $this->redirect('/staff');
            }

        } catch (\Exception $e) {
            if ($isAjax) {
                $this->json([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
            } else {
                $this->redirect('/staff/login?error=' . urlencode($e->getMessage()));
            }
        }
    }
    
    /**
     * Handle staff logout
     */
    public function logout(): void
    {
        // Ensure session is started before destroying
        if (!$this->session->isStarted()) {
            $this->session->start();
        }
        
        // Log the logout attempt
        $staffId = $this->session->get('staff_id');
        if ($staffId) {
            error_log("Staff logout: ID $staffId");
        }
        
        // Destroy session
        $this->session->destroy();
        
        // Redirect to login
        $this->redirect('/staff/login');
    }
    
    /**
     * Display staff home page
     */
    public function index(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;
        
        $staff = $this->staffModel->getStaffById($staffId);
        
        $data = [
            'title' => 'Staff Dashboard',
            'staff' => $staff
        ];
        
        $this->view('staff.staff_index', $data);
    }
    
    /**
     * Display staff profile page
     */
    public function profile(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;
        
        $staff = $this->staffModel->getStaffById($staffId);
        
        $data = [
            'title' => 'My Profile',
            'staff' => $staff
        ];
        
        $this->view('staff.profile', $data);
    }
    
    /**
     * Update staff profile
     */
    public function updateProfile(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }
        
        $staffId = $this->session->get('staff_id');
        if (!$staffId) {
            $this->json([
                'success' => false, 
                'error' => 'Authentication required. Please refresh the page and try again.'
            ]);
            return;
        }
        
        try {
            // Validate required fields
            $requiredFields = ['name', 'email'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new \Exception("$field is required");
                }
            }
            
            // Prepare update data
            $updateData = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'username' => trim($_POST['username'] ?? ''),
                'department' => trim($_POST['department'] ?? ''),
                'position' => trim($_POST['position'] ?? ''),
                'contact_number' => trim($_POST['contact_number'] ?? ''),
                'emergency_contact' => trim($_POST['emergency_contact'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
            ];
            
            // Handle date of birth and age calculation
            if (!empty($_POST['date_of_birth'])) {
                $updateData['date_of_birth'] = $_POST['date_of_birth'];
                $dob = new \DateTime($_POST['date_of_birth']);
                $today = new \DateTime();
                $updateData['age'] = $today->diff($dob)->y;
            }
            
            // Handle gender
            if (!empty($_POST['gender'])) {
                $updateData['gender'] = $_POST['gender'];
            }
            
            // Handle salary
            if (!empty($_POST['salary'])) {
                $updateData['salary'] = floatval($_POST['salary']);
            }
            
            // Update the staff record
            $updateResult = $this->staffModel->update($staffId, $updateData);
            
            // Check if update was successful OR if no changes were needed
            $currentStaff = $this->staffModel->getStaffById($staffId);
            
            if ($currentStaff) {
                $this->json([
                    'success' => true,
                    'message' => 'Profile updated successfully',
                    'data' => $updateData,
                    'rows_affected' => $updateResult ? 1 : 0
                ]);
            } else {
                throw new \Exception('Failed to update profile - staff not found');
            }
            
        } catch (\Exception $e) {
            $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Handle staff photo upload
     */
    public function uploadPhoto(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }
        
        $staffId = $this->session->get('staff_id');
        if (!$staffId) {
            $this->json(['success' => false, 'error' => 'Authentication required']);
            return;
        }
        
        try {
            if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception('Please select a valid photo file');
            }
            
            // Get current staff info
            $currentStaff = $this->staffModel->getStaffById($staffId);
            $oldPictureKey = $currentStaff['profile_picture_key'] ?? null;
            
            // Upload to S3
            $uploadResult = $this->s3Service->uploadDocument($_FILES['photo'], 'staff-profiles', $staffId);
            
            if (!$uploadResult['success']) {
                throw new \Exception($uploadResult['error']);
            }
            
            // Update staff record
            $updateData = [
                'profile_picture_url' => $uploadResult['url'],
                'profile_picture_key' => $uploadResult['key']
            ];
            
            $success = $this->staffModel->update($staffId, $updateData);
            
            if ($success) {
                // Delete old photo if it exists
                if ($oldPictureKey) {
                    try {
                        $this->s3Service->deleteDocument($oldPictureKey);
                    } catch (\Exception $e) {
                        error_log("Failed to delete old staff profile picture: " . $e->getMessage());
                    }
                }
                
                $this->json([
                    'success' => true,
                    'message' => 'Profile photo updated successfully',
                    'photo_url' => $uploadResult['url']
                ]);
            } else {
                // Clean up uploaded file if database update failed
                $this->s3Service->deleteDocument($uploadResult['key']);
                throw new \Exception('Failed to save photo information');
            }
            
        } catch (\Exception $e) {
            $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    
    /**
     * Display request workflow page
     */
    public function requestWorkflow(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;
        
        try {
            // Get all client requests with client and helper details
            $requests = $this->requestModel->getAllWithDetails();
            
            $data = [
                'title' => 'Request Workflow',
                'requests' => $requests
            ];
            
            $this->view('staff.request_workflow', $data);
        } catch (\Exception $e) {
            $this->redirect('/staff?error=' . urlencode('Error loading requests'));
        }
    }
    
    /**
     * Update request status
     */
    public function updateRequestStatus(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }
        
        try {
            $requestId = (int)($_POST['request_id'] ?? 0);
            $status = $_POST['status'] ?? '';
            
            if (!$requestId || !$status) {
                $this->json(['success' => false, 'message' => 'Request ID and status are required']);
                return;
            }
            
            // Validate status
            $validStatuses = ['Pending', 'In Progress', 'Matched', 'Completed', 'Cancelled'];
            if (!in_array($status, $validStatuses)) {
                $this->json(['success' => false, 'message' => 'Invalid status']);
                return;
            }
            
            $notes = "Status updated by staff on " . date('Y-m-d H:i:s');
            $success = $this->requestModel->updateStatus($requestId, $status, $notes);
            
            if ($success) {
                $this->json(['success' => true, 'message' => 'Request status updated successfully']);
            } else {
                $this->json(['success' => false, 'message' => 'Failed to update request status']);
            }
            
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Display document review dashboard
     */
    public function documentReview(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;

        try {
            // Get all documents with helper info for comprehensive review
            $identityDocuments = $this->identityModel->getAllWithHelperInfo();
            $medicalDocuments = $this->medicalModel->getAllWithHelperInfo();
            $trainingDocuments = $this->trainingModel->getAllWithHelperInfo();
            
            // Get pending documents for summary cards
            $pendingIdentityDocs = $this->identityModel->getByStatus('Pending Review');
            $pendingMedicalDocs = $this->medicalModel->getByStatus('Pending Review');
            $pendingTrainingDocs = $this->trainingModel->getByStatus('Pending Review');
            
            // Get expiring documents
            $expiringIdentityDocs = $this->identityModel->getExpiringDocuments(30);
            $expiringMedicalDocs = $this->medicalModel->getExpiringDocuments(30);
            $expiringTrainingDocs = $this->trainingModel->getExpiringDocuments(30);
            
            $data = [
                'title' => 'Document Review Dashboard',
                'identity_documents' => $identityDocuments,
                'medical_documents' => $medicalDocuments,
                'training_documents' => $trainingDocuments,
                'pending_identity' => $pendingIdentityDocs,
                'pending_medical' => $pendingMedicalDocs,
                'pending_training' => $pendingTrainingDocs,
                'expiring_identity' => $expiringIdentityDocs,
                'expiring_medical' => $expiringMedicalDocs,
                'expiring_training' => $expiringTrainingDocs,
                'total_pending' => count($pendingIdentityDocs) + count($pendingMedicalDocs) + count($pendingTrainingDocs),
                'total_expiring' => count($expiringIdentityDocs) + count($expiringMedicalDocs) + count($expiringTrainingDocs)
            ];
            
            $this->view('staff.document_review', $data);
        } catch (\Exception $e) {
            $this->redirect('/staff?error=' . urlencode('Error loading document review: ' . $e->getMessage()));
        }
    }
    
    /**
     * Review and approve/reject a document
     */
    public function reviewDocument(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid request method']);
            return;
        }
        
        $documentId = $_POST['document_id'] ?? null;
        $documentType = $_POST['document_type'] ?? null;
        $status = $_POST['status'] ?? null;
        $reviewNotes = $_POST['review_notes'] ?? null;
        
        // For now, using dummy staff ID (1) - in real app, get from session
        $staffId = 1;
        
        if (!$documentId || !$documentType || !$status) {
            $this->jsonResponse(['success' => false, 'error' => 'Missing required fields']);
            return;
        }
        
        if (!in_array($status, ['Approved', 'Rejected'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid status']);
            return;
        }
        
        try {
            $result = false;
            switch ($documentType) {
                case 'identity':
                    $result = $this->identityModel->updateReviewStatus($documentId, $status, $staffId, $reviewNotes);
                    break;
                case 'medical':
                    $result = $this->medicalModel->updateReviewStatus($documentId, $status, $staffId, $reviewNotes);
                    break;
                case 'training':
                    $result = $this->trainingModel->updateReviewStatus($documentId, $status, $staffId, $reviewNotes);
                    break;
            }
            
            if ($result) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => "Document {$status} successfully"
                ]);
            } else {
                $this->jsonResponse([
                    'success' => false,
                    'error' => 'Failed to update document status'
                ]);
            }
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => 'Error updating document: ' . $e->getMessage()
            ]);
        }
    }
    
    
    /**
     * Get helper document summary
     */
    public function getHelperDocumentSummary(): void
    {
        $helperId = $_GET['helper_id'] ?? null;
        
        if (!$helperId) {
            $this->jsonResponse(['success' => false, 'error' => 'Helper ID required']);
            return;
        }
        
        try {
            $identityDocs = $this->identityModel->getByHelperId($helperId);
            $medicalDocs = $this->medicalModel->getByHelperId($helperId);
            $trainingDocs = $this->trainingModel->getByHelperId($helperId);
            
            $summary = [
                'identity_documents' => [
                    'total' => count($identityDocs),
                    'approved' => count(array_filter($identityDocs, fn($doc) => $doc['status'] === 'Approved')),
                    'pending' => count(array_filter($identityDocs, fn($doc) => $doc['status'] === 'Pending Review')),
                    'rejected' => count(array_filter($identityDocs, fn($doc) => $doc['status'] === 'Rejected')),
                    'documents' => $identityDocs
                ],
                'medical_documents' => [
                    'total' => count($medicalDocs),
                    'approved' => count(array_filter($medicalDocs, fn($doc) => $doc['status'] === 'Approved')),
                    'pending' => count(array_filter($medicalDocs, fn($doc) => $doc['status'] === 'Pending Review')),
                    'rejected' => count(array_filter($medicalDocs, fn($doc) => $doc['status'] === 'Rejected')),
                    'documents' => $medicalDocs
                ],
                'training_documents' => [
                    'total' => count($trainingDocs),
                    'approved' => count(array_filter($trainingDocs, fn($doc) => $doc['status'] === 'Approved')),
                    'pending' => count(array_filter($trainingDocs, fn($doc) => $doc['status'] === 'Pending Review')),
                    'rejected' => count(array_filter($trainingDocs, fn($doc) => $doc['status'] === 'Rejected')),
                    'documents' => $trainingDocs
                ]
            ];
            
            $this->jsonResponse([
                'success' => true,
                'summary' => $summary
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => 'Error getting document summary: ' . $e->getMessage()
            ]);
        }
    }

    // ===== HELPER MANAGEMENT METHODS =====

    /**
     * Display helper management dashboard
     */
    public function helperManagement(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;

        try {
            $helpers = $this->helperModel->findAll();
            $statistics = [
                'total' => count($helpers),
                'available' => count(array_filter($helpers, fn($h) => $h['status'] === 'Available')),
                'deployed' => count(array_filter($helpers, fn($h) => $h['status'] === 'Deployed')),
                'training' => count(array_filter($helpers, fn($h) => $h['status'] === 'Training')),
                'inactive' => count(array_filter($helpers, fn($h) => $h['status'] === 'Inactive'))
            ];

            $data = [
                'title' => 'Helper Management',
                'helpers' => $helpers,
                'statistics' => $statistics
            ];

            $this->view('staff.helper-management', $data);
        } catch (\Exception $e) {
            $this->redirect('/staff?error=' . urlencode('Error loading helper management: ' . $e->getMessage()));
        }
    }

    /**
     * Show create helper form
     */
    public function createHelper(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;

        $data = [
            'title' => 'Create New Helper',
            'nationalities' => ['Philippines', 'Indonesia', 'Thailand', 'China', 'India', 'Myanmar', 'Vietnam', 'Cambodia'],
            'experience_levels' => ['Beginner (0-2 years)', 'Intermediate (2-5 years)', 'Experienced (5+ years)']
        ];

        $this->view('staff.create-helper', $data);
    }

    /**
     * Store new helper
     */
    public function storeHelper(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        try {
            // Validate required fields
            $required = ['name', 'email', 'password', 'confirm_password', 'nationality', 'date_of_birth', 'gender', 'experience_level'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new \Exception("$field is required");
                }
            }
            
            // Validate passwords match
            if ($_POST['password'] !== $_POST['confirm_password']) {
                throw new \Exception('Passwords do not match');
            }
            
            // Validate password length
            if (strlen($_POST['password']) < 6) {
                throw new \Exception('Password must be at least 6 characters long');
            }

            // Calculate age
            $dob = new \DateTime($_POST['date_of_birth']);
            $today = new \DateTime();
            $age = $today->diff($dob)->y;

            // Validate age (25-55 years)
            if ($age < 25 || $age > 55) {
                throw new \Exception('Age must be between 25 and 55 years');
            }

            $helperData = [
                'name' => trim($_POST['name']),
                'nationality' => $_POST['nationality'],
                'date_of_birth' => $_POST['date_of_birth'],
                'age' => $age,
                'gender' => $_POST['gender'],
                'contact_number' => trim($_POST['contact_number'] ?? ''),
                'emergency_contact' => trim($_POST['emergency_contact'] ?? ''),
                'experience_level' => $_POST['experience_level'],
                'previous_experience' => trim($_POST['previous_experience'] ?? ''),
                'status' => $_POST['status'] ?? 'Available',
                'salary_min' => !empty($_POST['salary_min']) ? (float)$_POST['salary_min'] : null,
                'salary_max' => !empty($_POST['salary_max']) ? (float)$_POST['salary_max'] : null,
                'special_skills' => trim($_POST['special_skills'] ?? ''),
                'dietary_restrictions' => trim($_POST['dietary_restrictions'] ?? ''),
                'medical_conditions' => trim($_POST['medical_conditions'] ?? ''),
                'is_active' => true
            ];

            // Add email and password (both required now)
            $helperData['email'] = trim($_POST['email']);
            $helperData['password_hash'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $helperId = $this->helperModel->create($helperData);

            $this->json([
                'success' => true,
                'message' => 'Helper created successfully',
                'helper_id' => $helperId
            ]);

        } catch (\Exception $e) {
            $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Show edit helper form
     */
    public function editHelper(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;

        $helperId = (int)($this->params['id'] ?? 0);
        if (!$helperId) {
            $this->redirect('/staff/helper-management?error=Invalid helper ID');
            return;
        }

        try {
            $helper = $this->helperModel->find($helperId);
            if (!$helper) {
                $this->redirect('/staff/helper-management?error=Helper not found');
                return;
            }

            $data = [
                'title' => 'Edit Helper - ' . $helper['name'],
                'helper' => $helper,
                'nationalities' => ['Philippines', 'Indonesia', 'Thailand', 'China', 'India', 'Myanmar', 'Vietnam', 'Cambodia'],
                'experience_levels' => ['Beginner (0-2 years)', 'Intermediate (2-5 years)', 'Experienced (5+ years)'],
                'statuses' => ['Available', 'Reserved', 'Deployed', 'Training', 'Inactive']
            ];

            $this->view('staff.edit-helper', $data);
        } catch (\Exception $e) {
            $this->redirect('/staff/helper-management?error=' . urlencode('Error loading helper: ' . $e->getMessage()));
        }
    }

    /**
     * Update helper
     */
    public function updateHelper(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $helperId = (int)($this->params['id'] ?? 0);
        if (!$helperId) {
            $this->json(['success' => false, 'error' => 'Invalid helper ID']);
            return;
        }

        try {
            $helper = $this->helperModel->find($helperId);
            if (!$helper) {
                throw new \Exception('Helper not found');
            }

            // Validate required fields
            $required = ['name', 'nationality', 'date_of_birth', 'gender', 'experience_level'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new \Exception("$field is required");
                }
            }

            // Calculate age
            $dob = new \DateTime($_POST['date_of_birth']);
            $today = new \DateTime();
            $age = $today->diff($dob)->y;

            // Validate age (25-55 years)
            if ($age < 25 || $age > 55) {
                throw new \Exception('Age must be between 25 and 55 years');
            }

            $updateData = [
                'name' => trim($_POST['name']),
                'nationality' => $_POST['nationality'],
                'date_of_birth' => $_POST['date_of_birth'],
                'age' => $age,
                'gender' => $_POST['gender'],
                'contact_number' => trim($_POST['contact_number'] ?? ''),
                'emergency_contact' => trim($_POST['emergency_contact'] ?? ''),
                'experience_level' => $_POST['experience_level'],
                'previous_experience' => trim($_POST['previous_experience'] ?? ''),
                'status' => $_POST['status'] ?? $helper['status'],
                'salary_min' => !empty($_POST['salary_min']) ? (float)$_POST['salary_min'] : null,
                'salary_max' => !empty($_POST['salary_max']) ? (float)$_POST['salary_max'] : null,
                'special_skills' => trim($_POST['special_skills'] ?? ''),
                'dietary_restrictions' => trim($_POST['dietary_restrictions'] ?? ''),
                'medical_conditions' => trim($_POST['medical_conditions'] ?? ''),
                'is_active' => isset($_POST['is_active']) ? (bool)$_POST['is_active'] : $helper['is_active']
            ];

            // Update email if provided and different
            if (!empty($_POST['email']) && $_POST['email'] !== $helper['email']) {
                $updateData['email'] = trim($_POST['email']);
            }
            
            // Handle password change if provided
            if (!empty($_POST['new_password'])) {
                // Validate passwords match
                if ($_POST['new_password'] !== $_POST['confirm_new_password']) {
                    throw new \Exception('New passwords do not match');
                }
                
                // Validate password length
                if (strlen($_POST['new_password']) < 6) {
                    throw new \Exception('New password must be at least 6 characters long');
                }
                
                $updateData['password_hash'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            }

            $success = $this->helperModel->update($helperId, $updateData);

            if ($success || $this->helperModel->find($helperId)) {
                $this->json([
                    'success' => true,
                    'message' => 'Helper updated successfully'
                ]);
            } else {
                throw new \Exception('Failed to update helper');
            }

        } catch (\Exception $e) {
            $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Delete helper (soft delete by setting inactive)
     */
    public function deleteHelper(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $helperId = (int)($this->params['id'] ?? 0);
        if (!$helperId) {
            $this->json(['success' => false, 'error' => 'Invalid helper ID']);
            return;
        }

        try {
            $helper = $this->helperModel->find($helperId);
            if (!$helper) {
                throw new \Exception('Helper not found');
            }

            // Skip deployment check for now to avoid issues
            // TODO: Add deployment check back when deployment system is fully implemented
            
            // Soft delete by setting inactive
            $success = $this->helperModel->update($helperId, [
                'is_active' => 0,
                'status' => 'Inactive'
            ]);

            if ($success !== false) {
                $this->json([
                    'success' => true,
                    'message' => 'Helper deactivated successfully'
                ]);
            } else {
                throw new \Exception('Failed to deactivate helper - database update failed');
            }

        } catch (\Exception $e) {
            error_log("Delete helper error: " . $e->getMessage());
            $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // ===== MEDICAL & TRAINING MANAGEMENT METHODS =====

    /**
     * Display medical and training management dashboard
     */
    public function medicalTraining(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;

        try {
            $helpers = $this->helperModel->findAll();
            $medicalRecords = $this->medicalRecordModel->getAllWithHelperInfo();
            $trainingRecords = $this->trainingRecordModel->getAllWithHelperInfo();

            $medicalStats = $this->medicalRecordModel->getStatistics();
            $trainingStats = $this->trainingRecordModel->getStatistics();

            $expiringMedical = $this->medicalRecordModel->getExpiringRecords(30);
            $expiringTraining = $this->trainingRecordModel->getExpiringCertificates(30);

            $data = [
                'title' => 'Medical & Training Records',
                'helpers' => $helpers,
                'medical_records' => $medicalRecords,
                'training_records' => $trainingRecords,
                'medical_stats' => $medicalStats,
                'training_stats' => $trainingStats,
                'expiring_medical' => $expiringMedical,
                'expiring_training' => $expiringTraining
            ];

            $this->view('staff.medical-training', $data);
        } catch (\Exception $e) {
            $this->redirect('/staff?error=' . urlencode('Error loading medical & training: ' . $e->getMessage()));
        }
    }
    
    /**
     * Get document URL for viewing (staff version)
     */
    public function getDocumentUrl(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) {
            $this->json(['success' => false, 'error' => 'Authentication required']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }
        
        try {
            $documentId = $_POST['document_id'] ?? '';
            $documentType = $_POST['document_type'] ?? '';
            
            if (empty($documentId) || empty($documentType)) {
                throw new \Exception('Missing document ID or type');
            }
            
            $document = null;
            
            // Get document based on type
            switch ($documentType) {
                case 'identity':
                    $document = $this->identityModel->getWithHelperInfo($documentId);
                    break;
                case 'medical':
                    $document = $this->medicalModel->getWithHelperInfo($documentId);
                    break;
                case 'training':
                    $document = $this->trainingModel->getWithHelperInfo($documentId);
                    break;
                default:
                    throw new \Exception('Invalid document type');
            }
            
            if (!$document) {
                throw new \Exception('Document not found');
            }
            
            // Generate presigned URL for S3 access
            $urlResult = $this->s3Service->getPresignedUrl($document['s3_key'], 3600); // 1 hour expiry
            
            if ($urlResult['success']) {
                $this->json([
                    'success' => true,
                    'document' => $document,
                    'url' => $urlResult['url'],
                    'expires_in' => $urlResult['expires_in']
                ]);
            } else {
                // Better error messages based on error type
                $errorMessage = $urlResult['error'] ?? 'Failed to generate presigned URL';
                if (isset($urlResult['code']) && $urlResult['code'] === 'NoSuchKey') {
                    $errorMessage = 'Document file not found in storage. The file may have been deleted or moved.';
                }
                throw new \Exception($errorMessage);
            }
            
        } catch (\Exception $e) {
            error_log("Document URL error: " . $e->getMessage());
            $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    /**
     * Approve a document
     */
    public function approveDocument(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) {
            $this->json(['success' => false, 'error' => 'Authentication required']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }
        
        try {
            $documentId = $_POST['document_id'] ?? '';
            $documentType = $_POST['document_type'] ?? '';
            $notes = $_POST['notes'] ?? '';
            
            if (empty($documentId) || empty($documentType)) {
                throw new \Exception('Missing document ID or type');
            }
            
            $success = false;
            
            // Update document status based on type
            switch ($documentType) {
                case 'identity':
                    $success = $this->identityModel->updateReviewStatus($documentId, 'Approved', $staffId, $notes);
                    break;
                case 'medical':
                    $success = $this->medicalModel->updateReviewStatus($documentId, 'Approved', $staffId, $notes);
                    break;
                case 'training':
                    $success = $this->trainingModel->updateReviewStatus($documentId, 'Approved', $staffId, $notes);
                    break;
                default:
                    throw new \Exception('Invalid document type');
            }
            
            if ($success) {
                $this->json(['success' => true, 'message' => 'Document approved successfully']);
            } else {
                throw new \Exception('Failed to approve document');
            }
            
        } catch (\Exception $e) {
            error_log("Document approval error: " . $e->getMessage());
            $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    /**
     * Reject a document
     */
    public function rejectDocument(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) {
            $this->json(['success' => false, 'error' => 'Authentication required']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }
        
        try {
            $documentId = $_POST['document_id'] ?? '';
            $documentType = $_POST['document_type'] ?? '';
            $notes = $_POST['notes'] ?? '';
            
            if (empty($documentId) || empty($documentType)) {
                throw new \Exception('Missing document ID or type');
            }
            
            $success = false;
            
            // Update document status based on type
            switch ($documentType) {
                case 'identity':
                    $success = $this->identityModel->updateReviewStatus($documentId, 'Rejected', $staffId, $notes);
                    break;
                case 'medical':
                    $success = $this->medicalModel->updateReviewStatus($documentId, 'Rejected', $staffId, $notes);
                    break;
                case 'training':
                    $success = $this->trainingModel->updateReviewStatus($documentId, 'Rejected', $staffId, $notes);
                    break;
                default:
                    throw new \Exception('Invalid document type');
            }
            
            if ($success) {
                $this->json(['success' => true, 'message' => 'Document rejected successfully']);
            } else {
                throw new \Exception('Failed to reject document');
            }
            
        } catch (\Exception $e) {
            error_log("Document rejection error: " . $e->getMessage());
            $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Display helper-specific medical and training records
     */
    public function helperMedicalTraining(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;

        $helperId = (int)($this->params['id'] ?? 0);
        if (!$helperId) {
            $this->redirect('/staff/medical-training?error=Invalid helper ID');
            return;
        }

        try {
            $helper = $this->helperModel->find($helperId);
            if (!$helper) {
                $this->redirect('/staff/medical-training?error=Helper not found');
                return;
            }

            $medicalRecords = $this->medicalRecordModel->getByHelperId($helperId);
            $trainingRecords = $this->trainingRecordModel->getByHelperId($helperId);

            $data = [
                'title' => 'Medical & Training - ' . $helper['name'],
                'helper' => $helper,
                'medical_records' => $medicalRecords,
                'training_records' => $trainingRecords
            ];

            $this->view('staff.helper-medical-training', $data);
        } catch (\Exception $e) {
            $this->redirect('/staff/medical-training?error=' . urlencode('Error loading helper records: ' . $e->getMessage()));
        }
    }

    /**
     * Add medical record
     */
    public function addMedicalRecord(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        try {
            $required = ['helper_id', 'record_type', 'record_date', 'status'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new \Exception("$field is required");
                }
            }

            $recordData = [
                'helper_id' => (int)$_POST['helper_id'],
                'record_type' => $_POST['record_type'],
                'record_date' => $_POST['record_date'],
                'doctor_name' => trim($_POST['doctor_name'] ?? ''),
                'clinic_hospital' => trim($_POST['clinic_hospital'] ?? ''),
                'status' => $_POST['status'],
                'notes' => trim($_POST['notes'] ?? ''),
                'expiry_date' => !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : null
            ];

            $recordId = $this->medicalRecordModel->create($recordData);

            $this->json([
                'success' => true,
                'message' => 'Medical record added successfully',
                'record_id' => $recordId
            ]);

        } catch (\Exception $e) {
            $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Add training record
     */
    public function addTrainingRecord(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        try {
            $required = ['helper_id', 'course_name', 'start_date'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new \Exception("$field is required");
                }
            }

            $recordData = [
                'helper_id' => (int)$_POST['helper_id'],
                'course_name' => trim($_POST['course_name']),
                'training_provider' => trim($_POST['training_provider'] ?? ''),
                'start_date' => $_POST['start_date'],
                'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
                'status' => $_POST['status'] ?? 'Enrolled',
                'certificate_number' => trim($_POST['certificate_number'] ?? ''),
                'certificate_expiry' => !empty($_POST['certificate_expiry']) ? $_POST['certificate_expiry'] : null,
                'notes' => trim($_POST['notes'] ?? '')
            ];

            $recordId = $this->trainingRecordModel->create($recordData);

            $this->json([
                'success' => true,
                'message' => 'Training record added successfully',
                'record_id' => $recordId
            ]);

        } catch (\Exception $e) {
            $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Update medical record
     */
    public function updateMedicalRecord(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $recordId = (int)($this->params['id'] ?? 0);
        if (!$recordId) {
            $this->json(['success' => false, 'error' => 'Invalid record ID']);
            return;
        }

        try {
            $record = $this->medicalRecordModel->find($recordId);
            if (!$record) {
                throw new \Exception('Medical record not found');
            }

            $updateData = [
                'record_type' => $_POST['record_type'] ?? $record['record_type'],
                'record_date' => $_POST['record_date'] ?? $record['record_date'],
                'doctor_name' => trim($_POST['doctor_name'] ?? $record['doctor_name']),
                'clinic_hospital' => trim($_POST['clinic_hospital'] ?? $record['clinic_hospital']),
                'status' => $_POST['status'] ?? $record['status'],
                'notes' => trim($_POST['notes'] ?? $record['notes']),
                'expiry_date' => !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : $record['expiry_date']
            ];

            $success = $this->medicalRecordModel->update($recordId, $updateData);

            if ($success || $this->medicalRecordModel->find($recordId)) {
                $this->json([
                    'success' => true,
                    'message' => 'Medical record updated successfully'
                ]);
            } else {
                throw new \Exception('Failed to update medical record');
            }

        } catch (\Exception $e) {
            $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Update training record
     */
    public function updateTrainingRecord(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        $recordId = (int)($this->params['id'] ?? 0);
        if (!$recordId) {
            $this->json(['success' => false, 'error' => 'Invalid record ID']);
            return;
        }

        try {
            $record = $this->trainingRecordModel->find($recordId);
            if (!$record) {
                throw new \Exception('Training record not found');
            }

            $updateData = [
                'course_name' => trim($_POST['course_name'] ?? $record['course_name']),
                'training_provider' => trim($_POST['training_provider'] ?? $record['training_provider']),
                'start_date' => $_POST['start_date'] ?? $record['start_date'],
                'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : $record['end_date'],
                'status' => $_POST['status'] ?? $record['status'],
                'certificate_number' => trim($_POST['certificate_number'] ?? $record['certificate_number']),
                'certificate_expiry' => !empty($_POST['certificate_expiry']) ? $_POST['certificate_expiry'] : $record['certificate_expiry'],
                'notes' => trim($_POST['notes'] ?? $record['notes'])
            ];

            $success = $this->trainingRecordModel->update($recordId, $updateData);

            if ($success || $this->trainingRecordModel->find($recordId)) {
                $this->json([
                    'success' => true,
                    'message' => 'Training record updated successfully'
                ]);
            } else {
                throw new \Exception('Failed to update training record');
            }

        } catch (\Exception $e) {
            $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // ===== DEPLOYMENT MANAGEMENT METHODS =====

    /**
     * Display deployments management
     */
    public function deployments(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;

        try {
            $deployments = $this->deploymentModel->getAllWithInfo();
            $statistics = $this->deploymentModel->getStatistics();
            $endingSoon = $this->deploymentModel->getEndingSoon(30);

            $data = [
                'title' => 'Deployment Management',
                'deployments' => $deployments,
                'statistics' => $statistics,
                'ending_soon' => $endingSoon
            ];

            $this->view('staff.deployments', $data);
        } catch (\Exception $e) {
            $this->redirect('/staff?error=' . urlencode('Error loading deployments: ' . $e->getMessage()));
        }
    }

    /**
     * Show create deployment form
     */
    public function createDeployment(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;

        try {
            $availableHelpers = array_filter(
                $this->helperModel->findAll(),
                fn($h) => in_array($h['status'], ['Available', 'Reserved'])
            );
            
            // Get clients from database directly for now
            $db = \Core\Database::getInstance();
            $clients = $db->fetchAll('SELECT * FROM clients WHERE status = "Active"');

            $data = [
                'title' => 'Create New Deployment',
                'helpers' => $availableHelpers,
                'clients' => $clients
            ];

            $this->view('staff.create-deployment', $data);
        } catch (\Exception $e) {
            $this->redirect('/staff/deployments?error=' . urlencode('Error loading create deployment: ' . $e->getMessage()));
        }
    }

    /**
     * Store new deployment
     */
    public function storeDeployment(): void
    {
        $staffId = $this->requireAuth();
        if (!$staffId) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }

        try {
            $required = ['helper_id', 'client_id', 'deployment_date'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new \Exception("$field is required");
                }
            }

            $deploymentData = [
                'contract_id' => (int)($_POST['contract_id'] ?? 0) ?: null,
                'helper_id' => (int)$_POST['helper_id'],
                'client_id' => (int)$_POST['client_id'],
                'deployment_date' => $_POST['deployment_date'],
                'expected_end_date' => !empty($_POST['expected_end_date']) ? $_POST['expected_end_date'] : null,
                'status' => $_POST['status'] ?? 'Planned',
                'notes' => trim($_POST['notes'] ?? '')
            ];

            $deploymentId = $this->deploymentModel->createWithHelperUpdate($deploymentData);

            $this->json([
                'success' => true,
                'message' => 'Deployment created successfully',
                'deployment_id' => $deploymentId
            ]);

        } catch (\Exception $e) {
            $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
