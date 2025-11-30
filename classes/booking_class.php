<?php
require_once __DIR__ . "/../settings/db_class.php";

class Booking extends db_connection
{
    
    //Get vendor bookings

    public function getVendorBookings($vendor_id)
    {
        $sql = "
            SELECT 
                bookings.*, 
                events.event_type,
                events.event_date,
                events.location AS event_location,
                events.guest_count,
                users.first_name,
                users.last_name
            FROM bookings
            JOIN events ON bookings.event_id = events.event_id
            JOIN users ON events.user_id = users.user_id
            WHERE bookings.vendor_id = '$vendor_id'
            ORDER BY bookings.created_at DESC
        ";

        return $this->db_fetch_all($sql);
    }

    
    //Update booking status
    
    public function updateBookingStatus($booking_id, $status)
    {
        $sql = "UPDATE bookings SET status='$status' WHERE booking_id='$booking_id'";
        return $this->db_write_query($sql);
    }

    
    //Get event bookings with vendor details
    
    public function get_event_bookings_with_vendors($event_id) {
        $sql = "SELECT 
                    b.booking_id,
                    b.event_id,
                    b.booking_date,
                    b.amount,
                    b.status,
                    b.payment_status,
                    b.created_at,
                    v.vendor_id,
                    v.business_name,
                    v.category,
                    v.rating,
                    v.total_reviews,
                    v.location,
                    v.image,
                    v.is_verified,
                    u.phone,
                    u.email
                FROM bookings b
                INNER JOIN vendors v ON b.vendor_id = v.vendor_id
                INNER JOIN users u ON v.user_id = u.user_id
                WHERE b.event_id = $event_id
                ORDER BY b.created_at DESC";
        
        return $this->db_fetch_all($sql);
    }

    
    //Create a new booking
    
    public function create_booking($event_id, $vendor_id, $amount, $booking_date, $notes = '') {
        $conn = $this->db_conn();
        $notes = mysqli_real_escape_string($conn, $notes);
        
        // Create booking with status='pending' and payment_status='unpaid'
        $sql = "INSERT INTO bookings (event_id, vendor_id, booking_date, amount, status, payment_status, notes, created_at)
                VALUES ($event_id, $vendor_id, '$booking_date', $amount, 'pending', 'unpaid', '$notes', NOW())";
        
        if ($this->db_write_query($sql)) {
            return mysqli_insert_id($conn);
        }
        return false;
    }

    
    //Get booking by ID
    
    public function get_booking_by_id($booking_id) {
        $sql = "SELECT * FROM bookings WHERE booking_id = $booking_id LIMIT 1";
        return $this->db_fetch_one($sql);
    }

    
    //Delete a booking
    
    public function delete_booking($booking_id) {
        $sql = "DELETE FROM bookings WHERE booking_id = $booking_id";
        return $this->db_write_query($sql);
    }

    
    //Update booking payment status
    
    public function update_payment_status($booking_id, $payment_status) {
        $sql = "UPDATE bookings 
                SET payment_status = '$payment_status', 
                    updated_at = NOW() 
                WHERE booking_id = $booking_id";
        return $this->db_write_query($sql);
    }

    
    //Mark all bookings for an event as paid
    
    public function mark_event_bookings_paid($event_id) {
        
        // Status should remain 'pending' until vendor accepts
        $sql = "UPDATE bookings 
                SET payment_status = 'paid',
                    updated_at = NOW()
                WHERE event_id = $event_id 
                AND status = 'pending'";
        return $this->db_write_query($sql);
    }

    
    //Check if booking exists for event and vendor
    
    public function booking_exists($event_id, $vendor_id) {
        $sql = "SELECT booking_id 
                FROM bookings 
                WHERE event_id = $event_id 
                AND vendor_id = $vendor_id 
                LIMIT 1";
        
        $result = $this->db_fetch_one($sql);
        return $result !== false;
    }

    
    //Get total amount for event bookings
    
    public function get_event_total($event_id) {
        $sql = "SELECT SUM(amount) as total 
                FROM bookings 
                WHERE event_id = $event_id";
        
        $result = $this->db_fetch_one($sql);
        return $result ? floatval($result['total']) : 0;
    }

    
    //Get booking count for an event

    public function get_booking_count($event_id) {
        $sql = "SELECT COUNT(*) as count 
                FROM bookings 
                WHERE event_id = $event_id";
        
        $result = $this->db_fetch_one($sql);
        return $result ? intval($result['count']) : 0;
    }

    
    //Confirm booking (vendor accepts)
    
    public function confirm_booking($booking_id) {
        $sql = "UPDATE bookings 
                SET status = 'confirmed',
                    updated_at = NOW()
                WHERE booking_id = $booking_id";
        return $this->db_write_query($sql);
    }

    
    //Reject booking (vendor declines)
    
    public function reject_booking($booking_id) {
        $sql = "UPDATE bookings 
                SET status = 'cancelled',
                    updated_at = NOW()
                WHERE booking_id = $booking_id";
        return $this->db_write_query($sql);
    }
}
?>