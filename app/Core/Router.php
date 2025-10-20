<?php

namespace Core;

/**
 * Simple router for handling HTTP requests
 */
class Router
{
    private array $routes = [];
    private array $params = [];
    private string $basePath = '';

    public function __construct()
    {
        // Home/Landing page
        $this->addRoute('GET', '/', 'Controllers\HomeController@index');
        
        // General authentication routes
        $this->addRoute('GET', '/login', 'Controllers\HomeController@login');
        $this->addRoute('GET', '/signup', 'Controllers\HomeController@signup');
        $this->addRoute('POST', '/register', 'Controllers\HomeController@register');
        $this->addRoute('POST', '/authenticate', 'Controllers\HomeController@authenticate');
        $this->addRoute('GET', '/logout', 'Controllers\HomeController@logout');
        
        // User Dashboard routes
        $this->addRoute('GET', '/maid', 'Controllers\MaidController@index');
        $this->addRoute('GET', '/client', 'Controllers\ClientDashboardController@index');
        $this->addRoute('GET', '/staff', 'Controllers\StaffController@index');
        
        // Maid authentication routes
        $this->addRoute('GET', '/maid/login', 'Controllers\MaidController@login');
        $this->addRoute('GET', '/maid/signup', 'Controllers\MaidController@signup');
        $this->addRoute('POST', '/maid/register', 'Controllers\MaidController@register');
        $this->addRoute('POST', '/maid/authenticate', 'Controllers\MaidController@authenticate');
        $this->addRoute('GET', '/maid/logout', 'Controllers\MaidController@logout');
        
        // Staff authentication routes
        $this->addRoute('GET', '/staff/login', 'Controllers\StaffController@login');
        $this->addRoute('GET', '/staff/signup', 'Controllers\StaffController@signup');
        $this->addRoute('POST', '/staff/register', 'Controllers\StaffController@register');
        $this->addRoute('POST', '/staff/authenticate', 'Controllers\StaffController@authenticate');
        $this->addRoute('GET', '/staff/logout', 'Controllers\StaffController@logout');
        
        // Client authentication routes
        $this->addRoute('GET', '/client/login', 'Controllers\ClientDashboardController@login');
        $this->addRoute('GET', '/client/signup', 'Controllers\ClientDashboardController@signup');
        $this->addRoute('POST', '/client/register', 'Controllers\ClientDashboardController@register');
        $this->addRoute('POST', '/client/authenticate', 'Controllers\ClientDashboardController@authenticate');
        $this->addRoute('GET', '/client/logout', 'Controllers\ClientDashboardController@logout');
        
        // Client specific routes
        $this->addRoute('GET', '/client/browse-helpers', 'Controllers\ClientDashboardController@browseHelpers');
        $this->addRoute('GET', '/client/my-requests', 'Controllers\ClientDashboardController@myRequests');
        $this->addRoute('POST', '/client/submit-request', 'Controllers\ClientDashboardController@submitRequest');
        $this->addRoute('GET', '/client/profile', 'Controllers\ClientDashboardController@profile');
        $this->addRoute('POST', '/client/update-profile', 'Controllers\ClientDashboardController@updateProfile');
        $this->addRoute('POST', '/client/upload-photo', 'Controllers\ClientDashboardController@uploadPhoto');
        $this->addRoute('POST', '/client/change-password', 'Controllers\ClientDashboardController@changePassword');
        $this->addRoute('GET', '/client/helper-details/{id}', 'Controllers\ClientDashboardController@helperDetails');
        $this->addRoute('GET', '/client/request-details/{id}', 'Controllers\ClientDashboardController@requestDetails');
        $this->addRoute('POST', '/client/cancel-request', 'Controllers\ClientDashboardController@cancelRequest');
        
        // Staff specific routes (require authentication)
        $this->addRoute('GET', '/staff/profile', 'Controllers\StaffController@profile');
        $this->addRoute('POST', '/staff/update-profile', 'Controllers\StaffController@updateProfile');
        $this->addRoute('POST', '/staff/upload-photo', 'Controllers\StaffController@uploadPhoto');
        
        // Staff management routes
        $this->addRoute('GET', '/staff/helper-management', 'Controllers\StaffController@helperManagement');
        $this->addRoute('GET', '/staff/helper-management/create', 'Controllers\StaffController@createHelper');
        $this->addRoute('POST', '/staff/helper-management/store', 'Controllers\StaffController@storeHelper');
        $this->addRoute('GET', '/staff/helper-management/edit/{id}', 'Controllers\StaffController@editHelper');
        $this->addRoute('POST', '/staff/helper-management/update/{id}', 'Controllers\StaffController@updateHelper');
        $this->addRoute('POST', '/staff/helper-management/delete/{id}', 'Controllers\StaffController@deleteHelper');
        
        // Medical & Training routes
        $this->addRoute('GET', '/staff/medical-training', 'Controllers\StaffController@medicalTraining');
        $this->addRoute('GET', '/staff/medical-training/helper/{id}', 'Controllers\StaffController@helperMedicalTraining');
        $this->addRoute('POST', '/staff/medical-training/add-medical', 'Controllers\StaffController@addMedicalRecord');
        $this->addRoute('POST', '/staff/medical-training/add-training', 'Controllers\StaffController@addTrainingRecord');
        $this->addRoute('POST', '/staff/medical-training/update-medical/{id}', 'Controllers\StaffController@updateMedicalRecord');
        $this->addRoute('POST', '/staff/medical-training/update-training/{id}', 'Controllers\StaffController@updateTrainingRecord');
        
        // Deployment routes
        $this->addRoute('GET', '/staff/deployments', 'Controllers\StaffController@deployments');
        $this->addRoute('GET', '/staff/deployments/create', 'Controllers\StaffController@createDeployment');
        $this->addRoute('POST', '/staff/deployments/store', 'Controllers\StaffController@storeDeployment');
        $this->addRoute('GET', '/staff/deployments/edit/{id}', 'Controllers\StaffController@editDeployment');
        $this->addRoute('POST', '/staff/deployments/update/{id}', 'Controllers\StaffController@updateDeployment');
        
        // Maid specific routes (require authentication)
        $this->addRoute('GET', '/maid/profile', 'Controllers\MaidController@profile');
        $this->addRoute('GET', '/maid/documents', 'Controllers\MaidController@documents');
        $this->addRoute('GET', '/maid/employment', 'Controllers\MaidController@employment');
        
        // Maid document upload routes
        $this->addRoute('POST', '/maid/upload-identity-document', 'Controllers\MaidController@uploadIdentityDocument');
        $this->addRoute('POST', '/maid/upload-medical-document', 'Controllers\MaidController@uploadMedicalDocument');
        $this->addRoute('POST', '/maid/upload-training-document', 'Controllers\MaidController@uploadTrainingDocument');
        $this->addRoute('POST', '/maid/delete-document', 'Controllers\MaidController@deleteDocument');
        $this->addRoute('POST', '/maid/get-document-url', 'Controllers\MaidController@getDocumentUrl');
        
        // Maid profile management routes
        $this->addRoute('POST', '/maid/update-profile', 'Controllers\MaidController@updateProfile');
        $this->addRoute('POST', '/maid/upload-photo', 'Controllers\MaidController@uploadPhoto');
        
        // Client specific routes
        $this->addRoute('GET', '/client/browse-helpers', 'Controllers\ClientDashboardController@browseHelpers');
        $this->addRoute('GET', '/client/my-requests', 'Controllers\ClientDashboardController@myRequests');
        
        // Staff specific routes
        $this->addRoute('GET', '/staff/helper-management', 'Controllers\StaffController@helperManagement');
        $this->addRoute('GET', '/staff/request-workflow', 'Controllers\StaffController@requestWorkflow');
        $this->addRoute('POST', '/staff/update-request-status', 'Controllers\StaffController@updateRequestStatus');
        $this->addRoute('GET', '/staff/medical-training', 'Controllers\StaffController@medicalTraining');
        $this->addRoute('GET', '/staff/deployments', 'Controllers\StaffController@deployments');
        
        // Staff document review routes
        $this->addRoute('GET', '/staff/document-review', 'Controllers\StaffController@documentReview');
        $this->addRoute('POST', '/staff/review-document', 'Controllers\StaffController@reviewDocument');
        $this->addRoute('POST', '/staff/get-document-url', 'Controllers\StaffController@getDocumentUrl');
        $this->addRoute('POST', '/staff/approve-document', 'Controllers\StaffController@approveDocument');
        $this->addRoute('POST', '/staff/reject-document', 'Controllers\StaffController@rejectDocument');
        $this->addRoute('GET', '/staff/helper-document-summary', 'Controllers\StaffController@getHelperDocumentSummary');
    }

