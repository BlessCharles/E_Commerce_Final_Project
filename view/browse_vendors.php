<?php
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

$event_id = $_GET['event_id'];
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
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
        
        @media (max-width: 1200px) {
            .vendor-grid {
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
        <div class="user-avatar">KM</div>
    </nav>
    
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-top">
            <a href="collab_work.php?event_id=<?php echo $event_id; ?>" class="back-link">← Back</a>
            <div class="header-controls">
                <input type="text" class="search-box" placeholder="Search vendors...">
                <select class="sort-dropdown">
                    <option>Sort: Price: Low to High</option>
                    <option>Price: High to Low</option>
                    <option>Rating: High to Low</option>
                    <option>Most Reviews</option>
                </select>
            </div>
        </div>
        <div class="header-title">
            🍽️ Browse Catering Services
        </div>
        <div class="results-count">Showing 24 caterers in Greater Accra</div>
    </div>
    
    <!-- Main Layout -->
    <div class="main-layout">
        <!-- Filter Sidebar -->
        <aside class="filter-sidebar">
            <div class="filter-header">
                <h3 class="filter-title">Filters</h3>
                <a href="#" class="clear-filters">Clear All</a>
            </div>
            
            <!-- Price Range -->
            <div class="filter-section">
                <label class="filter-label">Price Range</label>
                <div class="price-inputs">
                    <input type="number" class="price-input" placeholder="Min" value="5000">
                    <span>-</span>
                    <input type="number" class="price-input" placeholder="Max" value="15000">
                </div>
            </div>
            
            <!-- Rating -->
            <div class="filter-section">
                <label class="filter-label">Minimum Rating</label>
                <div class="rating-options">
                    <div class="rating-option">
                        <input type="radio" name="rating" id="rating5">
                        <label for="rating5">⭐ 5.0 & above</label>
                    </div>
                    <div class="rating-option">
                        <input type="radio" name="rating" id="rating4" checked>
                        <label for="rating4">⭐ 4.0 & above</label>
                    </div>
                    <div class="rating-option">
                        <input type="radio" name="rating" id="rating3">
                        <label for="rating3">⭐ 3.0 & above</label>
                    </div>
                    <div class="rating-option">
                        <input type="radio" name="rating" id="ratingAll">
                        <label for="ratingAll">All Ratings</label>
                    </div>
                </div>
            </div>
            
            <!-- Location -->
            <div class="filter-section">
                <label class="filter-label">Location</label>
                <select class="location-select">
                    <option>Greater Accra</option>
                    <option>Accra</option>
                    <option>Tema</option>
                    <option>Madina</option>
                    <option>Spintex</option>
                    <option>East Legon</option>
                </select>
            </div>
            
            <!-- Verified Only -->
            <div class="filter-section">
                <div class="rating-option">
                    <input type="checkbox" id="verified" checked>
                    <label for="verified">✓ Verified vendors only</label>
                </div>
            </div>
        </aside>
        
        <!-- Vendor Grid -->
        <div class="vendor-grid">
            <!-- Vendor Card 1 -->
            <div class="vendor-card">
                <div class="vendor-image">
                    👨‍🍳
                    <div class="verified-badge">✓ VERIFIED</div>
                </div>
                <div class="vendor-content">
                    <h3 class="vendor-name">Ama's Kitchen</h3>
                    <p class="vendor-description">Traditional Ghanaian & continental dishes. 15+ years experience in large events.</p>
                    <div class="vendor-meta">
                        <div class="vendor-rating">
                            <span class="stars">⭐ 4.8</span>
                            <span class="review-count">(127 reviews)</span>
                        </div>
                    </div>
                    <div class="vendor-price">GHS 9,000</div>
                </div>
                <div class="vendor-footer">
                    <button class="btn-view">View Details</button>
                    <button class="btn-select">Select</button>
                </div>
            </div>
            
            <!-- Vendor Card 2 -->
            <div class="vendor-card">
                <div class="vendor-image">
                    🍴
                    <div class="verified-badge">✓ VERIFIED</div>
                </div>
                <div class="vendor-content">
                    <h3 class="vendor-name">Royal Feast Catering</h3>
                    <p class="vendor-description">Premium catering service. Buffet & plated meal options available.</p>
                    <div class="vendor-meta">
                        <div class="vendor-rating">
                            <span class="stars">⭐ 4.6</span>
                            <span class="review-count">(94 reviews)</span>
                        </div>
                    </div>
                    <div class="vendor-price">GHS 8,800</div>
                </div>
                <div class="vendor-footer">
                    <button class="btn-view">View Details</button>
                    <button class="btn-select">Select</button>
                </div>
            </div>
            
            <!-- Vendor Card 3 -->
            <div class="vendor-card">
                <div class="vendor-image">
                    🥘
                    <div class="verified-badge">✓ VERIFIED</div>
                </div>
                <div class="vendor-content">
                    <h3 class="vendor-name">Tasty Bites Ghana</h3>
                    <p class="vendor-description">Authentic local cuisine. Specializing in funerals & family gatherings.</p>
                    <div class="vendor-meta">
                        <div class="vendor-rating">
                            <span class="stars">⭐ 4.7</span>
                            <span class="review-count">(82 reviews)</span>
                        </div>
                    </div>
                    <div class="vendor-price">GHS 9,200</div>
                </div>
                <div class="vendor-footer">
                    <button class="btn-view">View Details</button>
                    <button class="btn-select">Select</button>
                </div>
            </div>
            
            <!-- Vendor Card 4 -->
            <div class="vendor-card">
                <div class="vendor-image">
                    🍛
                    <div class="verified-badge">✓ VERIFIED</div>
                </div>
                <div class="vendor-content">
                    <h3 class="vendor-name">Golden Spoon Catering</h3>
                    <p class="vendor-description">Budget-friendly without compromising quality. Perfect for 200-500 guests.</p>
                    <div class="vendor-meta">
                        <div class="vendor-rating">
                            <span class="stars">⭐ 4.5</span>
                            <span class="review-count">(68 reviews)</span>
                        </div>
                    </div>
                    <div class="vendor-price">GHS 7,500</div>
                </div>
                <div class="vendor-footer">
                    <button class="btn-view">View Details</button>
                    <button class="btn-select">Select</button>
                </div>
            </div>
            
            <!-- Vendor Card 5 -->
            <div class="vendor-card">
                <div class="vendor-image">
                    🍲
                    <div class="verified-badge">✓ VERIFIED</div>
                </div>
                <div class="vendor-content">
                    <h3 class="vendor-name">Heritage Foods Ghana</h3>
                    <p class="vendor-description">Traditional Ghanaian meals with modern presentation. Award-winning service.</p>
                    <div class="vendor-meta">
                        <div class="vendor-rating">
                            <span class="stars">⭐ 4.9</span>
                            <span class="review-count">(143 reviews)</span>
                        </div>
                    </div>
                    <div class="vendor-price">GHS 10,500</div>
                </div>
                <div class="vendor-footer">
                    <button class="btn-view">View Details</button>
                    <button class="btn-select">Select</button>
                </div>
            </div>
            
            <!-- Vendor Card 6 -->
            <div class="vendor-card">
                <div class="vendor-image">
                    🥗
                    <div class="verified-badge">✓ VERIFIED</div>
                </div>
                <div class="vendor-content">
                    <h3 class="vendor-name">Fresh & Delicious Catering</h3>
                    <p class="vendor-description">Health-conscious menu options. Vegetarian & vegan options available.</p>
                    <div class="vendor-meta">
                        <div class="vendor-rating">
                            <span class="stars">⭐ 4.4</span>
                            <span class="review-count">(56 reviews)</span>
                        </div>
                    </div>
                    <div class="vendor-price">GHS 8,200</div>
                </div>
                <div class="vendor-footer">
                    <button class="btn-view">View Details</button>
                    <button class="btn-select">Select</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>