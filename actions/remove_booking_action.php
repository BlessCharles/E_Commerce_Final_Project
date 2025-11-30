<?php
header('Content-Type: application/json');

require_once '../settings/core.php';
require_once '../controllers/booking_controller.php';

// Check if user is logged in
if (!is_logged_in()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please login to perform this action'
    ]);
    exit();
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$booking_id = isset($input['booking_id']) ? intval($input['booking_id']) : 0;
$event_id = isset($input['event_id']) ? intval($input['event_id']) : 0;

if (!$booking_id || !$event_id) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid booking data'
    ]);
    exit();
}

try {
    // Verify booking belongs to user's event
    $booking = get_booking_by_id_ctr($booking_id);
    
    if (!$booking) {
        throw new Exception("Booking not found");
    }
    
    if ($booking['event_id'] != $event_id) {
        throw new Exception("Unauthorized access");
    }
    
    // Delete the booking
    $result = delete_booking_ctr($booking_id);
    
    if ($result) {
        error_log("Booking removed - ID: $booking_id, Event: $event_id");
        
        // Check if there are any remaining bookings
        $remaining = get_event_bookings_with_vendors_ctr($event_id);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Vendor removed successfully',
            'remaining_count' => count($remaining)
        ]);
    } else {
        throw new Exception("Failed to remove booking");
    }
    
} catch (Exception $e) {
    error_log("Error removing booking: " . $e->getMessage());
    
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>