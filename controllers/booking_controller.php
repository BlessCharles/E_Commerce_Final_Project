<?php
require_once dirname(__FILE__) . '/../classes/booking_class.php';

/**
 * Get event bookings with vendor details
 */
function get_event_bookings_with_vendors_ctr($event_id) {
    $booking = new Booking();
    return $booking->get_event_bookings_with_vendors($event_id);
}

/**
 * Create a new booking
 */
function create_booking_ctr($event_id, $vendor_id, $amount, $booking_date, $notes = '') {
    $booking = new Booking();
    return $booking->create_booking($event_id, $vendor_id, $amount, $booking_date, $notes);
}

/**
 * Get booking by ID
 */
function get_booking_by_id_ctr($booking_id) {
    $booking = new Booking();
    return $booking->get_booking_by_id($booking_id);
}

/**
 * Update booking status
 */
function update_booking_status_ctr($booking_id, $status) {
    $booking = new Booking();
    return $booking->updateBookingStatus($booking_id, $status);
}

/**
 * Update booking payment status
 * FIXED: Now uses the class method instead of raw SQL
 */
function update_booking_payment_status_ctr($booking_id, $payment_status) {
    $booking = new Booking();
    return $booking->update_payment_status($booking_id, $payment_status);
}

/**
 * Update all bookings for an event to paid
 * FIXED: Now uses the class method instead of raw SQL
 */
function mark_event_bookings_paid_ctr($event_id) {
    $booking = new Booking();
    return $booking->mark_event_bookings_paid($event_id);
}

/**
 * Delete a booking
 * FIXED: Now uses the class method instead of raw SQL
 */
function delete_booking_ctr($booking_id) {
    $booking = new Booking();
    return $booking->delete_booking($booking_id);
}

/**
 * Get vendor bookings
 */
function get_vendor_bookings_ctr($vendor_id) {
    $booking = new Booking();
    return $booking->getVendorBookings($vendor_id);
}

/**
 * Check if booking exists for event and vendor
 */
function booking_exists_ctr($event_id, $vendor_id) {
    $booking = new Booking();
    return $booking->booking_exists($event_id, $vendor_id);
}

/**
 * Get total amount for event bookings
 */
function get_event_total_ctr($event_id) {
    $booking = new Booking();
    return $booking->get_event_total($event_id);
}

/**
 * Get booking count for an event
 */
function get_booking_count_ctr($event_id) {
    $booking = new Booking();
    return $booking->get_booking_count($event_id);
}
?>