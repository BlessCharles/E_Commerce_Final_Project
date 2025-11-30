<?php
// views/collab_work.php
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
    <title>PlanSmart Ghana - Collaborative Workspace</title>
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
        }
        
        /* Event Header */
        .event-header {
            background: white;
            padding: 25px 60px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .event-title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .event-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e3a8a;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .btn-invite {
            padding: 12px 24px;
            background: #fbbf24;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            color: #1e3a8a;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btn-invite:hover {
            background: #f59e0b;
            transform: translateY(-1px);
        }
        
        /* Main Layout */
        .main-layout {
            display: grid;
            grid-template-columns: 380px 1fr 320px;
            gap: 25px;
            max-width: 1600px;
            margin: 30px auto;
            padding: 0 60px;
        }
        
        /* Budget Summary Card */
        .budget-summary {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            border-radius: 16px;
            padding: 25px;
            color: white;
            height: fit-content;
            position: sticky;
            top: 20px;
            box-shadow: 0 4px 20px rgba(30, 58, 138, 0.2);
        }
        
        .budget-title {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 10px;
        }
        
        .budget-amount {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .budget-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-top: 1px solid rgba(255,255,255,0.2);
            font-size: 14px;
        }
        
        .budget-item:first-child {
            border-top: none;
        }
        
        .item-label {
            opacity: 0.9;
        }
        
        .item-amount {
            font-weight: 700;
        }
        
        .budget-footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid rgba(255,255,255,0.3);
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: 700;
        }
        
        /* Selected Vendors Section */
        .vendors-section {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .section-header {
            font-size: 22px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 20px;
        }
        
        .vendor-item {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        
        .vendor-item:hover {
            border-color: #fbbf24;
        }
        
        .vendor-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        
        .vendor-info h4 {
            font-size: 18px;
            color: #1e3a8a;
            margin-bottom: 5px;
        }
        
        .vendor-category {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }
        
        .vendor-price {
            font-size: 20px;
            font-weight: 700;
            color: #1e3a8a;
        }
        
        .vendor-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #dcfce7;
            color: #15803d;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
        }
        
        .vendor-votes {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .vote-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 14px;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .vote-btn:hover {
            border-color: #fbbf24;
            background: #fffbeb;
        }
        
        .vote-btn.voted {
            border-color: #10b981;
            background: #dcfce7;
        }
        
        /* Activity Feed */
        .activity-feed {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            height: fit-content;
            max-height: calc(100vh - 180px);
            overflow-y: auto;
            position: sticky;
            top: 20px;
        }
        
        .feed-header {
            font-size: 18px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 20px;
        }
        
        .activity-item {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .activity-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .activity-header {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 8px;
        }
        
        .activity-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            color: white;
            flex-shrink: 0;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-author {
            font-weight: 700;
            color: #1e3a8a;
            font-size: 14px;
        }
        
        .activity-time {
            font-size: 12px;
            color: #94a3b8;
            margin-left: 8px;
        }
        
        .activity-message {
            font-size: 14px;
            color: #475569;
            line-height: 1.5;
            margin-top: 5px;
        }
        
        .activity-reactions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        
        .reaction-btn {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .reaction-btn:hover {
            background: #f8fafc;
        }
        
        .reaction-btn.active {
            background: #eff6ff;
            border-color: #3b82f6;
            color: #3b82f6;
        }
        
        /* Comment Input */
        .comment-input-container {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        
        .comment-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            resize: vertical;
            min-height: 80px;
        }
        
        .comment-input:focus {
            outline: none;
            border-color: #3b82f6;
        }
        
        .btn-comment {
            width: 100%;
            padding: 10px;
            background: #3b82f6;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            margin-top: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-comment:hover {
            background: #1e3a8a;
        }
        
        /* Contribution Tracker */
        .contribution-section {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-top: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .contribution-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .contribution-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f8fafc;
            border-radius: 10px;
            margin-bottom: 12px;
        }
        
        .contributor-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .contributor-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
        }
        
        .contributor-name {
            font-weight: 600;
            color: #334155;
        }
        
        .contribution-amount {
            font-size: 18px;
            font-weight: 700;
            color: #10b981;
        }
        
        .contribution-status {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }

        /* MODAL OVERLAY */
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

        /* MODAL BOX */
        .modal-content {
            background: white;
            padding: 25px;
            width: 380px;
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            animation: popIn 0.2s ease-out;
        }

        /* TITLE */
        .modal-content h2 {
            margin-bottom: 15px;
            color: #1e3a8a;
            font-size: 22px;
            font-weight: 700;
        }

        /* INPUTS */
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

        /* BUTTONS */
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

        /* DELETE BUTTON */
        .danger-btn {
            background: #dc2626;
            padding: 12px 15px;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            display: inline-block;
            margin-right: 10px;
        }

        .cta-container {
            display: flex;
            flex-direction: column; /* Stack buttons vertically */
            gap: 12px; /* Space between buttons */
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid rgba(255,255,255,0.3);
        }

        .btn-continue {
            text-decoration: none;
            padding: 14px 20px;
            background: #fbbf24;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            color: #1e3a8a;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(251, 191, 36, 0.3);
            transition: all 0.3s;
            width: 100%; /* Full width */
            text-align: center;
        }

        .btn-continue:hover {
            background: #f59e0b;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(251, 191, 36, 0.4);
        }

        /* POP-IN ANIMATION */
        @keyframes popIn {
            from { transform: scale(0.9); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }



        .profile-wrapper {
            position: relative;
        }

        .profile-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            cursor: pointer;
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
                    <a href="#" id="editAccount" class="dropdown-item">Edit Account</a>
                    <a href="#" id="deleteAccount" class="dropdown-item">Delete Account</a>

                    <a href="#" class="dropdown-item logout" onclick="event.preventDefault(); if (confirm('Are you sure you want to log out?')) {window.location.href='logout.php'; }">Logout</a>
            </div>
        </div>
    </nav>
    
    <!-- Event Header -->
    <div class="event-header">
        <div class="event-title-row">
            
            <button class="btn-invite">
                👥 Invite Family Member
            </button>
        </div>
    </div>
    
    <!-- Main Layout -->
    <div class="main-layout">
        <!-- Budget Summary Sidebar -->
        <aside class="budget-summary">
            <div class="budget-title">Total Budget</div>
            <div class="budget-amount">GHS 25,000</div>
            
            <div class="budget-item">
                <span class="item-label">🍽️ Catering</span>
                <span class="item-amount">GHS 9,000</span>
            </div>
            <div class="budget-item">
                <span class="item-label">🏛️ Venue</span>
                <span class="item-amount">GHS 4,500</span>
            </div>
            <div class="budget-item">
                <span class="item-label">⛺ Tent & Chairs</span>
                <span class="item-amount">GHS 3,500</span>
            </div>
            <div class="budget-item">
                <span class="item-label">📸 Photography</span>
                <span class="item-amount">GHS 2,500</span>
            </div>
            <div class="budget-item">
                <span class="item-label">🎨 Decoration</span>
                <span class="item-amount">GHS 2,000</span>
            </div>
            <div class="budget-item">
                <span class="item-label">🔊 Sound System</span>
                <span class="item-amount">GHS 1,500</span>
            </div>
            <div class="budget-item">
                <span class="item-label">🚌 Transportation</span>
                <span class="item-amount">GHS 1,500</span>
            </div>
            <div class="budget-item">
                <span class="item-label">📋 Other</span>
                <span class="item-amount">GHS 500</span>
            </div>
            
            <div class="budget-footer">
                <span>Total Estimated Cost:</span>
                <span>GHS 24,800</span>
            </div>

            <div class="cta-container">
            <a href="browse_vendors.php?event_id=<?php echo $event_id; ?>" class="btn-continue">
                Browse Vendors
            </a>
            <a href="book_confirm.php?event_id=<?php echo $event_id; ?>" class="btn-continue">
                Book & Review 
            </a>
        </div>
        </aside>
        
        <!-- Selected Vendors Section -->
        <main>
            <section class="vendors-section">
                <h2 class="section-header">Selected Vendors</h2>
                
                <!-- Vendor 1 -->
                <div class="vendor-item">
                    <div class="vendor-header">
                        <div class="vendor-info">
                            <h4>Ama's Kitchen</h4>
                            <p class="vendor-category">Catering Service</p>
                        </div>
                        <div class="vendor-price">GHS 9,000</div>
                    </div>
                    <div class="vendor-status">✓ Confirmed by 4 members</div>
                    <div class="vendor-votes">
                        <button class="vote-btn voted">
                            👍 Approve <strong>4</strong>
                        </button>
                        <button class="vote-btn">
                            👎 Disapprove <strong>0</strong>
                        </button>
                    </div>
                </div>
                
                <!-- Vendor 2 -->
                <div class="vendor-item">
                    <div class="vendor-header">
                        <div class="vendor-info">
                            <h4>Lens & Light Studios</h4>
                            <p class="vendor-category">Photography Service</p>
                        </div>
                        <div class="vendor-price">GHS 2,500</div>
                    </div>
                    <div class="vendor-status" style="background: #fef3c7; color: #a16207;">⏳ Pending votes (2 of 5)</div>
                    <div class="vendor-votes">
                        <button class="vote-btn">
                            👍 Approve <strong>2</strong>
                        </button>
                        <button class="vote-btn">
                            👎 Disapprove <strong>0</strong>
                        </button>
                    </div>
                </div>
                
                <!-- Vendor 3 -->
                <div class="vendor-item">
                    <div class="vendor-header">
                        <div class="vendor-info">
                            <h4>Paradise Gardens</h4>
                            <p class="vendor-category">Venue</p>
                        </div>
                        <div class="vendor-price">GHS 4,500</div>
                    </div>
                    <div class="vendor-status">✓ Confirmed by 5 members</div>
                    <div class="vendor-votes">
                        <button class="vote-btn voted">
                            👍 Approve <strong>5</strong>
                        </button>
                        <button class="vote-btn">
                            👎 Disapprove <strong>0</strong>
                        </button>
                    </div>
                </div>
            </section>
            
            <!-- Contribution Tracker -->
            <section class="contribution-section">
                <div class="contribution-header">
                    <h2 class="section-header" style="margin: 0;">Family Contributions</h2>
                    <button class="btn-invite" style="padding: 8px 16px; font-size: 14px;">
                        + Add Contribution
                    </button>
                </div>
                
                <div class="contribution-item">
                    <div class="contributor-info">
                        <div class="contributor-avatar">KM</div>
                        <div>
                            <div class="contributor-name">Kwame (You)</div>
                            <div class="contribution-status">✓ Paid via MTN MoMo</div>
                        </div>
                    </div>
                    <div class="contribution-amount">GHS 10,000</div>
                </div>
                
                <div class="contribution-item">
                    <div class="contributor-info">
                        <div class="contributor-avatar" style="background: linear-gradient(135deg, #ec4899, #db2777);">AM</div>
                        <div>
                            <div class="contributor-name">Ama</div>
                            <div class="contribution-status">✓ Paid via Telecel Cash</div>
                        </div>
                    </div>
                    <div class="contribution-amount">GHS 3,000</div>
                </div>
                
                <div class="contribution-item">
                    <div class="contributor-info">
                        <div class="contributor-avatar" style="background: linear-gradient(135deg, #3b82f6, #1e3a8a);">UJ</div>
                        <div>
                            <div class="contributor-name">Uncle Joe</div>
                            <div class="contribution-status">⏳ Promised GHS 3,000</div>
                        </div>
                    </div>
                    <div class="contribution-amount" style="color: #94a3b8;">GHS 3,000</div>
                </div>
                
                <div class="contribution-item">
                    <div class="contributor-info">
                        <div class="contributor-avatar" style="background: linear-gradient(135deg, #f59e0b, #d97706);">KO</div>
                        <div>
                            <div class="contributor-name">Kofi</div>
                            <div class="contribution-status">✓ Paid via Bank Transfer</div>
                        </div>
                    </div>
                    <div class="contribution-amount">GHS 2,500</div>
                </div>
                
                <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #e2e8f0; display: flex; justify-content: space-between; font-size: 18px; font-weight: 700;">
                    <span>Total Collected:</span>
                    <span style="color: #10b981;">GHS 18,500 </span>
                </div>
            </section>
        </main>
        
        <!-- Activity Feed -->
        <aside class="activity-feed">
            <h3 class="feed-header">Activity Feed</h3>
            
            <div class="activity-item">
                <div class="activity-header">
                    <div class="activity-avatar" style="background: linear-gradient(135deg, #ec4899, #db2777);">AM</div>
                    <div class="activity-content">
                        <div>
                            <span class="activity-author">Ama</span>
                            <span class="activity-time">2 hours ago</span>
                        </div>
                        <p class="activity-message">"I like Photographer A better. They did my wedding last year and the photos were amazing!"</p>
                        <div class="activity-reactions">
                            <button class="reaction-btn active">👍 2</button>
                            <button class="reaction-btn">💬 Reply</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="activity-item">
                <div class="activity-header">
                    <div class="activity-avatar">KM</div>
                    <div class="activity-content">
                        <div>
                            <span class="activity-author">Kwame</span>
                            <span class="activity-time">3 hours ago</span>
                        </div>
                        <p class="activity-message">"Budget updated - added GHS 500 for funeral programs and thank you cards"</p>
                        <div class="activity-reactions">
                            <button class="reaction-btn">👍 0</button>
                            <button class="reaction-btn">💬 Reply</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="activity-item">
                <div class="activity-header">
                    <div class="activity-avatar" style="background: linear-gradient(135deg, #3b82f6, #1e3a8a);">UJ</div>
                    <div class="activity-content">
                        <div>
                            <span class="activity-author">Uncle Joe</span>
                            <span class="activity-time">5 hours ago</span>
                        </div>
                        <p class="activity-message">"I'll contribute GHS 3,000. Will send it via mobile money by Friday."</p>
                        <div class="activity-reactions">
                            <button class="reaction-btn active">❤️ 3</button>
                            <button class="reaction-btn">💬 Reply</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="activity-item">
                <div class="activity-header">
                    <div class="activity-avatar" style="background: linear-gradient(135deg, #f59e0b, #d97706);">KO</div>
                    <div class="activity-content">
                        <div>
                            <span class="activity-author">Kofi</span>
                            <span class="activity-time">1 day ago</span>
                        </div>
                        <p class="activity-message">"The venue looks perfect. Paradise Gardens has good reviews and the location is convenient for everyone."</p>
                        <div class="activity-reactions">
                            <button class="reaction-btn active">👍 5</button>
                            <button class="reaction-btn">💬 Reply</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Comment Input -->
            <div class="comment-input-container">
                <textarea class="comment-input" placeholder="Add a comment or suggestion..."></textarea>
                <button class="btn-comment">Post Comment</button>
            </div>
        </aside>
    </div>

    <!-- EDIT ACCOUNT MODAL -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <h2>Edit Account</h2>

                <form action="update_account.php" method="POST">
                    <label>First Name</label>
                    <input type="text" name="first_name" value="<?php echo $_SESSION['first_name']; ?>">

                    <label>Last Name</label>
                    <input type="text" name="last_name" value="<?php echo $_SESSION['last_name']; ?>">

                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo $_SESSION['email']; ?>">

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

    
    <script src="../js/collab_work.js"></script>
    <script>
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