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
            width: 66%;
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
        
        .progress-bar-container {
            background: rgba(255,255,255,0.2);
            height: 12px;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .progress-bar-fill {
            background: #fbbf24;
            height: 100%;
            width: 99.2%;
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
        
        .btn-selected {
            background: #10b981;
            color: white;
        }

        /* CTA Button */
        .cta-container {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }

        .btn-continue {
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
        
        @media (max-width: 1200px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            
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
            <p>We've matched your budget with the best vendors for your event</p>
        </div>
        
        <!-- Budget Tracker -->
        <div class="budget-tracker">
            <div class="budget-info">
                <div>
                    <div class="budget-amount">GHS 24,800</div>
                    <div class="budget-total">of GHS 25,000 budget</div>
                </div>
                <div class="budget-status">✓ Within Budget</div>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar-fill"></div>
            </div>
        </div>
        
        <!-- Two Column Layout -->
        <div class="content-grid">
            <!-- Budget Breakdown Sidebar -->
            <div class="budget-breakdown">
                <h3 class="breakdown-title">Budget Breakdown</h3>
                
                <div class="breakdown-item">
                    <div>
                        <div class="breakdown-label">
                            🍽️ Catering
                        </div>
                        <div class="breakdown-bar" style="width: 180px; background: #ef4444;"></div>
                    </div>
                    <div class="breakdown-amount">
                        <div class="amount-value">GHS 9,000</div>
                        <div class="amount-percentage">36%</div>
                    </div>
                </div>
                
                <div class="breakdown-item">
                    <div>
                        <div class="breakdown-label">
                            🏛️ Venue
                        </div>
                        <div class="breakdown-bar" style="width: 90px; background: #3b82f6;"></div>
                    </div>
                    <div class="breakdown-amount">
                        <div class="amount-value">GHS 4,500</div>
                        <div class="amount-percentage">18%</div>
                    </div>
                </div>
                
                <div class="breakdown-item">
                    <div>
                        <div class="breakdown-label">
                            ⛺ Tent & Chairs
                        </div>
                        <div class="breakdown-bar" style="width: 90px; background: #8b5cf6;"></div>
                    </div>
                    <div class="breakdown-amount">
                        <div class="amount-value">GHS 3,500</div>
                        <div class="amount-percentage">14%</div>
                    </div>
                </div>
                
                <div class="breakdown-item">
                    <div>
                        <div class="breakdown-label">
                            📸 Photography
                        </div>
                        <div class="breakdown-bar" style="width: 62px; background: #10b981;"></div>
                    </div>
                    <div class="breakdown-amount">
                        <div class="amount-value">GHS 2,500</div>
                        <div class="amount-percentage">10%</div>
                    </div>
                </div>
                
                <div class="breakdown-item">
                    <div>
                        <div class="breakdown-label">
                            🎨 Decoration
                        </div>
                        <div class="breakdown-bar" style="width: 50px; background: #f59e0b;"></div>
                    </div>
                    <div class="breakdown-amount">
                        <div class="amount-value">GHS 2,000</div>
                        <div class="amount-percentage">8%</div>
                    </div>
                </div>
                
                <div class="breakdown-item">
                    <div>
                        <div class="breakdown-label">
                            🔊 Sound System
                        </div>
                        <div class="breakdown-bar" style="width: 37px; background: #ec4899;"></div>
                    </div>
                    <div class="breakdown-amount">
                        <div class="amount-value">GHS 1,500</div>
                        <div class="amount-percentage">6%</div>
                    </div>
                </div>
                
                <div class="breakdown-item">
                    <div>
                        <div class="breakdown-label">
                            🚌 Transportation
                        </div>
                        <div class="breakdown-bar" style="width: 37px; background: #06b6d4;"></div>
                    </div>
                    <div class="breakdown-amount">
                        <div class="amount-value">GHS 1,500</div>
                        <div class="amount-percentage">6%</div>
                    </div>
                </div>
                
                <div class="breakdown-item">
                    <div>
                        <div class="breakdown-label">
                            📋 Miscellaneous
                        </div>
                        <div class="breakdown-bar" style="width: 12px; background: #94a3b8;"></div>
                    </div>
                    <div class="breakdown-amount">
                        <div class="amount-value">GHS 300</div>
                        <div class="amount-percentage">2%</div>
                    </div>


                </div>
                <div class="cta-container">
                <button type="submit" class="btn-continue" onclick = "window.location.href='collab_work.php';">Get Smart Recommendations →</button>
                </div>
            </div>
            
            <!-- Vendor Recommendations -->
            <div class="recommendations-section">
                <!-- Catering -->
                <div class="category-section">
                    <div class="category-header">
                        <div class="category-title">
                            <span class="category-icon">🍽️</span>
                            Catering Services
                        </div>
                        <a href="browse_vendors.php" class="browse-all">Browse All Caterers →</a>
                    </div>
                    
                    <div class="vendor-grid">
                        <div class="vendor-card">
                            <div class="vendor-image">👨‍🍳</div>
                            <div class="vendor-badge">✓ VERIFIED</div>
                            <div class="vendor-name">Ama's Kitchen</div>
                            <div class="vendor-rating">
                                <span class="stars">⭐ 4.8</span>
                                <span>(127 reviews)</span>
                            </div>
                            <div class="vendor-price">GHS 9,000</div>
                            <div class="vendor-actions">
                                <button class="btn-view">View Details</button>
                                <button class="btn-select btn-selected">✓ Selected</button>
                            </div>
                        </div>
                        
                        <div class="vendor-card">
                            <div class="vendor-image">🍴</div>
                            <div class="vendor-badge">✓ VERIFIED</div>
                            <div class="vendor-name">Royal Feast Catering</div>
                            <div class="vendor-rating">
                                <span class="stars">⭐ 4.6</span>
                                <span>(94 reviews)</span>
                            </div>
                            <div class="vendor-price">GHS 8,800</div>
                            <div class="vendor-actions">
                                <button class="btn-view">View Details</button>
                                <button class="btn-select">Select</button>
                            </div>
                        </div>
                        
                        <div class="vendor-card">
                            <div class="vendor-image">🥘</div>
                            <div class="vendor-badge">✓ VERIFIED</div>
                            <div class="vendor-name">Tasty Bites Ghana</div>
                            <div class="vendor-rating">
                                <span class="stars">⭐ 4.7</span>
                                <span>(82 reviews)</span>
                            </div>
                            <div class="vendor-price">GHS 9,200</div>
                            <div class="vendor-actions">
                                <button class="btn-view">View Details</button>
                                <button class="btn-select">Select</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Photography -->
                <div class="category-section">
                    <div class="category-header">
                        <div class="category-title">
                            <span class="category-icon">📸</span>
                            Photography Services
                        </div>
                        <a href="#" class="browse-all">Browse All Photographers →</a>
                    </div>
                    
                    <div class="vendor-grid">
                        <div class="vendor-card">
                            <div class="vendor-image">📷</div>
                            <div class="vendor-badge">✓ VERIFIED</div>
                            <div class="vendor-name">Lens & Light Studios</div>
                            <div class="vendor-rating">
                                <span class="stars">⭐ 4.9</span>
                                <span>(156 reviews)</span>
                            </div>
                            <div class="vendor-price">GHS 2,500</div>
                            <div class="vendor-actions">
                                <button class="btn-view">View Details</button>
                                <button class="btn-select">Select</button>
                            </div>
                        </div>
                        
                        <div class="vendor-card">
                            <div class="vendor-image">📸</div>
                            <div class="vendor-badge">✓ VERIFIED</div>
                            <div class="vendor-name">Kwame Photography</div>
                            <div class="vendor-rating">
                                <span class="stars">⭐ 4.7</span>
                                <span>(103 reviews)</span>
                            </div>
                            <div class="vendor-price">GHS 2,300</div>
                            <div class="vendor-actions">
                                <button class="btn-view">View Details</button>
                                <button class="btn-select">Select</button>
                            </div>
                        </div>
                        
                        <div class="vendor-card">
                            <div class="vendor-image">🎥</div>
                            <div class="vendor-badge">✓ VERIFIED</div>
                            <div class="vendor-name">Moments Captured GH</div>
                            <div class="vendor-rating">
                                <span class="stars">⭐ 4.8</span>
                                <span>(89 reviews)</span>
                            </div>
                            <div class="vendor-price">GHS 2,600</div>
                            <div class="vendor-actions">
                                <button class="btn-view">View Details</button>
                                <button class="btn-select">Select</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>