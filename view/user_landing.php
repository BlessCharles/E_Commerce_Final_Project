<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header('Location: login.php');
    exit();
}

$user_name = $_SESSION['first_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanSmart Ghana - Choose Your Event</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            overflow-x: hidden;
        }
        
        /* Navigation Bar */
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
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-greeting {
            font-size: 16px;
            color: #1e3a8a;
            font-weight: 600;
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
        
        /* Hero Section */
        .hero {
            text-align: center;
            padding: 60px 20px 40px;
            color: white;
        }
        
        .hero h1 {
            font-size: 56px;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .hero p {
            font-size: 20px;
            max-width: 700px;
            margin: 0 auto 40px;
            line-height: 1.6;
            opacity: 0.95;
        }
        
        /* Carousel Container */
        .carousel-container {
            position: relative;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 0 60px;
            height: 480px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .carousel-track {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Event Cards */
        .event-card {
            position: absolute;
            width: 380px;
            height: 420px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px 30px;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .event-card.center {
            z-index: 3;
            transform: scale(1) translateX(0);
            opacity: 1;
        }
        
        .event-card.left {
            z-index: 1;
            transform: scale(0.85) translateX(-450px) rotateY(10deg);
            opacity: 0.6;
            pointer-events: none;
        }
        
        .event-card.right {
            z-index: 1;
            transform: scale(0.85) translateX(450px) rotateY(-10deg);
            opacity: 0.6;
            pointer-events: none;
        }
        
        .event-card.hidden {
            z-index: 0;
            opacity: 0;
            pointer-events: none;
        }
        
        .card-icon {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            margin-bottom: 25px;
        }
        
        .wedding-icon {
            background: linear-gradient(135deg, #fce7f3, #fbcfe8);
        }
        
        .funeral-icon {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        }
        
        .naming-icon {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
        }
        
        .event-card h2 {
            font-size: 32px;
            color: #1e3a8a;
            margin-bottom: 15px;
        }
        
        .event-card p {
            font-size: 16px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        
        .card-features {
            list-style: none;
            text-align: left;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .card-features li {
            font-size: 14px;
            color: #475569;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .card-features li:before {
            content: "✓ ";
            color: #10b981;
            font-weight: bold;
            margin-right: 8px;
        }
        
        /* Carousel Navigation */
        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            background: white;
            border: none;
            border-radius: 50%;
            font-size: 24px;
            color: #1e3a8a;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.3s;
            z-index: 10;
        }
        
        .carousel-nav:hover {
            background: #fbbf24;
            transform: translateY(-50%) scale(1.1);
        }
        
        .nav-left {
            left: 100px;
        }
        
        .nav-right {
            right: 100px;
        }
        
        /* Indicators */
        .carousel-indicators {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 5;
        }
        
        .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255,255,255,0.4);
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .indicator.active {
            background: #fbbf24;
            width: 30px;
            border-radius: 6px;
        }
        
        @media (max-width: 1024px) {
            .carousel-nav {
                display: none;
            }
            
            .event-card.left,
            .event-card.right {
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
        <div class="user-menu">
            <span class="user-greeting">Welcome, <?php echo htmlspecialchars($user_name); ?>!</span>
            <button class="btn-logout btn btn-sm btn-danger" onclick="if(confirm('Are you sure you want to log out?')) { window.location.href='logout.php'; }">
    <i class="fas fa-sign-out-alt"></i> Logout
</button>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <div class="hero">
        <h1>What Would You Like to Plan?</h1>
        <p>Choose your event type below to get started with personalized vendor recommendations and budget planning.</p>
    </div>
    
    <!-- Carousel -->
    <div class="carousel-container">
        <button class="carousel-nav nav-left" onclick="prevSlide()">‹</button>
        
        <div class="carousel-track" id="carousel">
            <!-- Wedding Card -->
            <div class="event-card center" data-index="0" data-event="wedding">
                <div class="card-icon wedding-icon">💒</div>
                <h2>Weddings</h2>
                <p>Create your dream wedding within budget</p>
                <ul class="card-features">
                    <li>Verified vendors & venues</li>
                    <li>Budget-smart recommendations</li>
                    <li>Family collaboration tools</li>
                    <li>Secure mobile money payments</li>
                </ul>
            </div>
            
            <!-- Funeral Card -->
            <div class="event-card right" data-index="1" data-event="funeral">
                <div class="card-icon funeral-icon">🕊️</div>
                <h2>Funerals</h2>
                <p>Honor loved ones with dignity and peace of mind</p>
                <ul class="card-features">
                    <li>Culturally respectful planning</li>
                    <li>Transparent cost breakdown</li>
                    <li>Group contribution tracking</li>
                    <li>Emergency vendor support</li>
                </ul>
            </div>
            
            <!-- Naming Ceremony Card -->
            <div class="event-card hidden" data-index="2" data-event="naming">
                <div class="card-icon naming-icon">👶</div>
                <h2>Naming Ceremonies</h2>
                <p>Welcome new life with traditional celebrations</p>
                <ul class="card-features">
                    <li>Traditional ceremony guides</li>
                    <li>Small to large event options</li>
                    <li>Budget-friendly packages</li>
                    <li>Quick booking process</li>
                </ul>
            </div>
        </div>
        
        <button class="carousel-nav nav-right" onclick="nextSlide()">›</button>
        
        <!-- Indicators -->
        <div class="carousel-indicators">
            <div class="indicator active" onclick="goToSlide(0)"></div>
            <div class="indicator" onclick="goToSlide(1)"></div>
            <div class="indicator" onclick="goToSlide(2)"></div>
        </div>
    </div>
    
    <script>
        let currentIndex = 0;
        const cards = document.querySelectorAll('.event-card');
        const indicators = document.querySelectorAll('.indicator');
        const totalCards = cards.length;
        
        function updateCarousel() {
            cards.forEach((card, index) => {
                card.classList.remove('center', 'left', 'right', 'hidden');
                
                if (index === currentIndex) {
                    card.classList.add('center');
                } else if (index === (currentIndex - 1 + totalCards) % totalCards) {
                    card.classList.add('left');
                } else if (index === (currentIndex + 1) % totalCards) {
                    card.classList.add('right');
                } else {
                    card.classList.add('hidden');
                }
            });
            
            indicators.forEach((indicator, index) => {
                indicator.classList.toggle('active', index === currentIndex);
            });
        }
        
        function nextSlide() {
            currentIndex = (currentIndex + 1) % totalCards;
            updateCarousel();
        }
        
        function prevSlide() {
            currentIndex = (currentIndex - 1 + totalCards) % totalCards;
            updateCarousel();
        }
        
        function goToSlide(index) {
            currentIndex = index;
            updateCarousel();
        }
        
        // Click on center card to select event
        cards.forEach(card => {
            card.addEventListener('click', () => {
                if (card.classList.contains('center')) {
                    const eventType = card.dataset.event;
                    window.location.href = `../budget/budget_input.php?event=${eventType}`;
                }
            });
        });
        
        // Auto-rotate every 5 seconds
        setInterval(nextSlide, 5000);
    </script>
</body>
</html>