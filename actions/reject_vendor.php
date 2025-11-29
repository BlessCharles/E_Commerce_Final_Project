<?php
session_start();
header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

require_once "../classes/vendor_class.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vendor_id'])) {
    $vendor_id = intval($_POST['vendor_id']);
    $admin_id = $_SESSION['user_id'];
    $reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
    
    $vendorObj = new Vendor();
    
    if ($vendorObj->reject_vendor($vendor_id, $admin_id, $reason)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Vendor rejected successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to reject vendor'
        ]);
    }
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid request'
    ]);
}
?>