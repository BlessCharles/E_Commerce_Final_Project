<?php

require_once __DIR__ . "/../settings/db_class.php";

class ReviewModel extends db_connection
{
    public function getVendorReviews($vendor_id)
    {
        $sql = "
            SELECT 
                reviews.*, 
                users.first_name, 
                users.last_name,
                bookings.amount
            FROM reviews
            JOIN users ON reviews.user_id = users.user_id
            JOIN bookings ON reviews.booking_id = bookings.booking_id
            WHERE reviews.vendor_id = '$vendor_id'
            ORDER BY reviews.created_at DESC
        ";

        return $this->db_fetch_all($sql);
    }
}
