<?php
header('Content-Type: application/json');
require_once '../controllers/collab_controller.php';

$action = $_GET['action'] ?? '';
$controller = new CollabController();

switch($action) {
    case 'getCollabData':
        $result = $controller->get_collab_data_ajax();
        echo json_encode($result);
        break;

    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid action'
        ]);
        break;
}
?>