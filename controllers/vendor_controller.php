<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

require_once "../classes/db_class.php";

$db = new db_connection();
$conn = $db->db_conn();  // returns mysqli connection

$user_id = $_SESSION['user_id'];

// FETCH VENDOR DETAILS
$sql = "SELECT * FROM vendors WHERE user_id = $user_id LIMIT 1";
$result = mysqli_query($conn, $sql);
$vendor = mysqli_fetch_assoc($result);

// If vendor exists, extract business name
$business_name = $vendor ? $vendor['business_name'] : "Your Business";

$user_name = $_SESSION['first_name'] . " " . $_SESSION['last_name'];

// pass variables to view
include "../view/vendor_dash.php";
