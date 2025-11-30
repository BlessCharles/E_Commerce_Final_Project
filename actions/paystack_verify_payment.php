<?php

//Verify Paystack Payment


header('Content-Type: application/json');

require_once '../settings/core.php';
require_once '../settings/paystack_config.php';
require_once '../controllers/booking_controller.php';

error_log("=== PAYSTACK VERIFY PAYMENT ===");

// Check if user is logged in
if (!is_logged_in()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please login to verify payment'
    ]);
    exit();
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$reference = isset($input['reference']) ? trim($input['reference']) : '';

if (!$reference) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Payment reference is required'
    ]);
    exit();
}

error_log("Verifying payment - Reference: $reference");

try {
    // Verify transaction with Paystack
    $verification = paystack_verify_transaction($reference);
    
    if (!$verification) {
        throw new Exception("No response from Paystack");
    }
    
    error_log("Paystack verification response: " . json_encode($verification));
    
    // Check if verification was successful
    if (!isset($verification['status']) || $verification['status'] !== true) {
        throw new Exception($verification['message'] ?? 'Verification failed');
    }
    
    // Get transaction data
    $transaction = $verification['data'];
    
    // Verify transaction status
    if ($transaction['status'] !== 'success') {
        throw new Exception("Payment was not successful. Status: " . $transaction['status']);
    }
    
    // Get session data
    $event_id = isset($_SESSION['paystack_event_id']) ? $_SESSION['paystack_event_id'] : 0;
    $expected_amount = isset($_SESSION['paystack_amount']) ? $_SESSION['paystack_amount'] : 0;
    $payment_plan = isset($_SESSION['paystack_payment_plan']) ? $_SESSION['paystack_payment_plan'] : 'full';
    $payment_method = isset($_SESSION['paystack_payment_method']) ? $_SESSION['paystack_payment_method'] : 'mobile_money';
    
    if (!$event_id) {
        throw new Exception("Event ID not found in session");
    }
    
    // Verify amount (convert from pesewas to GHS)
    $paid_amount = $transaction['amount'] / 100;
    
    if (abs($paid_amount - $expected_amount) > 0.01) {
        error_log("Amount mismatch - Expected: $expected_amount, Paid: $paid_amount");
        throw new Exception("Payment amount mismatch");
    }
    
    error_log("Payment verified - Amount: GHS $paid_amount, Event: $event_id");
    
    // Mark all bookings for this event as paid
    $update_result = mark_event_bookings_paid_ctr($event_id);
    
    if (!$update_result) {
        error_log("WARNING: Failed to update booking statuses for event $event_id");
    } else {
        error_log("All bookings for event $event_id marked as paid");
    }
    
    // Generate booking reference
    $booking_reference = 'BK-' . $event_id . '-' . time();
    
    // Store in session for success page
    $_SESSION['payment_verified'] = true;
    $_SESSION['booking_reference'] = $booking_reference;
    
    // Clear payment session data
    unset($_SESSION['paystack_ref']);
    unset($_SESSION['paystack_amount']);
    unset($_SESSION['paystack_event_id']);
    unset($_SESSION['paystack_payment_plan']);
    unset($_SESSION['paystack_payment_method']);
    unset($_SESSION['paystack_timestamp']);
    
    echo json_encode([
        'status' => 'success',
        'verified' => true,
        'message' => 'Payment verified successfully',
        'event_id' => $event_id,
        'booking_reference' => $booking_reference,
        'amount_paid' => $paid_amount,
        'payment_reference' => $reference,
        'payment_plan' => $payment_plan,
        'payment_method' => $payment_method
    ]);
    
} catch (Exception $e) {
    error_log("Payment verification error: " . $e->getMessage());
    
    echo json_encode([
        'status' => 'error',
        'verified' => false,
        'message' => $e->getMessage()
    ]);
}
?>