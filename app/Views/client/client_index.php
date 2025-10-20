<?php
ob_start();
?>

<div class="user-home">
    <div class="welcome-header">
        <h1>👨‍👩‍👧‍👦 Client Dashboard</h1>
        <p>Welcome back, <?= htmlspecialchars($client['name']) ?>!</p>
        <p>Find and manage your domestic help services</p>
    </div>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-icon">🔍</div>
            <h3>Browse Helpers</h3>
            <p>View available helpers and their profiles</p>
            <a href="/client/browse-helpers" class="card-link">Browse Helpers</a>
        </div>
        
        <div class="dashboard-card">
            <div class="card-icon">📋</div>
            <h3>My Requests</h3>
            <p>Submit and track your helper requests</p>
            <a href="/client/my-requests" class="card-link">My Requests</a>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">👤</div>
            <h3>My Profile</h3>
            <p>Update your profile and preferences</p>
            <a href="/client/profile" class="card-link">Manage Profile</a>
        </div>
    </div>
    
    
    <?php if (!empty($recent_requests)): ?>
    <div class="recent-section">
        <h2>Recent Requests</h2>
        <div class="requests-table">
            <table>
                <thead>
                    <tr>
                        <th>Request Type</th>
                        <th>Status</th>
                        <th>Urgency</th>
                        <th>Helper</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_requests as $request): ?>
                    <tr>
                        <td><?= htmlspecialchars($request['request_type']) ?></td>
                        <td>
                            <span class="status-badge <?= strtolower(str_replace(' ', '-', $request['status'])) ?>">
                                <?= htmlspecialchars($request['status']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="urgency-badge <?= strtolower($request['urgency']) ?>">
                                <?= htmlspecialchars($request['urgency']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($request['helper_name'] ?? 'Not assigned') ?></td>
                        <td><?= date('M j, Y', strtotime($request['created_at'])) ?></td>
                        <td>
                            <a href="/client/my-requests#request-<?= $request['id'] ?>" class="btn-sm">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="section-footer">
            <a href="/client/my-requests" class="btn-secondary">View All Requests</a>
        </div>
    </div>
    <?php endif; ?>
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
    margin: 5px 0;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.dashboard-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
    text-align: center;
}

.dashboard-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.card-icon {
    font-size: 48px;
    margin-bottom: 20px;
}

.dashboard-card h3 {
    font-size: 24px;
    color: #1f2937;
    margin-bottom: 15px;
}

.dashboard-card p {
    color: #6b7280;
    margin-bottom: 25px;
    line-height: 1.5;
}

.card-link {
    display: inline-block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: opacity 0.2s;
}

.card-link:hover {
    opacity: 0.9;
}

.recent-section {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.recent-section h2 {
    color: #1f2937;
    margin-bottom: 20px;
    font-size: 24px;
}

.requests-table {
    overflow-x: auto;
}

.requests-table table {
    width: 100%;
    border-collapse: collapse;
}

.requests-table th,
.requests-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.requests-table th {
    background: #f9fafb;
    font-weight: 600;
    color: #374151;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.pending {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.in-progress {
    background: #dbeafe;
    color: #1e40af;
}

.status-badge.matched {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.completed {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.cancelled {
    background: #fee2e2;
    color: #991b1b;
}

.urgency-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.urgency-badge.low {
    background: #f3f4f6;
    color: #4b5563;
}

.urgency-badge.medium {
    background: #fef3c7;
    color: #92400e;
}

.urgency-badge.high {
    background: #fed7aa;
    color: #c2410c;
}

.urgency-badge.urgent {
    background: #fee2e2;
    color: #991b1b;
}

.btn-sm {
    padding: 6px 12px;
    background: #6b7280;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 12px;
}

.btn-sm:hover {
    background: #4b5563;
}

.section-footer {
    margin-top: 20px;
    text-align: center;
}

.btn-secondary {
    padding: 10px 20px;
    background: #f3f4f6;
    color: #374151;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
}

.btn-secondary:hover {
    background: #e5e7eb;
}
</style>

<?php
$content = ob_get_clean();
$title = 'Client Dashboard';
require_once __DIR__ . '/../layouts/main.php';
?>
