<?php
ob_start();
?>

<div class="browse-helpers-page">
    <a href="/client" class="back-link">← Back to Dashboard</a>
    
    <div class="page-header">
        <h1>🔍 Browse Helpers</h1>
        <p>Find the perfect helper for your household needs</p>
    </div>
    
    
    <div class="search-section">
        <form id="filterForm" class="filter-form">
            <div class="filter-grid">
                <div class="filter-group">
                    <label for="nationality">Nationality</label>
                    <select id="nationality" name="nationality">
                        <option value="">All Nationalities</option>
                        <?php if (!empty($nationalities)): ?>
                            <?php foreach ($nationalities as $nat): ?>
                                <option value="<?= htmlspecialchars($nat['nationality']) ?>" 
                                        <?= ($filters['nationality'] ?? '') === $nat['nationality'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($nat['nationality']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="experience_level">Experience Level</label>
                    <select id="experience_level" name="experience_level">
                        <option value="">All Experience Levels</option>
                        <option value="Beginner (0-2 years)" <?= ($filters['experience_level'] ?? '') === 'Beginner (0-2 years)' ? 'selected' : '' ?>>Beginner (0-2 years)</option>
                        <option value="Intermediate (2-5 years)" <?= ($filters['experience_level'] ?? '') === 'Intermediate (2-5 years)' ? 'selected' : '' ?>>Intermediate (2-5 years)</option>
                        <option value="Experienced (5+ years)" <?= ($filters['experience_level'] ?? '') === 'Experienced (5+ years)' ? 'selected' : '' ?>>Experienced (5+ years)</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender">
                        <option value="">Any Gender</option>
                        <option value="Female" <?= ($filters['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                        <option value="Male" <?= ($filters['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="status">Availability</label>
                    <select id="status" name="status">
                        <option value="">All Status</option>
                        <option value="Available" <?= ($filters['status'] ?? '') === 'Available' ? 'selected' : '' ?>>Available</option>
                        <option value="Training" <?= ($filters['status'] ?? '') === 'Training' ? 'selected' : '' ?>>In Training</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="min_salary">Min Salary (SGD)</label>
                    <input type="number" id="min_salary" name="min_salary" min="0" step="50" 
                           value="<?= htmlspecialchars($filters['min_salary'] ?? '') ?>" placeholder="800">
                </div>
                
                <div class="filter-group">
                    <label for="max_salary">Max Salary (SGD)</label>
                    <input type="number" id="max_salary" name="max_salary" min="0" step="50" 
                           value="<?= htmlspecialchars($filters['max_salary'] ?? '') ?>" placeholder="1200">
                </div>
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="btn-filter">Apply Filters</button>
                <button type="button" class="btn-clear" onclick="clearFilters()">Clear All</button>
            </div>
        </form>
    </div>
    
    
    <div class="results-section">
        <div class="results-header">
            <h2>Available Helpers</h2>
            <span class="results-count"><?= count($helpers) ?> helpers found</span>
        </div>
        
        <?php if (empty($helpers)): ?>
            <div class="no-results">
                <div class="no-results-icon">😔</div>
                <h3>No helpers found</h3>
                <p>Try adjusting your search filters or check back later for new helper profiles.</p>
                <button type="button" class="btn-clear" onclick="clearFilters()">Clear Filters</button>
            </div>
        <?php else: ?>
            <div class="helpers-grid">
                <?php foreach ($helpers as $helper): ?>
                    <div class="helper-card" data-helper-id="<?= $helper['id'] ?>">
                        <div class="helper-photo">
                            <?php if (!empty($helper['profile_picture_url'])): ?>
                                <img src="<?= htmlspecialchars($helper['profile_picture_url']) ?>" alt="<?= htmlspecialchars($helper['name']) ?>">
                            <?php else: ?>
                                <div class="photo-placeholder">
                                    <span class="photo-icon">👤</span>
                                </div>
                            <?php endif; ?>
                            <div class="status-badge status-<?= strtolower(str_replace(' ', '-', $helper['status'])) ?>">
                                <?= htmlspecialchars($helper['status']) ?>
                            </div>
                        </div>
                        
                        <div class="helper-info">
                            <h3 class="helper-name"><?= htmlspecialchars($helper['name']) ?></h3>
                            <div class="helper-details">
                                <div class="detail-item">
                                    <span class="detail-icon">🌍</span>
                                    <span><?= htmlspecialchars($helper['nationality']) ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-icon">👤</span>
                                    <span><?= htmlspecialchars($helper['gender']) ?>, <?= $helper['age'] ?? 'N/A' ?> years</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-icon">⭐</span>
                                    <span><?= htmlspecialchars($helper['experience_level']) ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-icon">💰</span>
                                    <span>SGD <?= number_format((float)($helper['salary_min'] ?? 0), 0) ?> - <?= number_format((float)($helper['salary_max'] ?? 0), 0) ?></span>
                                </div>
                            </div>
                            
                            <?php if (!empty($helper['special_skills'])): ?>
                                <div class="helper-skills">
                                    <h4>Skills</h4>
                                    <p><?= htmlspecialchars(substr($helper['special_skills'], 0, 100)) ?><?= strlen($helper['special_skills']) > 100 ? '...' : '' ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-actions">
                                <button class="btn-view-profile" data-helper-id="<?= $helper['id'] ?>">
                                    View Profile
                                </button>
                                <?php if ($helper['status'] === 'Available'): ?>
                                    <button class="btn-request-helper" data-helper-id="<?= $helper['id'] ?>" data-helper-name="<?= htmlspecialchars($helper['name']) ?>">
                                        Request Helper
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="helperModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Helper Profile</h2>
            <span class="modal-close" onclick="closeModal()">&times;</span>
        </div>
        <div class="modal-body" id="modalBody">
            
        </div>
    </div>
</div>

<style>
.browse-helpers-page {
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

.search-section {
    background: white;
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
}

.filter-group select,
.filter-group input {
    padding: 12px 16px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.filter-group select:focus,
.filter-group input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.filter-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
}

.btn-filter {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.2s ease;
}

.btn-filter:hover {
    opacity: 0.9;
}

.btn-clear {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn-clear:hover {
    background: #e5e7eb;
}

.results-section {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.results-header h2 {
    color: #1f2937;
    font-size: 24px;
}

.results-count {
    color: #6b7280;
    font-size: 16px;
    font-weight: 500;
}

.no-results {
    text-align: center;
    padding: 60px 20px;
}

.no-results-icon {
    font-size: 48px;
    margin-bottom: 20px;
}

.no-results h3 {
    color: #374151;
    margin-bottom: 10px;
}

.no-results p {
    color: #6b7280;
    margin-bottom: 30px;
}

.helpers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 24px;
}

.helper-card {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    cursor: pointer;
}

.helper-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.helper-photo {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
}

.helper-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.photo-icon {
    font-size: 48px;
    color: #9ca3af;
}

.status-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-available {
    background: #d1fae5;
    color: #065f46;
}

.status-training {
    background: #fef3c7;
    color: #92400e;
}

.status-reserved {
    background: #dbeafe;
    color: #1e40af;
}

.status-deployed {
    background: #e0e7ff;
    color: #3730a3;
}

.status-inactive {
    background: #fee2e2;
    color: #991b1b;
}

.helper-info {
    padding: 20px;
}

.helper-name {
    color: #1f2937;
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 16px;
}

.helper-details {
    margin-bottom: 16px;
}

.detail-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}

.detail-icon {
    width: 20px;
    margin-right: 8px;
    font-size: 14px;
}

.detail-item span:last-child {
    color: #4b5563;
    font-size: 14px;
}

.helper-skills {
    margin-bottom: 20px;
}

.helper-skills h4 {
    color: #374151;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 6px;
}

.helper-skills p {
    color: #6b7280;
    font-size: 14px;
    line-height: 1.4;
}

.card-actions {
    display: flex;
    gap: 8px;
}

.btn-view-profile {
    flex: 1;
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    padding: 10px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn-view-profile:hover {
    background: #e5e7eb;
}

.btn-request-helper {
    flex: 1;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.2s ease;
}

.btn-request-helper:hover {
    opacity: 0.9;
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
    max-width: 800px;
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
    font-size: 24px;
}

.modal-close {
    font-size: 28px;
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
    .browse-helpers-page {
        padding: 10px;
    }
    
    .filter-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-actions {
        flex-direction: column;
    }
    
    .helpers-grid {
        grid-template-columns: 1fr;
    }
    
    .results-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .modal-content {
        width: 95%;
        margin: 2% auto;
    }
    
    .modal-body {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .page-header h1 {
        font-size: 24px;
    }
    
    .card-actions {
        flex-direction: column;
    }
}

.helper-profile {
    max-width: 100%;
}

.profile-header {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e5e7eb;
}

.profile-photo {
    flex-shrink: 0;
}

.modal-profile-img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e5e7eb;
    max-width: 80px;
    max-height: 80px;
}

.modal-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #e5e7eb;
}

.modal-placeholder .photo-icon {
    font-size: 24px;
    color: #9ca3af;
}

.profile-info {
    flex: 1;
}

.profile-info h3 {
    font-size: 28px;
    color: #1f2937;
    margin: 0 0 10px 0;
}

.profile-details {
    max-width: 100%;
}

.detail-section {
    margin-bottom: 25px;
}

.detail-section h4 {
    color: #374151;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
}

.detail-grid {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.detail-grid .detail-item {
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
    line-height: 1.5;
}

.detail-grid .detail-item:last-child {
    border-bottom: none;
}

.detail-grid .detail-item strong {
    display: inline-block;
    min-width: 100px;
    color: #374151;
}

.profile-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
    text-align: center;
}

.profile-actions .btn-request-helper {
    padding: 12px 30px;
    font-size: 16px;
    border-radius: 8px;
}

.profile-actions em {
    color: #6b7280;
    font-style: italic;
}
</style>

<script<?= defined('CSP_NONCE') ? ' nonce="' . CSP_NONCE . '"' : '' ?>>

document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const params = new URLSearchParams();
    
    for (const [key, value] of formData.entries()) {
        if (value.trim() !== '') {
            params.append(key, value);
        }
    }
    
    
    window.location.href = `/client/browse-helpers?${params.toString()}`;
});

document.addEventListener('DOMContentLoaded', function() {
    
    document.querySelectorAll('.btn-view-profile').forEach(button => {
        button.addEventListener('click', function() {
            const helperId = this.getAttribute('data-helper-id');
            viewHelperProfile(helperId);
        });
    });

    
    document.querySelectorAll('.btn-request-helper').forEach(button => {
        button.addEventListener('click', function() {
            const helperId = this.getAttribute('data-helper-id');
            const helperName = this.getAttribute('data-helper-name');
            requestHelperWithName(helperId, helperName);
        });
    });

    
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-request-btn')) {
            const helperId = e.target.getAttribute('data-helper-id');
            const helperName = e.target.getAttribute('data-helper-name');
            requestHelperWithName(helperId, helperName);
        }
    });
});

