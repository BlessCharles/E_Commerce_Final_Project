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
// Process image upload FIRST
$image_path = null;

if (isset($_FILES['vendor_image']) && $_FILES['vendor_image']['error'] === UPLOAD_ERR_OK) {
    // handle upload
    $image_path = 'uploads/vendor_' . $user_id . '.' . $extension; 
}

// Now save vendor
$vendor_id = $vendor->save_vendor_profile($user_id, $_POST, $image_path);
// Save vendor service checkboxes
$services = isset($_POST['events']) ? $_POST['events'] : [];
$vendor->save_vendor_services($vendor_id, $services);


header("Location: ../view/vendor_dash.php");
exit();
