<?php
session_start();
require_once "../classes/budget_class.php";

$action = $_GET['action'] ?? '';

switch($action){

    case "createEvent":
        createEvent();
        break;

    default:
        echo "Invalid action";
        break;
}

function createEvent(){
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../views/login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Get POST values
    $budget = str_replace(",", "", $_POST['budget']);
    $services = $_POST['services'] ?? [];
    $guest_count = $_POST['guest_count'];
    $event_date = $_POST['event_date'];

    $event_type = "wedding"; // TEMP (change later)
    $event_name = "My Event"; // TEMP (change later)

    $budgetClass = new Budget();

    // 1. Create event
    $event_id = $budgetClass->create_event(
        $user_id,
        $event_type,
        $event_name,
        $event_date,
        $guest_count,
        $budget
    );

    if (!$event_id) {
        echo "Error creating event";
        return;
    }

    // 2. Save service selections as budget allocations
    foreach ($services as $service) {

        // Example allocation per category
        $allocated = $budgetClass->auto_allocate_category($service, $budget);

        $budgetClass->create_allocation(
            $event_id,
            $service,
            $allocated
        );
    }

    // Redirect to next step
    

    header("Location: ../view/smart_recommend.php?event_id=".$event_id);
}
