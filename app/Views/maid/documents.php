<?php
ob_start();
?>

<div class="documents-page">
    <a href="/maid" class="back-link">← Back to Dashboard</a>
    
    <div class="page-header">
        <h1>📄 My Documents</h1>
        <p>Upload and manage your essential documents securely</p>
    </div>
    
    <div class="documents-grid">
        
        <div class="document-card">
            <div class="card-header">
                <div class="card-icon passport-icon">🛂</div>
                <div class="card-title">
                    <h3>Passport & ID</h3>
                    <span class="status-badge <?= !empty($identity_documents) ? 'uploaded' : 'pending' ?>">
                        <?= !empty($identity_documents) ? count($identity_documents) . ' Uploaded' : 'Pending Upload' ?>
                    </span>
                </div>
            </div>
            <div class="card-content">
                <p>Upload your passport, national ID, and other identification documents</p>
                
                
                <?php if (!empty($identity_documents)): ?>
                <div class="existing-documents">
                    <h4>Uploaded Documents</h4>
                    <?php foreach ($identity_documents as $doc): ?>
                    <div class="document-item">
                        <div class="document-info">
                            <span class="document-name"><?= htmlspecialchars($doc['original_filename']) ?></span>
                            <span class="document-type"><?= htmlspecialchars($doc['document_type']) ?></span>
                            <span class="document-status status-<?= strtolower(str_replace(' ', '-', $doc['status'])) ?>">
                                <?= htmlspecialchars($doc['status']) ?>
                            </span>
                        </div>
                        <div class="document-actions-small">
                            <button class="btn-view-small view-doc-btn" data-doc-id="<?= $doc['id'] ?>" data-doc-type="identity">View</button>
                            <button class="btn-delete-small delete-doc-btn" data-doc-id="<?= $doc['id'] ?>" data-doc-type="identity">Delete</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                
                <div class="upload-area upload-trigger" data-input-id="identityFileInput">
                    <div class="upload-placeholder">
                        <span class="upload-icon">📁</span>
                        <p>Drag files here or click to browse</p>
                        <small>Accepted formats: PDF, JPG, PNG (Max 5MB)</small>
                    </div>
                </div>

                <input type="file" id="identityFileInput" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" data-upload-type="identity">
                
                
                <div id="identityUploadForm" class="upload-form" style="display: none;">
                    <h4>Document Details</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Document Type</label>
                            <select id="identityDocumentType">
                                <option value="Passport">Passport</option>
                                <option value="National ID">National ID</option>
                                <option value="Work Permit">Work Permit</option>
                                <option value="Visa">Visa</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Document Number</label>
                            <input type="text" id="identityDocumentNumber" placeholder="Enter document number">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Issuing Country</label>
                            <input type="text" id="identityIssuingCountry" placeholder="Enter issuing country">
                        </div>
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input type="date" id="identityExpiryDate">
                        </div>
                    </div>
                </div>
                
                <div class="card-actions">
                    <button class="btn-upload upload-trigger-btn" data-input-id="identityFileInput">Upload Files</button>
                    <button class="btn-view view-docs-btn" <?= empty($identity_documents) ? 'disabled' : '' ?> data-doc-type="identity">View Documents</button>
                </div>
            </div>
        </div>

        
        <div class="document-card">
            <div class="card-header">
                <div class="card-icon medical-icon">🏥</div>
                <div class="card-title">
                    <h3>Medical Records</h3>
                    <span class="status-badge <?= !empty($medical_documents) ? 'uploaded' : 'pending' ?>">
                        <?= !empty($medical_documents) ? count($medical_documents) . ' Uploaded' : 'Pending Upload' ?>
                    </span>
                </div>
            </div>
            <div class="card-content">
                <p>Medical certificates, health check reports, and vaccination records</p>
                
                
                <?php if (!empty($medical_documents)): ?>
                <div class="existing-documents">
                    <h4>Uploaded Documents</h4>
                    <?php foreach ($medical_documents as $doc): ?>
                    <div class="document-item">
                        <div class="document-info">
                            <span class="document-name"><?= htmlspecialchars($doc['original_filename']) ?></span>
                            <span class="document-type"><?= htmlspecialchars($doc['document_type']) ?></span>
                            <span class="document-status status-<?= strtolower(str_replace(' ', '-', $doc['status'])) ?>">
                                <?= htmlspecialchars($doc['status']) ?>
                            </span>
                        </div>
                        <div class="document-actions-small">
                            <button class="btn-view-small view-doc-btn" data-doc-id="<?= $doc['id'] ?>" data-doc-type="medical">View</button>
                            <button class="btn-delete-small delete-doc-btn" data-doc-id="<?= $doc['id'] ?>" data-doc-type="medical">Delete</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                
                <div class="upload-area upload-trigger" data-input-id="medicalFileInput">
                    <div class="upload-placeholder">
                        <span class="upload-icon">📁</span>
                        <p>Drag files here or click to browse</p>
                        <small>Accepted formats: PDF, JPG, PNG (Max 5MB)</small>
                    </div>
                </div>
                
                <input type="file" id="medicalFileInput" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" data-upload-type="medical">
                
                
                <div id="medicalUploadForm" class="upload-form" style="display: none;">
                    <h4>Medical Document Details</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Document Type</label>
                            <select id="medicalDocumentType">
                                <option value="General Health Check">General Health Check</option>
                                <option value="Annual Physical">Annual Physical</option>
                                <option value="Vaccination Record">Vaccination Record</option>
                                <option value="Medical Clearance">Medical Clearance</option>
                                <option value="Blood Test">Blood Test</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Record Date</label>
                            <input type="date" id="medicalRecordDate" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Doctor Name</label>
                            <input type="text" id="medicalDoctorName" placeholder="Enter doctor name">
                        </div>
                        <div class="form-group">
                            <label>Clinic/Hospital</label>
                            <input type="text" id="medicalClinicHospital" placeholder="Enter clinic/hospital name">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Expiry Date (if applicable)</label>
                            <input type="date" id="medicalExpiryDate">
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <input type="text" id="medicalNotes" placeholder="Additional notes">
                        </div>
                    </div>
                </div>
                
                <div class="card-actions">
                    <button class="btn-upload upload-trigger-btn" data-input-id="medicalFileInput">Upload Files</button>
                    <button class="btn-view view-docs-btn" <?= empty($medical_documents) ? 'disabled' : '' ?> data-doc-type="medical">View Documents</button>
                </div>
            </div>
        </div>

        
        <div class="document-card">
            <div class="card-header">
                <div class="card-icon certificate-icon">🎓</div>
                <div class="card-title">
                    <h3>Training Certificates</h3>
                    <span class="status-badge <?= !empty($training_documents) ? 'uploaded' : 'pending' ?>">
                        <?= !empty($training_documents) ? count($training_documents) . ' Uploaded' : 'Pending Upload' ?>
                    </span>
                </div>
            </div>
            <div class="card-content">
                <p>Training certificates, qualifications, and skill certifications</p>
                
                
                <?php if (!empty($training_documents)): ?>
                <div class="existing-documents">
                    <h4>Uploaded Documents</h4>
                    <?php foreach ($training_documents as $doc): ?>
                    <div class="document-item">
                        <div class="document-info">
                            <span class="document-name"><?= htmlspecialchars($doc['original_filename']) ?></span>
                            <span class="document-type"><?= htmlspecialchars($doc['course_name']) ?></span>
                            <span class="document-status status-<?= strtolower(str_replace(' ', '-', $doc['status'])) ?>">
                                <?= htmlspecialchars($doc['status']) ?>
                            </span>
                        </div>
                        <div class="document-actions-small">
                            <button class="btn-view-small view-doc-btn" data-doc-id="<?= $doc['id'] ?>" data-doc-type="training">View</button>
                            <button class="btn-delete-small delete-doc-btn" data-doc-id="<?= $doc['id'] ?>" data-doc-type="training">Delete</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                
                <div class="upload-area upload-trigger" data-input-id="trainingFileInput">
                    <div class="upload-placeholder">
                        <span class="upload-icon">📁</span>
                        <p>Drag files here or click to browse</p>
                        <small>Accepted formats: PDF, JPG, PNG (Max 5MB)</small>
                    </div>
                </div>
                
                <input type="file" id="trainingFileInput" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" data-upload-type="training">
                
                
                <div id="trainingUploadForm" class="upload-form" style="display: none;">
                    <h4>Training Certificate Details</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Document Type</label>
                            <select id="trainingDocumentType">
                                <option value="Training Certificate">Training Certificate</option>
                                <option value="Skill Certification">Skill Certification</option>
                                <option value="Course Completion">Course Completion</option>
                                <option value="Professional License">Professional License</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Course Name *</label>
                            <input type="text" id="trainingCourseName" placeholder="Enter course name" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Training Provider</label>
                            <input type="text" id="trainingProvider" placeholder="Enter training provider">
                        </div>
                        <div class="form-group">
                            <label>Certificate Number</label>
                            <input type="text" id="trainingCertificateNumber" placeholder="Enter certificate number">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Issue Date</label>
                            <input type="date" id="trainingIssueDate">
                        </div>
                        <div class="form-group">
                            <label>Expiry Date (if applicable)</label>
                            <input type="date" id="trainingExpiryDate">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <input type="text" id="trainingNotes" placeholder="Additional notes">
                    </div>
                </div>
                
                <div class="card-actions">
                    <button class="btn-upload upload-trigger-btn" data-input-id="trainingFileInput">Upload Files</button>
                    <button class="btn-view view-docs-btn" <?= empty($training_documents) ? 'disabled' : '' ?> data-doc-type="training">View Documents</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="loadingModal" class="modal">
    <div class="modal-content loading-modal">
        <div class="loading-spinner"></div>
        <p>Uploading document...</p>
    </div>