function clearFilters() {
    window.location.href = '/client/browse-helpers';
}

function viewHelperProfile(helperId) {
    
    document.getElementById('modalBody').innerHTML = '<div style="text-align: center; padding: 40px;"><div style="font-size: 24px;">⏳</div><p>Loading helper profile...</p></div>';
    document.getElementById('helperModal').style.display = 'block';
    
    
    fetch(`/client/helper-details/${helperId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayHelperProfile(data.helper);
            } else {
                document.getElementById('modalBody').innerHTML = '<div style="text-align: center; padding: 40px;"><div style="font-size: 24px;">❌</div><p>Failed to load helper profile.</p></div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modalBody').innerHTML = '<div style="text-align: center; padding: 40px;"><div style="font-size: 24px;">❌</div><p>Error loading helper profile.</p></div>';
        });
}

function displayHelperProfile(helper) {
    const modalBody = document.getElementById('modalBody');
    modalBody.innerHTML = `
        <div class="helper-profile">
            <div class="profile-header">
                <div class="profile-photo">
                    ${helper.profile_picture_url ? 
                        `<img src="${helper.profile_picture_url}" alt="${helper.name}" class="modal-profile-img">` : 
                        '<div class="photo-placeholder modal-placeholder"><span class="photo-icon">👤</span></div>'
                    }
                </div>
                <div class="profile-info">
                    <h3>${helper.name}</h3>
                    <div class="status-badge status-${helper.status.toLowerCase().replace(' ', '-')}">${helper.status}</div>
                </div>
            </div>
            
            <div class="profile-details">
                <div class="detail-section">
                    <h4>Personal Information</h4>
                    <div class="detail-grid">
                        <div class="detail-item"><strong>Nationality:</strong> ${helper.nationality}</div>
                        <div class="detail-item"><strong>Gender:</strong> ${helper.gender}</div>
                        <div class="detail-item"><strong>Age:</strong> ${helper.age || 'N/A'} years</div>
                        <div class="detail-item"><strong>Experience:</strong> ${helper.experience_level}</div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h4>Salary Range</h4>
                    <p>SGD ${parseInt(helper.salary_min || 0).toLocaleString()} - ${parseInt(helper.salary_max || 0).toLocaleString()} per month</p>
                </div>
                
                ${helper.special_skills ? `
                    <div class="detail-section">
                        <h4>Skills & Abilities</h4>
                        <p>${helper.special_skills}</p>
                    </div>
                ` : ''}
                
                ${helper.previous_experience ? `
                    <div class="detail-section">
                        <h4>Previous Experience</h4>
                        <p>${helper.previous_experience}</p>
                    </div>
                ` : ''}
                
                ${helper.dietary_restrictions && helper.dietary_restrictions !== 'None' ? `
                    <div class="detail-section">
                        <h4>Dietary Restrictions</h4>
                        <p>${helper.dietary_restrictions}</p>
                    </div>
                ` : ''}
                
                <div class="profile-actions">
                    ${helper.status === 'Available' ? 
                        `<button class="btn-request-helper modal-request-btn" data-helper-id="${helper.id}" data-helper-name="${helper.name}">Request This Helper</button>` : 
                        '<p><em>This helper is not currently available for new requests.</em></p>'
                    }
                </div>
            </div>
        </div>
    `;
}

function requestHelperWithName(helperId, helperName) {
    
    const requirements = prompt(`Please describe your requirements for ${helperName}:\n(e.g., cooking, cleaning, childcare, etc.)`);

    if (requirements === null) {
        return; 
    }

    if (requirements.trim() === '') {
        alert('Please provide some requirements for your helper request.');
        return;
    }

    if (confirm(`Submit request for ${helperName}?\n\nRequirements: ${requirements}`)) {
        submitHelperRequest(helperId, requirements, helperName);
    }
}

function submitHelperRequest(helperId, requirements, helperName = 'helper') {
    
    const formData = new FormData();
    formData.append('csrf_token', '<?= (new Core\Session())->getCsrfToken() ?>');
    formData.append('request_type', 'New Hire');
    formData.append('urgency', 'Medium');
    formData.append('start_date', new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]); 
    formData.append('helper_id', helperId);
    formData.append('requirements', requirements);
    formData.append('notes', `Request for ${helperName} submitted via browse helpers page`);

    fetch('/client/submit-request', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Request submitted successfully! You can view it in your request history.');
            
            window.location.href = '/client/my-requests';
        } else {
            alert('Error submitting request: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error submitting request. Please try again.');
    });
}

function requestHelper(helperId) {
    
    const helperCard = event.target.closest('.helper-card');
    const helperName = helperCard.querySelector('h3').textContent;

    
    const requirements = prompt(`Please describe your requirements for ${helperName}:\n(e.g., cooking, cleaning, childcare, etc.)`);

    if (requirements === null) {
        return; 
    }

    if (requirements.trim() === '') {
        alert('Please provide some requirements for your helper request.');
        return;
    }

    if (confirm(`Submit request for ${helperName}?\n\nRequirements: ${requirements}`)) {
        submitHelperRequest(helperId, requirements, helperName);
    }
}

function closeModal() {
    document.getElementById('helperModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('helperModal');
    if (event.target === modal) {
        closeModal();
    }
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});
</script>

<?php
$content = ob_get_clean();
$title = 'Browse Helpers - Client Dashboard';
require_once __DIR__ . '/../layouts/main.php';
?>