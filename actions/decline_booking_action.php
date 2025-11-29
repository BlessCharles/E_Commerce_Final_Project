<?php
require_once "../classes/booking_class.php";

if (isset($_POST['booking_id'])) {

    $booking_id = $_POST['booking_id'];

    $booking = new BookingModel();
    $booking->updateBookingStatus($booking_id, "declined");

    echo "success";
}