</div>

<style>
.documents-page {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
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

.page-header {
    text-align: center;
    margin-bottom: 40px;
}

.page-header h1 {
    font-size: 32px;
    color: #1f2937;
    margin-bottom: 10px;
}

.page-header p {
    color: #6b7280;
    font-size: 16px;
}

.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 30px;
}

.document-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border: 1px solid #e5e7eb;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.document-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.card-header {
    padding: 24px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: 16px;
}

.card-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.passport-icon {
    background: #dbeafe;
    color: #1d4ed8;
}

.medical-icon {
    background: #dcfce7;
    color: #16a34a;
}

.certificate-icon {
    background: #fef3c7;
    color: #d97706;
}

.card-title h3 {
    margin: 0;
    font-size: 18px;
    color: #111827;
    font-weight: 600;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    margin-top: 4px;
}

.status-badge.pending {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.uploaded {
    background: #dcfce7;
    color: #166534;
}

.card-content {
    padding: 24px;
}

.card-content p {
    color: #6b7280;
    margin-bottom: 20px;
    line-height: 1.5;
}

.existing-documents {
    margin-bottom: 20px;
    padding: 16px;
    background: #f9fafb;
    border-radius: 8px;
}

.existing-documents h4 {
    margin: 0 0 12px 0;
    font-size: 14px;
    color: #374151;
    font-weight: 600;
}

.document-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #e5e7eb;
}

