<?php

//Initialize Paystack Payment Transaction


header('Content-Type: application/json');

require_once '../settings/core.php';
require_once '../settings/paystack_config.php';

error_log("=== PAYSTACK INITIALIZE TRANSACTION ===");

// Check if user is logged in
if (!is_logged_in()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please login to complete payment'
    ]);
    exit();
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$amount = isset($input['amount']) ? floatval($input['amount']) : 0;
$customer_email = isset($input['email']) ? trim($input['email']) : '';
$event_id = isset($input['event_id']) ? intval($input['event_id']) : 0;
$payment_plan = isset($input['payment_plan']) ? $input['payment_plan'] : 'full';
$payment_method = isset($input['payment_method']) ? $input['payment_method'] : 'mobile_money';

if (!$amount || !$customer_email || !$event_id) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid payment data'
    ]);
    exit();
}

// Validate amount
if ($amount <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Amount must be greater than 0'
    ]);
    exit();
}

// Validate email
if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid email address'
    ]);
    exit();
}

try {
    // Generate unique reference
    $customer_id = get_user_id();
    $reference = 'PLAN-' . $event_id . '-' . $customer_id . '-' . time();
    
    error_log("Initializing transaction - Customer: $customer_id, Event: $event_id, Amount: $amount GHS, Email: $customer_email");
    error_log("Callback URL configured: " . PAYSTACK_CALLBACK_URL);
    
    // Initialize Paystack transaction (callback URL is already in the config)
    $paystack_response = paystack_initialize_transaction($amount, $customer_email, $reference);
    
    if (!$paystack_response) {
        throw new Exception("No response from Paystack API");
    }
    
    if (isset($paystack_response['status']) && $paystack_response['status'] === true) {
        // Store transaction reference in session for verification later
        $_SESSION['paystack_ref'] = $reference;
        $_SESSION['paystack_amount'] = $amount;
        $_SESSION['paystack_event_id'] = $event_id;
        $_SESSION['paystack_payment_plan'] = $payment_plan;
        $_SESSION['paystack_payment_method'] = $payment_method;
        $_SESSION['paystack_timestamp'] = time();
        
        error_log("Paystack transaction initialized successfully - Reference: $reference");
        error_log("Authorization URL: " . $paystack_response['data']['authorization_url']);
        
        echo json_encode([
            'status' => 'success',
            'authorization_url' => $paystack_response['data']['authorization_url'],
            'reference' => $reference,
            'access_code' => $paystack_response['data']['access_code'],
            'message' => 'Redirecting to payment gateway...'
        ]);
    } else {
        error_log("Paystack API error: " . json_encode($paystack_response));
        
        $error_message = $paystack_response['message'] ?? 'Payment gateway error';
        throw new Exception($error_message);
    }
    
} catch (Exception $e) {
    error_log("Error initializing Paystack transaction: " . $e->getMessage());
    
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to initialize payment: ' . $e->getMessage()
    ]);
}
?>