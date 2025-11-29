<?php
session_start();
if (!isset($_SESSION['vendor_id'])) { header("Location: ../login/vendor_login.php"); exit; }

require_once "../settings/db_cred.php";

$vendor_id = $_SESSION['vendor_id'];

// --- BACKEND: FETCH STATS & DATA ---

// 1. Total Bookings & Pending Count
$bk_sql = "SELECT 
            COUNT(*) as total, 
            SUM(CASE WHEN booking_status = 'pending' THEN 1 ELSE 0 END) as pending 
           FROM bookings WHERE vendor_id = ?";
$stmt = $conn->prepare($bk_sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$bk_stats = $stmt->get_result()->fetch_assoc();

// 2. Active Events
$evt_sql = "SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN event_date >= NOW() THEN 1 ELSE 0 END) as active
            FROM events WHERE vendor_id = ?";
$stmt = $conn->prepare($evt_sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$evt_stats = $stmt->get_result()->fetch_assoc();

// 3. Menu Items
$menu_sql = "SELECT COUNT(*) as total FROM products WHERE vendor_id = ?";
$stmt = $conn->prepare($menu_sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$menu_stats = $stmt->get_result()->fetch_assoc();

// 4. Revenue (Sum of payments linked to this vendor's bookings/events)
// Note: This assumes payments are linked via orders. If no data, defaults to 0.
$rev_sql = "SELECT SUM(p.amt) as total_revenue 
            FROM payment p
            JOIN orders o ON p.order_id = o.order_id
            LEFT JOIN bookings b ON o.booking_id = b.booking_id
            LEFT JOIN events e ON o.event_id = e.event_id
            WHERE b.vendor_id = ? OR e.vendor_id = ?";
$stmt = $conn->prepare($rev_sql);
$stmt->bind_param("ii", $vendor_id, $vendor_id);
$stmt->execute();
$rev_stats = $stmt->get_result()->fetch_assoc();
$revenue = $rev_stats['total_revenue'] ?? 0;

// 5. Recent Bookings (Limit 5)
$recent_sql = "SELECT b.*, c.customer_name 
               FROM bookings b
               JOIN customer c ON b.customer_id = c.customer_id
               WHERE b.vendor_id = ?
               ORDER BY b.booking_datetime DESC LIMIT 5";
$stmt = $conn->prepare($recent_sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$recent_bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vendor Dashboard | TasteConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/dashboard_style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- Top Bar -->
    <div class="top-header">
        <a href="../index.php" class="exit-link"><i class="fas fa-arrow-left"></i> Exit Dashboard</a>
        <div class="header-title">Vendor Dashboard</div>
        <div style="width: 100px;"></div> <!-- Spacer to center title -->
    </div>

    <div class="dashboard-wrapper">
        
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <nav class="side-nav">
                <a href="#" class="nav-item active" data-target="overview">
                    <i class="fas fa-th-large"></i> Overview
                </a>
                <a href="#" class="nav-item" data-target="events">
                    <i class="fas fa-calendar-alt"></i> Events
                </a>
                <a href="#" class="nav-item" data-target="menu">
                    <i class="fas fa-utensils"></i> Menu Items
                </a>
                <a href="#" class="nav-item" data-target="bookings">
                    <i class="fas fa-clipboard-list"></i> Bookings
                </a>
                <a href="#" class="nav-item" data-target="payments">
                    <i class="fas fa-wallet"></i> Payments
                </a>
                <a href="#" class="nav-item" data-target="analytics">
                    <i class="fas fa-chart-bar"></i> Analytics
                </a>
                <a href="#" class="nav-item" data-target="reviews">
                    <i class="fas fa-comment-alt"></i> Reviews
                </a>
                <a href="#" class="nav-item" data-target="settings">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="content-area">
            
            <!-- OVERVIEW VIEW -->
            <div id="view-overview" class="view-section active">
                
                <!-- Stats Cards Row -->
                <div class="stats-grid">
                    <!-- Bookings Card -->
                    <div class="stat-card">
                        <div class="stat-icon icon-orange"><i class="far fa-calendar-check"></i></div>
                        <div class="stat-value"><?= $bk_stats['total'] ?></div>
                        <div class="stat-label">Total Bookings</div>
                        <div class="stat-sub text-green"><?= $bk_stats['pending'] ?> pending</div>
                        <div class="trend-icon text-green"><i class="fas fa-arrow-up"></i></div>
                    </div>

                    <!-- Events Card -->
                    <div class="stat-card">
                        <div class="stat-icon icon-blue"><i class="far fa-calendar-plus"></i></div>
                        <div class="stat-value"><?= $evt_stats['active'] ?></div>
                        <div class="stat-label">Active Events</div>
                        <div class="stat-sub"><?= $evt_stats['total'] ?> total events</div>
                    </div>

                    <!-- Menu Card -->
                    <div class="stat-card">
                        <div class="stat-icon icon-purple"><i class="fas fa-utensils"></i></div>
                        <div class="stat-value"><?= $menu_stats['total'] ?></div>
                        <div class="stat-label">Menu Items</div>
                        <div class="stat-sub"><?= $menu_stats['total'] ?> available</div>
                    </div>

                    <!-- Revenue Card -->
                    <div class="stat-card">
                        <div class="stat-icon icon-green"><i class="fas fa-dollar-sign"></i></div>
                        <div class="stat-value">₵<?= number_format($revenue, 2) ?></div>
                        <div class="stat-label">Total Revenue</div>
                        <div class="stat-sub text-orange">1 pending</div> <!-- Placeholder -->
                        <div class="trend-icon text-green"><i class="fas fa-arrow-up"></i></div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="section-container">
                    <h3 class="section-title">Quick Actions</h3>
                    <div class="quick-actions-row">
                        <button class="action-btn btn-orange" onclick="openModal('eventModal')">
                            <i class="fas fa-plus"></i> Create Event
                        </button>
                        <button class="action-btn btn-white" onclick="openModal('productModal')">
                            <i class="fas fa-plus"></i> Add Menu Item
                        </button>
                        <button class="action-btn btn-white" onclick="switchView('bookings')">
                            <i class="far fa-calendar-alt"></i> View Bookings
                        </button>
                        <button class="action-btn btn-white" onclick="switchView('payments')">
                            <i class="far fa-credit-card"></i> View Payments
                        </button>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="section-container">
                    <h3 class="section-title">Recent Bookings</h3>
                    <div class="booking-list">
                        <?php if (empty($recent_bookings)): ?>
                            <p style="color:#6b7280; padding:1rem;">No recent bookings found.</p>
                        <?php else: ?>
                            <?php foreach($recent_bookings as $rb): ?>
                            <div class="booking-item">
                                <div class="bk-info">
                                    <div class="bk-name"><?= htmlspecialchars($rb['customer_name']) ?></div>
                                    <div class="bk-details">
                                        <?= date('Y-m-d', strtotime($rb['booking_datetime'])) ?> at <?= date('g:i A', strtotime($rb['booking_datetime'])) ?> • <?= $rb['number_of_people'] ?> guests
                                    </div>
                                </div>
                                <div class="bk-status">
                                    <?php if($rb['booking_status'] == 'confirmed'): ?>
                                        <span class="badge badge-black">Confirmed</span>
                                        <span class="badge badge-black">Paid</span> <!-- Assuming paid if confirmed for demo -->
                                    <?php elseif($rb['booking_status'] == 'pending'): ?>
                                        <span class="badge badge-gray">Pending</span>
                                        <span class="badge badge-gray">Unpaid</span>
                                    <?php else: ?>
                                        <span class="badge badge-red"><?= ucfirst($rb['booking_status']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div> <!-- End Overview -->

            <!-- EVENTS VIEW (Hidden by default) -->
            <div id="view-events" class="view-section">
                <h2>Manage Events</h2>
                <!-- Load events table via AJAX or PHP Loop here (reusing previous logic) -->
                <div id="events-container">Loading...</div>
            </div>

            <!-- MENU VIEW -->
            <div id="view-menu" class="view-section">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                    <h2>Menu Items</h2>
                    <button class="action-btn btn-orange" onclick="openModal('productModal')">+ Add Item</button>
                </div>
                <table class="data-table">
                    <thead>
                        <tr><th>Image</th><th>Name</th><th>Price</th><th>Category</th><th>Action</th></tr>
                    </thead>
                    <tbody id="menu-table-body"></tbody>
                </table>
            </div>

            <!-- BOOKINGS VIEW -->
            <div id="view-bookings" class="view-section">
                <h2>All Bookings</h2>
                <!-- Full booking table goes here -->
                <div id="bookings-container">Loading...</div>
            </div>

            <!-- PAYMENTS VIEW -->
            <div id="view-payments" class="view-section">
                <h2>Payment History</h2>
                <p style="color:gray;">Payment integration coming soon.</p>
            </div>

        </main>
    </div>

    <!-- Modals (Add Event / Add Product) -->
    <!-- Add Event Modal -->
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <h3>Add New Event</h3>
            <form action="../actions/add_event_action.php" method="POST">
                <div class="form-group"><label>Title</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Description</label><textarea name="desc" required></textarea></div>
                <div class="form-group"><label>Date/Time</label><input type="datetime-local" name="date" required></div>
                <div class="form-group"><label>Price (₵)</label><input type="number" name="price" required></div>
                <div class="form-group"><label>Capacity</label><input type="number" name="capacity" required></div>
                <div class="modal-actions">
                    <button type="button" onclick="closeModal('eventModal')" class="btn-cancel">Cancel</button>
                    <button type="submit" name="add_event" class="btn-submit">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <h3>Add Menu Item</h3>
            <form id="addProductForm">
                <div class="form-group"><label>Name</label><input type="text" name="productTitle" required></div>
                <div class="form-group"><label>Price (₵)</label><input type="number" name="productPrice" required></div>
                <div class="form-group"><label>Category</label>
                    <select name="productCategory" required>
                        <option value="1">Traditional</option>
                        <option value="2">Street Food</option>
                        <option value="3">Continental</option>
                    </select>
                </div>
                <div class="form-group"><label>Brand</label>
                    <select name="productBrand" required>
                        <option value="1">Home Style</option>
                        <option value="2">Spicy Corner</option>
                    </select>
                </div>
                <div class="form-group"><label>Description</label><textarea name="productDescription" required></textarea></div>
                <div class="form-group"><label>Image</label><input type="file" id="productImage" accept="image/*"></div>
                
                <div class="modal-actions">
                    <button type="button" onclick="closeModal('productModal')" class="btn-cancel">Cancel</button>
                    <button type="submit" id="submitBtn" class="btn-submit">Add Item</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../js/vendor_dashboard_new.js"></script>
</body>
</html>