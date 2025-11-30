<?php
session_start();
if (!isset($_SESSION['vendor_id'])) { header("Location: ../login/vendor_login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Analytics | Vendor Dashboard</title>
    <link rel="stylesheet" href="../assets/dashboard_style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <nav class="side-nav">
                <a href="vendor_dashboard.php" class="nav-item"><i class="fas fa-th-large"></i> Overview</a>
                <a href="events_admin.php" class="nav-item"><i class="fas fa-calendar-alt"></i> Events</a>
                <a href="menu_admin.php" class="nav-item"><i class="fas fa-utensils"></i> Menu Items</a>
                <a href="bookings_admin.php" class="nav-item"><i class="fas fa-clipboard-list"></i> Bookings</a>
                <a href="payments_admin.php" class="nav-item"><i class="fas fa-wallet"></i> Payments</a>
                <a href="analytics_admin.php" class="nav-item active"><i class="fas fa-chart-bar"></i> Analytics</a>
                <a href="reviews_admin.php" class="nav-item"><i class="fas fa-comment-alt"></i> Reviews</a>
                <a href="settings_admin.php" class="nav-item"><i class="fas fa-cog"></i> Settings</a>
            </nav>
        </aside>

        <main class="content-area">
            <h2>Performance Analytics</h2>
            
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:2rem; margin-top:2rem;">
                <div class="section-container">
                    <h3>Revenue vs Bookings</h3>
                    <canvas id="revenueChart"></canvas>
                </div>
                <div class="section-container">
                    <h3>Popular Items</h3>
                    <canvas id="itemsChart"></canvas>
                </div>
            </div>
        </main>
    </div>

    <script>
        const ctx1 = document.getElementById('revenueChart');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue (₵)',
                    data: [1200, 1900, 3000, 5000, 2000, 3000],
                    borderColor: '#ea580c',
                    tension: 0.4
                }]
            }
        });

        const ctx2 = document.getElementById('itemsChart');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Jollof', 'Waakye', 'Fufu', 'Banku'],
                datasets: [{
                    data: [30, 50, 100, 40],
                    backgroundColor: ['#ea580c', '#f59e0b', '#10b981', '#3b82f6']
                }]
            }
        });
    </script>
</body>
</html>