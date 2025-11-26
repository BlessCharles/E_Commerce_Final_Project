<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanSmart Ghana - Budget Planning</title>
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
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
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
            width: 33%;
            height: 3px;
            background: #fbbf24;
            z-index: 1;
            transition: width 0.3s;
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
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .content-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .event-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            color: #1e3a8a;
            margin-bottom: 20px;
        }
        
        .content-header h1 {
            font-size: 36px;
            color: #1e3a8a;
            margin-bottom: 10px;
        }
        
        .content-header p {
            font-size: 18px;
            color: #64748b;
        }
        
        /* Budget Card */
        .budget-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 40px;
            margin-bottom: 30px;
        }
        
        .budget-input-group {
            margin-bottom: 40px;
        }
        
        .input-label {
            display: block;
            font-size: 16px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 12px;
        }
        
        .budget-input-wrapper {
            position: relative;
            max-width: 400px;
        }
        
        .currency-symbol {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
            font-weight: 700;
            color: #1e3a8a;
        }
        
        .budget-input {
            width: 100%;
            padding: 18px 20px 18px 70px;
            border: 3px solid #e2e8f0;
            border-radius: 12px;
            font-size: 24px;
            font-weight: 700;
            color: #1e3a8a;
            transition: all 0.3s;
        }
        
        .budget-input:focus {
            outline: none;
            border-color: #fbbf24;
            box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.1);
        }
        
        .budget-hint {
            margin-top: 10px;
            font-size: 14px;
            color: #64748b;
        }
        
        /* Services Checklist */
        .services-section {
            margin-bottom: 40px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 20px;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .service-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .service-item:hover {
            border-color: #fbbf24;
            background: #fffbeb;
        }
        
        .service-item.checked {
            border-color: #fbbf24;
            background: #fffbeb;
        }
        
        .service-checkbox {
            width: 24px;
            height: 24px;
            cursor: pointer;
            accent-color: #fbbf24;
        }
        
        .service-label {
            font-size: 16px;
            font-weight: 500;
            color: #334155;
            cursor: pointer;
            flex: 1;
        }
        
        .service-icon {
            font-size: 24px;
        }
        
        /* Event Details */
        .event-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .detail-group {
            display: flex;
            flex-direction: column;
        }
        
        .detail-label {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }
        
        .detail-input {
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .detail-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
        
        @media (max-width: 768px) {
            .services-grid {
                grid-template-columns: 1fr;
            }
            
            .event-details {
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
        <div class="user-menu">
            <div class="user-avatar">KM</div>
        </div>
    </nav>
    
    <!-- Progress Bar -->
    <div class="progress-container">
        <div class="progress-steps">
            <div class="progress-line"></div>
            <div class="progress-line-active"></div>
            
            <div class="progress-step">
                <div class="step-circle active">1</div>
                <span class="step-label">Budget & Details</span>
            </div>
            <div class="progress-step">
                <div class="step-circle">2</div>
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
        <div class="content-header">
            <div class="event-badge">
                🕊️ Planning a Funeral
            </div>
            <h1>Let's Plan Your Event</h1>
            <p>Tell us about your budget and requirements</p>
        </div>
        
        <div class="budget-card">
            <!-- Budget Input -->
            <div class="budget-input-group">
                <label class="input-label">What's your total budget?</label>
                <div class="budget-input-wrapper">
                    <span class="currency-symbol">GHS</span>
                    <input 
                        type="text" 
                        class="budget-input" 
                        placeholder="25,000" 
                        value="25,000"
                    >
                </div>
                <p class="budget-hint">💡 Don't worry, we'll help you optimize your spending</p>
            </div>
            
            <!-- Services Checklist -->
            <div class="services-section">
                <h2 class="section-title">What services do you need?</h2>
                <div class="services-grid">
                    <div class="service-item checked">
                        <input type="checkbox" class="service-checkbox" id="catering" checked>
                        <label for="catering" class="service-label">Catering</label>
                        <span class="service-icon">🍽️</span>
                    </div>
                    
                    <div class="service-item checked">
                        <input type="checkbox" class="service-checkbox" id="venue" checked>
                        <label for="venue" class="service-label">Venue</label>
                        <span class="service-icon">🏛️</span>
                    </div>
                    
                    <div class="service-item checked">
                        <input type="checkbox" class="service-checkbox" id="decoration" checked>
                        <label for="decoration" class="service-label">Decoration</label>
                        <span class="service-icon">🎨</span>
                    </div>
                    
                    <div class="service-item checked">
                        <input type="checkbox" class="service-checkbox" id="photography" checked>
                        <label for="photography" class="service-label">Photography</label>
                        <span class="service-icon">📸</span>
                    </div>
                    
                    <div class="service-item checked">
                        <input type="checkbox" class="service-checkbox" id="tent" checked>
                        <label for="tent" class="service-label">Tent & Chairs</label>
                        <span class="service-icon">⛺</span>
                    </div>
                    
                    <div class="service-item checked">
                        <input type="checkbox" class="service-checkbox" id="sound" checked>
                        <label for="sound" class="service-label">Sound System</label>
                        <span class="service-icon">🔊</span>
                    </div>
                    
                    <div class="service-item">
                        <input type="checkbox" class="service-checkbox" id="transport">
                        <label for="transport" class="service-label">Transportation</label>
                        <span class="service-icon">🚌</span>
                    </div>
                    
                    <div class="service-item">
                        <input type="checkbox" class="service-checkbox" id="other">
                        <label for="other" class="service-label">Other Services</label>
                        <span class="service-icon">➕</span>
                    </div>
                </div>
            </div>
            
            <!-- Event Details -->
            <div class="services-section">
                <h2 class="section-title">Event Details</h2>
                <div class="event-details">
                    <div class="detail-group">
                        <label class="detail-label">Expected Guest Count</label>
                        <input type="number" class="detail-input" placeholder="e.g., 300" value="300">
                    </div>
                    
                    <div class="detail-group">
                        <label class="detail-label">Event Date</label>
                        <input type="date" class="detail-input" value="2025-01-15">
                    </div>
                </div>
            </div>
            
            <!-- CTA -->
            <div class="cta-container">
                <button class="btn-continue">Get Smart Recommendations →</button>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle service items
        document.querySelectorAll('.service-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (e.target.type !== 'checkbox') {
                    const checkbox = this.querySelector('.service-checkbox');
                    checkbox.checked = !checkbox.checked;
                }
                this.classList.toggle('checked', this.querySelector('.service-checkbox').checked);
            });
        });
        
        // Format budget input
        const budgetInput = document.querySelector('.budget-input');
        budgetInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/,/g, '');
            if (!isNaN(value) && value !== '') {
                e.target.value = parseInt(value).toLocaleString();
            }
        });
    </script>
</body>
</html>