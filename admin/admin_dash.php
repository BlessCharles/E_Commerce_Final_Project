<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../view/login.php");
    exit;
}

require_once "../classes/vendor_class.php";

$vendorObj = new Vendor();

// Fetch vendors from database grouped by status
$pending_vendors = $vendorObj->get_vendors_by_status('pending');
$approved_vendors = $vendorObj->get_vendors_by_status('approved');
$rejected_vendors = $vendorObj->get_vendors_by_status('rejected');

// Get counts for stats
$pending_count = count($pending_vendors);
$approved_count = count($approved_vendors);
$rejected_count = count($rejected_vendors);
$total_count = $pending_count + $approved_count + $rejected_count;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanSmart Ghana - Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
        }
        
        /* Navigation Bar */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 60px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #1e3a8a;
        }
        
        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .admin-badge {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }
        
        .btn-logout {
            padding: 10px 24px;
            background: white;
            border: 2px solid #ef4444;
            color: #ef4444;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-logout:hover {
            background: #ef4444;
            color: white;
        }
        
        /* Main Content */
        .main-content {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 60px;
        }
        
        /* Header Section */
        .page-header {
            margin-bottom: 30px;
        }
        
        .page-header h1 {
            font-size: 32px;
            color: #1e3a8a;
            margin-bottom: 8px;
        }
        
        .page-header p {
            font-size: 16px;
            color: #64748b;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .stat-icon {
            font-size: 36px;
            margin-bottom: 12px;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #64748b;
        }
        
        /* Tabs */
        .tabs-container {
            background: white;
            border-radius: 16px 16px 0 0;
            padding: 0 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .tabs {
            display: flex;
            gap: 30px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .tab {
            padding: 18px 0;
            font-size: 16px;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            transition: all 0.3s;
        }
        
        .tab:hover {
            color: #1e3a8a;
        }
        
        .tab.active {
            color: #1e3a8a;
            border-bottom-color: #fbbf24;
        }
        
        /* Content Area */
        .content-area {
            background: white;
            border-radius: 0 0 16px 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        /* Vendor Cards */
        .vendors-grid {
            display: grid;
            gap: 20px;
        }
        
        .vendor-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            transition: all 0.3s;
        }
        
        .vendor-card:hover {
            border-color: #fbbf24;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.2);
        }
        
        .vendor-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .vendor-main-info {
            flex: 1;
        }
        
        .vendor-name {
            font-size: 22px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 5px;
        }
        
        .vendor-category {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 10px;
        }
        
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #a16207;
        }
        
        .status-approved {
            background: #dcfce7;
            color: #15803d;
        }
        
        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .vendor-details {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .detail-item {
            font-size: 14px;
        }
        
        .detail-label {
            color: #64748b;
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-weight: 600;
            color: #334155;
        }
        
        .vendor-description {
            margin-bottom: 20px;
        }
        
        .description-label {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }
        
        .description-text {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
        }
        
        .vendor-actions {
            display: flex;
            gap: 12px;
        }
        
        .btn-approve {
            flex: 1;
            padding: 12px;
            background: #10b981;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-approve:hover {
            background: #059669;
        }
        
        .btn-reject {
            flex: 1;
            padding: 12px;
            background: white;
            border: 2px solid #ef4444;
            border-radius: 8px;
            color: #ef4444;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-reject:hover {
            background: #ef4444;
            color: white;
        }
        
        .btn-view {
            padding: 12px 20px;
            background: #3b82f6;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-view:hover {
            background: #1e3a8a;
        }
        
        .btn-disabled {
            background: #e2e8f0;
            color: #94a3b8;
            cursor: not-allowed;
        }
        
        .btn-disabled:hover {
            background: #e2e8f0;
            color: #94a3b8;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-icon {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        .empty-text {
            font-size: 18px;
            color: #64748b;
            margin-bottom: 10px;
        }
        
        .empty-subtext {
            font-size: 14px;
            color: #94a3b8;
        }
        
        .action-note {
            margin-top: 10px;
            font-size: 13px;
            color: #64748b;
            font-style: italic;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="logo">
            <div class="logo-icon">🎉</div>
            <div class="logo-text">PlanSmart Ghana</div>
        </div>
        <div class="nav-right">
            <div class="admin-badge">⚙️ ADMIN</div>
            <button class="btn-logout" onclick="event.preventDefault(); if (confirm('Are you sure you want to log out?')) {window.location.href='../view/logout.php'; }">Logout</button>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1>Vendor Management Dashboard</h1>
            <p>Review and approve vendor applications to maintain platform quality</p>
        </div>
        
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-value"><?php echo $pending_count; ?></div>
                <div class="stat-label">Pending Applications</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-value"><?php echo $approved_count; ?></div>
                <div class="stat-label">Verified Vendors</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">❌</div>
                <div class="stat-value"><?php echo $rejected_count; ?></div>
                <div class="stat-label">Rejected Applications</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-value"><?php echo $total_count; ?></div>
                <div class="stat-label">Total Applications</div>
            </div>
        </div>
        
        <!-- Tabs -->
        <div class="tabs-container">
            <div class="tabs">
                <div class="tab active" data-tab="pending">Pending Review (<?php echo $pending_count; ?>)</div>
                <div class="tab" data-tab="approved">Verified Vendors (<?php echo $approved_count; ?>)</div>
                <div class="tab" data-tab="rejected">Rejected (<?php echo $rejected_count; ?>)</div>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content-area">
            <!-- PENDING VENDORS TAB -->
            <div class="tab-content active" id="pending-content">
                <div class="vendors-grid">
                    <?php if (empty($pending_vendors)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">✅</div>
                            <p class="empty-text">No Pending Applications</p>
                            <p class="empty-subtext">All caught up! New applications will appear here.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($pending_vendors as $vendor): ?>
                        <div class="vendor-card">
                            <div class="vendor-header">
                                <div class="vendor-main-info">
                                    <div class="vendor-name"><?php echo htmlspecialchars($vendor['business_name']); ?></div>
                                    <div class="vendor-category"><?php echo htmlspecialchars($vendor['category']); ?></div>
                                    <span class="status-badge status-pending">⏳ Pending Review</span>
                                </div>
                            </div>
                            
                            <div class="vendor-details">
                                <div class="detail-item">
                                    <div class="detail-label">Location</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($vendor['location']); ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Years in Business</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($vendor['years_experience']); ?> years</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Starting Price</div>
                                    <div class="detail-value">GHS <?php echo number_format($vendor['starting_price']); ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Email</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($vendor['email']); ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Phone</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($vendor['phone'] ?? 'N/A'); ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Application Date</div>
                                    <div class="detail-value"><?php echo date('M d, Y', strtotime($vendor['created_at'])); ?></div>
                                </div>
                            </div>
                            
                            <div class="vendor-description">
                                <div class="description-label">Business Description:</div>
                                <div class="description-text">
                                    <?php echo htmlspecialchars($vendor['business_description']); ?>
                                </div>
                            </div>
                            
                            <div class="vendor-actions">
                                <button class="btn-approve" onclick="approveVendor(<?php echo $vendor['vendor_id']; ?>)">✓ Approve & Verify</button>
                                <button class="btn-reject" onclick="rejectVendor(<?php echo $vendor['vendor_id']; ?>)">✗ Reject Application</button>
                            </div>
                            <div class="action-note">
                                Note: Approving will send verification email and activate vendor account
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- APPROVED VENDORS TAB -->
            <div class="tab-content" id="approved-content">
                <div class="vendors-grid">
                    <?php if (empty($approved_vendors)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">👤</div>
                            <p class="empty-text">No Approved Vendors Yet</p>
                            <p class="empty-subtext">Approved vendors will appear here.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($approved_vendors as $vendor): ?>
                        <div class="vendor-card">
                            <div class="vendor-header">
                                <div class="vendor-main-info">
                                    <div class="vendor-name"><?php echo htmlspecialchars($vendor['business_name']); ?></div>
                                    <div class="vendor-category"><?php echo htmlspecialchars($vendor['category']); ?></div>
                                    <span class="status-badge status-approved">✅ Verified</span>
                                </div>
                            </div>
                            
                            <div class="vendor-details">
                                <div class="detail-item">
                                    <div class="detail-label">Location</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($vendor['location']); ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Years in Business</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($vendor['years_experience']); ?> years</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Verified On</div>
                                    <div class="detail-value"><?php echo $vendor['verified_at'] ? date('M d, Y', strtotime($vendor['verified_at'])) : 'N/A'; ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- REJECTED VENDORS TAB -->
            <div class="tab-content" id="rejected-content">
                <div class="vendors-grid">
                    <?php if (empty($rejected_vendors)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">📋</div>
                            <p class="empty-text">No Rejected Applications</p>
                            <p class="empty-subtext">Rejected applications will appear here.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($rejected_vendors as $vendor): ?>
                        <div class="vendor-card">
                            <div class="vendor-header">
                                <div class="vendor-main-info">
                                    <div class="vendor-name"><?php echo htmlspecialchars($vendor['business_name']); ?></div>
                                    <div class="vendor-category"><?php echo htmlspecialchars($vendor['category']); ?></div>
                                    <span class="status-badge status-rejected">❌ Rejected</span>
                                </div>
                            </div>
                            
                            <div class="vendor-details">
                                <div class="detail-item">
                                    <div class="detail-label">Location</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($vendor['location']); ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Application Date</div>
                                    <div class="detail-value"><?php echo date('M d, Y', strtotime($vendor['created_at'])); ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    
    <script src="../js/admin.js"></script>
</body>
</html>