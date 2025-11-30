<?php
require_once '../settings/core.php';
require_once '../controllers/booking_controller.php';
require_once '../controllers/event_controller.php';

require_login('../view/login.php');

$user_id = get_user_id();
$user_name = get_user_name();

// Get event_id from URL or show all bookings
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

if ($event_id) {
    // Get specific event details
    $event = get_event_by_id_ctr($event_id);
    if (!$event || $event['user_id'] != $user_id) {
        header('Location: my_bookings.php?error=invalid_event');
        exit();
    }
    $bookings = get_event_bookings_with_vendors_ctr($event_id);
    $page_title = "Bookings for " . htmlspecialchars($event['event_name'] ?? $event['event_type']);
} else {
    // Show all user events with bookings
    $page_title = "All My Bookings";
    $bookings = []; // We'll get all bookings across all events
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - PlanSmart Ghana</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8fafc; }
        
        /* Navigation Bar */
        nav { display: flex; justify-content: space-between; align-items: center; padding: 20px 60px; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 100; }
        .logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .logo-icon { width: 40px; height: 40px; background: linear-gradient(135deg, #fbbf24, #f59e0b); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .logo-text { font-size: 24px; font-weight: bold; color: #1e3a8a; }
        
        .profile-wrapper { position: relative; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #1e3a8a); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; cursor: pointer; }
        .profile-dropdown { position: absolute; top: 50px; right: 0; width: 220px; background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.15); border-radius: 10px; padding: 10px; display: none; z-index: 10; }
        .dropdown-header { padding: 10px; border-bottom: 1px solid #e2e8f0; }
        .dropdown-item { display: block; padding: 12px; color: #1e3a8a; text-decoration: none; font-size: 14px; border-radius: 6px; }
        .dropdown-item:hover { background: #f1f5f9; }
        .dropdown-item.logout { color: red; font-weight: bold; }
        
        /* Main Content */
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        
        .page-header { margin-bottom: 40px; }
        .page-header h1 { font-size: 36px; color: #1e3a8a; margin-bottom: 10px; }
        .page-header p { font-size: 18px; color: #64748b; }
        
        .back-link { display: inline-flex; align-items: center; gap: 8px; color: #3b82f6; text-decoration: none; margin-bottom: 20px; font-size: 14px; }
        .back-link:hover { text-decoration: underline; }
        
        /* Event Info Card */
        .event-info-card { background: linear-gradient(135deg, #3b82f6, #1e3a8a); color: white; padding: 30px; border-radius: 16px; margin-bottom: 30px; }
        .event-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px; }
        .event-info-item { display: flex; flex-direction: column; gap: 5px; }
        .event-label { font-size: 12px; opacity: 0.8; text-transform: uppercase; }
        .event-value { font-size: 18px; font-weight: 600; }
        
        /* Status Filter */
        .filter-tabs { display: flex; gap: 10px; margin-bottom: 30px; flex-wrap: wrap; }
        .filter-tab { padding: 12px 24px; border: 2px solid #e2e8f0; border-radius: 10px; background: white; cursor: pointer; font-size: 14px; font-weight: 600; color: #64748b; transition: all 0.3s; }
        .filter-tab:hover { border-color: #fbbf24; background: #fffbeb; }
        .filter-tab.active { border-color: #fbbf24; background: #fbbf24; color: #1e3a8a; }
        
        /* Booking Cards */
        .bookings-grid { display: grid; gap: 20px; }
        
        .booking-card { background: white; border-radius: 16px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-left: 4px solid #e2e8f0; transition: all 0.3s; }
        .booking-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.1); transform: translateY(-2px); }
        
        .booking-card.pending { border-left-color: #fbbf24; }
        .booking-card.confirmed { border-left-color: #10b981; }
        .booking-card.completed { border-left-color: #3b82f6; }
        .booking-card.cancelled { border-left-color: #ef4444; }
        
        .booking-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; }
        .vendor-info h3 { font-size: 20px; color: #1e3a8a; margin-bottom: 5px; }
        .vendor-category { display: inline-flex; align-items: center; gap: 5px; color: #64748b; font-size: 14px; }
        
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; }
        .status-badge.pending { background: #fef3c7; color: #92400e; }
        .status-badge.confirmed { background: #d1fae5; color: #065f46; }
        .status-badge.completed { background: #dbeafe; color: #1e40af; }
        .status-badge.cancelled { background: #fee2e2; color: #991b1b; }
        
        .booking-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin: 20px 0; padding: 20px; background: #f8fafc; border-radius: 10px; }
        .detail-item { display: flex; flex-direction: column; gap: 5px; }
        .detail-label { font-size: 12px; color: #64748b; text-transform: uppercase; }
        .detail-value { font-size: 16px; font-weight: 600; color: #1e3a8a; }
        
        .vendor-contact { display: flex; gap: 15px; margin: 15px 0; }
        .contact-item { display: flex; align-items: center; gap: 8px; color: #64748b; font-size: 14px; }
        
        .booking-actions { display: flex; gap: 10px; margin-top: 20px; flex-wrap: wrap; }
        .btn { padding: 10px 20px; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary { background: #3b82f6; color: white; }
        .btn-primary:hover { background: #2563eb; }
        .btn-success { background: #10b981; color: white; }
        .btn-success:hover { background: #059669; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-danger:hover { background: #dc2626; }
        .btn-secondary { background: #e5e7eb; color: #374151; }
        .btn-secondary:hover { background: #d1d5db; }
        
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state-icon { font-size: 80px; margin-bottom: 20px; opacity: 0.3; }
        .empty-state h3 { font-size: 24px; color: #1e3a8a; margin-bottom: 10px; }
        .empty-state p { color: #64748b; margin-bottom: 30px; }
        
        .alert { padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-info { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        
        /* Payment Status */
        .payment-status { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: 700; margin-left: 10px; }
        .payment-status.paid { background: #d1fae5; color: #065f46; }
        .payment-status.unpaid { background: #fee2e2; color: #991b1b; }
        .payment-status.partial { background: #fef3c7; color: #92400e; }
        
        /* Timeline */
        .booking-timeline { margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb; }
        .timeline-item { display: flex; gap: 15px; margin-bottom: 15px; font-size: 13px; color: #64748b; }
        .timeline-icon { width: 30px; height: 30px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <a href="dashboard.php" class="logo">
            <div class="logo-icon">🎉</div>
            <div class="logo-text">PlanSmart Ghana</div>
        </a>
        <div class="profile-wrapper">
            <div class="user-avatar" id="profileBtn">👤</div>
            <div class="profile-dropdown" id="profileDropdown">
                <div class="dropdown-header">
                    <strong><?php echo htmlspecialchars($user_name); ?></strong>
                </div>
                <a href="dashboard.php" class="dropdown-item">📊 Dashboard</a>
                <a href="my_bookings.php" class="dropdown-item">📋 My Bookings</a>
                <a href="budget_input.php" class="dropdown-item">➕ Plan New Event</a>
                <a href="profile.php" class="dropdown-item">⚙️ Settings</a>
                <a href="../view/logout.php" class="dropdown-item logout" onclick="event.preventDefault(); if (confirm('Are you sure you want to log out?')) {window.location.href='../view/logout.php'; }">🚪 Logout</a>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="container">
        <?php if ($event_id): ?>
            <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
        <?php endif; ?>
        
        <div class="page-header">
            <h1><?php echo $page_title; ?></h1>
            <p>Track vendor responses and manage your bookings</p>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                ✓ Booking confirmed! Your vendors have been notified.
            </div>
        <?php endif; ?>
        
        <?php if ($event_id && $event): ?>
            <!-- Event Info Card -->
            <div class="event-info-card">
                <h2><?php echo htmlspecialchars($event['event_name'] ?? ucfirst($event['event_type']) . ' Event'); ?></h2>
                <div class="event-info-grid">
                    <div class="event-info-item">
                        <span class="event-label">Event Type</span>
                        <span class="event-value"><?php echo ucfirst($event['event_type']); ?></span>
                    </div>
                    <div class="event-info-item">
                        <span class="event-label">Event Date</span>
                        <span class="event-value"><?php echo date('M j, Y', strtotime($event['event_date'])); ?></span>
                    </div>
                    <div class="event-info-item">
                        <span class="event-label">Location</span>
                        <span class="event-value"><?php echo htmlspecialchars($event['location']); ?></span>
                    </div>
                    <div class="event-info-item">
                        <span class="event-label">Guests</span>
                        <span class="event-value"><?php echo number_format($event['guest_count']); ?></span>
                    </div>
                    <div class="event-info-item">
                        <span class="event-label">Budget</span>
                        <span class="event-value">GHS <?php echo number_format($event['total_budget'], 2); ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Status Filter -->
        <div class="filter-tabs">
            <button class="filter-tab active" data-status="all">All Bookings</button>
            <button class="filter-tab" data-status="pending">⏳ Pending</button>
            <button class="filter-tab" data-status="confirmed">✓ Confirmed</button>
            <button class="filter-tab" data-status="completed">✓ Completed</button>
            <button class="filter-tab" data-status="cancelled">✗ Cancelled</button>
        </div>
        
        <?php if (empty($bookings)): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <h3>No Bookings Yet</h3>
                <p>Start planning your event and book vendors to see them here</p>
                <a href="budget_input.php" class="btn btn-primary">Plan Your Event</a>
            </div>
        <?php else: ?>
            <!-- Bookings Grid -->
            <div class="bookings-grid">
                <?php foreach ($bookings as $booking): 
                    // Get actual status from database - column should be 'status' not 'booking_status'
                    $status = strtolower($booking['status'] ?? 'pending');
                    $payment_status = $booking['payment_status'] ?? 'unpaid';
                    
                    $category_icons = [
                        'catering' => '🍽️',
                        'venue' => '🏛️',
                        'rental' => '⛺',
                        'photography' => '📸',
                        'decoration' => '🎨',
                        'music' => '🔊',
                        'transportation' => '🚌',
                        'makeup' => '💄'
                    ];
                    $icon = $category_icons[$booking['category']] ?? '🎯';
                ?>
                <div class="booking-card <?php echo $status; ?>" data-status="<?php echo $status; ?>">
                    <div class="booking-header">
                        <div class="vendor-info">
                            <h3><?php echo htmlspecialchars($booking['business_name']); ?></h3>
                            <div class="vendor-category">
                                <span><?php echo $icon; ?></span>
                                <span><?php echo ucfirst($booking['category']); ?></span>
                                <?php if ($booking['is_verified'] ?? false): ?>
                                    <span style="color: #10b981;">✓ Verified</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <span class="status-badge <?php echo $status; ?>">
                                <?php 
                                    $status_text = [
                                        'pending' => '⏳ Pending',
                                        'confirmed' => '✓ Confirmed',
                                        'completed' => '✓ Completed',
                                        'cancelled' => '✗ Cancelled'
                                    ];
                                    echo $status_text[$status] ?? ucfirst($status);
                                ?>
                            </span>
                            <span class="payment-status <?php echo $payment_status; ?>">
                                <?php echo $payment_status === 'paid' ? '💳 Paid' : '⏳ ' . ucfirst($payment_status); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="booking-details">
                        <div class="detail-item">
                            <span class="detail-label">Booking Date</span>
                            <span class="detail-value"><?php echo date('M j, Y', strtotime($booking['booking_date'])); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Amount</span>
                            <span class="detail-value">GHS <?php echo number_format($booking['amount'], 2); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Rating</span>
                            <span class="detail-value">
                                ⭐ <?php echo number_format($booking['rating'] ?? 0, 1); ?> 
                                (<?php echo $booking['total_reviews'] ?? 0; ?> reviews)
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Location</span>
                            <span class="detail-value"><?php echo htmlspecialchars($booking['location'] ?? 'N/A'); ?></span>
                        </div>
                    </div>
                    
                    <div class="vendor-contact">
                        <div class="contact-item">
                            <span>📞</span>
                            <span><?php echo htmlspecialchars($booking['phone'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="contact-item">
                            <span>📧</span>
                            <span><?php echo htmlspecialchars($booking['email'] ?? 'N/A'); ?></span>
                        </div>
                    </div>
                    
                    <?php if ($status === 'pending'): ?>
                        <div class="alert alert-warning">
                            ⏳ Waiting for vendor to accept this booking. The vendor will be notified and should respond soon.
                        </div>
                    <?php elseif ($status === 'confirmed'): ?>
                        <div class="alert alert-success">
                            ✓ Vendor has confirmed! They will contact you soon to finalize service details.
                        </div>
                    <?php elseif ($status === 'cancelled'): ?>
                        <div class="alert alert-warning">
                            ✗ This booking has been cancelled.
                        </div>
                    <?php elseif ($status === 'completed'): ?>
                        <div class="alert alert-info">
                            ✓ Service completed! Please consider leaving a review.
                        </div>
                    <?php endif; ?>
                    
                    <div class="booking-timeline">
                        <div class="timeline-item">
                            <div class="timeline-icon">✓</div>
                            <div>
                                <strong>Booking Created</strong><br>
                                <?php echo date('M j, Y g:i A', strtotime($booking['created_at'])); ?>
                            </div>
                        </div>
                        <?php if ($payment_status === 'paid'): ?>
                        <div class="timeline-item">
                            <div class="timeline-icon">💳</div>
                            <div>
                                <strong>Payment Confirmed</strong><br>
                                Payment received and secured in escrow
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($status === 'confirmed'): ?>
                        <div class="timeline-item">
                            <div class="timeline-icon">✓</div>
                            <div>
                                <strong>Vendor Confirmed</strong><br>
                                Service confirmed and scheduled
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($status === 'completed'): ?>
                        <div class="timeline-item">
                            <div class="timeline-icon">🎉</div>
                            <div>
                                <strong>Service Completed</strong><br>
                                Event service successfully delivered
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="booking-actions">
                        <?php if ($status !== 'cancelled'): ?>
                        <button class="btn btn-secondary" onclick="alert('Email feature coming soon!')">
                            📧 Email Vendor
                        </button>
                        <?php endif; ?>
                        
                        <?php if ($status === 'confirmed' || $status === 'completed'): ?>
                        <button class="btn btn-success" onclick="alert('Review feature coming soon!')">
                            ⭐ Leave Review
                        </button>
                        <?php endif; ?>
                        
                        <?php if ($status === 'pending'): ?>
                        <button class="btn btn-danger" onclick="cancelBooking(<?php echo $booking['booking_id']; ?>)">
                            ✗ Cancel Booking
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Profile dropdown
        document.getElementById("profileBtn").addEventListener("click", () => {
            const menu = document.getElementById("profileDropdown");
            menu.style.display = (menu.style.display === "block") ? "none" : "block";
        });

        document.addEventListener("click", function (e) {
            const dropdown = document.getElementById("profileDropdown");
            const button = document.getElementById("profileBtn");
            if (!dropdown.contains(e.target) && !button.contains(e.target)) {
                dropdown.style.display = "none";
            }
        });

        // Filter tabs
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Update active tab
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                const status = this.dataset.status;
                
                // Filter booking cards
                document.querySelectorAll('.booking-card').forEach(card => {
                    if (status === 'all' || card.dataset.status === status) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Cancel booking
        function cancelBooking(bookingId) {
            if (!confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
                return;
            }

            fetch('../actions/cancel_booking_action.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ booking_id: bookingId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Booking cancelled successfully');
                    location.reload();
                } else {
                    alert(data.message || 'Failed to cancel booking');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
</body>
</html>