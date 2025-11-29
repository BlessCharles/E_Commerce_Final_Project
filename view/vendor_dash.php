<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../classes/vendor_class.php";
$user_id = $_SESSION['user_id'];

$vendorObj = new Vendor();
$vendor = $vendorObj->get_vendor_by_user($user_id);

// Get vendor_id from vendor data
$vendor_id = $vendor['vendor_id'] ?? null;

// Fetch stats, bookings, and reviews
$stats = [];
$bookings = [];
$reviews = [];

if ($vendor_id) {
    $stats = $vendorObj->get_vendor_stats($vendor_id);
    $bookings = $vendorObj->get_vendor_bookings($vendor_id);
    $reviews = $vendorObj->get_vendor_reviews($vendor_id);
}

$business_name = $vendor['business_name'] ?? "Your Business";
$business_description = $vendor['business_description'] ?? "";
$category = $vendor['category'] ?? "";
$years_experience = $vendor['years_experience'] ?? "";
$location = $vendor['location'] ?? "";
$address = $vendor['address'] ?? "";
$starting_price = $vendor['starting_price'] ?? "";
$price_range = $vendor['price_range'] ?? "";
$image_path = $vendor['image'] ?? "default.jpg";

$user_name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanSmart Ghana - Vendor Dashboard</title>
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

        .vendor-badge {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }

        .vendor-badge.pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .vendor-badge.rejected {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ec4899, #db2777);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        .profile-wrapper {
            position: relative;
        }

        .profile-dropdown {
            position: absolute;
            top: 50px;
            right: 0;
            width: 220px;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border-radius: 10px;
            padding: 10px;
            display: none;
            z-index: 10;
        }

        .dropdown-header {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .dropdown-item {
            display: block;
            padding: 12px;
            color: #1e3a8a;
            text-decoration: none;
            font-size: 14px;
            border-radius: 6px;
        }

        .dropdown-item:hover {
            background: #f1f5f9;
        }

        .dropdown-item.logout {
            color: red;
            font-weight: bold;
        }

        /* Main Content */
        .main-content {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 60px;
        }

        /* Welcome Section */
        .welcome-section {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-text h1 {
            font-size: 32px;
            color: #1e3a8a;
            margin-bottom: 8px;
        }

        .welcome-text p {
            font-size: 16px;
            color: #64748b;
        }

        .btn-edit-profile {
            padding: 12px 24px;
            background: #fbbf24;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            color: #1e3a8a;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-edit-profile:hover {
            background: #f59e0b;
            transform: translateY(-1px);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
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

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Booking Requests */
        .requests-grid {
            display: grid;
            gap: 20px;
        }

        .request-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            transition: all 0.3s;
        }

        .request-card:hover {
            border-color: #fbbf24;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.2);
        }

        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .request-info h3 {
            font-size: 20px;
            color: #1e3a8a;
            margin-bottom: 5px;
        }

        .request-event {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 8px;
        }

        .request-date {
            font-size: 14px;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .request-price {
            text-align: right;
        }

        .price-amount {
            font-size: 28px;
            font-weight: 700;
            color: #1e3a8a;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            margin-top: 8px;
        }

        .status-pending {
            background: #fef3c7;
            color: #a16207;
        }

        .status-confirmed {
            background: #dcfce7;
            color: #15803d;
        }

        .request-details {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .detail-item {
            font-size: 14px;
        }

        .detail-label {
            color: #64748b;
            margin-bottom: 3px;
        }

        .detail-value {
            font-weight: 600;
            color: #334155;
        }

        .request-actions {
            display: flex;
            gap: 12px;
        }

        .btn-accept {
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

        .btn-accept:hover {
            background: #059669;
        }

        .btn-decline {
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

        .btn-decline:hover {
            background: #ef4444;
            color: white;
        }

        .btn-contact {
            padding: 12px 20px;
            background: #3b82f6;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-contact:hover {
            background: #1e3a8a;
        }

        /* Reviews Section */
        .reviews-grid {
            display: grid;
            gap: 20px;
        }

        .review-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .reviewer-info {
            display: flex;
            gap: 15px;
        }

        .reviewer-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #1e3a8a);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
        }

        .reviewer-details h4 {
            font-size: 16px;
            color: #1e3a8a;
            margin-bottom: 5px;
        }

        .review-event {
            font-size: 13px;
            color: #64748b;
        }

        .review-rating {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stars {
            color: #fbbf24;
            font-size: 20px;
        }

        .review-text {
            font-size: 15px;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 15px;
        }

        .review-date {
            font-size: 13px;
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

        /* Profile Card Styles */
        .business-profile-card {
            max-width: 1000px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .profile-card-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .profile-card-header h2 {
            color: white;
            font-size: 26px;
            font-weight: 700;
            margin: 0;
        }

        .profile-actions {
            display: flex;
            gap: 12px;
        }

        .btn-edit-card {
            padding: 10px 20px;
            background: #fbbf24;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            color: #1e3a8a;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }

        .btn-edit-card:hover {
            background: #f59e0b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
        }

        .btn-delete-card {
            padding: 10px 20px;
            background: transparent;
            border: 2px solid white;
            border-radius: 8px;
            font-weight: 700;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }

        .btn-delete-card:hover {
            background: #ef4444;
            border-color: #ef4444;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .profile-card-body {
            padding: 40px;
        }

        .profile-image-section {
            display: flex;
            align-items: center;
            gap: 30px;
            padding-bottom: 30px;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 30px;
        }

        .business-profile-image {
            width: 180px;
            height: 180px;
            border-radius: 16px;
            object-fit: cover;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            border: 4px solid #f8fafc;
        }

        .business-name-section h3 {
            font-size: 28px;
            color: #1e3a8a;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .category-badge {
            display: inline-block;
            padding: 8px 16px;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: #1e3a8a;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
        }

        .profile-info-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .info-col {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 4px solid #fbbf24;
            transition: all 0.3s;
        }

        .info-col:hover {
            background: #f1f5f9;
            transform: translateX(5px);
        }

        .info-icon {
            font-size: 28px;
            line-height: 1;
        }

        .info-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
        }

        .info-label {
            font-size: 13px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 16px;
            color: #1e3a8a;
            font-weight: 600;
        }

        .profile-description-section {
            background: #f8fafc;
            padding: 25px;
            border-radius: 12px;
            border-left: 4px solid #3b82f6;
        }

        .profile-description-section h4 {
            font-size: 18px;
            color: #1e3a8a;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .profile-description-section p {
            font-size: 15px;
            color: #475569;
            line-height: 1.8;
        }

        /* Modals */
        .modal {
            display: none;
            position: fixed;
            z-index: 3000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.45);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 25px;
            width: 380px;
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            animation: popIn 0.2s ease-out;
        }

        .modal-content h2 {
            margin-bottom: 15px;
            color: #1e3a8a;
            font-size: 22px;
            font-weight: 700;
        }

        .modal-content input[type="text"],
        .modal-content input[type="email"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px 0;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            transition: 0.2s;
        }

        .modal-content input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
            outline: none;
        }

        .modal-content button {
            padding: 12px 18px;
            margin-right: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
        }

        .modal-content button[type="submit"] {
            background: #1e3a8a;
            color: white;
        }

        .modal-content .closeModal {
            background: #e2e8f0;
            color: #1e3a8a;
        }

        .danger-btn {
            background: #dc2626;
            padding: 12px 15px;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            display: inline-block;
            margin-right: 10px;
            text-decoration: none;
        }

        @keyframes popIn {
            from { transform: scale(0.9); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-card-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .profile-actions {
                width: 100%;
            }
            
            .btn-edit-card,
            .btn-delete-card {
                flex: 1;
            }
            
            .profile-image-section {
                flex-direction: column;
                text-align: center;
            }
            
            .info-row {
                grid-template-columns: 1fr;
            }
            
            .profile-card-body {
                padding: 20px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
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
            <?php 
            $verification_status = $vendor['verification_status'] ?? 'pending';
            
            if ($verification_status === 'approved'): 
            ?>
                <div class="vendor-badge">✓ VERIFIED VENDOR</div>
            <?php elseif ($verification_status === 'rejected'): ?>
                <div class="vendor-badge rejected">❌ REJECTED</div>
            <?php else: ?>
                <div class="vendor-badge pending">⏳ PENDING VERIFICATION</div>
            <?php endif; ?>
            <div class="profile-wrapper">
                <div class="user-avatar" id="profileBtn">👤</div>
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="dropdown-header">
                        <strong><?php echo htmlspecialchars($user_name); ?></strong>
                    </div>
                    <a href="#" id="editAccount" class="dropdown-item">Edit Account</a>
                    <a href="#" id="deleteAccount" class="dropdown-item">Delete Account</a>
                    <a href="#" class="dropdown-item logout" onclick="event.preventDefault(); if (confirm('Are you sure you want to log out?')) {window.location.href='logout.php'; }">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-text">
                <h1>Welcome, <?php echo htmlspecialchars($business_name); ?>! 👋</h1>
                <p>Here's what's happening with your business today</p>
            </div>
            <button class="btn-edit-profile" onclick="window.location.href='vendor_prof.php';">✏️ Edit Business Profile</button>
        </div>
        
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-value"><?php echo $stats['pending_requests'] ?? 0; ?></div>
                <div class="stat-label">Pending Requests</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-value"><?php echo $stats['completed_bookings'] ?? 0; ?></div>
                <div class="stat-label">Completed Bookings</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">⭐</div>
                <div class="stat-value"><?php echo number_format($stats['average_rating'] ?? 0, 1); ?></div>
                <div class="stat-label">Average Rating</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-value"><?php echo number_format($stats['total_earnings'] ?? 0); ?></div>
                <div class="stat-label">Total Earnings (GHS)</div>
            </div>
        </div>
        
        <!-- Tabs -->
        <div class="tabs-container">
            <div class="tabs">
                <div class="tab active" data-tab="bookings">Booking Requests (<?php echo count($bookings); ?>)</div>
                <div class="tab" data-tab="reviews">Customer Reviews (<?php echo count($reviews); ?>)</div>
                <div class="tab" data-tab="profile">My Business Profile</div>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content-area">
            <!-- Booking Requests Tab -->
            <div class="tab-content active" id="bookings-content">
                <div class="requests-grid">
                    <?php if (!empty($bookings)): ?>
                        <?php foreach ($bookings as $b): ?>
                            <div class="request-card">
                                <div class="request-header">
                                    <div class="request-info">
                                        <h3><?= htmlspecialchars($b['first_name'].' '.$b['last_name']) ?></h3>
                                        <p class="request-event">🎉 <?= ucfirst($b['event_type']) ?></p>
                                        <p class="request-date">
                                            📅 <?= date("F j, Y", strtotime($b['event_date'])) ?> 
                                            • <?= htmlspecialchars($b['event_location']) ?>
                                        </p>
                                    </div>
                                    <div class="request-price">
                                        <div class="price-amount">GHS <?= number_format($b['amount']) ?></div>
                                        <span class="status-badge status-<?= $b['status'] == 'pending' ? 'pending' : 'confirmed' ?>">
                                            <?= ucfirst($b['status']) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="request-details">
                                    <div class="detail-item">
                                        <div class="detail-label">Guest Count</div>
                                        <div class="detail-value"><?= $b['guest_count'] ?> people</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Requested</div>
                                        <div class="detail-value"><?= date("M d, Y", strtotime($b['created_at'])) ?></div>
                                    </div>
                                </div>
                                <div class="request-actions">
                                    <?php if ($b['status'] == "pending"): ?>
                                        <button class="btn-accept" onclick="updateBookingStatus(<?= $b['booking_id'] ?>, 'confirmed')">✓ Accept</button>
                                        <button class="btn-decline" onclick="updateBookingStatus(<?= $b['booking_id'] ?>, 'rejected')">✗ Decline</button>
                                    <?php endif; ?>
                                    <button class="btn-contact">💬 Contact Customer</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <p class="empty-text">No Booking Requests</p>
                            <p class="empty-subtext">New bookings will appear here</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Customer Reviews Tab -->
            <div class="tab-content" id="reviews-content">
                <div class="reviews-grid">
                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $r): ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <div class="reviewer-avatar">
                                            <?= strtoupper($r['first_name'][0]) ?>
                                        </div>
                                        <div class="reviewer-details">
                                            <h4><?= htmlspecialchars($r['first_name'].' '.$r['last_name']) ?></h4>
                                            <p class="review-event">Booking Amount: GHS <?= number_format($r['amount']) ?></p>
                                        </div>
                                    </div>
                                    <div class="review-rating">
                                        <div class="stars">⭐ <?= $r['rating'] ?></div>
                                    </div>
                                </div>
                                <p class="review-text"><?= htmlspecialchars($r['comment']) ?></p>
                                <div class="review-date"><?= date("F j, Y", strtotime($r['created_at'])) ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">📝</div>
                            <p class="empty-text">No Reviews Yet</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- My Business Profile Tab -->
            <div class="tab-content" id="profile-content">
                <div class="business-profile-card">
                    <!-- Profile Card Header with Action Buttons -->
                    <div class="profile-card-header">
                        <h2>📋 Business Profile</h2>
                        <div class="profile-actions">
                            <button class="btn-edit-card" onclick="window.location.href='vendor_prof.php';">
                                ✏️ Edit Profile
                            </button>
                            <button class="btn-delete-card" onclick="confirmDeleteProfile()">
                                🗑️ Delete Profile
                            </button>
                        </div>
                    </div>

                    <!-- Profile Card Body -->
                    <div class="profile-card-body">
                        <!-- Image Section -->
                        <div class="profile-image-section">
                            <?php 
                            // Handle image path correctly
                            $img_src = "../uploads/default.jpg"; // Default fallback
                            
                            if (!empty($image_path)) {
                                if (strpos($image_path, 'uploads/') === 0) {
                                    $img_src = "../" . $image_path;
                                } else {
                                    $img_src = $image_path;
                                }
                                
                                if (!file_exists($img_src)) {
                                    $img_src = "../uploads/default.jpg";
                                }
                            }
                            ?>
                            <img src="<?= htmlspecialchars($img_src) ?>" 
                                alt="Business Image" 
                                class="business-profile-image"
                                onerror="this.src='../uploads/default.jpg'">
                            
                            <div class="business-name-section">
                                <h3><?= htmlspecialchars($business_name) ?></h3>
                                <span class="category-badge">
                                    <?php
                                    $category_icons = [
                                        'catering' => '🍽️',
                                        'venue' => '🏛️',
                                        'photography' => '📸',
                                        'decoration' => '🎨',
                                        'entertainment' => '🎵',
                                        'planning' => '📋'
                                    ];
                                    echo ($category_icons[$category] ?? '📂') . ' ' . ucfirst($category);
                                    ?>
                                </span>
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="profile-info-section">
                            <div class="info-row">
                                <div class="info-col">
                                    <span class="info-icon">📍</span>
                                    <div class="info-content">
                                        <span class="info-label">Location</span>
                                        <span class="info-value"><?= htmlspecialchars($location) ?></span>
                                    </div>
                                </div>
                                <div class="info-col">
                                    <span class="info-icon">🏢</span>
                                    <div class="info-content">
                                        <span class="info-label">Address</span>
                                        <span class="info-value"><?= htmlspecialchars($address) ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="info-col">
                                    <span class="info-icon">⭐</span>
                                    <div class="info-content">
                                        <span class="info-label">Rating</span>
                                        <span class="info-value">
                                            <?= number_format($stats['average_rating'] ?? 0, 1) ?>/5.0 
                                            (<?= $stats['total_reviews'] ?? 0 ?> reviews)
                                        </span>
                                    </div>
                                </div>
                                <div class="info-col">
                                    <span class="info-icon">💼</span>
                                    <div class="info-content">
                                        <span class="info-label">Experience</span>
                                        <span class="info-value"><?= $years_experience ?> years</span>
                                    </div>
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="info-col">
                                    <span class="info-icon">💰</span>
                                    <div class="info-content">
                                        <span class="info-label">Starting Price</span>
                                        <span class="info-value">GHS <?= number_format($starting_price) ?></span>
                                    </div>
                                </div>
                                <div class="info-col">
                                    <span class="info-icon">💵</span>
                                    <div class="info-content">
                                        <span class="info-label">Price Range</span>
                                        <span class="info-value"><?= htmlspecialchars($price_range) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="profile-description-section">
                            <h4>📝 About Our Business</h4>
                            <p><?= nl2br(htmlspecialchars($business_description ?: 'No description provided yet.')) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT ACCOUNT MODAL -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h2>Edit Account</h2>
            <form action="update_account.php" method="POST">
                <label>First Name</label>
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($_SESSION['first_name']); ?>">
                <label>Last Name</label>
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($_SESSION['last_name']); ?>">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>">
                <button type="submit">Save Changes</button>
                <button type="button" class="closeModal">Cancel</button>
            </form>
        </div>
    </div>

    <!-- DELETE CONFIRMATION MODAL -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h2>Delete Account?</h2>
            <p>This action cannot be undone.</p>
            <a href="delete_account.php" class="danger-btn">Yes, delete my account</a>
            <button type="button" class="closeModal">Cancel</button>
        </div>
    </div>

    



    <script src="../js/vendor_dash.js"></script>

</body>
</html>