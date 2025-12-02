<?php
session_start();
require_once "../classes/vendor_class.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$vendor = new Vendor();
$user_id = $_SESSION['user_id'];

// Collect form data
$data = [
    'business_name' => $_POST['business_name'],
    'business_description' => $_POST['business_description'],
    'category' => $_POST['category'],
    'years_experience' => $_POST['years_experience'],
    'location' => $_POST['location'],
    'address' => $_POST['address'],
    'starting_price' => $_POST['starting_price'],
    'price_range' => $_POST['price_range'],
];

// Handle image upload
$image_path = null;

if (isset($_FILES['vendor_image']) && $_FILES['vendor_image']['error'] === UPLOAD_ERR_OK) {
    // Create uploads directory if it doesn't exist
    $upload_dir = '../uploads';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Get file extension
    $extension = pathinfo($_FILES['vendor_image']['name'], PATHINFO_EXTENSION);
    
    // Create filename
    $filename = 'vendor_' . $user_id . '.' . $extension;
    $target_path = $upload_dir . '/' . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($_FILES['vendor_image']['tmp_name'], $target_path)) {
        // Store relative path for database (without ../)
        $image_path = 'uploads/' . $filename;
    }
}

// Save vendor profile with image path and handle SQL errors
try {
    $vendor_id = $vendor->save_vendor_profile($user_id, $data, $image_path);

    if (empty($vendor_id)) {
        throw new Exception('Failed to obtain vendor id after save.');
    }

    // Save vendor service checkboxes
    $services = isset($_POST['events']) ? $_POST['events'] : [];
    $vendor->save_vendor_services($vendor_id, $services);

    // On success redirect
    header("Location: ../view/vendor_dash.php");
    exit();

} catch (Exception $e) {
    // Return visible error JSON to browser for debugging (no file logs)
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to save vendor profile',
        'error' => $e->getMessage(),
        'errno' => $e->getCode()
    ]);
    exit();
}