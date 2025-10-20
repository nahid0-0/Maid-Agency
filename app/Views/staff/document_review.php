<?php
ob_start();
?>

<div class="document-review-page">
    <a href="/staff" class="back-link">← Back to Staff Dashboard</a>
    
    <div class="page-header">
        <h1>📋 Document Review Dashboard</h1>
        <p>Review and approve maid documents for compliance</p>
    </div>
    
    
    <div class="summary-cards">
        <div class="summary-card pending">
            <div class="card-icon">⏳</div>
            <div class="card-content">
                <h3><?= $total_pending ?></h3>
                <p>Pending Review</p>
            </div>
        </div>
        
        <div class="summary-card expiring">
            <div class="card-icon">⚠️</div>
            <div class="card-content">
                <h3><?= $total_expiring ?></h3>
                <p>Expiring Soon</p>
            </div>
        </div>
        
        <div class="summary-card identity">
            <div class="card-icon">🛂</div>
            <div class="card-content">
                <h3><?= count($pending_identity) ?></h3>
                <p>Identity Documents</p>
            </div>
        </div>
        
        <div class="summary-card medical">
            <div class="card-icon">🏥</div>
            <div class="card-content">
                <h3><?= count($pending_medical) ?></h3>
                <p>Medical Documents</p>
            </div>
        </div>
        
        <div class="summary-card training">
            <div class="card-icon">🎓</div>
            <div class="card-content">
                <h3><?= count($pending_training) ?></h3>
                <p>Training Documents</p>
            </div>
        </div>
    </div>
    
    
    <div class="all-documents-section">
        <div class="section-header">
            <h2>📄 All Documents</h2>
            <p>Comprehensive document review and management</p>
        </div>
        
        
        <div class="documents-filter">
            <div class="filter-group">
                <select id="documentTypeFilter" onchange="filterDocuments()">
                    <option value="">All Document Types</option>
                    <option value="identity">Identity Documents</option>
                    <option value="medical">Medical Documents</option>
                    <option value="training">Training Documents</option>
                </select>
                <select id="documentStatusFilter" onchange="filterDocuments()">
                    <option value="">All Statuses</option>
                    <option value="Pending Review">Pending Review</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            <div class="search-group">
                <input type="number" id="documentSearch" placeholder="Search by helper ID..." onkeyup="filterDocuments()">
            </div>
        </div>
        
        
        <div class="documents-grid">
            
            <?php if (!empty($identity_documents)): ?>
                <?php foreach ($identity_documents as $doc): ?>
                <div class="document-card" data-type="identity" data-status="<?= $doc['status'] ?>" data-helper-id="<?= $doc['helper_id'] ?>">
                    <div class="document-header">
                        <div class="document-type">
                            <span class="type-icon">🆔</span>
                            <div class="type-info">
                                <h4><?= htmlspecialchars($doc['document_type']) ?></h4>
                                <p><?= htmlspecialchars($doc['helper_name']) ?></p>
                            </div>
                        </div>
                        <span class="status-badge <?= strtolower(str_replace(' ', '-', $doc['status'])) ?>">
                            <?= htmlspecialchars($doc['status']) ?>
                        </span>
                    </div>
                    <div class="document-details">
                        <div class="detail-item">
                            <strong>Document #:</strong> <?= htmlspecialchars($doc['document_number'] ?? 'N/A') ?>
                        </div>
                        <div class="detail-item">
                            <strong>Country:</strong> <?= htmlspecialchars($doc['issuing_country'] ?? 'N/A') ?>
                        </div>
                        <div class="detail-item">
                            <strong>Expiry:</strong> <?= $doc['expiry_date'] ? date('M j, Y', strtotime($doc['expiry_date'])) : 'N/A' ?>
                        </div>
                        <div class="detail-item">
                            <strong>Uploaded:</strong> <?= date('M j, Y', strtotime($doc['created_at'])) ?>
                        </div>
                    </div>
                    <div class="document-actions">
                        <button class="btn-view" onclick="viewDocument(<?= $doc['id'] ?>, 'identity')">👁️ View</button>
                        <button class="btn-approve" onclick="reviewDocument(<?= $doc['id'] ?>, 'identity', 'approve')">✅ Approve</button>
                        <button class="btn-reject" onclick="showRejectModal(<?= $doc['id'] ?>, 'identity')">❌ Reject</button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            
            <?php if (!empty($medical_documents)): ?>
                <?php foreach ($medical_documents as $doc): ?>
                <div class="document-card" data-type="medical" data-status="<?= $doc['status'] ?>" data-helper-id="<?= $doc['helper_id'] ?>">
                    <div class="document-header">
                        <div class="document-type">
                            <span class="type-icon">🏥</span>
                            <div class="type-info">
                                <h4><?= htmlspecialchars($doc['document_type']) ?></h4>
                                <p><?= htmlspecialchars($doc['helper_name']) ?></p>
                            </div>
                        </div>
                        <span class="status-badge <?= strtolower(str_replace(' ', '-', $doc['status'])) ?>">
                            <?= htmlspecialchars($doc['status']) ?>
                        </span>
                    </div>
                    <div class="document-details">
                        <div class="detail-item">
                            <strong>Clinic:</strong> <?= htmlspecialchars($doc['clinic_hospital'] ?? 'N/A') ?>
                        </div>
                        <div class="detail-item">
                            <strong>Date:</strong> <?= $doc['record_date'] ? date('M j, Y', strtotime($doc['record_date'])) : 'N/A' ?>
                        </div>
                        <div class="detail-item">
                            <strong>Expiry:</strong> <?= $doc['expiry_date'] ? date('M j, Y', strtotime($doc['expiry_date'])) : 'N/A' ?>
                        </div>
                        <div class="detail-item">
                            <strong>Uploaded:</strong> <?= date('M j, Y', strtotime($doc['created_at'])) ?>
                        </div>
                    </div>
                    <div class="document-actions">
                        <button class="btn-view" onclick="viewDocument(<?= $doc['id'] ?>, 'medical')">👁️ View</button>
                        <button class="btn-approve" onclick="reviewDocument(<?= $doc['id'] ?>, 'medical', 'approve')">✅ Approve</button>
                        <button class="btn-reject" onclick="showRejectModal(<?= $doc['id'] ?>, 'medical')">❌ Reject</button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            
            <?php if (!empty($training_documents)): ?>
                <?php foreach ($training_documents as $doc): ?>
                <div class="document-card" data-type="training" data-status="<?= $doc['status'] ?>" data-helper-id="<?= $doc['helper_id'] ?>">
                    <div class="document-header">
                        <div class="document-type">
                            <span class="type-icon">📚</span>
                            <div class="type-info">
                                <h4><?= htmlspecialchars($doc['course_name'] ?? $doc['document_type'] ?? 'Training Certificate') ?></h4>
                                <p><?= htmlspecialchars($doc['helper_name']) ?></p>
                            </div>
                        </div>
                        <span class="status-badge <?= strtolower(str_replace(' ', '-', $doc['status'])) ?>">
                            <?= htmlspecialchars($doc['status']) ?>
                        </span>
                    </div>
                    <div class="document-details">
                        <div class="detail-item">
                            <strong>Provider:</strong> <?= htmlspecialchars($doc['training_provider'] ?? 'N/A') ?>
                        </div>
                        <div class="detail-item">
                            <strong>Issue Date:</strong> <?= $doc['issue_date'] ? date('M j, Y', strtotime($doc['issue_date'])) : 'N/A' ?>
                        </div>
                        <div class="detail-item">
                            <strong>Expiry:</strong> <?= $doc['expiry_date'] ? date('M j, Y', strtotime($doc['expiry_date'])) : 'N/A' ?>
                        </div>
                        <div class="detail-item">
                            <strong>Uploaded:</strong> <?= date('M j, Y', strtotime($doc['created_at'])) ?>
                        </div>
                    </div>
                    <div class="document-actions">
                        <button class="btn-view" onclick="viewDocument(<?= $doc['id'] ?>, 'training')">👁️ View</button>
                        <button class="btn-approve" onclick="reviewDocument(<?= $doc['id'] ?>, 'training', 'approve')">✅ Approve</button>
                        <button class="btn-reject" onclick="showRejectModal(<?= $doc['id'] ?>, 'training')">❌ Reject</button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <?php if (empty($identity_documents) && empty($medical_documents) && empty($training_documents)): ?>
        <div class="no-documents">
            <div class="no-documents-icon">📄</div>
            <h3>No Documents Found</h3>
            <p>No documents are currently available for review.</p>
        </div>
        <?php endif; ?>
    </div>

