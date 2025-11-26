<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanSmart Ghana - Sign Up</title>
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
            cursor: pointer;
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
        
        .nav-link {
            color: #1e3a8a;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            color: #3b82f6;
        }
        
        .container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        
        .signup-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 50px;
            width: 100%;
            max-width: 520px;
        }
        
        .signup-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .signup-header h1 {
            font-size: 32px;
            color: #1e3a8a;
            margin-bottom: 10px;
        }
        
        .signup-header p {
            font-size: 16px;
            color: #64748b;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .user-type-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
        }
        
        .user-type-label {
            display: block;
            font-size: 15px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .user-type-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .radio-card {
            position: relative;
            cursor: pointer;
        }
        
        .radio-card input[type="radio"] {
            position: absolute;
            opacity: 0;
        }
        
        .radio-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 15px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .radio-card input[type="radio"]:checked + .radio-label {
            border-color: #fbbf24;
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.2);
        }
        
        .radio-label:hover {
            border-color: #cbd5e1;
            transform: translateY(-2px);
        }
        
        .radio-icon {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .radio-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e3a8a;
            margin-bottom: 5px;
        }
        
        .radio-description {
            font-size: 12px;
            color: #64748b;
            text-align: center;
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
        
        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .terms-group {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 30px;
        }
        
        .terms-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            cursor: pointer;
            flex-shrink: 0;
        }
        
        .terms-group label {
            font-size: 14px;
            color: #475569;
            line-height: 1.5;
        }
        
        .terms-group a {
            color: #3b82f6;
            text-decoration: none;
        }
        
        .terms-group a:hover {
            text-decoration: underline;
        }
        
        .btn-signup {
            width: 100%;
            padding: 16px;
            background: #fbbf24;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 700;
            color: #1e3a8a;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
        }
        
        .btn-signup:hover {
            background: #f59e0b;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(251, 191, 36, 0.4);
        }
        
        .btn-signup:disabled {
            background: #d1d5db;
            cursor: not-allowed;
            transform: none;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
            color: #94a3b8;
            font-size: 14px;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }
        
        .divider span {
            padding: 0 15px;
        }
        
        .social-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .btn-social {
            flex: 1;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #475569;
        }
        
        .btn-social:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
            transform: translateY(-2px);
        }
        
        .login-link {
            text-align: center;
            font-size: 15px;
            color: #64748b;
        }
        
        .login-link a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            nav {
                padding: 15px 20px;
            }
            
            .signup-card {
                padding: 30px 25px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">
            <div class="logo-icon">🎉</div>
            <div class="logo-text">PlanSmart Ghana</div>
        </div>
        <a href="../index.php" class="nav-link">← Back to Home</a>
    </nav>
    
    <div class="container">
        <div class="signup-card">
            <div class="signup-header">
                <h1>Create Account</h1>
                <p>Start planning your perfect event today</p>
            </div>
            
            <form id="register-form" action="../actions/register_action.php" method="POST">
                <!-- User Type Selection -->
                <div class="user-type-section">
                    <label class="user-type-label">I am a:</label>
                    <div class="user-type-options">
                        <div class="radio-card">
                            <input type="radio" id="customer" name="user_type" value="customer" required checked>
                            <label for="customer" class="radio-label">
                                <span class="radio-icon">👤</span>
                                <span class="radio-title">Customer</span>
                                <span class="radio-description">Planning an event</span>
                            </label>
                        </div>
                        
                        <div class="radio-card">
                            <input type="radio" id="vendor" name="user_type" value="vendor" required>
                            <label for="vendor" class="radio-label">
                                <span class="radio-icon">🏪</span>
                                <span class="radio-title">Vendor</span>
                                <span class="radio-description">Providing services</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Name Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            class="form-input"
                            placeholder="Kwame"
                            required
                        >
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            class="form-input"
                            placeholder="Mensah"
                            required
                        >
                    </div>
                </div>
                
                <!-- Email -->
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        placeholder="kwame@example.com"
                        required
                    >
                </div>
                
                <!-- Phone -->
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        class="form-input"
                        placeholder="024 123 4567"
                        required
                    >
                </div>
                
                <!-- Password -->
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="At least 8 characters"
                        required
                        minlength="8"
                    >
                </div>
                
                <!-- Confirm Password -->
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        class="form-input"
                        placeholder="Re-enter your password"
                        required
                        minlength="8"
                    >
                </div>
                
                <!-- Terms -->
                <div class="terms-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">
                        I agree to PlanSmart Ghana's <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                    </label>
                </div>
                
                <!-- Sign Up Button -->
                <button type="submit" class="btn-signup">Create Account</button>
            </form>
            
            
            
            <!-- Login Link -->
            <div class="login-link">
                Already have an account? <a href="login.php">Log In</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/register.js"></script>
</body>
</html>