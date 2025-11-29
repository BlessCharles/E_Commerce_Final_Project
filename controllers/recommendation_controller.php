<?php
require_once __DIR__ . '/../classes/vendor_class.php';
require_once __DIR__ . '/../classes/budget_class.php';

class RecommendationController {

    /**
     * Get recommendations for an event
     * Returns event details, budget allocations, and vendor recommendations
     */
    public function get_event_recommendations($event_id, $user_id) {
        $budgetClass = new Budget();
        $vendorClass = new Vendor();

        // Get event details
        $event = $budgetClass->get_event_by_id($event_id);
        
        if (!$event || $event['user_id'] != $user_id) {
            return [
                'status' => 'error',
                'message' => 'Event not found or unauthorized access'
            ];
        }

        // Get budget allocations
        $allocations = $budgetClass->get_event_allocations($event_id);
        
        if (!$allocations) {
            return [
                'status' => 'error',
                'message' => 'No budget allocations found'
            ];
        }

        // Get vendor recommendations for each category
        $recommendations = [];
        $total_allocated = 0;

        foreach ($allocations as $allocation) {
            $category = $allocation['category'];
            $allocated_amount = $allocation['allocated_amount'];
            $total_allocated += $allocated_amount;

            // NEW LOGIC: Show vendors from 0 to allocated budget
            // This ensures we only show vendors the user can afford
            $budget_min = 0;
            $budget_max = $allocated_amount;

            // Get vendors for this category
            $vendors = $vendorClass->get_vendors_by_category_and_budget(
                $category, 
                $budget_min, 
                $budget_max,
                3 // Get top 3 vendors
            );

            $recommendations[$category] = [
                'allocated_amount' => $allocated_amount,
                'percentage' => round(($allocated_amount / $event['total_budget']) * 100, 1),
                'vendors' => $vendors ? $vendors : []
            ];
        }

        return [
            'status' => 'success',
            'event' => $event,
            'total_budget' => $event['total_budget'],
            'total_allocated' => $total_allocated,
            'recommendations' => $recommendations
        ];
    }

    /**
     * AJAX handler to get recommendations
     */
    public function get_recommendations_ajax() {
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

        return $this->get_event_recommendations($event_id, $user_id);
    }

    /**
     * Get all vendors for a category (for browse all)
     */
    public function browse_category_ajax() {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            return [
                'status' => 'error',
                'message' => 'Please log in to continue'
            ];
        }

        if (!isset($_GET['category'])) {
            return [
                'status' => 'error',
                'message' => 'Category is required'
            ];
        }

        $category = $_GET['category'];
        $vendorClass = new Vendor();

        $vendors = $vendorClass->get_all_vendors_by_category($category);

        return [
            'status' => 'success',
            'category' => $category,
            'vendors' => $vendors ? $vendors : []
        ];
    }
}
?>