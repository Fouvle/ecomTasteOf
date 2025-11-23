<?php
// controllers/vendor_controller.php
require_once dirname(__FILE__) . '/../classes/vendor_class.php';

function get_vendor_details_ctr($customer_id) {
    $vendor = new Vendor();
    return $vendor->getVendorByCustomerId($customer_id);
}

function add_event_ctr($vendor_id, $title, $desc, $date, $price, $capacity) {
    $vendor = new Vendor();
    return $vendor->addEvent($vendor_id, $title, $desc, $date, $price, $capacity);
}

function get_vendor_events_ctr($vendor_id) {
    $vendor = new Vendor();
    return $vendor->getEvents($vendor_id);
}

function get_pending_bookings_ctr($vendor_id) {
    $vendor = new Vendor();
    return $vendor->getPendingBookings($vendor_id);
}
?>