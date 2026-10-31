<?php

namespace Core;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

/**
 * Storage service — backed by Cloudinary.
 *
 * Drop-in replacement for the old S3Service; all controllers keep the
 * same uploadDocument / deleteDocument / getPresignedUrl / fileExists API.
 */
class S3Service
{
    private Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => CLOUDINARY_CLOUD_NAME,
                'api_key'    => CLOUDINARY_API_KEY,
                'api_secret' => CLOUDINARY_API_SECRET,
            ],
            'url' => ['secure' => true],
        ]);
    }

    /**
     * Upload a file to Cloudinary.
     *
     * @param array  $file     $_FILES entry
     * @param string $folder   Logical folder name (e.g. 'identity', 'medical')
     * @param int    $entityId Helper / client / staff ID — used to namespace the upload
     */
    public function uploadDocument(array $file, string $folder, int $entityId): array
    {
        try {
            if (empty($file['tmp_name']) || !file_exists($file['tmp_name'])) {
                throw new \Exception('Invalid file upload');
            }

            $publicId = $folder . '/' . $entityId . '_' . time() . '_' . uniqid();

            $result = $this->cloudinary->uploadApi()->upload($file['tmp_name'], [
                'public_id'       => $publicId,
                'folder'          => 'maid-agency/' . $folder,
                'resource_type'   => 'auto',
                'use_filename'    => false,
                'unique_filename' => true,
            ]);

            return [
                'success'           => true,
                'url'               => $result['secure_url'],
                'key'               => $result['public_id'],
                'original_filename' => $file['name'],
                'file_size'         => $file['size'],
                'mime_type'         => $file['type'],
            ];

        } catch (\Exception $e) {
            error_log('Cloudinary upload error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Delete a file from Cloudinary by its public_id.
     */
    public function deleteDocument(string $key): array
    {
        try {
            $this->cloudinary->uploadApi()->destroy($key, ['resource_type' => 'auto']);
            return ['success' => true];
        } catch (\Exception $e) {
            error_log('Cloudinary delete error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Return a direct URL for a Cloudinary asset.
     * (Cloudinary URLs are permanent; $expiration is kept for API compatibility.)
     */
    public function getPresignedUrl(string $key, int $expiration = 3600): array
    {
        try {
            $url = $this->cloudinary->image($key)->toUrl();
            return ['success' => true, 'url' => (string) $url, 'expires_in' => $expiration];
        } catch (\Exception $e) {
            error_log('Cloudinary URL error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Check if a Cloudinary asset exists.
     */
    public function fileExists(string $key): bool
    {
        try {
            $this->cloudinary->adminApi()->asset($key);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
