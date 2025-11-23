<?php
session_start();
if (!isset($_SESSION['vendor_id'])) { header("Location: ../login/login.php"); exit; }

require_once "../controllers/vendor_controller.php";
$vendor_id = $_SESSION['vendor_id'];
$events = get_vendor_events_ctr($vendor_id);
$pending_bookings = get_pending_bookings_ctr($vendor_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vendor Dashboard | TasteConnect</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="dashboard-container">
    <!-- Sidebar (Sketch: Account, Event, etc) -->
    <div class="sidebar">
        <h2 style="color:var(--primary-orange); padding-left:0.8rem;">TC Vendor</h2>
        <br>
        <a href="#" class="sidebar-link active"><i class="fas fa-home"></i> Dashboard</a>
        <a href="#" class="sidebar-link"><i class="fas fa-utensils"></i> My Menu</a>
        <a href="#" class="sidebar-link"><i class="fas fa-calendar-alt"></i> Events</a>
        <a href="#" class="sidebar-link"><i class="fas fa-money-bill-wave"></i> Payments</a>
        <a href="../views/logout.php" class="sidebar-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Vendor Dashboard</h1>
        <p>Manage your food services and event bookings.</p>

        <!-- Tabs from Sketch -->
        <div class="dash-tabs">
            <div class="dash-tab active" data-target="#food-service">Food Service</div>
            <div class="dash-tab" data-target="#bookings">Bookings & Events</div>
        </div>

        <!-- 1. Food Service Tab (Manage Products) -->
        <div id="food-service" class="tab-content active">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                <h3>Current Food Offerings</h3>
                <button class="btn btn-primary" onclick="openModal('productModal')">+ Add Food Item</button>
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="product-table-body">
                    <!-- AJAX will load products here -->
                    <tr><td colspan="5">Loading products...</td></tr>
                </tbody>
            </table>
        </div>

        <!-- 2. Booking & Events Tab (Matches Sketch Logic) -->
        <div id="bookings" class="tab-content">
            <div class="row" style="display:flex; gap:2rem;">
                
                <!-- Events Management -->
                <div style="flex:1;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <h3>Your Events</h3>
                        <button class="btn btn-primary" onclick="openModal('eventModal')">+ Add Event</button>
                    </div>
                    <div class="event-list" style="margin-top:1rem;">
                        <?php foreach($events as $evt): ?>
                        <div style="background:white; padding:1rem; margin-bottom:1rem; border:1px solid #eee; border-radius:8px;">
                            <strong><?= htmlspecialchars($evt['event_title']) ?></strong><br>
                            <small><?= date('M d, Y h:i A', strtotime($evt['event_date'])) ?></small>
                            <p><?= htmlspecialchars($evt['event_description']) ?></p>
                            <span style="color:var(--primary-orange);">₵<?= $evt['price'] ?></span> | Capacity: <?= $evt['max_participants'] ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Pending Bookings (Sketch: "Pending Payments -> Approve") -->
                <div style="flex:1; border-left:1px solid #eee; padding-left:2rem;">
                    <h3>Pending Bookings</h3>
                    <p style="font-size:0.9rem; color:gray;">Approve only if payment is confirmed.</p>
                    
                    <?php if(empty($pending_bookings)): ?>
                        <p>No pending bookings.</p>
                    <?php else: ?>
                        <?php foreach($pending_bookings as $bk): ?>
                        <div style="background:#fff7ed; padding:1rem; margin-bottom:1rem; border-radius:8px;">
                            <strong>Booking #<?= $bk['booking_id'] ?></strong> by <?= $bk['customer_name'] ?><br>
                            Date: <?= $bk['booking_datetime'] ?><br>
                            People: <?= $bk['number_of_people'] ?><br>
                            <div style="margin-top:0.5rem;">
                                <button class="btn btn-sm btn-primary">Approve</button>
                                <button class="btn btn-sm btn-outline">Reject</button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Add Event Modal (Sketch: Title, Desc, Date, Price, Capacity, Allergen) -->
<div id="eventModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
    <div style="background:white; width:500px; margin:100px auto; padding:2rem; border-radius:8px;">
        <h3>Add New Event</h3>
        <form action="../actions/add_event_action.php" method="POST">
            <div class="form-group"><label>Event Title</label><input type="text" name="title" class="form-control" required></div>
            <div class="form-group"><label>Description (Inc. Allergens)</label><textarea name="desc" class="form-control" required></textarea></div>
            <div class="form-group"><label>Date & Time</label><input type="datetime-local" name="date" class="form-control" required></div>
            <div class="form-group"><label>Price</label><input type="number" name="price" class="form-control" required></div>
            <div class="form-group"><label>Max Participants</label><input type="number" name="capacity" class="form-control" required></div>
            <button type="submit" name="add_event" class="btn btn-primary">Save Event</button>
            <button type="button" class="btn btn-outline" onclick="closeModal('eventModal')">Cancel</button>
        </form>
    </div>
</div>

<script src="../js/vendor_dashboard.js"></script>
<script>
    function openModal(id) { document.getElementById(id).style.display = 'block'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
</script>

</body>
</html>