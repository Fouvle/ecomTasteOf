<?php
// classes/vendor_class.php
// Ensure we include the correct DB credentials file. some environments use `db_cred.php`.
// Use __DIR__ to build an absolute path relative to this file.
require_once __DIR__ . '/../settings/db_cred.php';

class Vendor {
    public function getVendorByCustomerId($id) {
        global $conn;
        $sql = "SELECT * FROM vendors WHERE customer_id = '$id'";
        return $conn->query($sql)->fetch_assoc();
    }

    public function addEvent($vendor_id, $title, $desc, $date, $price, $capacity) {
        global $conn;
        // Matches 'Events' table in provided schema
        $sql = "INSERT INTO events (vendor_id, event_title, event_description, event_date, price, max_participants) 
                VALUES ('$vendor_id', '$title', '$desc', '$date', '$price', '$capacity')";
        return $conn->query($sql);
    }

    public function getEvents($vendor_id) {
        global $conn;
        $sql = "SELECT * FROM events WHERE vendor_id = '$vendor_id' ORDER BY event_date DESC";
        return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // Implementation of the "Pending Payments" sketch logic
    // Joins bookings, orders and payments to see status
    public function getPendingBookings($vendor_id) {
        global $conn;
        $sql = "SELECT b.booking_id, b.booking_datetime, b.number_of_people, c.customer_name, b.booking_status
                FROM bookings b
                JOIN customer c ON b.customer_id = c.customer_id
                WHERE b.vendor_id = '$vendor_id' AND b.booking_status = 'pending'";
        return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
}
?>