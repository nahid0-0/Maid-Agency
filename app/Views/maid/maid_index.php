<?php
ob_start();
?>

<div class="user-home">
    <div class="welcome-header">
        <h1>🧹 Maid Dashboard</h1>
        <p>Welcome to your personal workspace</p>
    </div>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-icon">👤</div>
            <h3>My Profile</h3>
            <p>View and update your personal information</p>
            <a href="/maid/profile" class="card-link">Manage Profile</a>
        </div>
        
        <div class="dashboard-card">
            <div class="card-icon">📄</div>
            <h3>My Documents</h3>
            <p>Passport, medical records, and certificates</p>
            <a href="/maid/documents" class="card-link">Manage Documents</a>
        </div>
    </div>
</div>

<style>
.user-home {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.welcome-header {
    text-align: center;
    margin-bottom: 40px;
}

.welcome-header h1 {
    font-size: 32px;
    color: #1f2937;
    margin-bottom: 10px;
}

.welcome-header p {
    color: #6b7280;
    font-size: 16px;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.dashboard-card {
    background: white;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.dashboard-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.card-icon {
    font-size: 48px;
    margin-bottom: 15px;
}

.dashboard-card h3 {
    color: #1f2937;
    margin-bottom: 10px;
    font-size: 20px;
}

.dashboard-card p {
    color: #6b7280;
    margin-bottom: 20px;
    font-size: 14px;
}

.card-link {
    display: inline-block;
    background: #667eea;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    transition: background 0.2s ease;
}

.card-link:hover {
    background: #5a6fd8;
    text-decoration: none;
    color: white;
}
</style>

<?php
$content = ob_get_clean();
$title = 'Maid Home';
include APP_ROOT . '/app/Views/layouts/main.php';
?>