<div id="rejectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Reject Document</h3>
            <span class="modal-close" onclick="closeRejectModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Please provide a reason for rejecting this document:</p>
            <textarea id="rejectNotes" placeholder="Enter rejection reason..." rows="4"></textarea>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeRejectModal()">Cancel</button>
            <button class="btn-reject" onclick="confirmReject()">Reject Document</button>
        </div>
    </div>
</div>

<style>
.document-review-page {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.page-header {
    text-align: center;
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 28px;
    color: #1f2937;
    margin-bottom: 8px;
}

.page-header p {
    color: #6b7280;
    font-size: 16px;
}

.summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.summary-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 15px;
    border-left: 4px solid #e5e7eb;
}

.summary-card.pending { border-left-color: #f59e0b; }
.summary-card.expiring { border-left-color: #ef4444; }
.summary-card.identity { border-left-color: #3b82f6; }
.summary-card.medical { border-left-color: #10b981; }
.summary-card.training { border-left-color: #8b5cf6; }

.card-icon {
    font-size: 24px;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    border-radius: 10px;
}

.card-content h3 {
    font-size: 24px;
    font-weight: 700;
    margin: 0;
    color: #1f2937;
}

.card-content p {
    font-size: 14px;
    color: #6b7280;
    margin: 4px 0 0 0;
}

.review-sections {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.section-header h2 {
    font-size: 20px;
    color: #1f2937;
    margin: 0;
}

.count-badge {
    background: #f59e0b;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #6b7280;
    text-decoration: none;
    font-size: 14px;
    margin-bottom: 20px;
    padding: 8px 16px;
    border-radius: 6px;
    transition: all 0.2s ease;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}

.back-link:hover {
    color: #374151;
    background-color: #f3f4f6;
    text-decoration: none;
}

.all-documents-section {
    margin-bottom: 40px;
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.section-header h2 {
    margin: 0 0 8px 0;
    font-size: 20px;
    font-weight: 600;
    color: #1f2937;
}

.section-header p {
    margin: 0;
    color: #6b7280;
    font-size: 14px;
}

.documents-filter {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.filter-group {
    display: flex;
    gap: 15px;
}

.filter-group select {
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background: white;
    font-size: 14px;
    min-width: 150px;
}

.search-group input {
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background: white;
    font-size: 14px;
    width: 250px;
}

.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
}

.document-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
}

.document-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.document-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.document-type {
    display: flex;
    align-items: center;
    gap: 12px;
}

.type-icon {
    font-size: 24px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    border-radius: 8px;
}

.type-info h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
}

.type-info p {
    margin: 0;
    font-size: 14px;
    color: #6b7280;
}

.document-details {
    margin-bottom: 20px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    padding: 5px 0;
    font-size: 14px;
    border-bottom: 1px solid #f3f4f6;
}

.detail-item:last-child {
    border-bottom: none;
}

.document-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-view {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    transition: background 0.2s;
    flex: 1;
    min-width: 70px;
}

.btn-view:hover {
    background: #2563eb;
}

.btn-approve {
    background: #10b981;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    transition: background 0.2s;
    flex: 1;
    min-width: 80px;
}

.btn-approve:hover {
    background: #059669;
}

.btn-reject {
    background: #ef4444;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    transition: background 0.2s;
    flex: 1;
    min-width: 70px;
}

.btn-reject:hover {
    background: #dc2626;
}

.status-badge.pending-review {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.approved {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.rejected {
    background: #fee2e2;
    color: #991b1b;
}

.no-documents {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
}

.no-documents-icon {
    font-size: 48px;
    margin-bottom: 20px;
}

.no-documents h3 {
    margin: 0 0 10px 0;
    font-size: 18px;
    color: #374151;
}

.no-documents p {
    margin: 0;
    font-size: 14px;
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
}

.modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
}

.modal-close {
    color: #6b7280;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
    line-height: 1;
}

.modal-close:hover {
    color: #374151;
}

.modal-body {
    padding: 24px;
}

.modal-body p {
    margin: 0 0 15px 0;
    color: #374151;
    font-size: 14px;
}

.modal-body textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    font-family: inherit;
    resize: vertical;
    min-height: 100px;
}

.modal-body textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 20px 24px;
    border-top: 1px solid #e5e7eb;
}

.btn-cancel {
    background: #f3f4f6;
    color: #374151;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-cancel:hover {
    background: #e5e7eb;
}

.modal-footer .btn-reject {
    background: #ef4444;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.2s;
}

.modal-footer .btn-reject:hover {
    background: #dc2626;
}
</style>

<script>
let currentRejectDocument = null;

function viewDocument(documentId, documentType) {
    
    fetch('/staff/get-document-url', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `document_id=${documentId}&document_type=${documentType}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.open(data.url, '_blank');
        } else {
            alert('Error loading document: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error loading document');
    });
}

function showRejectModal(documentId, documentType) {
    currentRejectDocument = { id: documentId, type: documentType };
    document.getElementById('rejectModal').style.display = 'block';
    document.getElementById('rejectNotes').value = '';
    document.getElementById('rejectNotes').focus();
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    currentRejectDocument = null;
}

function confirmReject() {
    const notes = document.getElementById('rejectNotes').value.trim();
    if (!notes) {
        alert('Please provide a reason for rejection');
        return;
    }
    
    if (currentRejectDocument) {
        
        fetch('/staff/reject-document', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `document_id=${currentRejectDocument.id}&document_type=${currentRejectDocument.type}&notes=${encodeURIComponent(notes)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Document rejected successfully!');
                location.reload(); 
            } else {
                alert('Error: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
        });
        
        closeRejectModal();
    }
}

function updateCounts() {
    
    const pendingCards = document.querySelectorAll('.document-card');
    const totalPending = pendingCards.length;
    
    
    const pendingCard = document.querySelector('.summary-card.pending h3');
    if (pendingCard) {
        pendingCard.textContent = totalPending;
    }
    
    
    const sections = ['identity', 'medical', 'training'];
    sections.forEach(section => {
        const sectionCards = document.querySelectorAll(`[data-document-type="${section}"]`);
        const badge = document.querySelector(`.${section} h3`);
        if (badge) {
            badge.textContent = sectionCards.length;
        }
    });
}

window.onclick = function(event) {
    const modal = document.getElementById('rejectModal');
    if (event.target === modal) {
        closeRejectModal();
    }
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeRejectModal();
    }
});

function filterDocuments() {
    const typeFilter = document.getElementById('documentTypeFilter').value.toLowerCase();
    const statusFilter = document.getElementById('documentStatusFilter').value.toLowerCase();
    const searchTerm = document.getElementById('documentSearch').value.toLowerCase();
    
    const cards = document.querySelectorAll('.document-card[data-type]');
    let visibleCount = 0;
    
    cards.forEach(card => {
        const type = card.getAttribute('data-type');
        const status = card.getAttribute('data-status').toLowerCase();
        const helperId = card.getAttribute('data-helper-id');

        const matchesType = !typeFilter || type === typeFilter;
        const matchesStatus = !statusFilter || status === statusFilter.replace(' ', '-');
        const matchesSearch = !searchTerm || helperId === searchTerm;

        if (matchesType && matchesStatus && matchesSearch) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    
    const noDocuments = document.querySelector('.no-documents');
    if (noDocuments) {
        noDocuments.style.display = visibleCount === 0 ? 'block' : 'none';
    }
}

function reviewDocument(documentId, documentType, action) {
    const actionText = action === 'approve' ? 'approve' : 'reject';
    const confirmText = `Are you sure you want to ${actionText} this document?`;
    
    if (!confirm(confirmText)) {
        return;
    }
    
    let notes = '';
    if (action === 'reject') {
        notes = prompt('Please provide a reason for rejection (optional):');
        if (notes === null) return; 
    }
    
    const endpoint = action === 'approve' ? 
        `/staff/approve-document` : 
        `/staff/reject-document`;
    
    fetch(endpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `document_id=${documentId}&document_type=${documentType}&notes=${encodeURIComponent(notes)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Document ${actionText}d successfully!`);
            location.reload(); 
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Network error. Please try again.');
    });
}
</script>

<?php
$content = ob_get_clean();
include APP_ROOT . '/app/Views/layouts/main.php';
?>
