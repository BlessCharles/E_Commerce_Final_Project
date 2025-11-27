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
        
        .rating-value {
            font-weight: 700;
            color: #1e3a8a;
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
            <div class="vendor-badge">✓ VERIFIED VENDOR</div>
            <div class="user-avatar">AK</div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-text">
                <h1>Welcome back, Ama's Kitchen! 👋</h1>
                <p>Here's what's happening with your business today</p>
            </div>
            <button class="btn-edit-profile">✏️ Edit Business Profile</button>
        </div>
        
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-value">8</div>
                <div class="stat-label">Pending Requests</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-value">45</div>
                <div class="stat-label">Completed Bookings</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">⭐</div>
                <div class="stat-value">4.8</div>
                <div class="stat-label">Average Rating</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-value">127K</div>
                <div class="stat-label">Total Earnings (GHS)</div>
            </div>
        </div>
        
        <!-- Tabs -->
        <div class="tabs-container">
            <div class="tabs">
                <div class="tab active">Booking Requests (8)</div>
                <div class="tab">Customer Reviews (127)</div>
                <div class="tab">My Business Profile</div>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content-area">
            <!-- Booking Requests -->
            <div class="requests-grid">
                <!-- Request 1 - Pending -->
                <div class="request-card">
                    <div class="request-header">
                        <div class="request-info">
                            <h3>Kwame Mensah</h3>
                            <p class="request-event">🕊️ Funeral Service</p>
                            <p class="request-date">📅 January 15, 2025 • Koforidua</p>
                        </div>
                        <div class="request-price">
                            <div class="price-amount">GHS 9,000</div>
                            <span class="status-badge status-pending">⏳ Pending</span>
                        </div>
                    </div>
                    
                    <div class="request-details">
                        <div class="detail-item">
                            <div class="detail-label">Guest Count</div>
                            <div class="detail-value">300 people</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Event Type</div>
                            <div class="detail-value">Funeral</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Requested</div>
                            <div class="detail-value">2 hours ago</div>
                        </div>
                    </div>
                    
                    <div class="request-actions">
                        <button class="btn-accept">✓ Accept Booking</button>
                        <button class="btn-decline">✗ Decline</button>
                        <button class="btn-contact">💬 Contact Customer</button>
                    </div>
                </div>
                
                <!-- Request 2 - Confirmed -->
                <div class="request-card">
                    <div class="request-header">
                        <div class="request-info">
                            <h3>Akosua Boateng</h3>
                            <p class="request-event">💒 Wedding Reception</p>
                            <p class="request-date">📅 February 20, 2025 • Accra</p>
                        </div>
                        <div class="request-price">
                            <div class="price-amount">GHS 12,500</div>
                            <span class="status-badge status-confirmed">✓ Confirmed</span>
                        </div>
                    </div>
                    
                    <div class="request-details">
                        <div class="detail-item">
                            <div class="detail-label">Guest Count</div>
                            <div class="detail-value">200 people</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Event Type</div>
                            <div class="detail-value">Wedding</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Confirmed</div>
                            <div class="detail-value">Yesterday</div>
                        </div>
                    </div>
                    
                    <div class="request-actions">
                        <button class="btn-contact" style="flex: 1;">💬 Message Customer</button>
                        <button class="btn-accept" style="flex: 1;">📄 View Details</button>
                    </div>
                </div>
                
                <!-- Request 3 - Pending -->
                <div class="request-card">
                    <div class="request-header">
                        <div class="request-info">
                            <h3>Yaw Osei</h3>
                            <p class="request-event">👶 Naming Ceremony</p>
                            <p class="request-date">📅 January 25, 2025 • Tema</p>
                        </div>
                        <div class="request-price">
                            <div class="price-amount">GHS 5,000</div>
                            <span class="status-badge status-pending">⏳ Pending</span>
                        </div>
                    </div>
                    
                    <div class="request-details">
                        <div class="detail-item">
                            <div class="detail-label">Guest Count</div>
                            <div class="detail-value">80 people</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Event Type</div>
                            <div class="detail-value">Naming Ceremony</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Requested</div>
                            <div class="detail-value">5 hours ago</div>
                        </div>
                    </div>
                    
                    <div class="request-actions">
                        <button class="btn-accept">✓ Accept Booking</button>
                        <button class="btn-decline">✗ Decline</button>
                        <button class="btn-contact">💬 Contact Customer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>