.document-item:last-child {
    border-bottom: none;
}

.document-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.document-name {
    font-size: 12px;
    font-weight: 500;
    color: #374151;
}

.document-type {
    font-size: 11px;
    color: #6b7280;
}

.document-status {
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 10px;
    font-weight: 500;
}

.status-pending-review {
    background: #fef3c7;
    color: #92400e;
}

.status-approved {
    background: #dcfce7;
    color: #166534;
}

.status-rejected {
    background: #fee2e2;
    color: #dc2626;
}

.document-actions-small {
    display: flex;
    gap: 4px;
}

.btn-view-small, .btn-delete-small {
    padding: 4px 8px;
    border: none;
    border-radius: 4px;
    font-size: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-view-small {
    background: #f3f4f6;
    color: #374151;
}

.btn-view-small:hover {
    background: #e5e7eb;
}

.btn-delete-small {
    background: #fee2e2;
    color: #dc2626;
}

.btn-delete-small:hover {
    background: #fecaca;
}

.upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    padding: 24px;
    text-align: center;
    margin-bottom: 20px;
    transition: border-color 0.2s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: #9ca3af;
}

.upload-placeholder {
    color: #6b7280;
}

.upload-icon {
    font-size: 32px;
    display: block;
    margin-bottom: 12px;
}

.upload-placeholder p {
    margin: 0 0 8px 0;
    font-weight: 500;
}

.upload-placeholder small {
    color: #9ca3af;
    font-size: 12px;
}

.card-actions {
    display: flex;
    gap: 12px;
}

.btn-upload, .btn-view {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    flex: 1;
}

.btn-upload {
    background: #667eea;
    color: white;
}

