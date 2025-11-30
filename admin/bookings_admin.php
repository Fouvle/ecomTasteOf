<?php
session_start();
if (!isset($_SESSION['vendor_id'])) { header("Location: ../login/vendor_login.php"); exit; }
require_once "../settings/db_cred.php";

$vendor_id = $_SESSION['vendor_id'];

// Fetch Bookings
$sql = "SELECT b.*, c.customer_name, c.customer_contact 
        FROM bookings b 
        JOIN customer c ON b.customer_id = c.customer_id 
        WHERE b.vendor_id = ? 
        ORDER BY b.booking_datetime DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bookings | Vendor Dashboard</title>
    <link rel="stylesheet" href="../assets/dashboard_style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <nav class="side-nav">
                <a href="vendor_dashboard.php" class="nav-item"><i class="fas fa-th-large"></i> Overview</a>
                <a href="events_admin.php" class="nav-item"><i class="fas fa-calendar-alt"></i> Events</a>
                <a href="menu_admin.php" class="nav-item"><i class="fas fa-utensils"></i> Menu Items</a>
                <a href="bookings_admin.php" class="nav-item active"><i class="fas fa-clipboard-list"></i> Bookings</a>
                <a href="payments_admin.php" class="nav-item"><i class="fas fa-wallet"></i> Payments</a>
                <a href="analytics_admin.php" class="nav-item"><i class="fas fa-chart-bar"></i> Analytics</a>
                <a href="reviews_admin.php" class="nav-item"><i class="fas fa-comment-alt"></i> Reviews</a>
                <a href="settings_admin.php" class="nav-item"><i class="fas fa-cog"></i> Settings</a>
            </nav>
        </aside>

        <main class="content-area">
            <h2>Bookings Management</h2>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Guests</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($bookings as $bk): 
                        // Simulate payment check (In real app, join with orders/payments table)
                        $is_paid = ($bk['booking_status'] === 'confirmed'); 
                    ?>
                    <tr id="row-<?= $bk['booking_id'] ?>">
                        <td><?= date('M d, Y h:i A', strtotime($bk['booking_datetime'])) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($bk['customer_name']) ?></strong><br>
                            <small><?= htmlspecialchars($bk['customer_contact']) ?></small>
                        </td>
                        <td><?= $bk['number_of_people'] ?></td>
                        <td><span class="badge badge-<?= strtolower($bk['booking_status']) ?>"><?= ucfirst($bk['booking_status']) ?></span></td>
                        <td>
                            <?php if($is_paid): ?>
                                <span class="badge badge-black">Paid</span>
                            <?php else: ?>
                                <span class="badge badge-red">Unpaid</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($bk['booking_status'] == 'pending'): ?>
                                <button class="action-btn btn-orange" onclick="updateStatus(<?= $bk['booking_id'] ?>, 'confirmed')" style="padding:0.4rem;">Approve</button>
                                <button class="action-btn btn-white" onclick="updateStatus(<?= $bk['booking_id'] ?>, 'cancelled')" style="padding:0.4rem;">Reject</button>
                            <?php endif; ?>
                            <button class="action-btn btn-white" onclick="deleteBooking(<?= $bk['booking_id'] ?>)" style="padding:0.4rem; color:red;"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>

    <script>
        function updateStatus(id, status) {
            $.post('../actions/manage_booking_action.php', { action: 'update_status', booking_id: id, status: status }, function(res) {
                if(res.status === 'success') location.reload();
            }, 'json');
        }
        function deleteBooking(id) {
            if(confirm('Delete this booking?')) {
                $.post('../actions/manage_booking_action.php', { action: 'delete', booking_id: id }, function(res) {
                    if(res.status === 'success') location.reload();
                }, 'json');
            }
        }
    </script>
</body>
</html>