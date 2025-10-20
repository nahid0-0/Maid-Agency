<?php

namespace Core;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Exception;

/**
 * S3 Service for document management
 * Handles file uploads, downloads, and management for maid documents
 */
class S3Service
{
    private S3Client $s3Client;
    private string $bucket;
    private array $awsConfig;
    
    public function __construct()
    {
        // Check if Composer autoloader exists (following s3-test.php pattern)
        if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
            require_once __DIR__ . '/../../vendor/autoload.php';
        } else {
            throw new Exception('AWS SDK not installed. Please run: composer require aws/aws-sdk-php');
        }
        
        // Load environment variables if not already loaded
        if (!class_exists('EnvLoader')) {
            require_once __DIR__ . '/EnvLoader.php';
            \EnvLoader::load();
        }
        
        // AWS Configuration using environment variables
        $this->awsConfig = [
            'version' => 'latest',
            'region' => \EnvLoader::get('AWS_DEFAULT_REGION', 'us-east-1'),
            'credentials' => [
                'key' => \EnvLoader::get('AWS_ACCESS_KEY_ID'),
                'secret' => \EnvLoader::get('AWS_SECRET_ACCESS_KEY')
            ]
        ];
        
        $this->bucket = \EnvLoader::get('S3_BUCKET', 'your-actual-bucket-name');
        
        // Validate AWS credentials
        if (empty($this->awsConfig['credentials']['key']) || empty($this->awsConfig['credentials']['secret'])) {
            throw new Exception('AWS credentials not found in environment variables');
        }
        
        // Initialize S3 Client (following s3-test.php pattern)
        try {
            $this->s3Client = new S3Client($this->awsConfig);
        } catch (Exception $e) {
            throw new Exception('Error creating S3 client: ' . $e->getMessage());
        }
    }
    
    /**
     * Upload a document to S3
     * 
     * @param array $file The uploaded file array from $_FILES
     * @param string $folder The folder to upload to (identity, medical, training)
     * @param int $helperId The helper ID for organizing files
     * @return array Result with success status, URL, and metadata
     */
    public function uploadDocument(array $file, string $folder, int $helperId): array
    {
        // Follow s3-test.php validation pattern
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'error' => 'File upload error: ' . $file['error']
            ];
        }
        
        try {
            // Validate file type
            $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception('Invalid file type. Only PDF, JPG, and PNG files are allowed.');
            }
            
            // Validate file size (5MB max)
            $maxSize = 5 * 1024 * 1024; // 5MB
            if ($file['size'] > $maxSize) {
                throw new Exception('File too large. Maximum size is 5MB.');
            }
            
            // Generate unique filename (following s3-test.php pattern)
            $fileName = $folder . '/helper_' . $helperId . '/' . time() . '_' . basename($file['name']);
            
            // Upload file to S3 (exactly like s3-test.php)
            $result = $this->s3Client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $fileName,
                'Body' => fopen($file['tmp_name'], 'r'),
                'ContentType' => $file['type'] ?: 'application/octet-stream'
                // Note: ACL removed - bucket doesn't allow ACLs (security best practice)
            ]);
            
            // Generate file URL (following s3-test.php pattern)
            $fileUrl = "https://{$this->bucket}.s3.{$this->awsConfig['region']}.amazonaws.com/{$fileName}";
            
            return [
                'success' => true,
                'url' => $fileUrl,
                'key' => $fileName,
                'etag' => $result['ETag'],
                'original_filename' => $file['name'],
                'file_size' => $file['size'],
                'mime_type' => $file['type']
            ];
            
        } catch (AwsException $e) {
            return [
                'success' => false,
                'error' => 'AWS Error: ' . $e->getMessage(),
                'code' => $e->getAwsErrorCode()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Delete a document from S3
     * 
     * @param string $key The S3 key of the file to delete
     * @return array Result with success status
     */
    public function deleteDocument(string $key): array
    {
        try {
            $this->s3Client->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $key
            ]);
            
            return [
                'success' => true,
                'message' => 'Document deleted successfully'
            ];
            
        } catch (AwsException $e) {
            return [
                'success' => false,
                'error' => 'AWS Error: ' . $e->getMessage(),
                'code' => $e->getAwsErrorCode()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate a presigned URL for secure document viewing
     * 
     * @param string $key The S3 key of the file
     * @param int $expiration Expiration time in seconds (default: 1 hour)
     * @return array Result with success status and URL
     */
    public function getPresignedUrl(string $key, int $expiration = 3600): array
    {
        try {
            // First check if file exists
            if (!$this->fileExists($key)) {
                return [
                    'success' => false,
                    'error' => 'File not found in S3 storage',
                    'code' => 'NoSuchKey'
                ];
            }
            
            $command = $this->s3Client->getCommand('GetObject', [
                'Bucket' => $this->bucket,
                'Key' => $key
            ]);
            
            $request = $this->s3Client->createPresignedRequest($command, "+{$expiration} seconds");
            $url = (string)$request->getUri();
            
            return [
                'success' => true,
                'url' => $url,
                'expires_in' => $expiration
            ];
            
        } catch (AwsException $e) {
            return [
                'success' => false,
                'error' => 'AWS Error: ' . $e->getMessage(),
                'code' => $e->getAwsErrorCode()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Check if a file exists in S3
     * 
     * @param string $key The S3 key to check
     * @return bool True if file exists, false otherwise
     */
    public function fileExists(string $key): bool
    {
        try {
            $this->s3Client->headObject([
                'Bucket' => $this->bucket,
                'Key' => $key
            ]);
            return true;
        } catch (AwsException $e) {
            return false;
        }
    }
    
    /**
     * Get file metadata from S3
     * 
     * @param string $key The S3 key
     * @return array File metadata or error
     */
    public function getFileMetadata(string $key): array
    {
        try {
            $result = $this->s3Client->headObject([
                'Bucket' => $this->bucket,
                'Key' => $key
            ]);
            
            return [
                'success' => true,
                'size' => $result['ContentLength'],
                'type' => $result['ContentType'],
                'last_modified' => $result['LastModified'],
                'metadata' => $result['Metadata'] ?? []
            ];
            
        } catch (AwsException $e) {
            return [
                'success' => false,
                'error' => 'AWS Error: ' . $e->getMessage(),
                'code' => $e->getAwsErrorCode()
            ];
        }
    }
    
    /**
     * List all documents for a helper
     * 
     * @param int $helperId The helper ID
     * @param string $folder Optional folder filter (identity, medical, training)
     * @return array List of documents
     */
    public function listHelperDocuments(int $helperId, string $folder = ''): array
    {
        try {
            $prefix = $folder ? "{$folder}/helper_{$helperId}/" : "helper_{$helperId}/";
            
            $result = $this->s3Client->listObjectsV2([
                'Bucket' => $this->bucket,
                'Prefix' => $prefix
            ]);
            
            $documents = [];
            if (isset($result['Contents'])) {
                foreach ($result['Contents'] as $object) {
                    $documents[] = [
                        'key' => $object['Key'],
                        'size' => $object['Size'],
                        'last_modified' => $object['LastModified'],
                        'url' => "https://{$this->bucket}.s3.{$this->config['region']}.amazonaws.com/{$object['Key']}"
                    ];
                }
            }
            
            return [
                'success' => true,
                'documents' => $documents
            ];
            
        } catch (AwsException $e) {
            return [
                'success' => false,
                'error' => 'AWS Error: ' . $e->getMessage(),
                'code' => $e->getAwsErrorCode()
            ];
        }
    }
}
