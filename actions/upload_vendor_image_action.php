<?php
header('Content-Type: application/json');
require_once '../settings/core.php';

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Validate file

if (!isset($_FILES['vendor_image']) || $_FILES['vendor_image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded or upload error']);
    exit();
}

// Ensure uploads folder exists
$upload_base = '../uploads';
if (!file_exists($upload_base)) {
    mkdir($upload_base, 0755, true);
}

// Allowed file types
$allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
$file_type = $_FILES['vendor_image']['type'];

if (!in_array($file_type, $allowed_types)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
    exit();
}

// Set final filename (each vendor gets ONE image)
$extension = pathinfo($_FILES['vendor_image']['name'], PATHINFO_EXTENSION);
$filename = 'vendor_' . $user_id . '.' . $extension;
$target_path = $upload_base . '/' . $filename;

// Move uploaded file
if (move_uploaded_file($_FILES['vendor_image']['tmp_name'], $target_path)) {
    // Return path to save in database
    echo json_encode([
        'status' => 'success',
        'message' => 'Image uploaded',
        'path' => 'uploads/' . $filename
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save file']);
}
