<?php
require_once __DIR__ . '/../classes/event_class.php';
require_once __DIR__ . '/../classes/booking_class.php';
require_once __DIR__ . '/../classes/budget_class.php';

class CollabController {

    /**
     * Get all collaboration data for an event
     * @param int $event_id
     * @param int $user_id
     * @return array
     */
    public function get_collab_data($event_id, $user_id) {
        $eventClass = new Event();
        $bookingClass = new Booking();
        $budgetClass = new Budget();

        // Verify user has access to this event
        $event = $eventClass->get_event_by_id($event_id);
        if (!$event || $event['user_id'] != $user_id) {
            return [
                'status' => 'error',
                'message' => 'Event not found or unauthorized access'
            ];
        }

        // Get budget allocations
        $allocations = $budgetClass->get_event_allocations($event_id);
        
        // Get selected vendors (from bookings)
        $vendors = $bookingClass->get_event_bookings_with_vendors($event_id);
        
        // Calculate totals
        $total_budget = floatval($event['total_budget']);
        $total_spent = 0;
        foreach ($vendors as $vendor) {
            $total_spent += floatval($vendor['amount']);
        }

        return [
            'status' => 'success',
            'event' => $event,
            'total_budget' => $total_budget,
            'allocations' => $allocations,
            'vendors' => $vendors,
            'total_spent' => $total_spent
        ];
    }

    /**
     * AJAX handler to get collab data
     */
    public function get_collab_data_ajax() {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            return [
                'status' => 'error',
                'message' => 'Please log in to continue'
            ];
        }

        if (!isset($_GET['event_id'])) {
            return [
                'status' => 'error',
                'message' => 'Event ID is required'
            ];
        }

        $event_id = intval($_GET['event_id']);
        $user_id = $_SESSION['user_id'];

        return $this->get_collab_data($event_id, $user_id);
    }
}
?>