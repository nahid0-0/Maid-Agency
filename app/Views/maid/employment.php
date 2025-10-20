<?php
ob_start();
?>

<div class="redirect-page">
    <div class="redirect-content">
        <div class="redirect-icon">🏠</div>
        <h1>Welcome to Your Dashboard</h1>
        <p>Complete your profile and upload your documents to get started</p>
        
        <div class="action-buttons">
            <a href="/maid/profile" class="btn-primary">
                <span class="btn-icon">👤</span>
                Complete Profile
            </a>
            <a href="/maid/documents" class="btn-primary">
                <span class="btn-icon">📄</span>
                Upload Documents
            </a>
            <a href="/maid" class="btn-secondary">
                <span class="btn-icon">🏠</span>
                Go to Dashboard
            </a>
        </div>
    </div>
</div>

<style>
.redirect-page {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.redirect-content {
    text-align: center;
    max-width: 600px;
    background: white;
    padding: 60px 40px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.redirect-icon {
    font-size: 64px;
    margin-bottom: 24px;
}

.redirect-content h1 {
    font-size: 28px;
    color: #1f2937;
    margin-bottom: 16px;
    font-weight: 600;
}

.redirect-content p {
    font-size: 16px;
    color: #6b7280;
    margin-bottom: 40px;
    line-height: 1.6;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 16px;
    max-width: 300px;
    margin: 0 auto;
}

.btn-primary, .btn-secondary {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 16px 24px;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    font-size: 16px;
    transition: all 0.2s ease;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a6fd8;
    text-decoration: none;
    color: white;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover {
    background: #e5e7eb;
    text-decoration: none;
    color: #1f2937;
    transform: translateY(-1px);
}

.btn-icon {
    font-size: 18px;
}

@media (max-width: 480px) {
    .redirect-content {
        padding: 40px 20px;
    }
    
    .redirect-content h1 {
        font-size: 24px;
    }
    
    .redirect-icon {
        font-size: 48px;
    }
}
</style>

<?php
$content = ob_get_clean();
$title = 'Dashboard';
include APP_ROOT . '/app/Views/layouts/main.php';
?>

