<?php

namespace Controllers;

use Core\Session;

/**
 * Base controller with common functionality
 */
abstract class BaseController
{
    protected Session $session;
    public array $params = [];

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * Render a view
     */
    protected function view(string $view, array $data = []): void
    {
        // Extract data for use in view
        extract($data);
        
        // Include the view file
        $viewFile = APP_ROOT . '/app/Views/' . str_replace('.', '/', $view) . '.php';
        
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            throw new \Exception("View not found: {$view}");
        }
    }

    /**
     * Redirect to a URL
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Return JSON response
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Validate CSRF token
     */
    protected function validateCsrf(): bool
    {
        $token = $_POST[CSRF_TOKEN_NAME] ?? '';
        return $this->session->validateCsrfToken($token);
    }
}