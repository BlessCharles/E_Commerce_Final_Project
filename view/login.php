
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanSmart Ghana - Login</title>
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
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 50px;
            width: 100%;
            max-width: 480px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .login-header h1 {
            font-size: 32px;
            color: #1e3a8a;
            margin-bottom: 10px;
        }
        
        .login-header p {
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
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .form-group {
            margin-bottom: 25px;
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
        
        .form-input.error {
            border-color: #ef4444;
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .checkbox-group label {
            font-size: 14px;
            color: #475569;
            cursor: pointer;
        }
        
        .forgot-link {
            font-size: 14px;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }
        
        .forgot-link:hover {
            text-decoration: underline;
        }
        
        .btn-login {
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
        
        .btn-login:hover {
            background: #f59e0b;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(251, 191, 36, 0.4);
        }
        
        .btn-login:disabled {
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
        
        .signup-link {
            text-align: center;
            font-size: 15px;
            color: #64748b;
        }
        
        .signup-link a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
        }
        
        .signup-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            nav {
                padding: 15px 20px;
            }
            
            .login-card {
                padding: 30px 25px;
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
        <div class="login-card">
            <div class="login-header">
                <h1>Welcome Back!</h1>
                <p>Log in to continue planning your event</p>
            </div>
            
            <form id="login-form">
                <!-- Email -->
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        placeholder="you@example.com"
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
                        placeholder="Enter your password"
                        required
                    >
                </div>
                
                <!-- Remember & Forgot -->
                <div class="form-options">
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>
                
                <!-- Login Button -->
                <button type="submit" class="btn-login">Log In</button>
            </form>
            
            
            
            <!-- Sign Up Link -->
            <div class="signup-link">
                Don't have an account? <a href="register.php">Sign Up Free</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/login.js"></script>
</body>
</html>