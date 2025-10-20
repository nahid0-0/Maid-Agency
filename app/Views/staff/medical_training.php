<?php
ob_start();
?>

<div class="user-dashboard">
    <div class="dashboard-header">
        <h1>Medical & Training Records</h1>
        <p>Track helper medical records and training completion</p>
    </div>
    
    <div class="dashboard-content">
        <div class="medical-section">
            <h3>Medical Records</h3>
            <p>Schedule and track medical examinations for helpers.</p>
            
        </div>
        
        <div class="training-section">
            <h3>Training Management</h3>
            <p>Assign and track training completion for helpers.</p>
            
        </div>
        
        <div class="compliance-section">
            <h3>Compliance Status</h3>
            <p>Overview of helpers' medical and training compliance.</p>
            
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

.medical-section, .training-section, .compliance-section {
    background: white;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
</style>

<?php
$content = ob_get_clean();
$title = 'Medical & Training Records';
include APP_ROOT . '/app/Views/layouts/main.php';
?>

