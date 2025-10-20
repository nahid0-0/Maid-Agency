<?php

namespace Controllers;

use Core\S3Service;
use Models\HelperIdentityDocument;
use Models\HelperMedicalDocument;
use Models\HelperTrainingDocument;
use Models\Helper;

/**
 * Maid controller
 */
class MaidController extends BaseController
{
    private S3Service $s3Service;
    private HelperIdentityDocument $identityModel;
    private HelperMedicalDocument $medicalModel;
    private HelperTrainingDocument $trainingModel;
    private Helper $helperModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->s3Service = new S3Service();
        $this->identityModel = new HelperIdentityDocument();
        $this->medicalModel = new HelperMedicalDocument();
        $this->trainingModel = new HelperTrainingDocument();
        $this->helperModel = new Helper();
    }
    
    /**
     * Check if maid is authenticated
     */
    private function requireAuth(): ?int
    {
        $helperId = $this->session->get('maid_id');
        if (!$helperId) {
            $this->redirect('/maid/login');
            return null;
        }
        return $helperId;
    }
    /**
     * Show maid login page
     */
    public function login(): void
    {
        // If already logged in, redirect to dashboard
        if ($this->session->get('maid_id')) {
            $this->redirect('/maid');
            return;
        }
        
        $this->view('maid.login');
    }
    
    /**
     * Show maid signup page
     */
    public function signup(): void
    {
        // If already logged in, redirect to dashboard
        if ($this->session->get('maid_id')) {
            $this->redirect('/maid');
            return;
        }
        
        $this->view('maid.signup');
    }
    
    /**
     * Handle maid registration
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/maid/signup?error=' . urlencode('Invalid request method'));
            return;
        }

        // Determine if this is an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        try {
            // Validate required fields
            $requiredFields = ['full_name', 'email', 'password', 'nationality', 'date_of_birth', 'gender', 'experience_level'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new \Exception("$field is required");
                }
            }

            // Check if email already exists
            if ($this->helperModel->emailExists($_POST['email'])) {
                throw new \Exception('Email address is already registered');
            }

            // Validate age (25-55 years)
            $dob = new \DateTime($_POST['date_of_birth']);
            $today = new \DateTime();
            $age = $today->diff($dob)->y;

            if ($age < 25 || $age > 55) {
                throw new \Exception('Age must be between 25 and 55 years to register');
            }

            // Create helper record
            $helperId = $this->helperModel->createWithAuth([
                'name' => $_POST['full_name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'nationality' => $_POST['nationality'],
                'date_of_birth' => $_POST['date_of_birth'],
                'gender' => $_POST['gender'],
                'contact_number' => $_POST['contact_number'] ?? null,
                'emergency_contact' => $_POST['emergency_contact'] ?? null,
                'experience_level' => $_POST['experience_level'],
                'is_active' => true
            ]);

            if ($isAjax) {
                $this->json([
                    'success' => true,
                    'message' => 'Registration successful! You can now login.',
                    'helper_id' => $helperId
                ]);
            } else {
                $this->redirect('/maid/login?success=' . urlencode('Registration successful! You can now login.'));
            }

        } catch (\Exception $e) {
            if ($isAjax) {
                $this->json([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
            } else {
                $this->redirect('/maid/signup?error=' . urlencode($e->getMessage()));
            }
        }
    }
    
    /**
     * Handle maid authentication
     */
    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/maid/login?error=' . urlencode('Invalid request method'));
            return;
        }

        // Determine if this is an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                throw new \Exception('Email and password are required');
            }

            // Authenticate helper
            $helper = $this->helperModel->authenticate($email, $password);

            if (!$helper) {
                throw new \Exception('Invalid email or password');
            }

            // Set session
            $this->session->set('maid_id', $helper['id']);
            $this->session->set('maid_name', $helper['name']);
            $this->session->set('role', 'maid');

            if ($isAjax) {
                $this->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'redirect' => '/maid'
                ]);
            } else {
                $this->redirect('/maid');
            }

        } catch (\Exception $e) {
            if ($isAjax) {
                $this->json([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
            } else {
                $this->redirect('/maid/login?error=' . urlencode($e->getMessage()));
            }
        }
    }
    
    /**
     * Handle maid logout
     */
    public function logout(): void
    {
        // Ensure session is started before destroying
        if (!$this->session->isStarted()) {
            $this->session->start();
        }
        
        // Log the logout attempt
        $maidId = $this->session->get('maid_id');
        if ($maidId) {
            error_log("Maid logout: ID $maidId");
        }
        
        // Destroy session
        $this->session->destroy();
        
        // Redirect to login
        $this->redirect('/maid/login');
    }
    
    /**
     * Display maid home page
     */
    public function index(): void
    {
        $helperId = $this->requireAuth();
        if (!$helperId) return;
        
        // Get helper info
        $helper = $this->helperModel->find($helperId);
        
        $data = [
            'title' => 'Maid Dashboard',
            'helper' => $helper
        ];
        
        $this->view('maid.maid_index', $data);
    }
    
    /**
     * Display maid profile page
     */
    public function profile(): void
    {
        $helperId = $this->requireAuth();
        if (!$helperId) return;
        
        $helper = $this->helperModel->find($helperId);
        
        $data = [
            'title' => 'My Profile',
            'helper' => $helper
        ];
        
        $this->view('maid.profile', $data);
    }
    
    
    /**
     * Display maid documents page
     */
    public function documents(): void
    {
        $helperId = $this->requireAuth();
        if (!$helperId) return;
        
        // Get existing documents
        $identityDocs = $this->identityModel->getByHelperId($helperId);
        $medicalDocs = $this->medicalModel->getByHelperId($helperId);
        $trainingDocs = $this->trainingModel->getByHelperId($helperId);
        
        $data = [
            'title' => 'My Documents',
            'identity_documents' => $identityDocs,
            'medical_documents' => $medicalDocs,
            'training_documents' => $trainingDocs,
            'helper_id' => $helperId
        ];
        
        $this->view('maid.documents', $data);
    }
    
    /**
     * Upload identity document
     */
    public function uploadIdentityDocument(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }
        
        $helperId = $this->requireAuth();
        if (!$helperId) return;
        
        if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
            $this->json(['success' => false, 'error' => 'No file uploaded or upload error']);
            return;
        }
        
        // Upload to S3
        $uploadResult = $this->s3Service->uploadDocument($_FILES['document'], 'identity', $helperId);
        
        if (!$uploadResult['success']) {
            $this->json($uploadResult);
            return;
        }
        
        // Save to database
        $documentData = [
            'helper_id' => $helperId,
            'document_type' => $_POST['document_type'] ?? 'Other',
            'document_number' => !empty($_POST['document_number']) ? $_POST['document_number'] : null,
            'issuing_country' => !empty($_POST['issuing_country']) ? $_POST['issuing_country'] : null,
            'issue_date' => !empty($_POST['issue_date']) ? $_POST['issue_date'] : null,
            'expiry_date' => !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : null,
            's3_url' => $uploadResult['url'],
            's3_key' => $uploadResult['key'],
            'original_filename' => $uploadResult['original_filename'],
            'file_size' => $uploadResult['file_size'],
            'mime_type' => $uploadResult['mime_type']
        ];
        
        try {
            $documentId = $this->identityModel->create($documentData);
            $this->json([
                'success' => true,
                'message' => 'Identity document uploaded successfully',
                'document_id' => $documentId,
                'url' => $uploadResult['url']
            ]);
        } catch (Exception $e) {
            // If database save fails, try to delete from S3
            $this->s3Service->deleteDocument($uploadResult['key']);
            $this->json([
                'success' => false,
                'error' => 'Failed to save document information: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Upload medical document
     */
    public function uploadMedicalDocument(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }
        
        $helperId = $this->requireAuth();
        if (!$helperId) return;
        
        if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
            $this->json(['success' => false, 'error' => 'No file uploaded or upload error']);
            return;
        }
        
        // Upload to S3
        $uploadResult = $this->s3Service->uploadDocument($_FILES['document'], 'medical', $helperId);
        
        if (!$uploadResult['success']) {
            $this->json($uploadResult);
            return;
        }
        
        // Save to database
        $documentData = [
            'helper_id' => $helperId,
            'document_type' => $_POST['document_type'] ?? 'Other',
            'record_date' => !empty($_POST['record_date']) ? $_POST['record_date'] : date('Y-m-d'),
            'doctor_name' => !empty($_POST['doctor_name']) ? $_POST['doctor_name'] : null,
            'clinic_hospital' => !empty($_POST['clinic_hospital']) ? $_POST['clinic_hospital'] : null,
            'expiry_date' => !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : null,
            'notes' => !empty($_POST['notes']) ? $_POST['notes'] : null,
            's3_url' => $uploadResult['url'],
            's3_key' => $uploadResult['key'],
            'original_filename' => $uploadResult['original_filename'],
            'file_size' => $uploadResult['file_size'],
            'mime_type' => $uploadResult['mime_type']
        ];
        
        try {
            $documentId = $this->medicalModel->create($documentData);
            $this->json([
                'success' => true,
                'message' => 'Medical document uploaded successfully',
                'document_id' => $documentId,
                'url' => $uploadResult['url']
            ]);
        } catch (Exception $e) {
            // If database save fails, try to delete from S3
            $this->s3Service->deleteDocument($uploadResult['key']);
            $this->json([
                'success' => false,
                'error' => 'Failed to save document information: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Upload training document
     */
    public function uploadTrainingDocument(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }
        
        $helperId = $this->requireAuth();
        if (!$helperId) return;
        
        if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
            $this->json(['success' => false, 'error' => 'No file uploaded or upload error']);
            return;
        }
        
        // Upload to S3
        $uploadResult = $this->s3Service->uploadDocument($_FILES['document'], 'training', $helperId);
        
        if (!$uploadResult['success']) {
            $this->json($uploadResult);
            return;
        }
        
        // Save to database
        $documentData = [
            'helper_id' => $helperId,
            'document_type' => $_POST['document_type'] ?? 'Training Certificate',
            'course_name' => !empty($_POST['course_name']) ? $_POST['course_name'] : 'Unknown Course',
            'training_provider' => !empty($_POST['training_provider']) ? $_POST['training_provider'] : null,
            'certificate_number' => !empty($_POST['certificate_number']) ? $_POST['certificate_number'] : null,
            'issue_date' => !empty($_POST['issue_date']) ? $_POST['issue_date'] : null,
            'expiry_date' => !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : null,
            'notes' => !empty($_POST['notes']) ? $_POST['notes'] : null,
            's3_url' => $uploadResult['url'],
            's3_key' => $uploadResult['key'],
            'original_filename' => $uploadResult['original_filename'],
            'file_size' => $uploadResult['file_size'],
            'mime_type' => $uploadResult['mime_type']
        ];
        
        try {
            $documentId = $this->trainingModel->create($documentData);
            $this->json([
                'success' => true,
                'message' => 'Training document uploaded successfully',
                'document_id' => $documentId,
                'url' => $uploadResult['url']
            ]);
        } catch (Exception $e) {
            // If database save fails, try to delete from S3
            $this->s3Service->deleteDocument($uploadResult['key']);
            $this->json([
                'success' => false,
                'error' => 'Failed to save document information: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Delete a document
     */
    public function deleteDocument(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }
        
        $documentId = $_POST['document_id'] ?? null;
        $documentType = $_POST['document_type'] ?? null;
        
        if (!$documentId || !$documentType) {
            $this->json(['success' => false, 'error' => 'Missing document ID or type']);
            return;
        }
        
        try {
            // Get document info to get S3 key
            $document = null;
            switch ($documentType) {
                case 'identity':
                    $document = $this->identityModel->getById($documentId);
                    break;
                case 'medical':
                    $document = $this->medicalModel->getById($documentId);
                    break;
                case 'training':
                    $document = $this->trainingModel->getById($documentId);
                    break;
            }
            
            if (!$document) {
                $this->json(['success' => false, 'error' => 'Document not found']);
                return;
            }
            
            // Delete from S3
            $this->s3Service->deleteDocument($document['s3_key']);
            
            // Delete from database
            $deleteResult = false;
            switch ($documentType) {
                case 'identity':
                    $deleteResult = $this->identityModel->delete($documentId);
                    break;
                case 'medical':
                    $deleteResult = $this->medicalModel->delete($documentId);
                    break;
                case 'training':
                    $deleteResult = $this->trainingModel->delete($documentId);
                    break;
            }
            
            if ($deleteResult) {
                $this->json([
                    'success' => true,
                    'message' => 'Document deleted successfully'
                ]);
            } else {
                $this->json([
                    'success' => false,
                    'error' => 'Failed to delete document from database'
                ]);
            }
            
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'error' => 'Error deleting document: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get document URL for viewing
     */
    public function getDocumentUrl(): void
    {
        $helperId = $this->session->get('maid_id');
        if (!$helperId) {
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
            
            // Verify the document belongs to the current helper
            if ($document['helper_id'] != $helperId) {
                throw new \Exception('Access denied');
            }
            
            // Generate presigned URL for secure viewing
            $urlResult = $this->s3Service->getPresignedUrl($document['s3_key'], 3600); // 1 hour expiry
            
            if (!$urlResult['success']) {
                throw new \Exception('Failed to generate document URL: ' . $urlResult['error']);
            }
            
            $this->json([
                'success' => true,
                'document' => $document,
                'url' => $urlResult['url'],
                'expires_in' => $urlResult['expires_in']
            ]);
            
        } catch (\Exception $e) {
            $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Display maid employment page
     */
    public function employment(): void
    {
        $helperId = $this->requireAuth();
        if (!$helperId) return;
        
        $helper = $this->helperModel->find($helperId);
        
        $data = [
            'title' => 'Employment Status',
            'helper' => $helper
        ];
        
        $this->view('maid.employment', $data);
    }
    
    /**
     * Update helper profile
     */
    public function updateProfile(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }
        
        $helperId = $this->session->get('maid_id');
        if (!$helperId) {
            // Add more debugging info for authentication issues
            $sessionData = [
                'session_id' => session_id(),
                'session_status' => session_status(),
                'has_session_data' => !empty($_SESSION),
                'cookies_present' => !empty($_COOKIE)
            ];
            error_log('Profile update authentication failed: ' . json_encode($sessionData));
            
            $this->json([
                'success' => false, 
                'error' => 'Authentication required. Please refresh the page and try again.',
                'debug' => APP_DEBUG ? $sessionData : null
            ]);
            return;
        }
        
        try {
            // Validate required fields
            $requiredFields = ['name', 'email', 'nationality', 'date_of_birth', 'gender', 'experience_level'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new \Exception("$field is required");
                }
            }
            
            // Check if email is being changed and if it already exists for another user
            $currentHelper = $this->helperModel->find($helperId);
            if ($_POST['email'] !== $currentHelper['email']) {
                $existingHelper = $this->helperModel->findByEmail($_POST['email']);
                if ($existingHelper && $existingHelper['id'] != $helperId) {
                    throw new \Exception('Email address is already in use by another account');
                }
            }
            
            // Calculate age from date of birth
            $dob = new \DateTime($_POST['date_of_birth']);
            $today = new \DateTime();
            $age = $today->diff($dob)->y;
            
            // Validate age
            if ($age < 25 || $age > 55) {
                throw new \Exception('Age must be between 25 and 55 years');
            }
            
            // Prepare update data
            $updateData = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'nationality' => trim($_POST['nationality']),
                'date_of_birth' => $_POST['date_of_birth'],
                'age' => $age,
                'gender' => $_POST['gender'],
                'contact_number' => !empty($_POST['contact_number']) ? trim($_POST['contact_number']) : null,
                'emergency_contact' => !empty($_POST['emergency_contact']) ? trim($_POST['emergency_contact']) : null,
                'experience_level' => $_POST['experience_level'],
                'previous_experience' => !empty($_POST['previous_experience']) ? trim($_POST['previous_experience']) : null,
                'special_skills' => !empty($_POST['special_skills']) ? trim($_POST['special_skills']) : null,
                'salary_min' => !empty($_POST['salary_min']) ? floatval($_POST['salary_min']) : null,
                'salary_max' => !empty($_POST['salary_max']) ? floatval($_POST['salary_max']) : null,
                'dietary_restrictions' => !empty($_POST['dietary_restrictions']) ? trim($_POST['dietary_restrictions']) : null,
                'medical_conditions' => !empty($_POST['medical_conditions']) ? trim($_POST['medical_conditions']) : null
            ];
            
            // Validate salary range
            if ($updateData['salary_min'] && $updateData['salary_max']) {
                if ($updateData['salary_min'] > $updateData['salary_max']) {
                    throw new \Exception('Minimum salary cannot be greater than maximum salary');
                }
            }
            
            // Update the helper record
            $updateResult = $this->helperModel->update($helperId, $updateData);
            
            // Check if update was successful OR if no changes were needed (data was the same)
            // We verify the record exists to ensure the update didn't fail due to missing record
            $currentHelper = $this->helperModel->find($helperId);
            
            if ($currentHelper) {
                $this->json([
                    'success' => true,
                    'message' => 'Profile updated successfully',
                    'data' => $updateData,
                    'rows_affected' => $updateResult ? 1 : 0
                ]);
            } else {
                throw new \Exception('Failed to update profile - helper not found');
            }
            
        } catch (\Exception $e) {
            $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Upload profile photo
     */
    public function uploadPhoto(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request method']);
            return;
        }
        
        $helperId = $this->session->get('maid_id');
        if (!$helperId) {
            $this->json(['success' => false, 'error' => 'Authentication required']);
            return;
        }
        
        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $this->json(['success' => false, 'error' => 'No photo uploaded or upload error']);
            return;
        }
        
        try {
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
            
            // Get current helper to check for existing profile picture
            $currentHelper = $this->helperModel->find($helperId);
            $oldPictureKey = $currentHelper['profile_picture_key'] ?? null;
            
            // Upload to S3
            $uploadResult = $this->s3Service->uploadDocument($_FILES['photo'], 'profiles', $helperId);
            
            if (!$uploadResult['success']) {
                throw new \Exception($uploadResult['error']);
            }
            
            // Update helper record with photo URL and key
            $updateData = [
                'profile_picture_url' => $uploadResult['url'],
                'profile_picture_key' => $uploadResult['key']
            ];
            
            $success = $this->helperModel->update($helperId, $updateData);
            
            if ($success) {
                // If update successful and there was an old picture, delete it from S3
                if ($oldPictureKey) {
                    try {
                        $this->s3Service->deleteDocument($oldPictureKey);
                    } catch (\Exception $e) {
                        // Log the error but don't fail the upload
                        error_log("Failed to delete old profile picture: " . $e->getMessage());
                    }
                }
                
                $this->json([
                    'success' => true,
                    'message' => 'Profile photo updated successfully',
                    'photo_url' => $uploadResult['url']
                ]);
            } else {
                // If database update fails, try to delete the new uploaded file from S3
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
}
