<?php
session_start();
if (!isset($_SESSION['vendor_id'])) { header("Location: ../login/login.php"); exit; }

require_once "../controllers/vendor_controller.php";
require_once "../controllers/booking_controller.php"; // Added booking controller

$vendor_id = $_SESSION['vendor_id'];
$events = get_vendor_events_ctr($vendor_id);
// Fetch ALL bookings now
$all_bookings = get_all_vendor_bookings_ctr($vendor_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vendor Dashboard | TasteConnect</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2 style="color:var(--primary-orange); padding-left:0.8rem;">TC Vendor</h2>
        <br>
        <a href="#" class="sidebar-link active"><i class="fas fa-home"></i> Dashboard</a>
        <a href="#" class="sidebar-link"><i class="fas fa-utensils"></i> My Menu</a>
        <a href="#" class="sidebar-link"><i class="fas fa-calendar-alt"></i> Events & Bookings</a>
        <a href="../views/logout.php" class="sidebar-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Vendor Dashboard</h1>
        <p>Manage your food services and event bookings.</p>

        <div class="dash-tabs">
            <div class="dash-tab active" data-target="#food-service">Food Service</div>
            <div class="dash-tab" data-target="#bookings">Bookings & Events</div>
        </div>

        <!-- 1. Food Service Tab -->
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
                    <!-- JS Loads this -->
                </tbody>
            </table>
        </div>

        <!-- 2. Booking & Events Tab -->
        <div id="bookings" class="tab-content">
            <!-- Events Section -->
            <div style="margin-bottom:3rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                    <h3>Your Events</h3>
                    <button class="btn btn-primary" onclick="openModal('eventModal')">+ Add Event</button>
                </div>
                <div class="event-list" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap:1rem;">
                    <?php foreach($events as $evt): ?>
                    <div style="background:white; padding:1rem; border:1px solid #eee; border-radius:8px;">
                        <strong style="font-size:1.1rem;"><?= htmlspecialchars($evt['event_title']) ?></strong><br>
                        <small style="color:gray;"><i class="fas fa-clock"></i> <?= date('M d, Y h:i A', strtotime($evt['event_date'])) ?></small>
                        <p style="margin:0.5rem 0; font-size:0.9rem;"><?= htmlspecialchars($evt['event_description']) ?></p>
                        <span style="color:var(--primary-orange); font-weight:bold;">₵<?= $evt['price'] ?></span> | Capacity: <?= $evt['max_participants'] ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Bookings Management Table -->
            <div>
                <h3>Manage Bookings</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Customer</th>
                            <th>Details</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($all_bookings)): ?>
                            <tr><td colspan="5" style="text-align:center;">No bookings found.</td></tr>
                        <?php else: ?>
                            <?php foreach($all_bookings as $bk): ?>
                            <tr id="row-<?= $bk['booking_id'] ?>">
                                <td>
                                    <?= date('M d, Y', strtotime($bk['booking_datetime'])) ?><br>
                                    <small><?= date('h:i A', strtotime($bk['booking_datetime'])) ?></small>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($bk['customer_name']) ?></strong><br>
                                    <small><?= htmlspecialchars($bk['customer_contact']) ?></small>
                                </td>
                                <td>
                                    <?= $bk['number_of_people'] ?> People
                                </td>
                                <td>
                                    <span class="badge status-<?= $bk['booking_status'] ?>">
                                        <?= ucfirst($bk['booking_status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($bk['booking_status'] === 'pending'): ?>
                                        <button class="btn btn-sm btn-primary" onclick="updateStatus(<?= $bk['booking_id'] ?>, 'confirmed')"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-sm btn-outline" onclick="updateStatus(<?= $bk['booking_id'] ?>, 'cancelled')"><i class="fas fa-times"></i></button>
                                    <?php endif; ?>
                                    
                                    <button class="btn btn-sm btn-outline" onclick="editBooking(<?= $bk['booking_id'] ?>, '<?= date('Y-m-d', strtotime($bk['booking_datetime'])) ?>', '<?= date('H:i', strtotime($bk['booking_datetime'])) ?>', <?= $bk['number_of_people'] ?>)"><i class="fas fa-edit"></i></button>
                                    
                                    <button class="btn btn-sm btn-outline" style="color:red; border-color:red;" onclick="deleteBooking(<?= $bk['booking_id'] ?>)"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Booking Modal -->
<div id="editBookingModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:white; width:400px; margin:100px auto; padding:2rem; border-radius:8px;">
        <h3>Edit Booking</h3>
        <form id="editBookingForm">
            <input type="hidden" name="booking_id" id="edit_booking_id">
            <input type="hidden" name="action" value="edit_details">
            
            <div class="form-group"><label>Date</label><input type="date" name="date" id="edit_date" class="form-control" required></div>
            <div class="form-group"><label>Time</label><input type="time" name="time" id="edit_time" class="form-control" required></div>
            <div class="form-group"><label>People</label><input type="number" name="people" id="edit_people" class="form-control" required></div>
            
            <button type="submit" class="btn btn-primary" style="width:100%; margin-top:1rem;">Save Changes</button>
            <button type="button" class="btn btn-outline" style="width:100%; margin-top:0.5rem;" onclick="closeModal('editBookingModal')">Cancel</button>
        </form>
    </div>
</div>

<!-- Add Event Modal (Existing) -->
<div id="eventModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:white; width:500px; margin:100px auto; padding:2rem; border-radius:8px;">
        <h3>Add New Event</h3>
        <form action="../actions/add_event_action.php" method="POST">
            <div class="form-group"><label>Event Title</label><input type="text" name="title" class="form-control" required></div>
            <div class="form-group"><label>Description</label><textarea name="desc" class="form-control" required></textarea></div>
            <div class="form-group"><label>Date & Time</label><input type="datetime-local" name="date" class="form-control" required></div>
            <div class="form-group"><label>Price</label><input type="number" name="price" class="form-control" required></div>
            <div class="form-group"><label>Max Participants</label><input type="number" name="capacity" class="form-control" required></div>
            <button type="submit" name="add_event" class="btn btn-primary" style="width:100%; margin-top:1rem;">Save Event</button>
            <button type="button" class="btn btn-outline" style="width:100%; margin-top:0.5rem;" onclick="closeModal('eventModal')">Cancel</button>
        </form>
    </div>
</div>

<script src="../js/vendor_dashboard.js"></script>
<script>
    function openModal(id) { document.getElementById(id).style.display = 'block'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
    
    // Status Badge Styles
    const style = document.createElement('style');
    style.innerHTML = `
        .badge { padding:4px 8px; border-radius:12px; font-size:0.8rem; font-weight:bold; text-transform:uppercase; }
        .status-pending { background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; }
        .status-confirmed { background:#ecfdf5; color:#047857; border:1px solid #a7f3d0; }
        .status-cancelled { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
    `;
    document.head.appendChild(style);
</script>

</body>
</html>