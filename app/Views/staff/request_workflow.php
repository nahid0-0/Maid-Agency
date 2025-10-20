<?php
ob_start();
?>

<div class="user-dashboard">
    <a href="/staff" class="back-link">← Back to Dashboard</a>
    
    <div class="dashboard-header">
        <h1>Request Workflow</h1>
        <p>Process client requests from submission to deployment</p>
    </div>
    
    <div class="dashboard-content">
        <?php if (!empty($requests)): ?>
            <div class="requests-container">
                <?php foreach ($requests as $request): ?>
                    <div class="request-card">
                        <div class="request-header">
                            <h4>Request #<?= htmlspecialchars($request['id']) ?></h4>
                            <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $request['status'])) ?>">
                                <?= htmlspecialchars($request['status']) ?>
                            </span>
                        </div>
                        
                        <div class="request-details">
                            <p><strong>Client:</strong> <?= htmlspecialchars($request['client_name']) ?></p>
                            <p><strong>Type:</strong> <?= htmlspecialchars($request['request_type']) ?></p>
                            <p><strong>Urgency:</strong> <?= htmlspecialchars($request['urgency']) ?></p>
                            
                            <p><strong>Helper Preference:</strong> 
                                <?php if (!empty($request['helper_id'])): ?>
                                    <span style="color: #059669; font-weight: 600;">Specific: <?= htmlspecialchars($request['helper_name']) ?></span>
                                <?php else: ?>
                                    <span style="color: #6b7280; font-style: italic;">Any suitable helper</span>
                                <?php endif; ?>
                            </p>
                            
                            <p><strong>Start Date:</strong> 
                                <?php 
                                $startDate = $request['start_date'] ?? '';
                                if ($startDate && $startDate !== '0000-00-00') {
                                    echo date('M j, Y', strtotime($startDate));
                                } else {
                                    echo 'Not specified';
                                }
                                ?>
                            </p>
                            
                            <p><strong>Requirements:</strong></p>
                            <div style="background: #f9fafb; padding: 8px; border-radius: 6px; margin: 5px 0; font-size: 13px;">
                                <?= htmlspecialchars($request['requirements']) ?>
                            </div>
                            
                            <?php if (!empty($request['notes'])): ?>
                            <p><strong>Notes:</strong> <?= htmlspecialchars($request['notes']) ?></p>
                            <?php endif; ?>
                            
                            <p><strong>Created:</strong> <?= date('M j, Y g:i A', strtotime($request['created_at'])) ?></p>
                        </div>
                        
                        <div class="request-actions">
                            <?php if ($request['status'] === 'Pending'): ?>
                                <button class="btn btn-primary process-request-btn" data-request-id="<?= $request['id'] ?>" data-status="In Progress">
                                    Start Review
                                </button>
                            <?php elseif ($request['status'] === 'In Progress'): ?>
                                <button class="btn btn-success process-request-btn" data-request-id="<?= $request['id'] ?>" data-status="Matched">
                                    Approve & Match
                                </button>
                                <button class="btn btn-danger process-request-btn" data-request-id="<?= $request['id'] ?>" data-status="Cancelled">
                                    Cancel
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-requests">
                <h3>No Requests Found</h3>
                <p>No client requests to process at this time.</p>
            </div>
        <?php endif; ?>
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
    padding: 20px 0;
}

.requests-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.request-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-left: 4px solid #f59e0b;
    width: 400px;
    min-height: 300px;
}

.request-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e5e7eb;
}

.request-header h4 {
    margin: 0;
    color: #1f2937;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.status-in-progress {
    background: #dbeafe;
    color: #1e40af;
}

.status-badge.status-matched {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.status-cancelled {
    background: #fee2e2;
    color: #991b1b;
}

.request-details {
    margin-bottom: 20px;
}

.request-details p {
    margin-bottom: 8px;
    font-size: 14px;
}

.request-details strong {
    color: #374151;
}

.request-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}

.no-requests {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
}
</style>

<script<?= defined('CSP_NONCE') ? ' nonce="' . CSP_NONCE . '"' : '' ?>>
function processRequest(requestId, newStatus) {
    const statusText = newStatus.replace('-', ' ');
    
    if (confirm(`Are you sure you want to change this request to "${statusText}"?`)) {
        const formData = new FormData();
        formData.append('request_id', requestId);
        formData.append('status', newStatus);
        
        fetch('/staff/update-request-status', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Request status updated successfully!');
                location.reload(); 
            } else {
                alert('Error updating request: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating request. Please try again.');
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const processButtons = document.querySelectorAll('.process-request-btn');

    processButtons.forEach(button => {
        button.addEventListener('click', function() {
            const requestId = this.getAttribute('data-request-id');
            const status = this.getAttribute('data-status');
            processRequest(requestId, status);
        });
    });
});
</script>

<?php
$content = ob_get_clean();
require_once APP_ROOT . '/app/Views/layouts/main.php';
?>

