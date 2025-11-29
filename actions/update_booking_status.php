<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

require_once "../classes/vendor_class.php";

// Validate input
if (!isset($_POST['booking_id']) || !isset($_POST['status'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

$booking_id = intval($_POST['booking_id']);
$status = $_POST['status'];

// Validate status
$valid_statuses = ['confirmed', 'rejected', 'completed', 'cancelled'];
if (!in_array($status, $valid_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit();
}

// Update booking status
$vendorObj = new Vendor();
$result = $vendorObj->update_booking_status($booking_id, $status);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Booking status updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update booking status']);
}
?>