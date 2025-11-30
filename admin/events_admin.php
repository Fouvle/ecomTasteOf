<?php
session_start();
if (!isset($_SESSION['vendor_id'])) { header("Location: ../login/vendor_login.php"); exit; }
require_once "../settings/db_cred.php";

$vendor_id = $_SESSION['vendor_id'];

// Handle Deletion
if(isset($_GET['delete_id'])) {
    $del_id = $_GET['delete_id'];
    $del_sql = "DELETE FROM events WHERE event_id = ? AND vendor_id = ?";
    $stmt = $conn->prepare($del_sql);
    $stmt->bind_param("ii", $del_id, $vendor_id);
    if($stmt->execute()) {
        header("Location: events_admin.php?msg=deleted");
    }
}

// Fetch Events
$sql = "SELECT * FROM events WHERE vendor_id = ? ORDER BY event_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$events = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events | Vendor Dashboard</title>
    <link rel="stylesheet" href="../assets/dashboard_style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include '../includes/vendor_header.php'; // Or just hardcode top bar ?>
    
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <nav class="side-nav">
                <a href="vendor_dashboard.php" class="nav-item"><i class="fas fa-th-large"></i> Overview</a>
                <a href="events_admin.php" class="nav-item active"><i class="fas fa-calendar-alt"></i> Events</a>
                <a href="menu_admin.php" class="nav-item"><i class="fas fa-utensils"></i> Menu Items</a>
                <a href="bookings_admin.php" class="nav-item"><i class="fas fa-clipboard-list"></i> Bookings</a>
                <a href="payments_admin.php" class="nav-item"><i class="fas fa-wallet"></i> Payments</a>
                <a href="analytics_admin.php" class="nav-item"><i class="fas fa-chart-bar"></i> Analytics</a>
                <a href="reviews_admin.php" class="nav-item"><i class="fas fa-comment-alt"></i> Reviews</a>
                <a href="settings_admin.php" class="nav-item"><i class="fas fa-cog"></i> Settings</a>
            </nav>
        </aside>

        <main class="content-area">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
                <h2>Manage Events</h2>
                <button class="btn-submit" onclick="document.getElementById('addEventModal').style.display='block'">+ New Event</button>
            </div>

            <div class="stats-grid">
                <?php foreach($events as $evt): ?>
                <div class="stat-card" style="padding:1.5rem;">
                    <div style="display:flex; justify-content:space-between;">
                        <h3 style="margin:0;"><?= htmlspecialchars($evt['event_title']) ?></h3>
                        <div class="stat-icon icon-blue" style="width:30px; height:30px; font-size:0.9rem;">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                    <p style="color:#6b7280; font-size:0.9rem; margin:0.5rem 0;">
                        <i class="far fa-clock"></i> <?= date('M d, Y h:i A', strtotime($evt['event_date'])) ?>
                    </p>
                    <p style="font-size:0.9rem;"><?= substr(htmlspecialchars($evt['event_description']), 0, 80) ?>...</p>
                    <div style="margin-top:1rem; display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-weight:bold; color:var(--primary-orange);">₵<?= $evt['price'] ?></span>
                        <div>
                            <a href="#" class="action-btn btn-white" style="display:inline-block; padding:0.4rem;">Edit</a>
                            <a href="events_admin.php?delete_id=<?= $evt['event_id'] ?>" class="action-btn btn-white" style="display:inline-block; padding:0.4rem; color:red;" onclick="return confirm('Delete this event?')">Delete</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <!-- Add Event Modal -->
    <div id="addEventModal" class="modal">
        <div class="modal-content">
            <h3>Create New Event</h3>
            <form action="../actions/add_event_action.php" method="POST">
                <div class="form-group"><label>Event Title</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Description</label><textarea name="desc" required></textarea></div>
                <div class="form-group"><label>Date & Time</label><input type="datetime-local" name="date" required></div>
                <div class="form-group"><label>Price (₵)</label><input type="number" name="price" required></div>
                <div class="form-group"><label>Max Capacity</label><input type="number" name="capacity" required></div>
                <div class="modal-actions">
                    <button type="button" onclick="document.getElementById('addEventModal').style.display='none'" class="btn-cancel">Cancel</button>
                    <button type="submit" name="add_event" class="btn-submit">Save Event</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>