<?php
require_once __DIR__ . '/../classes/vendor_class.php';
require_once __DIR__ . '/../classes/budget_class.php';
require_once __DIR__ . '/../classes/booking_class.php';
require_once __DIR__ . '/../classes/event_class.php';

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

    /**
     * Save vendor selections as bookings
     * @param int $event_id
     * @param array $vendors - array with category => {vendor_id, price}
     * @return array
     */
    public function save_vendor_selections($event_id, $vendors) {
        session_start();
        
        if (!isset($_SESSION['user_id'])) {
            return [
                'status' => 'error',
                'message' => 'Please log in to continue'
            ];
        }
        
        $user_id = $_SESSION['user_id'];
        
        // Verify user owns this event
        $budgetClass = new Budget();
        $event = $budgetClass->get_event_by_id($event_id);
        
        if (!$event || $event['user_id'] != $user_id) {
            return [
                'status' => 'error',
                'message' => 'Event not found or unauthorized access'
            ];
        }
        
        $bookingClass = new Booking();
        $saved_count = 0;
        $errors = [];
        
        // Get event date for booking (use event_date if exists, otherwise use current date)
        $booking_date = !empty($event['event_date']) ? $event['event_date'] : date('Y-m-d');
        
        // Loop through selected vendors and create bookings
        foreach ($vendors as $category => $vendor_data) {
            $vendor_id = intval($vendor_data['vendor_id']);
            $amount = floatval($vendor_data['price']);
            
            // Validate vendor exists
            $vendor_check = "SELECT vendor_id FROM vendors WHERE vendor_id = $vendor_id LIMIT 1";
            if (!$bookingClass->db_fetch_one($vendor_check)) {
                $errors[] = "Vendor $vendor_id not found";
                continue;
            }
            
            // Check if booking already exists for this vendor and event
            $existing_sql = "SELECT booking_id FROM bookings 
                            WHERE event_id = $event_id AND vendor_id = $vendor_id LIMIT 1";
            $existing = $bookingClass->db_fetch_one($existing_sql);
            
            if ($existing) {
                // Update existing booking
                $booking_id = $existing['booking_id'];
                $update_sql = "UPDATE bookings 
                              SET amount = $amount, 
                                  booking_date = '$booking_date',
                                  status = 'pending',
                                  updated_at = NOW()
                              WHERE booking_id = $booking_id";
                
                if ($bookingClass->db_write_query($update_sql)) {
                    $saved_count++;
                } else {
                    $errors[] = "Failed to update booking for vendor $vendor_id";
                }
            } else {
                // Create new booking
                $result = $bookingClass->create_booking(
                    $event_id, 
                    $vendor_id, 
                    $amount, 
                    $booking_date,
                    "Selected from recommendations"
                );
                
                if ($result) {
                    $saved_count++;
                } else {
                    $errors[] = "Failed to create booking for vendor $vendor_id";
                }
            }
        }
        
        if ($saved_count > 0) {
            return [
                'status' => 'success',
                'message' => "Successfully saved $saved_count vendor selection(s)",
                'saved_count' => $saved_count,
                'errors' => $errors
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Failed to save vendor selections',
                'errors' => $errors
            ];
        }
    }
}
?>