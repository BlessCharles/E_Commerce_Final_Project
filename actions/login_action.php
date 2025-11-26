<?php
header('Content-Type: application/json');
session_start();

require_once "../controllers/user_controller.php";

try {
    $controller = new UserController();
    $result = $controller->login_ajax();
    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $e->getMessage()
    ]);
}
?>