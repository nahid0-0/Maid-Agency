<?php

?>

<div class="medical-training-page">
    <div class="page-header">
        <div class="header-content">
            <a href="/staff" class="back-link">← Back to Staff Dashboard</a>
            <h1>🏥 Medical & Training Records</h1>
            <p>Manage medical records and training certificates</p>
        </div>
    </div>

    
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card medical">
                <div class="stat-icon">🏥</div>
                <div class="stat-content">
                    <h3><?= $medical_stats['total_records'] ?? 0 ?></h3>
                    <p>Medical Records</p>
                    <div class="stat-breakdown">
                        <span class="fit">Fit: <?= $medical_stats['fit_count'] ?? 0 ?></span>
                        <span class="pending">Pending: <?= $medical_stats['pending_count'] ?? 0 ?></span>
                    </div>
                </div>
            </div>
            
            <div class="stat-card training">
                <div class="stat-icon">📚</div>
                <div class="stat-content">
                    <h3><?= $training_stats['total_records'] ?? 0 ?></h3>
                    <p>Training Records</p>
                    <div class="stat-breakdown">
                        <span class="completed">Completed: <?= $training_stats['completed_count'] ?? 0 ?></span>
                        <span class="in-progress">In Progress: <?= $training_stats['in_progress_count'] ?? 0 ?></span>
                    </div>
                </div>
            </div>
            
            <div class="stat-card expiring">
                <div class="stat-icon">⚠️</div>
                <div class="stat-content">
                    <h3><?= count($expiring_medical) + count($expiring_training) ?></h3>
                    <p>Expiring Soon</p>
                    <div class="stat-breakdown">
                        <span class="medical">Medical: <?= count($expiring_medical) ?></span>
                        <span class="training">Training: <?= count($expiring_training) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <?php if (!empty($expiring_medical) || !empty($expiring_training)): ?>
    <div class="alerts-section">
        <h3>⚠️ Expiring Records (Next 30 Days)</h3>
        <div class="alert-grid">
            <?php foreach (array_merge($expiring_medical, $expiring_training) as $item): ?>
            <div class="alert-card <?= isset($item['record_type']) ? 'medical' : 'training' ?>">
                <div class="alert-content">
                    <strong><?= htmlspecialchars($item['helper_name']) ?></strong>
                    <p>
                        <?= isset($item['record_type']) ? 
                            htmlspecialchars($item['record_type']) : 
                            htmlspecialchars($item['course_name']) ?>
                    </p>
                    <span class="expiry-date">
                        Expires: <?= date('M d, Y', strtotime(
                            $item['expiry_date'] ?? $item['certificate_expiry']
                        )) ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="filters-section">
        <div class="search-container">
            <input type="number" id="helperIdSearch" placeholder="Search by Helper ID..." class="search-input">
            <input type="text" id="helperNameSearch" placeholder="Search by Helper Name..." class="search-input">
            <select id="recordTypeFilter" class="filter-select">
                <option value="">All Types</option>
                <option value="medical">Medical Records</option>
                <option value="training">Training Records</option>
            </select>
            <button id="clearButton" class="btn-secondary">🔄 Clear</button>
        </div>
    </div>

    
    <div class="results-summary">
        <span id="resultsCounter">Showing all records</span>
    </div>

    
    <div class="tabs-container">
        <h2 style="padding: 20px 20px 0; margin: 0; color: #1f2937;">📋 All Medical & Training Records</h2>
        <div id="all-records-tab" class="tab-content active" style="display: block;">
            <div class="table-container">
                <table class="records-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Helper</th>
                            <th>Record Details</th>
                            <th>Date</th>
                            <th>Provider/Clinic</th>
                            <th>Status</th>
                            <th>Expiry</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        $allRecords = [];

                        
                        foreach ($medical_records as $record) {
                            $allRecords[] = array_merge($record, ['record_category' => 'Medical']);
                        }

                        
                        foreach ($training_records as $record) {
                            $allRecords[] = array_merge($record, ['record_category' => 'Training']);
                        }

                        
                        usort($allRecords, function($a, $b) {
                            $dateA = $a['record_date'] ?? $a['start_date'] ?? '';
                            $dateB = $b['record_date'] ?? $b['start_date'] ?? '';
                            return strtotime($dateB) - strtotime($dateA);
                        });
                        ?>

                        <?php if (empty($allRecords)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #6b7280;">
                                <div style="font-size: 48px; margin-bottom: 10px;">📋</div>
                                <strong>No records found</strong>
                                <p style="margin: 5px 0 0 0;">Medical and training records will appear here once they are added.</p>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($allRecords as $record): ?>
                        <tr class="record-row"
                            data-helper-id="<?= isset($record['helper_id']) ? $record['helper_id'] : 'MISSING' ?>"
                            data-helper-name="<?= htmlspecialchars(strtolower($record['helper_name'] ?? 'unknown')) ?>"
                            data-record-type="<?= strtolower($record['record_category']) ?>">
                            
                            <td>
                                <?php if ($record['record_category'] === 'Medical'): ?>
                                    <span class="record-type" style="background: #fee2e2; color: #991b1b;">🏥 Medical</span>
                                <?php else: ?>
                                    <span class="record-type" style="background: #dbeafe; color: #1e40af;">📚 Training</span>
                                <?php endif; ?>
                            </td>

                            
                            <td>
                                <div class="helper-info">
                                    <strong><?= htmlspecialchars($record['helper_name']) ?></strong>
                                    <small><?= htmlspecialchars($record['nationality'] ?? '') ?></small>
                                </div>
                            </td>

                            
                            <td>
                                <?php if ($record['record_category'] === 'Medical'): ?>
                                    <strong><?= htmlspecialchars($record['record_type']) ?></strong>
                                <?php else: ?>
                                    <strong><?= htmlspecialchars($record['course_name']) ?></strong>
                                    <?php if (!empty($record['certificate_number'])): ?>
                                        <br><small>Cert: <?= htmlspecialchars($record['certificate_number']) ?></small>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>

                            
                            <td>
                                <?php
                                $displayDate = $record['record_date'] ?? $record['start_date'] ?? '';
                                if ($displayDate) {
                                    echo date('M d, Y', strtotime($displayDate));
                                } else {
                                    echo '<span class="text-muted">N/A</span>';
                                }
                                ?>
                            </td>

                            
                            <td>
                                <?php if ($record['record_category'] === 'Medical'): ?>
                                    <?php if (!empty($record['doctor_name'])): ?>
                                        <strong><?= htmlspecialchars($record['doctor_name']) ?></strong><br>
                                    <?php endif; ?>
                                    <small><?= htmlspecialchars($record['clinic_hospital'] ?? 'Not specified') ?></small>
                                <?php else: ?>
                                    <?= htmlspecialchars($record['training_provider'] ?? 'Not specified') ?>
                                <?php endif; ?>
                            </td>

                            
                            <td>
                                <span class="status-badge <?= strtolower(str_replace(' ', '-', $record['status'])) ?>">
                                    <?= htmlspecialchars($record['status']) ?>
                                </span>
                            </td>

                            
                            <td>
                                <?php
                                $expiryDateField = $record['expiry_date'] ?? $record['certificate_expiry'] ?? null;
                                if ($expiryDateField):
                                    $expiryDate = new DateTime($expiryDateField);
                                    $today = new DateTime();
                                    $daysUntilExpiry = $today->diff($expiryDate)->days;
                                    $isExpired = $expiryDate < $today;
                                    $isExpiringSoon = $daysUntilExpiry <= 30 && !$isExpired;
                                ?>
                                    <span class="expiry-date <?= $isExpired ? 'expired' : ($isExpiringSoon ? 'expiring' : '') ?>">
                                        <?= date('M d, Y', strtotime($expiryDateField)) ?>
                                        <?php if ($isExpired): ?>
                                            (Expired)
                                        <?php elseif ($isExpiringSoon): ?>
                                            (<?= $daysUntilExpiry ?> days)
                                        <?php endif; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">No expiry</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<div id="addRecordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add Record</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="addRecordForm">
                <input type="hidden" id="recordType" name="record_type_form">
                
                
                <div class="form-group">
                    <label for="helper_id">Helper *</label>
                    <select id="helper_id" name="helper_id" required>
                        <option value="">Select Helper</option>
                        <?php foreach ($helpers as $helper): ?>
                            <option value="<?= $helper['id'] ?>"><?= htmlspecialchars($helper['name']) ?> - <?= htmlspecialchars($helper['nationality']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                
                <div id="medicalFields" class="record-fields">
                    <div class="form-group">
                        <label for="medical_record_type">Record Type *</label>
                        <select id="medical_record_type" name="record_type">
                            <option value="">Select Type</option>
                            <option value="General Health Check">General Health Check</option>
                            <option value="Annual Physical">Annual Physical</option>
                            <option value="Vaccination">Vaccination</option>
                            <option value="Medical Clearance">Medical Clearance</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="record_date">Record Date *</label>
                            <input type="date" id="record_date" name="record_date">
                        </div>
                        <div class="form-group">
                            <label for="expiry_date">Expiry Date</label>
                            <input type="date" id="expiry_date" name="expiry_date">
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="doctor_name">Doctor Name</label>
                            <input type="text" id="doctor_name" name="doctor_name">
                        </div>
                        <div class="form-group">
                            <label for="clinic_hospital">Clinic/Hospital</label>
                            <input type="text" id="clinic_hospital" name="clinic_hospital">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="medical_status">Status *</label>
                        <select id="medical_status" name="status">
                            <option value="">Select Status</option>
                            <option value="Fit">Fit</option>
                            <option value="Unfit">Unfit</option>
                            <option value="Conditional">Conditional</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                </div>

                
                <div id="trainingFields" class="record-fields">
                    <div class="form-group">
                        <label for="course_name">Course Name *</label>
                        <input type="text" id="course_name" name="course_name" placeholder="e.g., Basic First Aid, Child Care Certification">
                    </div>
                    
                    <div class="form-group">
                        <label for="training_provider">Training Provider</label>
                        <input type="text" id="training_provider" name="training_provider" placeholder="e.g., Singapore Red Cross, SkillsFuture">
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="start_date">Start Date *</label>
                            <input type="date" id="start_date" name="start_date">
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" id="end_date" name="end_date">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="training_status">Status</label>
                        <select id="training_status" name="status">
                            <option value="Enrolled">Enrolled</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="Failed">Failed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="certificate_number">Certificate Number</label>
                            <input type="text" id="certificate_number" name="certificate_number">
                        </div>
                        <div class="form-group">
                            <label for="certificate_expiry">Certificate Expiry</label>
                            <input type="date" id="certificate_expiry" name="certificate_expiry">
                        </div>
                    </div>
                </div>

                
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Additional notes or observations"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" onclick="closeModal()">Cancel</button>
            <button type="button" class="btn-primary" onclick="saveRecord()">Save Record</button>
        </div>
    </div>
</div>

<style>

* {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}

.medical-training-page {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
    background-color: #f8f9fa;
    min-height: 100vh;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e5e7eb;
}

.header-content {
    flex: 1;
}

.back-link {
    color: #3b82f6;
    text-decoration: none;
    font-weight: 500;
    margin-bottom: 10px;
    display: inline-block;
    transition: color 0.3s ease;
}

.back-link:hover {
    color: #1d4ed8;
}

.page-header h1 {
    margin: 10px 0 5px 0;
    color: #1f2937;
    font-size: 2rem;
}

.page-header p {
    color: #6b7280;
    margin: 0;
    font-size: 14px;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.stats-section {
    margin-bottom: 30px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-card.medical { border-left: 4px solid #ef4444; }
.stat-card.training { border-left: 4px solid #3b82f6; }
.stat-card.expiring { border-left: 4px solid #f59e0b; }

.stat-icon {
    font-size: 2.5rem;
}

.stat-content h3 {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
}

.stat-content p {
    margin: 5px 0;
    color: #6b7280;
    font-weight: 500;
}

.stat-breakdown {
    display: flex;
    gap: 10px;
    font-size: 12px;
}

.stat-breakdown span {
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 500;
}

.stat-breakdown .fit { background: #d1fae5; color: #065f46; }
.stat-breakdown .pending { background: #fef3c7; color: #92400e; }
.stat-breakdown .completed { background: #d1fae5; color: #065f46; }
.stat-breakdown .in-progress { background: #dbeafe; color: #1e40af; }
.stat-breakdown .medical { background: #fee2e2; color: #991b1b; }
.stat-breakdown .training { background: #dbeafe; color: #1e40af; }

.alerts-section {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 30px;
}

.alerts-section h3 {
    margin: 0 0 15px 0;
    color: #991b1b;
}

.alert-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.alert-card {
    background: white;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #ef4444;
}

.alert-card.training {
    border-left-color: #f59e0b;
}

.alert-content strong {
    display: block;
    color: #1f2937;
}

.alert-content p {
    margin: 5px 0;
    color: #6b7280;
    font-size: 14px;
}

.expiry-date {
    font-size: 12px;
    font-weight: 500;
}

.expiry-date.expired { color: #dc2626; }
.expiry-date.expiring { color: #d97706; }

.tabs-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.tabs-nav {
    display: flex;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.tab-btn {
    flex: 1;
    background: none;
    border: none;
    padding: 15px 20px;
    cursor: pointer;
    font-weight: 500;
    color: #6b7280;
    transition: all 0.3s ease;
}

.tab-btn.active {
    background: white;
    color: #1f2937;
    border-bottom: 2px solid #3b82f6;
}

.tab-btn:hover:not(.active) {
    background: #f1f5f9;
    color: #374151;
}

.tab-content {
    display: none;
    padding: 20px;
}

.tab-content.active {
    display: block;
}

.table-container {
    overflow-x: auto;
}

.records-table {
    width: 100%;
    border-collapse: collapse;
}

.records-table th {
    background: #f8fafc;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 2px solid #e5e7eb;
}

.records-table td {
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
}

.records-table tr:hover {
    background: #f8fafc;
}

.helper-info strong {
    display: block;
    color: #1f2937;
}

.helper-info small {
    color: #6b7280;
    font-size: 12px;
}

.record-type {
    background: #e0e7ff;
    color: #3730a3;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.fit { background: #d1fae5; color: #065f46; }
.status-badge.unfit { background: #fee2e2; color: #991b1b; }
.status-badge.conditional { background: #fef3c7; color: #92400e; }
.status-badge.pending { background: #f3f4f6; color: #6b7280; }
.status-badge.completed { background: #d1fae5; color: #065f46; }
.status-badge.in-progress { background: #dbeafe; color: #1e40af; }
.status-badge.enrolled { background: #e0e7ff; color: #3730a3; }
.status-badge.failed { background: #fee2e2; color: #991b1b; }
.status-badge.cancelled { background: #f3f4f6; color: #6b7280; }

.text-muted {
    color: #9ca3af;
    font-size: 13px;
}

.filters-section {
    background: white;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 24px;
    border: 1px solid #e5e7eb;
}

.search-container {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    align-items: center;
}

.search-input, .filter-select {
    padding: 10px 15px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.search-input {
    flex: 1;
    min-width: 200px;
    font-family: inherit;
}

.search-input:focus, .filter-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.results-summary {
    margin-bottom: 16px;
    padding: 12px 16px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}

.results-summary span {
    color: #64748b;
    font-size: 14px;
    font-weight: 500;
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 2px solid #e5e7eb;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: inherit;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-action {
    background: none;
    border: none;
    padding: 8px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.3s ease;
}

.btn-action:hover {
    background: #f3f4f6;
}

.helpers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.helper-card {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px;
    transition: transform 0.3s ease;
}

.helper-card:hover {
    transform: translateY(-2px);
}

.helper-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.helper-photo {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
}

.helper-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-placeholder {
    font-size: 1.5rem;
    color: #9ca3af;
}

.helper-info h4 {
    margin: 0 0 5px 0;
    color: #1f2937;
}

.helper-info p {
    margin: 0 0 8px 0;
    color: #6b7280;
    font-size: 14px;
}

.helper-stats {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
}

.stat-label {
    font-size: 12px;
    color: #6b7280;
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 2px solid #e5e7eb;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background: white;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 80vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
    margin: 0;
    color: #1f2937;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #6b7280;
}

.modal-close:hover {
    color: #374151;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 20px;
    border-top: 1px solid #e5e7eb;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #374151;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #e5e7eb;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s ease;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3b82f6;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.record-fields {
    display: none;
}

.record-fields.active {
    display: block;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .tabs-nav {
        flex-direction: column;
    }
    
    .helpers-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        margin: 2% auto;
        width: 95%;
    }
}

</style>

<script>
function clearFilters() {
    document.getElementById('helperIdSearch').value = '';
    document.getElementById('helperNameSearch').value = '';
    document.getElementById('recordTypeFilter').value = '';
    filterRecords();
}

function filterRecords() {
    const helperIdSearch = document.getElementById('helperIdSearch').value.trim();
    const helperNameSearch = document.getElementById('helperNameSearch').value.toLowerCase().trim();
    const recordTypeFilter = document.getElementById('recordTypeFilter').value;

    const rows = document.querySelectorAll('.record-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const helperId = row.dataset.helperId || '';
        const helperName = row.dataset.helperName || '';
        const recordType = row.dataset.recordType || '';

        
        const matchesId = !helperIdSearch || String(helperId) === String(helperIdSearch);
        const matchesName = !helperNameSearch || helperName.includes(helperNameSearch);
        const matchesType = !recordTypeFilter || recordType === recordTypeFilter;

        
        if (matchesId && matchesName && matchesType) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    
    updateResultsCount(visibleCount, rows.length);
}

function updateResultsCount(visible, total) {
    const counter = document.getElementById('resultsCounter');

    if (visible === total) {
        counter.textContent = `Showing all ${total} records`;
        counter.style.color = '#64748b';
    } else if (visible === 0) {
        counter.textContent = 'No records match your search criteria';
        counter.style.color = '#ef4444';
    } else {
        counter.textContent = `Showing ${visible} of ${total} records`;
        counter.style.color = '#64748b';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const helperIdInput = document.getElementById('helperIdSearch');
    const helperNameInput = document.getElementById('helperNameSearch');
    const recordTypeSelect = document.getElementById('recordTypeFilter');
    const clearButton = document.getElementById('clearButton');

    
    if (helperIdInput) {
        helperIdInput.addEventListener('input', filterRecords);
    }
    if (helperNameInput) {
        helperNameInput.addEventListener('input', filterRecords);
    }
    if (recordTypeSelect) {
        recordTypeSelect.addEventListener('change', filterRecords);
    }

    
    if (clearButton) {
        clearButton.addEventListener('click', clearFilters);
    }

    
    const totalRows = document.querySelectorAll('.record-row').length;
    if (totalRows > 0) {
        updateResultsCount(totalRows, totalRows);
    } else {
        const counter = document.getElementById('resultsCounter');
        if (counter) {
            counter.textContent = 'No records available';
        }
    }
});
</script>
