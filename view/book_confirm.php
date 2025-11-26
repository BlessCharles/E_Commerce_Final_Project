<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanSmart Ghana - Booking Confirmation</title>
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
            background: #fbbf24;
            z-index: 0;
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
            background: #10b981;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
        }
        
        .step-circle.active {
            background: #fbbf24;
            color: #1e3a8a;
        }
        
        .step-label {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }
        
        /* Main Content */
        .main-content {
            max-width: 1200px;
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
        
        /* Two Column Layout */
        .content-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 30px;
        }
        
        /* Order Summary */
        .order-summary {
            background: white;
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .summary-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 25px;
        }
        
        .vendor-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            margin-bottom: 15px;
        }
        
        .vendor-info {
            flex: 1;
        }
        
        .vendor-name {
            font-size: 18px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 5px;
        }
        
        .vendor-category {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 8px;
        }
        
        .vendor-badge {
            display: inline-block;
            background: #dcfce7;
            color: #15803d;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 12px;
        }
        
        .vendor-price {
            font-size: 20px;
            font-weight: 700;
            color: #1e3a8a;
        }
        
        .summary-divider {
            height: 2px;
            background: #e2e8f0;
            margin: 25px 0;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .summary-label {
            color: #64748b;
        }
        
        .summary-value {
            font-weight: 600;
            color: #334155;
        }
        
        .summary-total {
            display: flex;
            justify-content: space-between;
            padding-top: 20px;
            border-top: 3px solid #1e3a8a;
            margin-top: 20px;
            font-size: 24px;
            font-weight: 700;
            color: #1e3a8a;
        }
        
        /* Payment Options */
        .payment-card {
            background: white;
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .payment-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 15px;
        }
        
        .payment-option {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 18px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .payment-option:hover {
            border-color: #fbbf24;
            background: #fffbeb;
        }
        
        .payment-option.selected {
            border-color: #fbbf24;
            background: #fffbeb;
        }
        
        .payment-radio {
            width: 20px;
            height: 20px;
            margin-top: 2px;
            cursor: pointer;
            accent-color: #fbbf24;
        }
        
        .payment-info {
            flex: 1;
        }
        
        .payment-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e3a8a;
            margin-bottom: 5px;
        }
        
        .payment-description {
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
        }
        
        /* Payment Methods */
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }
        
        .method-btn {
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }
        
        .method-btn:hover {
            border-color: #fbbf24;
            background: #fffbeb;
        }
        
        .method-btn.selected {
            border-color: #fbbf24;
            background: #fffbeb;
        }
        
        .method-icon {
            font-size: 32px;
            margin-bottom: 8px;
        }
        
        .method-name {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
        }
        
        /* Security Notice */
        .security-notice {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        
        .notice-icon {
            font-size: 24px;
            flex-shrink: 0;
        }
        
        .notice-text {
            font-size: 14px;
            color: #1e3a8a;
            line-height: 1.6;
        }
        
        .notice-text strong {
            font-weight: 700;
        }
        
        /* Confirm Button */
        .btn-confirm {
            width: 100%;
            padding: 18px;
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
        
        .btn-confirm:hover {
            background: #f59e0b;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(251, 191, 36, 0.5);
        }
        
        .terms-text {
            margin-top: 15px;
            text-align: center;
            font-size: 13px;
            color: #64748b;
        }
        
        .terms-text a {
            color: #3b82f6;
            text-decoration: none;
        }
        
        .terms-text a:hover {
            text-decoration: underline;
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
            
            <div class="progress-step">
                <div class="step-circle">✓</div>
                <span class="step-label">Budget & Details</span>
            </div>
            <div class="progress-step">
                <div class="step-circle">✓</div>
                <span class="step-label">Get Recommendations</span>
            </div>
            <div class="progress-step">
                <div class="step-circle active">3</div>
                <span class="step-label">Review & Book</span>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1>Review & Confirm Booking</h1>
            <p>One last step before we secure your vendors</p>
        </div>
        
        <div class="content-grid">
            <!-- Order Summary -->
            <div class="order-summary">
                <h2 class="summary-title">Order Summary</h2>
                
                <!-- Vendor List -->
                <div class="vendor-item">
                    <div class="vendor-info">
                        <div class="vendor-name">Ama's Kitchen</div>
                        <div class="vendor-category">🍽️ Catering Service</div>
                        <span class="vendor-badge">✓ VERIFIED</span>
                    </div>
                    <div class="vendor-price">GHS 9,000</div>
                </div>
                
                <div class="vendor-item">
                    <div class="vendor-info">
                        <div class="vendor-name">Paradise Gardens</div>
                        <div class="vendor-category">🏛️ Venue</div>
                        <span class="vendor-badge">✓ VERIFIED</span>
                    </div>
                    <div class="vendor-price">GHS 4,500</div>
                </div>
                
                <div class="vendor-item">
                    <div class="vendor-info">
                        <div class="vendor-name">Royal Tent Rentals</div>
                        <div class="vendor-category">⛺ Tent & Chairs</div>
                        <span class="vendor-badge">✓ VERIFIED</span>
                    </div>
                    <div class="vendor-price">GHS 3,500</div>
                </div>
                
                <div class="vendor-item">
                    <div class="vendor-info">
                        <div class="vendor-name">Lens & Light Studios</div>
                        <div class="vendor-category">📸 Photography</div>
                        <span class="vendor-badge">✓ VERIFIED</span>
                    </div>
                    <div class="vendor-price">GHS 2,500</div>
                </div>
                
                <div class="vendor-item">
                    <div class="vendor-info">
                        <div class="vendor-name">Elegant Events GH</div>
                        <div class="vendor-category">🎨 Decoration</div>
                        <span class="vendor-badge">✓ VERIFIED</span>
                    </div>
                    <div class="vendor-price">GHS 2,000</div>
                </div>
                
                <div class="vendor-item">
                    <div class="vendor-info">
                        <div class="vendor-name">SoundPro Ghana</div>
                        <div class="vendor-category">🔊 Sound System</div>
                        <span class="vendor-badge">✓ VERIFIED</span>
                    </div>
                    <div class="vendor-price">GHS 1,500</div>
                </div>
                
                <div class="vendor-item">
                    <div class="vendor-info">
                        <div class="vendor-name">Swift Transport Services</div>
                        <div class="vendor-category">🚌 Transportation</div>
                        <span class="vendor-badge">✓ VERIFIED</span>
                    </div>
                    <div class="vendor-price">GHS 1,500</div>
                </div>
                
                <!-- Summary Calculation -->
                <div class="summary-divider"></div>
                
                <div class="summary-row">
                    <span class="summary-label">Subtotal (7 vendors)</span>
                    <span class="summary-value">GHS 24,500</span>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Platform Fee</span>
                    <span class="summary-value">GHS 0</span>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Service Charge</span>
                    <span class="summary-value">GHS 300</span>
                </div>
                
                <div class="summary-total">
                    <span>Total Amount</span>
                    <span>GHS 24,800</span>
                </div>
            </div>
            
            <!-- Payment Options -->
            <div>
                <div class="payment-card">
                    <h2 class="summary-title">Payment Options</h2>
                    
                    <div class="payment-section">
                        <h3 class="section-title">How would you like to pay?</h3>
                        
                        <div class="payment-option selected">
                            <input type="radio" name="payment-plan" class="payment-radio" checked>
                            <div class="payment-info">
                                <div class="payment-title">💰 Pay Full Amount</div>
                                <p class="payment-description">Pay GHS 24,800 now and secure all vendors immediately</p>
                            </div>
                        </div>
                        
                        <div class="payment-option">
                            <input type="radio" name="payment-plan" class="payment-radio">
                            <div class="payment-info">
                                <div class="payment-title">📅 Installment Plan (3 months)</div>
                                <p class="payment-description">Pay GHS 8,267 monthly for 3 months. Available for events 3+ months away</p>
                            </div>
                        </div>
                        
                        <div class="payment-option">
                            <input type="radio" name="payment-plan" class="payment-radio">
                            <div class="payment-info">
                                <div class="payment-title">👨‍👩‍👧‍👦 Group Contribution</div>
                                <p class="payment-description">Family members contribute their share. Track contributions in real-time</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="payment-section">
                        <h3 class="section-title">Select Payment Method</h3>
                        
                        <div class="payment-methods">
                            <button class="method-btn selected">
                                <div class="method-icon">📱</div>
                                <div class="method-name">MTN MoMo</div>
                            </button>
                            
                            <button class="method-btn">
                                <div class="method-icon">💳</div>
                                <div class="method-name">Telecel Cash</div>
                            </button>
                            
                            <button class="method-btn">
                                <div class="method-icon">💎</div>
                                <div class="method-name">Visa/Mastercard</div>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Security Notice -->
                    <div class="security-notice">
                        <div class="notice-icon">🔒</div>
                        <div class="notice-text">
                            <strong>Your money is protected!</strong> Funds will be held securely in escrow until all vendors confirm service delivery. If any vendor fails to deliver, you'll receive a full refund for that service.
                        </div>
                    </div>
                    
                    <!-- Confirm Button -->
                    <button class="btn-confirm">
                        Confirm Booking & Pay GHS 24,800
                    </button>
                    
                    <p class="terms-text">
                        By confirming, you agree to our <a href="#">Terms of Service</a> and <a href="#">Refund Policy</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle payment options
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.payment-option').forEach(opt => {
                    opt.classList.remove('selected');
                    opt.querySelector('.payment-radio').checked = false;
                });
                this.classList.add('selected');
                this.querySelector('.payment-radio').checked = true;
            });
        });
        
        // Toggle payment methods
        document.querySelectorAll('.method-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.method-btn').forEach(b => b.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
    </script>
</body>
</html>