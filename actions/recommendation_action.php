<?php
header('Content-Type: application/json');
require_once '../controllers/recommendation_controller.php';

$action = $_GET['action'] ?? '';
$controller = new RecommendationController();

switch($action) {
    case 'getRecommendations':
        $result = $controller->get_recommendations_ajax();
        echo json_encode($result);
        break;

    case 'browseCategory':
        $result = $controller->browse_category_ajax();
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