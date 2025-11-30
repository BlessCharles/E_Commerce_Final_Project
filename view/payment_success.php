<?php
require_once '../settings/core.php';
require_once '../controllers/booking_controller.php';

require_login('../view/login.php');

$customer_id = get_user_id();
$booking_ref = isset($_GET['booking_ref']) ? htmlspecialchars($_GET['booking_ref']) : '';
$reference = isset($_GET['reference']) ? htmlspecialchars($_GET['reference']) : '';
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

// Get bookings
$bookings = [];
if ($event_id) {
    $bookings = get_event_bookings_with_vendors_ctr($event_id);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - PlanSmart Ghana</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #ffffff; }
        
        .navbar { background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%); padding: 20px 0; box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05); }
        .nav-container { max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 0 40px; }
        .logo { font-family: 'Cormorant Garamond', serif; font-size: 28px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-decoration: none; }
        
        .container { max-width: 900px; margin: 60px auto; padding: 0 20px; }
        
        .success-box { 
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); 
            border: 2px solid #6ee7b7; 
            border-radius: 20px; 
            padding: 50px 40px; 
            text-align: center;
        }
        
        .success-icon { 
            font-size: 80px; 
            margin-bottom: 20px; 
            animation: bounce 1s ease-in-out;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        h1 { 
            font-family: 'Cormorant Garamond', serif; 
            font-size: 3rem; 
            color: #065f46; 
            margin-bottom: 10px; 
        }
        
        .subtitle { 
            font-size: 18px; 
            color: #047857; 
            margin-bottom: 30px; 
        }
        
        .order-details { 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            margin: 30px 0; 
            text-align: left;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        
        .detail-row { 
            display: flex; 
            justify-content: space-between; 
            padding: 12px 0; 
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
        }
        
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-weight: 600; }
        .detail-value { color: #6b7280; word-break: break-all; }
        
        .btn { 
            padding: 16px 40px; 
            border: none; 
            border-radius: 50px; 
            font-size: 16px; 
            font-weight: 600; 
            cursor: pointer; 
            transition: all 0.4s ease; 
            text-decoration: none; 
            display: inline-block;
            margin: 0 10px;
        }
        
        .btn-primary { 
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); 
            color: #1e3a8a; 
            box-shadow: 0 8px 25px rgba(251, 191, 36, 0.3); 
        }
        
        .btn-primary:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 12px 35px rgba(251, 191, 36, 0.4); 
        }
        
        .btn-secondary { 
            background: white; 
            color: #374151; 
            border: 2px solid #e5e7eb; 
        }
        
        .btn-secondary:hover { background: #f9fafb; }
        
        .btn-bookings {
            background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%); 
            color: white; 
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }
        
        .btn-bookings:hover {
            transform: translateY(-2px); 
            box-shadow: 0 12px 35px rgba(59, 130, 246, 0.4);
        }
        
        .buttons-container { 
            display: flex; 
            justify-content: center; 
            margin-top: 40px; 
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .confirmation-message { 
            background: #eff6ff; 
            border: 2px solid #3b82f6; 
            padding: 20px; 
            border-radius: 12px; 
            color: #1e40af;
            margin-bottom: 20px;
        }

        .vendors-list {
            background: #f9fafb;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: left;
        }

        .vendor-item {
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .vendor-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="../index.php" class="logo">PlanSmart Ghana</a>
            <div style="display: flex; gap: 20px;">
                <a href="dashboard.php" style="color: #374151; text-decoration: none;">Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="success-box">
            <div class="success-icon">🎉</div>
            <h1>Booking Successful!</h1>
            <p class="subtitle">Your payment has been processed successfully</p>
            
            <div class="confirmation-message">
                <strong>✓ Payment Confirmed</strong><br>
                Thank you for your booking! Your vendors have been notified and will contact you shortly to confirm service details.
            </div>
            
            <div class="order-details">
                <div class="detail-row">
                    <span class="detail-label">Booking Reference</span>
                    <span class="detail-value"><?php echo $booking_ref; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Reference</span>
                    <span class="detail-value"><?php echo $reference; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Booking Date</span>
                    <span class="detail-value"><?php echo date('F j, Y'); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" style="color: #059669; font-weight: 600;">Confirmed ✓</span>
                </div>
            </div>

            <?php if (!empty($bookings)): ?>
            <div class="vendors-list">
                <h3 style="margin-bottom: 15px; color: #1e3a8a;">Confirmed Vendors</h3>
                <?php foreach ($bookings as $booking): ?>
                <div class="vendor-item">
                    <strong><?php echo htmlspecialchars($booking['business_name']); ?></strong> - 
                    <?php echo ucfirst($booking['category']); ?> - 
                    GHS <?php echo number_format($booking['amount'], 2); ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <div class="buttons-container">
                <a href="my_bookings.php<?php echo $event_id ? '?event_id=' . $event_id : ''; ?>" class="btn btn-bookings">
                    📋 View My Bookings
                </a>
                <a href="dashboard.php" class="btn btn-primary">
                    📊 Go to Dashboard
                </a>
                <a href="budget_input.php" class="btn btn-secondary">
                    ➕ Plan Another Event
                </a>
            </div>
        </div>
    </div>
</body>
</html>