.btn-upload:hover {
    background: #5a6fd8;
}

.btn-view {
    background: #f3f4f6;
    color: #6b7280;
    border: 1px solid #d1d5db;
}

.btn-view:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-view:not(:disabled):hover {
    background: #e5e7eb;
}

.upload-form {
    background: #f9fafb;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.upload-form h4 {
    margin: 0 0 16px 0;
    font-size: 14px;
    color: #374151;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 12px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-size: 12px;
    color: #374151;
    margin-bottom: 4px;
    font-weight: 500;
}

.form-group input, .form-group select {
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    font-size: 14px;
}

.form-group input:focus, .form-group select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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

.loading-modal {
    background-color: white;
    margin: 20% auto;
    padding: 40px;
    border-radius: 12px;
    width: 300px;
    text-align: center;
    box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f4f6;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 16px 24px;
    border-radius: 8px;
    font-weight: 500;
    z-index: 10000;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    animation: slideIn 0.3s ease-out;
    max-width: 400px;
}

.toast-notification.success {
    background: #d1fae5;
    color: #065f46;
    border-left: 4px solid #10b981;
}

.toast-notification.error {
    background: #fee2e2;
    color: #991b1b;
    border-left: 4px solid #ef4444;
}

@keyframes slideIn {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(400px);
        opacity: 0;
    }
}

