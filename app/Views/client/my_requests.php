<?php
ob_start();
?>

<div class="requests-page">
    <a href="/client" class="back-link">← Back to Dashboard</a>
    
    <div class="page-header">
        <h1>📋 My Requests</h1>
        <p>Track your helper applications and request history</p>
    </div>

    
    <div class="requests-history-section">
        <div class="section-header">
            <h2>Request History</h2>
            <div class="filter-controls">
                <select id="statusFilter">
                    <option value="">All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Matched">Matched</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
        </div>
        
        <?php if (empty($requests)): ?>
            <div class="no-requests">
                <div class="no-requests-icon">📭</div>
                <h3>No requests found</h3>
                <p>You haven't submitted any requests yet. Create your first request above!</p>
            </div>
        <?php else: ?>
            <div class="requests-grid">
                <?php foreach ($requests as $request): ?>
                    <div class="request-card" data-status="<?= strtolower(str_replace(' ', '-', $request['status'])) ?>" id="request-<?= $request['id'] ?>">
                        <div class="request-header">
                            <div class="request-id">Request #<?= $request['id'] ?></div>
                            <div class="request-date"><?= date('M j, Y', strtotime($request['created_at'])) ?></div>
                        </div>
                        
                        <div class="request-content">
                            <div class="request-type">
                                <span class="type-icon">📋</span>
                                <strong><?= htmlspecialchars($request['request_type']) ?></strong>
                            </div>
                            
                            <div class="request-status">
                                <span class="status-badge <?= strtolower(str_replace(' ', '-', $request['status'])) ?>">
                                    <?= htmlspecialchars($request['status']) ?>
                                </span>
                                <span class="urgency-badge <?= strtolower($request['urgency']) ?>">
                                    <?= htmlspecialchars($request['urgency']) ?>
                                </span>
                            </div>
                            
                            <div class="request-details">
                                <?php if ($request['start_date']): ?>
                                    <div class="detail-item">
                                        <span class="detail-icon">📅</span>
                                        <span>Start: <?= date('M j, Y', strtotime($request['start_date'])) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($request['helper_name']): ?>
                                    <div class="detail-item">
                                        <span class="detail-icon">👤</span>
                                        <span>Helper: <?= htmlspecialchars($request['helper_name']) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="detail-item">
                                    <span class="detail-icon">📝</span>
                                    <span><?= htmlspecialchars(substr($request['requirements'], 0, 80)) ?><?= strlen($request['requirements']) > 80 ? '...' : '' ?></span>
                                </div>
                            </div>
                            
                            <?php if ($request['notes']): ?>
                                <div class="request-notes">
                                    <strong>Notes:</strong> <?= htmlspecialchars($request['notes']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="request-actions">
                            <button class="btn-view view-details-btn" data-request-id="<?= $request['id'] ?>">View Details</button>
                            <?php if ($request['status'] === 'Pending'): ?>
                                <button class="btn-cancel cancel-request-btn" data-request-id="<?= $request['id'] ?>">Cancel</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="requestModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Request Details</h2>
            <span class="modal-close" onclick="closeRequestModal()">&times;</span>
        </div>
        <div class="modal-body" id="requestModalBody">
            
        </div>
    </div>
</div>

<style>
.requests-page {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
    background: #f8fafc;
    min-height: 100vh;
}

.page-header {
    text-align: center;
    margin-bottom: 40px;
}

.page-header h1 {
    font-size: 32px;
    color: #1f2937;
    margin-bottom: 8px;
}

.page-header p {
    color: #6b7280;
    font-size: 18px;
}

.requests-history-section {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.filter-controls {
    display: flex;
    gap: 16px;
}

.filter-controls select {
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
}

.no-requests {
    text-align: center;
    padding: 60px 20px;
}

.no-requests-icon {
    font-size: 48px;
    margin-bottom: 20px;
}

.no-requests h3 {
    color: #374151;
    margin-bottom: 10px;
}

.no-requests p {
    color: #6b7280;
}

.requests-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 24px;
    margin-top: 30px;
}

.request-card {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.request-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.request-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e5e7eb;
}

.request-id {
    font-weight: 600;
    color: #374151;
}

.request-date {
    font-size: 14px;
    color: #6b7280;
}

.request-content {
    margin-bottom: 20px;
}

.request-type {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.type-icon {
    font-size: 16px;
}

.request-status {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-in-progress {
    background: #dbeafe;
    color: #1e40af;
}

.status-matched {
    background: #d1fae5;
    color: #065f46;
}

.status-completed {
    background: #e0e7ff;
    color: #3730a3;
}

.status-cancelled {
    background: #fee2e2;
    color: #991b1b;
}

.urgency-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    text-transform: uppercase;
}

.urgency-low {
    background: #f3f4f6;
    color: #374151;
}

.urgency-medium {
    background: #fef3c7;
    color: #92400e;
}

.urgency-high {
    background: #fed7aa;
    color: #9a3412;
}

.urgency-urgent {
    background: #fee2e2;
    color: #991b1b;
}

.request-details {
    margin-bottom: 12px;
}

.detail-item {
    display: flex;
    align-items: center;
    margin-bottom: 6px;
    font-size: 14px;
}

.detail-icon {
    width: 20px;
    margin-right: 8px;
    font-size: 14px;
}

.request-notes {
    background: #f9fafb;
    padding: 12px;
    border-radius: 6px;
    font-size: 14px;
    margin-bottom: 16px;
}

.request-actions {
    display: flex;
    gap: 8px;
}

.btn-view {
    flex: 1;
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn-view:hover {
    background: #e5e7eb;
}

.btn-cancel {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn-cancel:hover {
    background: #fecaca;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 0;
    border-radius: 16px;
    width: 90%;
    max-width: 700px;
    max-height: 80vh;
    overflow: hidden;
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    font-size: 20px;
}

.modal-close {
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
    transition: opacity 0.2s ease;
}

.modal-close:hover {
    opacity: 0.7;
}

.modal-body {
    padding: 30px;
    max-height: 60vh;
    overflow-y: auto;
}

@media (max-width: 768px) {
    .requests-page {
        padding: 10px;
    }

    .requests-grid {
        grid-template-columns: 1fr;
    }

    .filter-controls {
        justify-content: center;
    }

    .modal-content {
        width: 95%;
        margin: 2% auto;
    }
}

@media (max-width: 480px) {
    .page-header h1 {
        font-size: 24px;
    }
    
    .section-header h2 {
        font-size: 20px;
    }
    
    .request-actions {
        flex-direction: column;
    }
}
</style>

<script<?= defined('CSP_NONCE') ? ' nonce="' . CSP_NONCE . '"' : '' ?>>

document.querySelectorAll('.view-details-btn').forEach(button => {
    button.addEventListener('click', function() {
        const requestId = this.getAttribute('data-request-id');
        viewRequestDetails(requestId);
    });
});

document.querySelectorAll('.cancel-request-btn').forEach(button => {
    button.addEventListener('click', function() {
        const requestId = this.getAttribute('data-request-id');
        cancelRequest(requestId);
    });
});

document.getElementById('statusFilter').addEventListener('change', function() {
    filterRequests();
});

function filterRequests() {
    const status = document.getElementById('statusFilter').value.toLowerCase();
    const cards = document.querySelectorAll('.request-card');
    
    cards.forEach(card => {
        if (!status || card.dataset.status.includes(status.replace(' ', '-'))) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function viewRequestDetails(requestId) {
    
    document.getElementById('requestModalBody').innerHTML = '<div style="text-align: center; padding: 40px;"><div style="font-size: 24px;">⏳</div><p>Loading request details...</p></div>';
    document.getElementById('requestModal').style.display = 'block';
    
    
    fetch(`/client/request-details/${requestId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayRequestDetails(data.request);
            } else {
                document.getElementById('requestModalBody').innerHTML = '<div style="text-align: center; padding: 40px;"><div style="font-size: 24px;">❌</div><p>Failed to load request details.</p></div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('requestModalBody').innerHTML = '<div style="text-align: center; padding: 40px;"><div style="font-size: 24px;">❌</div><p>Error loading request details.</p></div>';
        });
}

function displayRequestDetails(request) {
    const modalBody = document.getElementById('requestModalBody');
    modalBody.innerHTML = `
        <div class="request-detail">
            <h3>Request #${request.id}</h3>
            <div class="detail-grid">
                <div class="detail-item"><strong>Type:</strong> ${request.request_type}</div>
                <div class="detail-item"><strong>Status:</strong> <span class="status-badge ${request.status.toLowerCase().replace(' ', '-')}">${request.status}</span></div>
                <div class="detail-item"><strong>Urgency:</strong> <span class="urgency-badge ${request.urgency.toLowerCase()}">${request.urgency}</span></div>
                <div class="detail-item"><strong>Created:</strong> ${new Date(request.created_at).toLocaleDateString()}</div>
                ${request.start_date ? `<div class="detail-item"><strong>Start Date:</strong> ${new Date(request.start_date).toLocaleDateString()}</div>` : ''}
                ${request.helper_name ? `<div class="detail-item"><strong>Assigned Helper:</strong> ${request.helper_name}</div>` : ''}
            </div>
            
            <div class="detail-section">
                <h4>Requirements</h4>
                <p>${request.requirements}</p>
            </div>
            
            ${request.notes ? `
                <div class="detail-section">
                    <h4>Additional Notes</h4>
                    <p>${request.notes}</p>
                </div>
            ` : ''}
        </div>
    `;
}

function cancelRequest(requestId) {
    if (confirm('Are you sure you want to cancel this request? This action cannot be undone.')) {
        fetch('/client/cancel-request', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ request_id: requestId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Request cancelled and removed from your history.', 'success');
                
                const requestCard = document.getElementById('request-' + requestId);
                if (requestCard) {
                    requestCard.style.transition = 'opacity 0.5s ease-out';
                    requestCard.style.opacity = '0';
                    setTimeout(() => {
                        requestCard.remove();
                        
                        const remainingCards = document.querySelectorAll('.request-card');
                        if (remainingCards.length === 0) {
                            
                            location.reload();
                        }
                    }, 500);
                }
            } else {
                showMessage(data.error || 'Failed to cancel request.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Failed to cancel request.', 'error');
        });
    }
}

function closeRequestModal() {
    document.getElementById('requestModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('requestModal');
    if (event.target === modal) {
        closeRequestModal();
    }
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeRequestModal();
    }
});

function showMessage(message, type) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;
    messageDiv.textContent = message;
    messageDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 500;
        z-index: 1001;
        transition: opacity 0.3s ease;
        ${type === 'success' ? 'background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;' : 'background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;'}
    `;
    
    document.body.appendChild(messageDiv);
    
    setTimeout(() => {
        messageDiv.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(messageDiv);
        }, 300);
    }, 3000);
}
</script>

<?php
$content = ob_get_clean();
$title = 'My Requests - Client Dashboard';
require_once __DIR__ . '/../layouts/main.php';
?>