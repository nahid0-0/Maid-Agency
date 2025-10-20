<?php

namespace Core;

/**
 * Local Storage Service - Fallback for S3 when AWS is not configured
 */
class LocalStorageService
{
    private string $basePath;
    private string $baseUrl;

    public function __construct()
    {
        $this->basePath = APP_ROOT . '/storage/uploads';
        $this->baseUrl = '/storage/uploads'; // Relative to web root
        
        // Create base directory if it doesn't exist
        if (!is_dir($this->basePath)) {
            mkdir($this->basePath, 0755, true);
        }
    }

    /**
     * Upload a document to local storage
     */
    public function uploadDocument(array $file, string $folder, int $helperId): array
    {
        try {
            // Validate file - more flexible for testing
            if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
                throw new \Exception('Invalid file upload - no temp file');
            }
            
            // Check if file exists (works for both uploaded files and test files)
            if (!file_exists($file['tmp_name'])) {
                throw new \Exception('Invalid file upload - temp file does not exist');
            }

            // Create folder structure
            $folderPath = $this->basePath . '/' . $folder;
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0755, true);
            }

            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = $helperId . '_' . time() . '_' . uniqid() . '.' . $extension;
            $filePath = $folderPath . '/' . $filename;
            $key = $folder . '/' . $filename;

            // Move file - use copy for test files, move_uploaded_file for real uploads
            $moveSuccess = false;
            if (is_uploaded_file($file['tmp_name'])) {
                // Real uploaded file
                $moveSuccess = move_uploaded_file($file['tmp_name'], $filePath);
            } else {
                // Test file or other file
                $moveSuccess = copy($file['tmp_name'], $filePath);
            }
            
            if (!$moveSuccess) {
                throw new \Exception('Failed to move/copy file');
            }

            // Set proper permissions
            chmod($filePath, 0644);

            return [
                'success' => true,
                'url' => $this->baseUrl . '/' . $key,
                'key' => $key,
                'path' => $filePath,
                'original_filename' => $file['name'],
                'file_size' => filesize($filePath),
                'mime_type' => $file['type']
            ];

        } catch (\Exception $e) {
            error_log("Local storage upload error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete a document from local storage
     */
    public function deleteDocument(string $key): array
    {
        try {
            $filePath = $this->basePath . '/' . $key;
            
            if (file_exists($filePath)) {
                if (unlink($filePath)) {
                    return ['success' => true];
                } else {
                    throw new \Exception('Failed to delete file');
                }
            } else {
                return ['success' => true, 'message' => 'File already deleted'];
            }

        } catch (\Exception $e) {
            error_log("Local storage delete error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get URL for a document (for local storage, this is just the relative path)
     */
    public function getPresignedUrl(string $key, int $expiration = 3600): array
    {
        $filePath = $this->basePath . '/' . $key;
        
        if (file_exists($filePath)) {
            return [
                'success' => true,
                'url' => $this->baseUrl . '/' . $key,
                'expires_in' => $expiration
            ];
        } else {
            return [
                'success' => false,
                'error' => 'File not found'
            ];
        }
    }

    /**
     * Check if file exists
     */
    public function fileExists(string $key): bool
    {
        return file_exists($this->basePath . '/' . $key);
    }
}