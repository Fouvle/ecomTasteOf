<?php
// actions/add_event_action.php
session_start();
require_once "../controllers/vendor_controller.php";

if(isset($_POST['add_event'])) {
    $vendor_id = $_SESSION['vendor_id'];
    $title = $_POST['title'];
    $desc = $_POST['desc']; // Includes allergen info per sketch
    $date = $_POST['date'];
    $price = $_POST['price'];
    $capacity = $_POST['capacity'];

    $result = add_event_ctr($vendor_id, $title, $desc, $date, $price, $capacity);

    if($result) {
        header("Location: ../admin/vendor_dashboard.php?status=success");
    } else {
        header("Location: ../admin/vendor_dashboard.php?status=error");
    }
}
?>