<?php
ob_start();
?>

<div class="user-dashboard">
    <a href="/staff" class="back-link">← Back to Dashboard</a>
    
    <div class="dashboard-header">
        <h1>Helper Management</h1>
        <p>Manage helper profiles and track their employment status</p>
    </div>
    
    <div class="dashboard-content">
        <div class="search-section">
            <h3>Search Helpers</h3>
            <p>Filter helpers by skills, languages, experience, and availability.</p>
            
        </div>
        
        <div class="helpers-list-section">
            <h3>All Helpers</h3>
            <p>Comprehensive list of all registered helpers.</p>
            
        </div>
        
        <div class="add-helper-section">
            <h3>Add New Helper</h3>
            <p>Register a new helper profile in the system.</p>
            
        </div>
    </div>
</div>

<style>
.user-dashboard {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.dashboard-header {
    text-align: center;
    margin-bottom: 40px;
}

.dashboard-content {
    display: grid;
    gap: 30px;
}

.search-section, .helpers-list-section, .add-helper-section {
    background: white;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
</style>

<?php
$content = ob_get_clean();
$title = 'Helper Management';
include APP_ROOT . '/app/Views/layouts/main.php';
?>