    /**
     * Add a route
     */
    public function addRoute(string $method, string $path, string $handler): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    /**
     * Dispatch the current request
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $this->getCurrentPath();

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $path)) {
                $this->callHandler($route['handler']);
                return;
            }
        }

        // No route found
        $this->handleNotFound();
    }

    /**
     * Get current request path
     */
    private function getCurrentPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remove query string
        if (($pos = strpos($path, '?')) !== false) {
            $path = substr($path, 0, $pos);
        }
        
        // Remove base path if set
        if ($this->basePath && strpos($path, $this->basePath) === 0) {
            $path = substr($path, strlen($this->basePath));
        }
        
        // Handle index.php requests - treat them as root
        if ($path === '/index.php') {
            $path = '/';
        }
        
        // Ensure path starts with /
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }
        
        return $path ?: '/';
    }

    /**
     * Check if route path matches current path
     */
    private function matchPath(string $routePath, string $currentPath): bool
    {
        // Handle parameter routes like /path/{id}
        $routePattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        $routePattern = str_replace('/', '\/', $routePattern);
        $routePattern = '/^' . $routePattern . '$/';
        
        if (preg_match($routePattern, $currentPath, $matches)) {
            // Store parameters for use in controller
            array_shift($matches); // Remove full match
            $this->params = $matches;
            
            // Extract parameter names from route
            preg_match_all('/\{([^}]+)\}/', $routePath, $paramNames);
            if (!empty($paramNames[1])) {
                $this->params = array_combine($paramNames[1], $matches);
            }
            
            return true;
        }
        
        return false;
    }

    /**
     * Call the route handler
     */
    private function callHandler(string $handler): void
    {
        if (strpos($handler, '@') !== false) {
            [$controllerClass, $method] = explode('@', $handler);
            
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                
                // Set parameters in controller if it has the property
                if (property_exists($controller, 'params')) {
                    $controller->params = $this->params;
                }
                
                if (method_exists($controller, $method)) {
                    $controller->$method();
                    return;
                }
            }
        }
        
        throw new \Exception("Handler not found: {$handler}");
    }

    /**
     * Handle 404 not found
     */
    private function handleNotFound(): void
    {
        http_response_code(404);
        echo "404 - Page Not Found";
    }

    /**
     * Set base path for the application
     */
    public function setBasePath(string $basePath): void
    {
        $this->basePath = rtrim($basePath, '/');
    }
}