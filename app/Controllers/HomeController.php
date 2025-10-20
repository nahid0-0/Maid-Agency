<?php

namespace Controllers;

/**
 * Home page controller - handles role selection and routing
 */
class HomeController extends BaseController
{
    /**
     * Display role selection page (this will be moved to a welcome/landing controller later)
     */
    public function index(): void
    {
        $data = [
            'title' => 'Welcome to ' . APP_NAME,
            'hideNavbar' => true
        ];
        
        $this->view('home.index', $data);
    }
    
    /**
     * Display general login page - redirect to client login
     */
    public function login(): void
    {
        // Since most users are clients, redirect directly to client login
        $this->redirect('/client/login');
    }
    
    /**
     * Handle authentication - redirect to client login
     */
    public function authenticate(): void
    {
        // Redirect to client login since most users are clients
        $this->redirect('/client/login');
    }
    
    /**
     * Handle logout - clear all sessions
     */
    public function logout(): void
    {
        $this->session->destroy();
        $this->redirect('/?message=' . urlencode('You have been logged out successfully'));
    }
    
    /**
     * Display general signup page - redirect to client signup
     */
    public function signup(): void
    {
        // Since most users are clients, redirect directly to client signup
        $this->redirect('/client/signup');
    }
    
    /**
     * Handle general registration - redirect to client registration
     */
    public function register(): void
    {
        // Redirect to client registration since most users are clients
        $this->redirect('/client/signup');
    }
}