<?php
// views/smart_recommend.php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if event_id is provided
if (!isset($_GET['event_id'])) {
    header('Location: budget_input.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
$event_id = $_GET['event_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanSmart Ghana - Smart Recommendations</title>
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
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #1e3a8a);
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
        
        /* Progress Bar */
        .progress-container {
            background: white;
            padding: 20px 60px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .progress-steps {
            display: flex;
            justify-content: space-between;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }
        
        .progress-line {
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 3px;
            background: #e2e8f0;
            z-index: 0;
        }
        
        .progress-line-active {
            position: absolute;
            top: 20px;
            left: 0;
            width: 50%;
            height: 3px;
            background: #fbbf24;
            z-index: 1;
        }
        
        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 2;
        }
        
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #94a3b8;
            margin-bottom: 10px;
        }
        
        .step-circle.active {
            background: #fbbf24;
            color: #1e3a8a;
        }
        
        .step-circle.complete {
            background: #10b981;
            color: white;
        }
        
        .step-label {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }
        
        /* Main Content */
        .main-content {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .page-header h1 {
            font-size: 36px;
            color: #1e3a8a;
            margin-bottom: 10px;
        }
        
        .page-header p {
            font-size: 18px;
            color: #64748b;
        }
        
        /* Budget Tracker Card */
        .budget-tracker {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            border-radius: 16px;
            padding: 30px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(30, 58, 138, 0.2);
        }
        
        .budget-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .budget-amount {
            font-size: 32px;
            font-weight: 700;
        }
        
        .budget-total {
            font-size: 18px;
            opacity: 0.9;
        }
        
        .budget-status {
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .budget-status.over-budget {
            background: rgba(239, 68, 68, 0.3);
        }
        
        .progress-bar-container {
            background: rgba(255,255,255,0.2);
            height: 12px;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .progress-bar-fill {
            background: #fbbf24;
            height: 100%;
            border-radius: 6px;
            transition: width 0.5s;
        }
        
        /* Two Column Layout */
        .content-grid {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 30px;
        }
        
        /* Budget Breakdown Sidebar */
        .budget-breakdown {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        
        .breakdown-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 20px;
        }
        
        .breakdown-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .breakdown-item:last-child {
            border-bottom: none;
        }
        
        .breakdown-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #334155;
        }
        
        .breakdown-bar {
            height: 6px;
            border-radius: 3px;
            margin-top: 5px;
        }
        
        .breakdown-amount {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
        
        .amount-value {
            font-size: 16px;
            font-weight: 700;
            color: #1e3a8a;
        }
        
        .amount-percentage {
            font-size: 12px;
            color: #64748b;
        }
        
        /* Vendor Recommendations */
        .recommendations-section {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        
        .category-section {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .category-title {
            font-size: 22px;
            font-weight: 700;
            color: #1e3a8a;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .category-icon {
            font-size: 28px;
        }
        
        .browse-all {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }
        
        .browse-all:hover {
            text-decoration: underline;
        }
        
        .vendor-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .vendor-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .vendor-card:hover {
            border-color: #fbbf24;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.2);
            transform: translateY(-2px);
        }
        
        .vendor-image {
            width: 100%;
            height: 140px;
            background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .vendor-badge {
            background: #10b981;
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 12px;
            display: inline-block;
            margin-bottom: 10px;
        }
        
        .vendor-name {
            font-size: 18px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 8px;
        }
        
        .vendor-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            color: #64748b;
            margin-bottom: 12px;
        }
        
        .stars {
            color: #fbbf24;
            font-weight: 700;
        }
        
        .vendor-price {
            font-size: 24px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 15px;
        }
        
        .vendor-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-view {
            flex: 1;
            padding: 10px;
            border: 2px solid #1e3a8a;
            background: white;
            color: #1e3a8a;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-view:hover {
            background: #1e3a8a;
            color: white;
        }
        
        .btn-select {
            flex: 1;
            padding: 10px;
            border: none;
            background: #fbbf24;
            color: #1e3a8a;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-select:hover {
            background: #f59e0b;
        }
        
        .btn-select.btn-selected {
            background: #10b981;
            color: white;
        }

        /* CTA Button */
        .cta-container {
            display: flex;
            justify-content: center; /* center them */
            gap: 20px; /* space between */
            margin-top: 30px;
        }

        .btn-continue {
            text-decoration: none;
            padding: 18px 60px;
            background: #fbbf24;
            border: none;
            border-radius: 12px;
            font-size: 20px;
            font-weight: 700;
            color: #1e3a8a;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(251, 191, 36, 0.4);
            transition: all 0.3s;
            width: auto;
        }
        
        .btn-continue:hover {
            background: #f59e0b;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(251, 191, 36, 0.5);
        }
        
        .btn-continue:disabled {
            background: #e2e8f0;
            color: #94a3b8;
            cursor: not-allowed;
            box-shadow: none;
        }

        /* Loading State */
        .loading {
            text-align: center;
            padding: 60px 20px;
        }

        .loading-spinner {
            border: 4px solid #e2e8f0;
            border-top: 4px solid #fbbf24;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @media (max-width: 1200px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            
            .vendor-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .budget-breakdown {
                position: relative;
            }
        }

        @media (max-width: 768px) {
            .vendor-grid {
                grid-template-columns: 1fr;
            }

            nav {
                padding: 15px 20px;
            }

            .progress-container {
                padding: 15px 20px;
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
        
        <div class="profile-wrapper">
            <div class="user-avatar" id="profileBtn">👤</div>
            <div class="profile-dropdown" id="profileDropdown">
                <div class="dropdown-header">
                    <strong><?php echo htmlspecialchars($user_name); ?></strong>
                </div>
                <a href="profile.php" class="dropdown-item">Edit Account</a>
                <a href="#" class="dropdown-item logout" onclick="event.preventDefault(); if (confirm('Are you sure you want to log out?')) {window.location.href='logout.php'; }">Logout</a>
            </div>
        </div>
    </nav>
    
    <!-- Progress Bar -->
    <div class="progress-container">
        <div class="progress-steps">
            <div class="progress-line"></div>
            <div class="progress-line-active"></div>
            
            <div class="progress-step">
                <div class="step-circle complete">✓</div>
                <span class="step-label">Budget & Details</span>
            </div>
            <div class="progress-step">
                <div class="step-circle active">2</div>
                <span class="step-label">Get Recommendations</span>
            </div>
            <div class="progress-step">
                <div class="step-circle">3</div>
                <span class="step-label">Review & Book</span>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1>Your Personalized Vendor Recommendations</h1>
            <p>We've matched your budget with the best verified vendors for your event</p>
        </div>
        
        <!-- Loading State -->
        <div id="loadingState" class="loading">
            <div class="loading-spinner"></div>
            <p>Loading your personalized recommendations...</p>
        </div>
        
        <!-- Budget Tracker -->
        <div class="budget-tracker" style="display: none;">
            <div class="budget-info">
                <div>
                    <div class="budget-amount">GHS 0.00</div>
                    <div class="budget-total">of GHS 0.00 budget</div>
                </div>
                <div class="budget-status">Loading...</div>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar-fill" style="width: 0%"></div>
            </div>
        </div>
        
        <!-- Two Column Layout -->
        <div class="content-grid" style="display: none;">
            <!-- Budget Breakdown Sidebar -->
            <div class="budget-breakdown">
                <h3 class="breakdown-title">Budget Breakdown</h3>
                <!-- Will be populated by JavaScript -->
        
            </div>

            

            
            <!-- Vendor Recommendations -->
            <div class="recommendations-section">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>
            <!-- TWO BUTTONS UNDER BREAKDOWN -->
    
        <div class="cta-container">
            <a href="browse_vendors.php?event_id=<?php echo $event_id; ?>" class="btn-continue">
                Browse Vendors
            </a>
            <a href="collab_work.php?event_id=<?php echo $event_id; ?>" class="btn-continue">
                Continue to Collab
            </a>
        </div>

        
    </div>
    
    <script src="../js/smart_recommend.js"></script>
    <script>
        // Profile dropdown toggle
        document.getElementById("profileBtn").addEventListener("click", () => {
            const menu = document.getElementById("profileDropdown");
            menu.style.display = (menu.style.display === "block") ? "none" : "block";
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", function (e) {
            const dropdown = document.getElementById("profileDropdown");
            const button = document.getElementById("profileBtn");

            if (!dropdown.contains(e.target) && !button.contains(e.target)) {
                dropdown.style.display = "none";
            }
        });
    </script>
</body>
</html>