<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanSmart Ghana - Plan with Confidence, Celebrate with Joy</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            background: #0f172a;
        }
        
        /* Animated Background */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #3b82f6 100%);
            z-index: -1;
        }
        
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        
        .shape {
            position: absolute;
            background: rgba(251, 191, 36, 0.1);
            border-radius: 50%;
            animation: float 20s infinite ease-in-out;
        }
        
        .shape:nth-child(1) {
            width: 300px;
            height: 300px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 200px;
            height: 200px;
            top: 60%;
            right: 15%;
            animation-delay: 5s;
        }
        
        .shape:nth-child(3) {
            width: 150px;
            height: 150px;
            bottom: 20%;
            left: 20%;
            animation-delay: 10s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0) scale(1);
                opacity: 0.3;
            }
            50% {
                transform: translateY(-30px) scale(1.1);
                opacity: 0.5;
            }
        }
        
        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            z-index: 100;
            border-bottom: 1px solid rgba(251, 191, 36, 0.2);
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: 0 4px 15px rgba(251, 191, 36, 0.4);
        }
        
        .logo-text {
            font-size: 26px;
            font-weight: 800;
            background: linear-gradient(135deg, #fbbf24, #ffffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav-buttons {
            display: flex;
            gap: 15px;
        }
        
        .btn-nav-login {
            padding: 12px 28px;
            background: transparent;
            border: 2px solid #fbbf24;
            color: #fbbf24;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-nav-login:hover {
            background: #fbbf24;
            color: #0f172a;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(251, 191, 36, 0.4);
        }
        
        .btn-nav-signup {
            padding: 12px 28px;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border: none;
            color: #0f172a;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(251, 191, 36, 0.4);
        }
        
        .btn-nav-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(251, 191, 36, 0.6);
        }
        
        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 60px 60px;
            position: relative;
        }
        
        .hero-content {
            max-width: 1200px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }
        
        .hero-text {
            color: white;
        }
        
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(251, 191, 36, 0.2);
            border: 1px solid rgba(251, 191, 36, 0.4);
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            color: #fbbf24;
            margin-bottom: 30px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        .hero-title {
            font-size: 64px;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 25px;
            background: linear-gradient(135deg, #ffffff, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-subtitle {
            font-size: 22px;
            line-height: 1.6;
            color: #cbd5e1;
            margin-bottom: 40px;
        }
        
        .hero-buttons {
            display: flex;
            gap: 20px;
            margin-bottom: 50px;
        }
        
        .btn-primary {
            padding: 20px 45px;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border: none;
            color: #0f172a;
            border-radius: 12px;
            font-size: 20px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 25px rgba(251, 191, 36, 0.4);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(251, 191, 36, 0.6);
        }
        
        .btn-secondary {
            padding: 20px 45px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 12px;
            font-size: 20px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: #fbbf24;
            transform: translateY(-3px);
        }
        
        .hero-stats {
            display: flex;
            gap: 40px;
        }
        
        .stat-item {
            text-align: left;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: 900;
            color: #fbbf24;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #94a3b8;
            font-weight: 500;
        }
        
        /* Hero Visual */
        .hero-visual {
            position: relative;
        }
        
        .mockup-container {
            position: relative;
            transform: perspective(1000px) rotateY(-5deg);
            transition: transform 0.5s;
        }
        
        .mockup-container:hover {
            transform: perspective(1000px) rotateY(0deg) scale(1.02);
        }
        
        .phone-mockup {
            width: 100%;
            max-width: 500px;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            border-radius: 40px;
            padding: 15px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
            position: relative;
        }
        
        .phone-screen {
            background: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .screen-content {
            padding: 30px;
            text-align: center;
        }
        
        .screen-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }
        
        .screen-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 10px;
        }
        
        .screen-text {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 20px;
        }
        
        .screen-cards {
            display: grid;
            gap: 15px;
            margin-top: 20px;
        }
        
        .feature-card {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            padding: 20px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            text-align: left;
        }
        
        .card-icon {
            font-size: 32px;
        }
        
        .card-text {
            flex: 1;
        }
        
        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 5px;
        }
        
        .card-desc {
            font-size: 13px;
            color: #64748b;
        }
        
        /* Floating Elements */
        .floating-badge {
            position: absolute;
            background: white;
            padding: 12px 20px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            font-weight: 600;
            animation: float-badge 3s infinite ease-in-out;
        }
        
        @keyframes float-badge {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-15px);
            }
        }
        
        .badge-1 {
            top: -30px;
            right: -50px;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: #0f172a;
            animation-delay: 0s;
        }
        
        .badge-2 {
            bottom: 50px;
            left: -80px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            animation-delay: 1s;
        }
        
        /* Features Section */
        .features {
            padding: 100px 60px;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(10px);
        }
        
        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 80px;
        }
        
        .section-title {
            font-size: 48px;
            font-weight: 900;
            color: white;
            margin-bottom: 20px;
        }
        
        .section-subtitle {
            font-size: 20px;
            color: #cbd5e1;
            line-height: 1.6;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .feature-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(251, 191, 36, 0.2);
            padding: 40px;
            border-radius: 20px;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }
        
        .feature-box:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: #fbbf24;
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(251, 191, 36, 0.2);
        }
        
        .feature-icon {
            font-size: 48px;
            margin-bottom: 25px;
        }
        
        .feature-title {
            font-size: 24px;
            font-weight: 700;
            color: white;
            margin-bottom: 15px;
        }
        
        .feature-desc {
            font-size: 16px;
            color: #cbd5e1;
            line-height: 1.6;
        }
        
        /* Social Proof */
        .social-proof {
            padding: 80px 60px;
            text-align: center;
        }
        
        .testimonial-container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.05);
            padding: 50px;
            border-radius: 20px;
            border: 1px solid rgba(251, 191, 36, 0.2);
        }
        
        .testimonial-text {
            font-size: 24px;
            font-style: italic;
            color: #cbd5e1;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }
        
        .author-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
        }
        
        .author-info {
            text-align: left;
        }
        
        .author-name {
            font-size: 18px;
            font-weight: 700;
            color: white;
        }
        
        .author-role {
            font-size: 14px;
            color: #94a3b8;
        }
        
        /* CTA Section */
        .cta-section {
            padding: 120px 60px;
            text-align: center;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            position: relative;
            overflow: hidden;
        }
        
        .cta-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .cta-title {
            font-size: 56px;
            font-weight: 900;
            color: white;
            margin-bottom: 25px;
        }
        
        .cta-subtitle {
            font-size: 22px;
            color: #cbd5e1;
            margin-bottom: 50px;
            line-height: 1.6;
        }
        
        .cta-button {
            padding: 25px 60px;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border: none;
            color: #0f172a;
            border-radius: 15px;
            font-size: 24px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(251, 191, 36, 0.4);
        }
        
        .cta-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(251, 191, 36, 0.6);
        }
        
        .cta-note {
            margin-top: 20px;
            font-size: 16px;
            color: #cbd5e1;
        }
        
        /* Footer */
        footer {
            background: rgba(15, 23, 42, 0.9);
            padding: 40px 60px;
            text-align: center;
            border-top: 1px solid rgba(251, 191, 36, 0.2);
        }
        
        .footer-text {
            color: #94a3b8;
            font-size: 14px;
        }
        
        @media (max-width: 1024px) {
            .hero-content {
                grid-template-columns: 1fr;
                gap: 50px;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .hero-title {
                font-size: 48px;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav>
        <div class="logo">
            <div class="logo-icon">🎉</div>
            <div class="logo-text">PlanSmart Ghana</div>
        </div>
        <div class="nav-buttons">
            <button class="btn-nav-login" onclick="window.location.href='view/login.php'">Login</button>
            <button class="btn-nav-signup" onclick="window.location.href='view/register.php'">Get Started Free</button>
            
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <div class="hero-badge">
                    ✨ Ghana's #1 Event Planning Platform
                </div>
                <h1 class="hero-title">Plan with Confidence, Celebrate with Joy</h1>
                <p class="hero-subtitle">
                    Transform how you plan weddings, funerals, and naming ceremonies. Connect with verified vendors, manage your budget, and collaborate with family, all in one beautiful platform.
                </p>
                <div class="hero-buttons">
                    <button class="btn-primary" onclick="window.location.href='view/register.php'">
                        Start Planning Free →
                    </button>
                    <button class="btn-secondary">
                        See How It Works
                    </button>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Verified Vendors</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">2,000+</div>
                        <div class="stat-label">Events Planned</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">4.8/5</div>
                        <div class="stat-label">Customer Rating</div>
                    </div>
                </div>
            </div>
            
            <div class="hero-visual">
                <div class="mockup-container">
                    <div class="phone-mockup">
                        <div class="phone-screen">
                            <div class="screen-content">
                                <div class="screen-icon">💰</div>
                                <div class="screen-title">Budget: Any Amount</div>
                                <div class="screen-text">Smart recommendations matched to your budget</div>
                                <div class="screen-cards">
                                    <div class="feature-card">
                                        <div class="card-icon">✓</div>
                                        <div class="card-text">
                                            <div class="card-title">Verified Vendors</div>
                                            <div class="card-desc">Trusted & rated</div>
                                        </div>
                                    </div>
                                    <div class="feature-card">
                                        <div class="card-icon">👨‍👩‍👧‍👦</div>
                                        <div class="card-text">
                                            <div class="card-title">Family Collaboration</div>
                                            <div class="card-desc">Plan together</div>
                                        </div>
                                    </div>
                                    <div class="feature-card">
                                        <div class="card-icon">🔒</div>
                                        <div class="card-text">
                                            <div class="card-title">Secure Payments</div>
                                            <div class="card-desc">Escrow protected</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="floating-badge badge-1">💸 Save 20-25%</div>
                    <div class="floating-badge badge-2">✓ Verified</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="features">
        <div class="section-header">
            <h2 class="section-title">Why Ghanaians Love PlanSmart</h2>
            <p class="section-subtitle">Everything you need to plan perfect ceremonies, all in one place</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-box">
                <div class="feature-icon">🎯</div>
                <h3 class="feature-title">Budget-Smart Matching</h3>
                <p class="feature-desc">Our intelligent algorithm recommends vendors that fit your exact budget. No surprises, no overspending.</p>
            </div>
            
            <div class="feature-box">
                <div class="feature-icon">👨‍👩‍👧‍👦</div>
                <h3 class="feature-title">Family Collaboration</h3>
                <p class="feature-desc">Invite relatives to view, vote, and contribute. No more chaotic WhatsApp groups!</p>
            </div>
            
            <div class="feature-box">
                <div class="feature-icon">✓</div>
                <h3 class="feature-title">Verified Vendors Only</h3>
                <p class="feature-desc">Every vendor is verified with reviews from real customers. Book with confidence.</p>
            </div>
            
            <div class="feature-box">
                <div class="feature-icon">💳</div>
                <h3 class="feature-title">Flexible Payments</h3>
                <p class="feature-desc">Pay via mobile money, or paystack. Funds held safely in escrow.</p>
            </div>
            
            <div class="feature-box">
                <div class="feature-icon">📊</div>
                <h3 class="feature-title">Price Comparison</h3>
                <p class="feature-desc">Compare vendors side-by-side. See reviews, portfolios, and exact prices, all transparent.</p>
            </div>
            
            <div class="feature-box">
                <div class="feature-icon">🇬🇭</div>
                <h3 class="feature-title">Culturally Tailored</h3>
                <p class="feature-desc">Built specifically for Ghanaian weddings, funerals, and naming ceremonies.</p>
            </div>
        </div>
    </section>
    
    <!-- Social Proof -->
    <section class="social-proof">
        <div class="testimonial-container">
            <p class="testimonial-text">
                "PlanSmart saved us from the stress and overspending we feared. My family was able to plan Mom's funeral together from different cities, and we stayed within our GHS 25,000 budget. Every vendor was professional and delivered exactly as promised."
            </p>
            <div class="testimonial-author">
                <div class="author-avatar">KM</div>
                <div class="author-info">
                    <div class="author-name">Kwame Mensah</div>
                    <div class="author-role">Planned funeral for 300 guests</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-content">
            <h2 class="cta-title">Ready to Plan Your Event?</h2>
            <p class="cta-subtitle">Join thousands of Ghanaians who've discovered a better way to celebrate life's most important moments.</p>
            
            <button class="cta-button" onclick="window.location.href='view/register.php'">Start Planning Free Today</button>
            <p class="cta-note">No credit card required • Takes 2 minutes • 100% Free to browse</p>
        </div>
    </section>
    
    <!-- Footer -->
    <footer>
        <p class="footer-text">© 2025 PlanSmart Ghana. Bridging tradition with technology.</p>
    </footer>
</body>
</html>