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
    
    $vendorObj = new Vendor();
    
    if ($vendorObj->approve_vendor($vendor_id, $admin_id)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Vendor approved successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to approve vendor'
        ]);
    }
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid request'
    ]);
}
?>