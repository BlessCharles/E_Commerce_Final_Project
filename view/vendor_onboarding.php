<?php
session_start();

// Check if user is logged in as vendor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'vendor') {
    header('Location: ../login/login.php');
    exit();
}

$user_name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanSmart Ghana - Vendor Onboarding</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Navigation */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 60px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
        
        .btn-logout {
            padding: 10px 24px;
            background: transparent;
            border: 2px solid #1e3a8a;
            color: #1e3a8a;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-logout:hover {
            background: #1e3a8a;
            color: white;
        }
        
        /* Container */
        .container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        
        /* Onboarding Card */
        .onboarding-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 50px;
            width: 100%;
            max-width: 800px;
        }
        
        .onboarding-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .onboarding-header h1 {
            font-size: 32px;
            color: #1e3a8a;
            margin-bottom: 10px;
        }
        
        .onboarding-header p {
            font-size: 16px;
            color: #64748b;
        }
        
        /* Progress Steps */
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 50px;
            position: relative;
        }
        
        .progress-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e2e8f0;
            z-index: 0;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .step.active .step-number {
            background: #fbbf24;
            color: #1e3a8a;
        }
        
        .step-label {
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
        }
        
        .step.active .step-label {
            color: #1e3a8a;
            font-weight: 600;
        }
        
        /* Form */
        .form-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 20px;
            color: #1e3a8a;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }
        
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
            font-family: inherit;
            transition: all 0.3s;
        }
        
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        /* Service Checkboxes */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .checkbox-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .checkbox-card:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }
        
        .checkbox-card input[type="checkbox"]:checked + label {
            font-weight: 600;
        }
        
        .checkbox-card input[type="checkbox"]:checked ~ * {
            border-color: #fbbf24;
            background: #fffbeb;
        }
        
        .checkbox-card input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        
        .checkbox-card label {
            cursor: pointer;
            font-size: 14px;
            color: #334155;
        }
        
        /* Buttons */
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 40px;
        }
        
        .btn {
            flex: 1;
            padding: 16px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #fbbf24;
            color: #1e3a8a;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
        }
        
        .btn-primary:hover {
            background: #f59e0b;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(251, 191, 36, 0.4);
        }
        
        .btn-secondary {
            background: #e2e8f0;
            color: #334155;
        }
        
        .btn-secondary:hover {
            background: #cbd5e1;
        }

        @media (max-width: 768px) {
            .form-row, .services-grid {
                grid-template-columns: 1fr;
            }
            
            .progress-steps {
                flex-direction: column;
                gap: 20px;
            }
            
            .progress-steps::before {
                display: none;
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
        <button class="btn-logout" onclick="if(confirm('Are you sure you want to log out?')) { window.location.href='logout.php'; }">
    <i class="fas fa-sign-out-alt"></i> Logout
</button>
    </nav>
    
    <!-- Container -->
    <div class="container">
        <div class="onboarding-card">
            <div class="onboarding-header">
                <h1>Welcome to PlanSmart, <?php echo htmlspecialchars($user_name); ?>!</h1>
                <p>Let's set up your vendor profile to start receiving bookings</p>
            </div>
            
            <!-- Progress Steps -->
            <div class="progress-steps">
                <div class="step active">
                    <div class="step-number">1</div>
                    <div class="step-label">Business Info</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-label">Services</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-label">Pricing</div>
                </div>
            </div>
            
            <form action="../../actions/save_vendor_profile.php" method="POST" enctype="multipart/form-data">
                <!-- Business Information -->
                <div class="form-section">
                    <h3 class="section-title">Business Information</h3>
                    
                    <div class="form-group">
                        <label class="form-label">Business Name *</label>
                        <input type="text" name="business_name" class="form-input" placeholder="e.g., Elegance Events Ghana" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Business Description *</label>
                        <textarea name="business_description" class="form-textarea" placeholder="Tell customers about your business, experience, and what makes you special..." required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Business Category *</label>
                            <select name="category" class="form-select" required>
                                <option value="">Select a category</option>
                                <option value="venue">Venue</option>
                                <option value="catering">Catering</option>
                                <option value="decoration">Decoration</option>
                                <option value="photography">Photography</option>
                                <option value="music">Music & Entertainment</option>
                                <option value="makeup">Makeup & Beauty</option>
                                <option value="transportation">Transportation</option>
                                <option value="rental">Equipment Rental</option>
                                <option value="other">Other Services</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Years of Experience *</label>
                            <input type="number" name="years_experience" class="form-input" placeholder="e.g., 5" min="0" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Business Location (City) *</label>
                        <input type="text" name="location" class="form-input" placeholder="e.g., Accra" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Business Address</label>
                        <input type="text" name="address" class="form-input" placeholder="e.g., East Legon, Accra">
                    </div>
                </div>
                
                <!-- Services Offered -->
                <div class="form-section">
                    <h3 class="section-title">Event Types You Serve</h3>
                    <div class="services-grid">
                        <div class="checkbox-card">
                            <input type="checkbox" id="wedding" name="events[]" value="wedding">
                            <label for="wedding">Weddings</label>
                        </div>
                        <div class="checkbox-card">
                            <input type="checkbox" id="funeral" name="events[]" value="funeral">
                            <label for="funeral">Funerals</label>
                        </div>
                        <div class="checkbox-card">
                            <input type="checkbox" id="naming" name="events[]" value="naming">
                            <label for="naming">Naming Ceremonies</label>
                        </div>
                        <div class="checkbox-card">
                            <input type="checkbox" id="birthday" name="events[]" value="birthday">
                            <label for="birthday">Birthdays</label>
                        </div>
                        <div class="checkbox-card">
                            <input type="checkbox" id="corporate" name="events[]" value="corporate">
                            <label for="corporate">Corporate Events</label>
                        </div>
                        <div class="checkbox-card">
                            <input type="checkbox" id="other" name="events[]" value="other">
                            <label for="other">Other Events</label>
                        </div>
                    </div>
                </div>
                
                <!-- Pricing -->
                <div class="form-section">
                    <h3 class="section-title">Pricing Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Starting Price (GH₵) *</label>
                            <input type="number" name="starting_price" class="form-input" placeholder="e.g., 500" min="0" step="0.01" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Price Range (GH₵)</label>
                            <input type="text" name="price_range" class="form-input" placeholder="e.g., 500 - 5000">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Payment Methods Accepted</label>
                        <input type="text" name="payment_methods" class="form-input" placeholder="e.g., Cash, Mobile Money, Bank Transfer">
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="button-group">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='../../actions/logout.php'">Save & Complete Later</button>
                    <button type="submit" class="btn btn-primary">Complete Profile</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>