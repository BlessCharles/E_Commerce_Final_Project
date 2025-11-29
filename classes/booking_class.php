<?php
require_once __DIR__ . "/../settings/db_class.php";

class Booking extends db_connection
{
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

    public function updateBookingStatus($booking_id, $status)
    {
        $sql = "UPDATE bookings SET status='$status' WHERE booking_id='$booking_id'";
        return $this->db_write_query($sql);
    }

    /**
     * Get event bookings with vendor details
     * @param int $event_id
     * @return array|false
     */
    public function get_event_bookings_with_vendors($event_id) {
        $sql = "SELECT 
                    b.booking_id,
                    b.event_id,
                    b.booking_date,
                    b.amount,
                    b.status as booking_status,
                    b.payment_status,
                    v.vendor_id,
                    v.business_name,
                    v.category,
                    v.rating,
                    v.total_reviews,
                    v.location,
                    v.image,
                    u.phone,
                    u.email
                FROM bookings b
                INNER JOIN vendors v ON b.vendor_id = v.vendor_id
                INNER JOIN users u ON v.user_id = u.user_id
                WHERE b.event_id = $event_id
                ORDER BY b.created_at DESC";
        
        return $this->db_fetch_all($sql);
    }

    /**
     * Create a new booking
     * @param int $event_id
     * @param int $vendor_id
     * @param float $amount
     * @param string $booking_date
     * @param string $notes
     * @return int|false - booking_id or false
     */
    public function create_booking($event_id, $vendor_id, $amount, $booking_date, $notes = '') {
        $conn = $this->db_conn();
        $notes = mysqli_real_escape_string($conn, $notes);
        
        // Include payment_status in INSERT to match your database schema
        $sql = "INSERT INTO bookings (event_id, vendor_id, booking_date, amount, status, payment_status, notes)
                VALUES ($event_id, $vendor_id, '$booking_date', $amount, 'pending', 'unpaid', '$notes')";
        
        if ($this->db_write_query($sql)) {
            return $this->last_insert_id();
        }
        return false;
    }

    /**
     * Get booking by ID
     * @param int $booking_id
     * @return array|false
     */
    public function get_booking_by_id($booking_id) {
        $sql = "SELECT * FROM bookings WHERE booking_id = $booking_id LIMIT 1";
        return $this->db_fetch_one($sql);
    }

    /**
     * Get last insert ID
     * @return int
     */
    public function last_insert_id() {
        $conn = $this->db_conn();
        return mysqli_insert_id($conn);
    }
}
?>