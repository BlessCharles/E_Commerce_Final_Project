<?php

require_once __DIR__ . "/../settings/db_class.php";

class BookingModel extends db_connection
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
}
