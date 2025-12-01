<?php
session_start();
if (!isset($_SESSION['vendor_id'])) { header("Location: ../login/vendor_login.php"); exit; }
require_once "../settings/db_cred.php";

$vendor_id = $_SESSION['vendor_id'];

// Fetch Payments linked to this vendor via orders
$sql = "SELECT p.*, o.order_date, c.customer_name 
        FROM payment p
        JOIN orders o ON p.order_id = o.order_id
        JOIN customer c ON p.customer_id = c.customer_id
        LEFT JOIN bookings b ON o.booking_id = b.booking_id
        LEFT JOIN events e ON o.event_id = e.event_id
        WHERE b.vendor_id = ? OR e.vendor_id = ?
        ORDER BY p.payment_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $vendor_id, $vendor_id);
$stmt->execute();
$payments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payments | Vendor Dashboard</title>
    <link rel="stylesheet" href="../assets/dashboard_style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <nav class="side-nav">
                <a href="vendor_dashboard.php" class="nav-item"><i class="fas fa-th-large"></i> Overview</a>
                <a href="events_admin.php" class="nav-item"><i class="fas fa-calendar-alt"></i> Events</a>
                <a href="menu_admin.php" class="nav-item"><i class="fas fa-utensils"></i> Menu Items</a>
                <a href="bookings_admin.php" class="nav-item"><i class="fas fa-clipboard-list"></i> Bookings</a>
                <a href="payments_admin.php" class="nav-item active"><i class="fas fa-wallet"></i> Payments</a>
                <a href="analytics_admin.php" class="nav-item"><i class="fas fa-chart-bar"></i> Analytics</a>
                <a href="reviews_admin.php" class="nav-item"><i class="fas fa-comment-alt"></i> Reviews</a>
                <a href="settings_admin.php" class="nav-item"><i class="fas fa-cog"></i> Settings</a>
            </nav>
        </aside>

        <main class="content-area">
            <h2>Financial Overview</h2>
            <div class="stat-card" style="margin-bottom:2rem; width:300px;">
                <div class="stat-label">Total Earnings</div>
                <div class="stat-value">₵<?= number_format(array_sum(array_column($payments, 'amount')), 2) ?></div>
            </div>

            <h3>Transaction History</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>amount</th>
                        <th>Method</th>
                        <th>Reference</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($payments as $pay): ?>
                    <tr>
                        <td><?= date('M d, Y', strtotime($pay['payment_date'])) ?></td>
                        <td><?= htmlspecialchars($pay['customer_name']) ?></td>
                        <td style="color:#166534; font-weight:bold;">+₵<?= $pay['amount'] ?></td>
                        <td><?= strtoupper(str_replace('_', ' ', $pay['payment_method'])) ?></td>
                        <td>#<?= $pay['pay_id'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>