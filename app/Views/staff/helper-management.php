<?php

?>

<div class="helper-management-page">
    <div class="page-header">
        <div class="header-content">
            <a href="/staff" class="back-link">← Back to Staff Dashboard</a>
            <h1>👥 Helper Management</h1>
            <p>Manage all domestic helpers in the system</p>
        </div>
        <div class="header-actions">
            <button class="btn-primary" onclick="window.location.href='/staff/helper-management/create'">
                ➕ Add New Helper
            </button>
        </div>
    </div>

    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <h3><?= $statistics['total'] ?></h3>
                <p>Total Helpers</p>
            </div>
        </div>
        <div class="stat-card available">
            <div class="stat-icon">✅</div>
            <div class="stat-content">
                <h3><?= $statistics['available'] ?></h3>
                <p>Available</p>
            </div>
        </div>
        <div class="stat-card deployed">
            <div class="stat-icon">🚀</div>
            <div class="stat-content">
                <h3><?= $statistics['deployed'] ?></h3>
                <p>Deployed</p>
            </div>
        </div>
        <div class="stat-card training">
            <div class="stat-icon">📚</div>
            <div class="stat-content">
                <h3><?= $statistics['training'] ?></h3>
                <p>In Training</p>
            </div>
        </div>
        <div class="stat-card inactive">
            <div class="stat-icon">⏸️</div>
            <div class="stat-content">
                <h3><?= $statistics['inactive'] ?></h3>
                <p>Inactive</p>
            </div>
        </div>
    </div>

    
    <div class="filters-section">
        <div class="filter-group">
            <div class="search-container">
                <input type="text" id="searchHelper" placeholder="Search by name, nationality, or email..." class="search-input">
                <input type="number" id="helperIdSearch" placeholder="Search by Helper ID..." class="search-input helper-id-input">
            </div>
            <div class="filter-container">
                <select id="statusFilter" class="filter-select">
                    <option value="">All Statuses</option>
                    <option value="Available">Available</option>
                    <option value="Deployed">Deployed</option>
                    <option value="Training">Training</option>
                    <option value="Reserved">Reserved</option>
                    <option value="Inactive">Inactive</option>
                </select>
                <select id="nationalityFilter" class="filter-select">
                    <option value="">All Nationalities</option>
                    <option value="Philippines">Philippines</option>
                    <option value="Indonesia">Indonesia</option>
                    <option value="Thailand">Thailand</option>
                    <option value="China">China</option>
                    <option value="India">India</option>
                    <option value="Myanmar">Myanmar</option>
                    <option value="Vietnam">Vietnam</option>
                    <option value="Cambodia">Cambodia</option>
                </select>
                <button class="btn-secondary" onclick="clearFilters()">🔄 Clear Filters</button>
            </div>
        </div>
    </div>

    
    <div class="results-summary">
        <span id="resultsCounter">Showing all <?= count($helpers) ?> helpers</span>
    </div>

    
    <div class="table-container">
        <table class="helpers-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Nationality</th>
                    <th>Age</th>
                    <th>Experience</th>
                    <th>Status</th>
                    <th>Salary Range</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody id="helpersTableBody">
                <?php foreach ($helpers as $helper): ?>
                <tr class="helper-row" data-status="<?= htmlspecialchars($helper['status']) ?>" data-nationality="<?= htmlspecialchars($helper['nationality']) ?>" data-helper-id="<?= $helper['id'] ?>" data-email="<?= htmlspecialchars($helper['email'] ?? '') ?>">
                    <td>
                        <div class="helper-id">
                            <span class="id-badge">#<?= $helper['id'] ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="helper-photo">
                            <?php if (!empty($helper['profile_picture_url'])): ?>
                                <img src="<?= htmlspecialchars($helper['profile_picture_url']) ?>" alt="<?= htmlspecialchars($helper['name']) ?>" class="profile-img">
                            <?php else: ?>
                                <div class="photo-placeholder">
                                    <span class="photo-icon">👤</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="helper-name">
                            <strong><?= htmlspecialchars($helper['name']) ?></strong>
                            <?php if ($helper['email']): ?>
                                <small><?= htmlspecialchars($helper['email']) ?></small>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <span class="nationality-badge"><?= htmlspecialchars($helper['nationality']) ?></span>
                    </td>
                    <td><?= $helper['age'] ?> years</td>
                    <td>
                        <span class="experience-badge <?= strtolower(explode(' ', $helper['experience_level'])[0]) ?>">
                            <?= htmlspecialchars($helper['experience_level']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="status-badge <?= strtolower($helper['status']) ?>">
                            <?= htmlspecialchars($helper['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($helper['salary_min'] && $helper['salary_max']): ?>
                            $<?= number_format($helper['salary_min']) ?> - $<?= number_format($helper['salary_max']) ?>
                        <?php elseif ($helper['salary_min']): ?>
                            From $<?= number_format($helper['salary_min']) ?>
                        <?php else: ?>
                            <span class="text-muted">Not set</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($helper['contact_number']): ?>
                            <a href="tel:<?= htmlspecialchars($helper['contact_number']) ?>" class="contact-link">
                                <?= htmlspecialchars($helper['contact_number']) ?>
                            </a>
                        <?php else: ?>
                            <span class="text-muted">No contact</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (empty($helpers)): ?>
    <div class="empty-state">
        <div class="empty-icon">👥</div>
        <h3>No Helpers Found</h3>
        <p>Start by adding your first helper to the system.</p>
        <button class="btn-primary" onclick="window.location.href='/staff/helper-management/create'">
            Add First Helper
        </button>
    </div>
    <?php endif; ?>
</div>

<style>

* {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}

.helper-management-page {
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

.header-actions .btn-primary {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.header-actions .btn-primary:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 15px;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-card.available { border-left: 4px solid #10b981; }
.stat-card.deployed { border-left: 4px solid #3b82f6; }
.stat-card.training { border-left: 4px solid #f59e0b; }
.stat-card.inactive { border-left: 4px solid #6b7280; }

.stat-icon {
    font-size: 2rem;
    opacity: 0.8;
}

.stat-content h3 {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
}

.stat-content p {
    margin: 5px 0 0 0;
    color: #6b7280;
    font-weight: 500;
}

.filters-section {
    background: white;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 24px;
    border: 1px solid #e5e7eb;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.search-container {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.filter-container {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
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
    font-size: 14px;
    font-family: inherit;
}

.helper-id-input {
    flex: 0 0 180px;
    min-width: 180px;
}

.search-input:focus, .filter-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.helper-id {
    display: flex;
    align-items: center;
    justify-content: center;
}

.id-badge {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: white;
    font-weight: 600;
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 6px;
    letter-spacing: 0.5px;
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 2px solid #e5e7eb;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #e5e7eb;
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

.table-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.helpers-table {
    width: 100%;
    border-collapse: collapse;
}

.helpers-table th {
    background: #f8fafc;
    padding: 15px 12px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 2px solid #e5e7eb;
}

.helpers-table td {
    padding: 15px 12px;
    border-bottom: 1px solid #e5e7eb;
}

.helper-row:hover {
    background: #f8fafc;
}

.helper-photo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
}

.profile-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e5e7eb;
    color: #9ca3af;
}

.helper-name strong {
    display: block;
    color: #1f2937;
}

.helper-name small {
    color: #6b7280;
    font-size: 12px;
}

.nationality-badge {
    background: #dbeafe;
    color: #1e40af;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.experience-badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.experience-badge.beginner { background: #fef3c7; color: #92400e; }
.experience-badge.intermediate { background: #dbeafe; color: #1e40af; }
.experience-badge.experienced { background: #d1fae5; color: #065f46; }

.status-badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.available { background: #d1fae5; color: #065f46; }
.status-badge.deployed { background: #dbeafe; color: #1e40af; }
.status-badge.training { background: #fef3c7; color: #92400e; }
.status-badge.reserved { background: #e0e7ff; color: #3730a3; }
.status-badge.inactive { background: #f3f4f6; color: #6b7280; }

.contact-link {
    color: #3b82f6;
    text-decoration: none;
    font-size: 13px;
}

.contact-link:hover {
    text-decoration: underline;
}

.text-muted {
    color: #9ca3af;
    font-size: 13px;
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

.btn-delete:hover {
    background: #fee2e2;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state h3 {
    margin: 0 0 10px 0;
    color: #1f2937;
}

.empty-state p {
    color: #6b7280;
    margin-bottom: 30px;
}

@media (max-width: 768px) {
    .filter-group {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-input {
        min-width: auto;
    }
    
    .helpers-table {
        font-size: 14px;
    }
    
    .helpers-table th,
    .helpers-table td {
        padding: 10px 8px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>

<script>
function clearFilters() {
    document.getElementById('searchHelper').value = '';
    document.getElementById('helperIdSearch').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('nationalityFilter').value = '';
    filterHelpers();
}

function filterHelpers() {
    const searchTerm = document.getElementById('searchHelper').value.toLowerCase();
    const helperIdSearch = document.getElementById('helperIdSearch').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const nationalityFilter = document.getElementById('nationalityFilter').value;
    
    const rows = document.querySelectorAll('.helper-row');
    
    rows.forEach(row => {
        const name = row.querySelector('.helper-name strong').textContent.toLowerCase();
        const nationality = row.dataset.nationality.toLowerCase();
        const email = row.dataset.email.toLowerCase();
        const helperId = row.dataset.helperId;
        const status = row.dataset.status;
        
        
        const matchesGeneralSearch = !searchTerm || 
            name.includes(searchTerm) || 
            nationality.includes(searchTerm) || 
            email.includes(searchTerm);
        
        
        const matchesHelperIdSearch = !helperIdSearch || helperId === helperIdSearch;
        
        
        const matchesStatus = !statusFilter || status === statusFilter;
        const matchesNationality = !nationalityFilter || row.dataset.nationality === nationalityFilter;
        
        
        if (matchesGeneralSearch && matchesHelperIdSearch && matchesStatus && matchesNationality) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    
    updateResultsCount();
}

function updateResultsCount() {
    const allRows = document.querySelectorAll('.helper-row');
    const visibleRows = Array.from(allRows).filter(row => row.style.display !== 'none');
    const totalRows = allRows.length;
    
    const counter = document.getElementById('resultsCounter');
    
    if (visibleRows.length === totalRows) {
        counter.textContent = `Showing all ${totalRows} helpers`;
    } else {
        counter.textContent = `Showing ${visibleRows.length} of ${totalRows} helpers`;
    }
    
    
    if (visibleRows.length === 0) {
        counter.style.color = '#ef4444';
        counter.textContent = 'No helpers match your search criteria';
    } else {
        counter.style.color = '#64748b';
    }
}

document.getElementById('searchHelper').addEventListener('input', filterHelpers);
document.getElementById('helperIdSearch').addEventListener('input', filterHelpers);
document.getElementById('statusFilter').addEventListener('change', filterHelpers);
document.getElementById('nationalityFilter').addEventListener('change', filterHelpers);
</script>