@media (max-width: 768px) {
    .documents-grid {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .card-actions {
        flex-direction: column;
    }
}
</style>

<script<?= defined('CSP_NONCE') ? ' nonce="' . CSP_NONCE . '"' : '' ?>>
function showLoading() {
    document.getElementById('loadingModal').style.display = 'block';
}

function hideLoading() {
    document.getElementById('loadingModal').style.display = 'none';
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => {
            if (toast.parentNode) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

function uploadIdentityDocument(input) {
    if (!input.files || input.files.length === 0) return;
    
    const form = document.getElementById('identityUploadForm');
    form.style.display = 'block';
    
    
    if (!form.querySelector('.upload-submit')) {
        const submitBtn = document.createElement('button');
        submitBtn.className = 'btn-upload upload-submit';
        submitBtn.textContent = 'Upload Document';
        submitBtn.type = 'button';
        submitBtn.onclick = function() {
            submitIdentityDocument(input.files[0]);
        };
        form.appendChild(submitBtn);
    }
}

function submitIdentityDocument(file) {
    showLoading();
    
    const formData = new FormData();
    formData.append('document', file);
    formData.append('document_type', document.getElementById('identityDocumentType').value);
    formData.append('document_number', document.getElementById('identityDocumentNumber').value);
    formData.append('issuing_country', document.getElementById('identityIssuingCountry').value);
    formData.append('expiry_date', document.getElementById('identityExpiryDate').value);
    
    fetch('/maid/upload-identity-document', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast('Identity document uploaded successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Upload failed: ' + data.error, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Upload failed. Please try again.', 'error');
    });
}

function uploadMedicalDocument(input) {
    if (!input.files || input.files.length === 0) return;
    
    const form = document.getElementById('medicalUploadForm');
    form.style.display = 'block';
    
    
    if (!form.querySelector('.upload-submit')) {
        const submitBtn = document.createElement('button');
        submitBtn.className = 'btn-upload upload-submit';
        submitBtn.textContent = 'Upload Document';
        submitBtn.type = 'button';
        submitBtn.onclick = function() {
            submitMedicalDocument(input.files[0]);
        };
        form.appendChild(submitBtn);
    }
}

function submitMedicalDocument(file) {
    showLoading();
    
    const formData = new FormData();
    formData.append('document', file);
    formData.append('document_type', document.getElementById('medicalDocumentType').value);
    formData.append('record_date', document.getElementById('medicalRecordDate').value);
    formData.append('doctor_name', document.getElementById('medicalDoctorName').value);
    formData.append('clinic_hospital', document.getElementById('medicalClinicHospital').value);
    formData.append('expiry_date', document.getElementById('medicalExpiryDate').value);
    formData.append('notes', document.getElementById('medicalNotes').value);
    
    fetch('/maid/upload-medical-document', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast('Medical document uploaded successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Upload failed: ' + data.error, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Upload failed. Please try again.', 'error');
    });
}

function uploadTrainingDocument(input) {
    if (!input.files || input.files.length === 0) return;
    
    const form = document.getElementById('trainingUploadForm');
    form.style.display = 'block';
    
    
    if (!form.querySelector('.upload-submit')) {
        const submitBtn = document.createElement('button');
        submitBtn.className = 'btn-upload upload-submit';
        submitBtn.textContent = 'Upload Document';
        submitBtn.type = 'button';
        submitBtn.onclick = function() {
            submitTrainingDocument(input.files[0]);
        };
        form.appendChild(submitBtn);
    }
}

function submitTrainingDocument(file) {
    const courseName = document.getElementById('trainingCourseName').value.trim();
    if (!courseName) {
        showToast('Course name is required', 'error');
        return;
    }
    
    showLoading();
    
    const formData = new FormData();
    formData.append('document', file);
    formData.append('document_type', document.getElementById('trainingDocumentType').value);
    formData.append('course_name', courseName);
    formData.append('training_provider', document.getElementById('trainingProvider').value);
    formData.append('certificate_number', document.getElementById('trainingCertificateNumber').value);
    formData.append('issue_date', document.getElementById('trainingIssueDate').value);
    formData.append('expiry_date', document.getElementById('trainingExpiryDate').value);
    formData.append('notes', document.getElementById('trainingNotes').value);
    
    fetch('/maid/upload-training-document', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast('Training certificate uploaded successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Upload failed: ' + data.error, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Upload failed. Please try again.', 'error');
    });
}

function viewDocumentInTab(documentId, documentType) {
    
    fetch('/maid/get-document-url', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `document_id=${documentId}&document_type=${documentType}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            
            window.open(data.url, '_blank');
        } else {
            showToast('Error loading document: ' + (data.error || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Network error occurred while loading document', 'error');
    });
}

function deleteDocument(documentId, documentType) {
    if (!confirm('Are you sure you want to delete this document?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('document_id', documentId);
    formData.append('document_type', documentType);
    
    fetch('/maid/delete-document', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Document deleted successfully!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Delete failed: ' + data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Delete failed. Please try again.', 'error');
    });
}

function viewDocuments(type) {
    
    const documentCards = document.querySelectorAll('.document-card');
    let targetCard = null;
    
    
    if (type === 'identity') targetCard = documentCards[0];
    else if (type === 'medical') targetCard = documentCards[1];  
    else if (type === 'training') targetCard = documentCards[2];
    
    if (targetCard) {
        
        targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
        targetCard.style.boxShadow = '0 0 20px rgba(102, 126, 234, 0.3)';
        targetCard.style.transform = 'scale(1.02)';
        
        
        setTimeout(() => {
            targetCard.style.boxShadow = '';
            targetCard.style.transform = '';
        }, 2000);
        
        
        const existingDocs = targetCard.querySelector('.existing-documents');
        if (existingDocs) {
            existingDocs.style.background = '#f0f9ff';
            setTimeout(() => {
                existingDocs.style.background = '';
            }, 2000);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    
    document.querySelectorAll('.upload-trigger, .upload-trigger-btn').forEach(element => {
        element.addEventListener('click', function() {
            const inputId = this.getAttribute('data-input-id');
            if (inputId) {
                document.getElementById(inputId).click();
            }
        });
    });

    
    document.getElementById('identityFileInput').addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            uploadIdentityDocument(this);
        }
    });

    document.getElementById('medicalFileInput').addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            uploadMedicalDocument(this);
        }
    });

    document.getElementById('trainingFileInput').addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            uploadTrainingDocument(this);
        }
    });

    
    document.querySelectorAll('.view-doc-btn').forEach(button => {
        button.addEventListener('click', function() {
            const docId = this.getAttribute('data-doc-id');
            const docType = this.getAttribute('data-doc-type');
            viewDocumentInTab(docId, docType);
        });
    });

    
    document.querySelectorAll('.delete-doc-btn').forEach(button => {
        button.addEventListener('click', function() {
            const docId = this.getAttribute('data-doc-id');
            const docType = this.getAttribute('data-doc-type');
            deleteDocument(docId, docType);
        });
    });

    
    document.querySelectorAll('.view-docs-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (!this.disabled) {
                const docType = this.getAttribute('data-doc-type');
                viewDocuments(docType);
            }
        });
    });
});
</script>

<?php
$content = ob_get_clean();
include APP_ROOT . '/app/Views/layouts/main.php';
?>