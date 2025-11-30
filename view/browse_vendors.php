<?php
session_start();
require_once '../classes/vendor_class.php';

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

$event_id = $_GET['event_id'];
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];

// Initialize vendor class
$vendor_obj = new Vendor();

// Get filter parameters
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 999999;
$min_rating = isset($_GET['min_rating']) ? floatval($_GET['min_rating']) : 0;
$location = isset($_GET['location']) ? $_GET['location'] : '';
$verified_only = isset($_GET['verified_only']) ? true : false;

// Fetch vendors based on filters
$vendors = $vendor_obj->get_filtered_vendors($category, $search, $min_price, $max_price, $min_rating, $location, $verified_only);

// Get all unique categories for dropdown
$categories = $vendor_obj->get_all_categories();

// Count results
$vendor_count = count($vendors);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanSmart Ghana - Browse Vendors</title>
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
        
        /* Page Header */
        .page-header {
            background: white;
            padding: 30px 60px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .back-link {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .header-title {
            font-size: 32px;
            font-weight: 700;
            color: #1e3a8a;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .results-count {
            font-size: 16px;
            color: #64748b;
        }
        
        .header-controls {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .search-box {
            width: 300px;
            padding: 10px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
        }
        
        .sort-dropdown {
            padding: 10px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
            background: white;
        }
        
        /* Main Layout */
        .main-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 60px;
        }
        
        /* Filter Sidebar */
        .filter-sidebar {
            background: white;
            border-radius: 16px;
            padding: 25px;
            height: fit-content;
            position: sticky;
            top: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .filter-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e3a8a;
        }
        
        .clear-filters {
            color: #3b82f6;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        
        .filter-section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .filter-section:last-child {
            border-bottom: none;
        }
        
        .filter-label {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 12px;
            display: block;
        }
        
        .price-inputs {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .price-input {
            width: 100px;
            padding: 8px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .rating-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .rating-option {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        
        .rating-option input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .rating-option label {
            font-size: 14px;
            color: #475569;
            cursor: pointer;
        }
        
        .location-select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
        }
        
        /* Vendor Grid */
        .vendor-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }
        
        .vendor-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .vendor-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        
        .vendor-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            position: relative;
            overflow: hidden;
        }

        .vendor-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .verified-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #10b981;
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .vendor-content {
            padding: 20px;
        }
        
        .vendor-name {
            font-size: 20px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 8px;
        }
        
        .vendor-description {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 12px;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .vendor-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        
        .vendor-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }
        
        .stars {
            color: #fbbf24;
            font-weight: 700;
        }
        
        .review-count {
            color: #64748b;
        }
        
        .vendor-price {
            font-size: 24px;
            font-weight: 700;
            color: #1e3a8a;
        }
        
        .vendor-footer {
            display: flex;
            gap: 10px;
            padding: 0 20px 20px;
        }
        
        .btn-view {
            flex: 1;
            padding: 12px;
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
            padding: 12px;
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

        .no-results {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .no-results h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #1e3a8a;
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
        }

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
        
        @media (max-width: 1200px) {
            .vendor-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .vendor-grid {
                grid-template-columns: 1fr;
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
                <a href="#" id="editAccount" class="dropdown-item">Edit Account</a>
                <a href="#" id="deleteAccount" class="dropdown-item">Delete Account</a>
                <a href="#" class="dropdown-item logout" onclick="event.preventDefault(); if (confirm('Are you sure you want to log out?')) {window.location.href='logout.php'; }">Logout</a>
            </div>
        </div>
    </nav>
    
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-top">
            <a href="collab_work.php?event_id=<?php echo $event_id; ?>" class="back-link">← Back</a>
            <div class="header-controls">
                <input type="text" class="search-box" id="searchBox" placeholder="Search vendors..." value="<?php echo htmlspecialchars($search); ?>">
                <select class="sort-dropdown" id="categoryFilter">
                    <option value="all" <?php echo $category === 'all' ? 'selected' : ''; ?>>All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                            <?php echo ucfirst(htmlspecialchars($cat)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="header-title">
            🎯 Browse Vendors
        </div>
        <div class="results-count">Showing <?php echo $vendor_count; ?> vendors</div>
    </div>
    
    <!-- Main Layout -->
    <div class="main-layout">
        <!-- Filter Sidebar -->
        <aside class="filter-sidebar">
            <form method="GET" action="" id="filterForm">
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                <input type="hidden" name="category" id="hiddenCategory" value="<?php echo $category; ?>">
                <input type="hidden" name="search" id="hiddenSearch" value="<?php echo htmlspecialchars($search); ?>">
                
                <div class="filter-header">
                    <h3 class="filter-title">Filters</h3>
                    <a href="?event_id=<?php echo $event_id; ?>" class="clear-filters">Clear All</a>
                </div>
                
                <!-- Price Range -->
                <div class="filter-section">
                    <label class="filter-label">Price Range (GHS)</label>
                    <div class="price-inputs">
                        <input type="number" class="price-input" name="min_price" placeholder="Min" value="<?php echo $min_price > 0 ? $min_price : ''; ?>">
                        <span>-</span>
                        <input type="number" class="price-input" name="max_price" placeholder="Max" value="<?php echo $max_price < 999999 ? $max_price : ''; ?>">
                    </div>
                </div>
                
                <!-- Rating -->
                <div class="filter-section">
                    <label class="filter-label">Minimum Rating</label>
                    <div class="rating-options">
                        <div class="rating-option">
                            <input type="radio" name="min_rating" value="5" id="rating5" <?php echo $min_rating == 5 ? 'checked' : ''; ?>>
                            <label for="rating5">⭐ 5.0 & above</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="min_rating" value="4" id="rating4" <?php echo $min_rating == 4 ? 'checked' : ''; ?>>
                            <label for="rating4">⭐ 4.0 & above</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="min_rating" value="3" id="rating3" <?php echo $min_rating == 3 ? 'checked' : ''; ?>>
                            <label for="rating3">⭐ 3.0 & above</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="min_rating" value="0" id="ratingAll" <?php echo $min_rating == 0 ? 'checked' : ''; ?>>
                            <label for="ratingAll">All Ratings</label>
                        </div>
                    </div>
                </div>
                
                <!-- Location -->
                <div class="filter-section">
                    <label class="filter-label">Location</label>
                    <select class="location-select" name="location">
                        <option value="">All Locations</option>
                        <option value="Greater Accra" <?php echo $location === 'Greater Accra' ? 'selected' : ''; ?>>Greater Accra</option>
                        <option value="Accra" <?php echo $location === 'Accra' ? 'selected' : ''; ?>>Accra</option>
                        <option value="Tema" <?php echo $location === 'Tema' ? 'selected' : ''; ?>>Tema</option>
                        <option value="Madina" <?php echo $location === 'Madina' ? 'selected' : ''; ?>>Madina</option>
                        <option value="Spintex" <?php echo $location === 'Spintex' ? 'selected' : ''; ?>>Spintex</option>
                        <option value="East Legon" <?php echo $location === 'East Legon' ? 'selected' : ''; ?>>East Legon</option>
                    </select>
                </div>
                
                <!-- Verified Only -->
                <div class="filter-section">
                    <div class="rating-option">
                        <input type="checkbox" name="verified_only" id="verified" <?php echo $verified_only ? 'checked' : ''; ?>>
                        <label for="verified">✓ Verified vendors only</label>
                    </div>
                </div>

                <button type="submit" class="btn-select" style="width: 100%; margin-top: 10px;">Apply Filters</button>
            </form>
        </aside>
        
        <!-- Vendor Grid -->
        <div class="vendor-grid">
            <?php if ($vendor_count > 0): ?>
                <?php foreach ($vendors as $vendor): ?>
                    <div class="vendor-card">
                        <div class="vendor-image">
                            <?php if (!empty($vendor['image']) && file_exists('../' . $vendor['image'])): ?>
                                <img src="../<?php echo htmlspecialchars($vendor['image']); ?>" alt="<?php echo htmlspecialchars($vendor['business_name']); ?>">
                            <?php else: ?>
                                <?php
                                // Default emoji based on category
                                $emoji = '🎯';
                                switch(strtolower($vendor['category'])) {
                                    case 'catering': $emoji = '👨‍🍳'; break;
                                    case 'venue': $emoji = '🏛️'; break;
                                    case 'photography': $emoji = '📸'; break;
                                    case 'decoration': $emoji = '🎨'; break;
                                    case 'entertainment': $emoji = '🎵'; break;
                                    case 'equipment': $emoji = '🔊'; break;
                                }
                                echo $emoji;
                                ?>
                            <?php endif; ?>
                            <?php if ($vendor['verification_status'] === 'approved'): ?>
                                <div class="verified-badge">✓ VERIFIED</div>
                            <?php endif; ?>
                        </div>
                        <div class="vendor-content">
                            <h3 class="vendor-name"><?php echo htmlspecialchars($vendor['business_name']); ?></h3>
                            <p class="vendor-description"><?php echo htmlspecialchars($vendor['business_description']); ?></p>
                            <div class="vendor-meta">
                                <div class="vendor-rating">
                                    <span class="stars">⭐ <?php echo number_format($vendor['rating'], 1); ?></span>
                                    <span class="review-count">(<?php echo $vendor['total_reviews']; ?> reviews)</span>
                                </div>
                            </div>
                            <div class="vendor-price">GHS <?php echo number_format($vendor['starting_price'], 0); ?></div>
                        </div>
                        <div class="vendor-footer">
                            <button class="btn-view" onclick="window.location.href='vendor_details.php?vendor_id=<?php echo $vendor['vendor_id']; ?>&event_id=<?php echo $event_id; ?>'">View Details</button>
                            <button class="btn-select" onclick="window.location.href='../actions/book_vendor.php?vendor_id=<?php echo $vendor['vendor_id']; ?>&event_id=<?php echo $event_id; ?>'">Select</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results">
                    <h3>No vendors found</h3>
                    <p>Try adjusting your filters or search terms</p>
                </div>
            <?php endif; ?>
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
    
    <script src="../js/browse_vendors.js"></script>
</body>
</html>