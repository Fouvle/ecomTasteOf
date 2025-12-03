<?php
session_start();
// Ensure Login & Role
if (!isset($_SESSION['customer_id']) || (isset($_SESSION['role']) && $_SESSION['role'] === 'vendor')) {
    header("Location: ../login/login.php");
    exit();
}

require_once "../settings/db_cred.php";
$customer_id = $_SESSION['customer_id'];

// --- FETCH STATS ---
// 1. Total Bookings
$bk_sql = "SELECT COUNT(*) as total FROM bookings WHERE customer_id = ?";
$stmt = $conn->prepare($bk_sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$total_bookings = $stmt->get_result()->fetch_assoc()['total'];

// 2. Active (Pending/Confirmed)
$act_sql = "SELECT COUNT(*) as active FROM bookings WHERE customer_id = ? AND booking_status IN ('pending', 'confirmed')";
$stmt = $conn->prepare($act_sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$active_bookings = $stmt->get_result()->fetch_assoc()['active'];

// 3. Reviews Given
$rev_sql = "SELECT COUNT(*) as total FROM reviews WHERE customer_id = ?";
$stmt = $conn->prepare($rev_sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$total_reviews = $stmt->get_result()->fetch_assoc()['total'];

// --- FETCH BOOKINGS LIST ---
$list_sql = "SELECT b.*, v.business_name, v.business_address 
             FROM bookings b 
             JOIN vendors v ON b.vendor_id = v.vendor_id 
             WHERE b.customer_id = ? 
             ORDER BY b.booking_datetime DESC";
$stmt = $conn->prepare($list_sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


// --- FETCH REVIEWS ---
$list_sql = "SELECT r.*, v.business_name 
             FROM reviews r 
             JOIN vendors v ON r.vendor_id = v.vendor_id 
             WHERE r.customer_id = ? 
             ORDER BY r.created_at DESC";
$stmt = $conn->prepare($list_sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Dashboard | TasteConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/dashboard_style.css"> <!-- Reusing Vendor Dashboard CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Specific overrides for customer view */
        .sidebar { background: #fff; }
        .nav-item.active { background-color: #e0f2fe; color: #0284c7; border-right-color: #0284c7; } /* Blue theme for users */
        .btn-pay { background-color: #10b981; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        .btn-pay:hover { background-color: #059669; }
    </style>
</head>
<body>

    <div class="top-header">
        <a href="../index.php" class="exit-link"><i class="fas fa-arrow-left"></i> Back to Home</a>
        <div class="header-title">My Dashboard</div>
        <div style="width: 100px;"></div>
    </div>

    <div class="dashboard-wrapper">
        
        <!-- Sidebar -->
        <aside class="sidebar">
            <div style="padding: 2rem 1.5rem; text-align: center;">
                <div style="width: 80px; height: 80px; background: #e5e7eb; border-radius: 50%; margin: 0 auto 1rem auto; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #9ca3af;">
                    <i class="fas fa-user"></i>
                </div>
                <h3 style="margin: 0; font-size: 1.1rem;"><?= htmlspecialchars($_SESSION['customer_name']) ?></h3>
                <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">Foodie Member</p>
            </div>
            
            <nav class="side-nav">
                <a href="#" class="nav-item active" data-target="overview"><i class="fas fa-th-large"></i> Overview</a>
                <a href="#" class="nav-item" data-target="bookings"><i class="fas fa-calendar-alt"></i> My Bookings</a>
                <a href="my_reviews.php" class="nav-item"><i class="fas fa-star"></i> My Reviews</a>
                <a href="#" class="nav-item" data-target="settings"><i class="fas fa-cog"></i> Settings</a>
                <a href="../views/logout.php" class="nav-item" style="color:#ef4444;"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="content-area">
            
            <!-- OVERVIEW TAB -->
            <div id="view-overview" class="view-section active">
                <h2 class="section-title">Welcome back!</h2>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon icon-blue"><i class="fas fa-calendar-check"></i></div>
                        <div class="stat-value"><?= $active_bookings ?></div>
                        <div class="stat-label">Active Bookings</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon icon-orange"><i class="fas fa-history"></i></div>
                        <div class="stat-value"><?= $total_bookings ?></div>
                        <div class="stat-label">Total History</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon icon-purple"><i class="fas fa-comment-dots"></i></div>
                        <div class="stat-value"><?= $total_reviews ?></div>
                        <div class="stat-label">Reviews Written</div>
                    </div>
                </div>

                <div class="section-container">
                    <h3 class="section-title">Upcoming Reservations</h3>
                    <div class="booking-list">
                        <?php 
                        $has_upcoming = false;
                        foreach($bookings as $bk): 
                            if($bk['booking_status'] == 'pending' || $bk['booking_status'] == 'confirmed'):
                                $has_upcoming = true;
                        ?>
                        <div class="booking-item">
                            <div class="bk-info">
                                <div class="bk-name"><?= htmlspecialchars($bk['business_name']) ?></div>
                                <div class="bk-details">
                                    <i class="far fa-clock"></i> <?= date('M d, Y @ g:i A', strtotime($bk['booking_datetime'])) ?>
                                    &nbsp;•&nbsp; <?= $bk['number_of_people'] ?> Guests
                                </div>
                            </div>
                            <div class="bk-status" style="align-items: center; gap: 10px;">
                                <span class="badge badge-<?= strtolower($bk['booking_status'] == 'pending' ? 'gray' : 'black') ?>">
                                    <?= ucfirst($bk['booking_status']) ?>
                                </span>
                                <?php if($bk['booking_status'] == 'pending'): ?>
                                    <!-- Payment Button -->
                                    <button class="btn-pay" onclick="openPaymentModal(<?= $bk['booking_id'] ?>, '<?= $bk['business_name'] ?>', 50)">
                                        Pay Now (₵50)
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; endforeach; ?>
                        
                        <?php if(!$has_upcoming): ?>
                            <p style="color:gray; padding:1rem; text-align:center;">No upcoming bookings. <a href="../views/all_products.php" style="color:var(--primary-orange);">Explore Vendors</a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- BOOKINGS TAB -->
            <div id="view-bookings" class="view-section">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                    <h2>Booking History</h2>
                    <a href="../views/all_products.php" class="action-btn btn-orange">+ New Booking</a>
                </div>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Vendor</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($bookings as $bk): ?>
                        <tr id="row-<?= $bk['booking_id'] ?>">
                            <td><?= date('M d, Y', strtotime($bk['booking_datetime'])) ?></td>
                            <td><?= htmlspecialchars($bk['business_name']) ?></td>
                            <td>
                                <span class="badge badge-<?= $bk['booking_status'] == 'confirmed' ? 'black' : ($bk['booking_status'] == 'cancelled' ? 'red' : 'gray') ?>">
                                    <?= ucfirst($bk['booking_status']) ?>
                                </span>
                            </td>
                            <td>
                                <!-- Actions based on status -->
                                <?php if($bk['booking_status'] == 'pending'): ?>
                                    <button class="action-btn btn-white" style="color:red;" onclick="cancelBooking(<?= $bk['booking_id'] ?>)">Cancel</button>
                                    <button class="btn-pay" onclick="openPaymentModal(<?= $bk['booking_id'] ?>, '<?= $bk['business_name'] ?>', 50)">Pay</button>
                                <?php elseif($bk['booking_status'] == 'confirmed'): ?>
                                    <button class="action-btn btn-white" onclick="openReviewModal(<?= $bk['booking_id'] ?>, <?= $bk['vendor_id'] ?>, '<?= $bk['business_name'] ?>')">Write Review</button>
                                <?php endif; ?>
                                <button class="action-btn btn-white" onclick="viewDetails(<?= $bk['booking_id'] ?>)"><i class="far fa-eye"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- REVIEWS TAB -->
            <div id="view-reviews" class="view-section">
                <h2>My Reviews</h2>
                <div id="reviews-container">
                    <!-- Loaded via JS or simple PHP loop if separate table exists -->
                    <p style="color:gray;">You haven't written any reviews yet.</p>
                </div>
            </div>

            <!-- SETTINGS TAB -->
            <div id="view-settings" class="view-section">
                <h2>Account Settings</h2>
                <form class="section-container" style="max-width:500px;">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['customer_name']) ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($_SESSION['customer_email']) ?>" disabled>
                    </div>
                    <p style="font-size:0.9rem; color:gray;">To update details, please contact support.</p>
                </form>
            </div>

        </main>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content" style="width: 350px; text-align:center;">
            <div style="font-size:3rem; color:#10b981; margin-bottom:1rem;"><i class="fas fa-check-circle"></i></div>
            <h3>Confirm Payment</h3>
            <p>You are paying <strong id="payAmount"></strong> to <strong id="payVendor"></strong></p>
            <form id="paymentForm">
                <input type="hidden" name="booking_id" id="payBookingId">
                <input type="hidden" name="action" value="pay_booking">
                
                <div class="form-group" style="text-align:left; margin-top:1rem;">
                    <label>Select Mobile Money Provider</label>
                    <select name="provider" class="form-control" required>
                        <option value="mtn">MTN MoMo</option>
                        <option value="telecel">Telecel Cash</option>
                    </select>
                </div>
                <div class="form-group" style="text-align:left;">
                    <label>Wallet Number</label>
                    <input type="tel" name="phone" class="form-control" placeholder="024xxxxxxx" required>
                </div>

                <div class="modal-actions" style="justify-content:center;">
                    <button type="button" onclick="closeModal('paymentModal')" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-submit" style="background:#10b981;">Pay Now</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Review Modal -->
    <div id="reviewModal" class="modal">
        <div class="modal-content">
            <h3>Write a Review</h3>
            <p>How was your experience at <strong id="reviewVendorName"></strong>?</p>
            <form id="reviewForm">
                <input type="hidden" name="booking_id" id="reviewBookingId">
                <input type="hidden" name="vendor_id" id="reviewVendorId">
                
                <div class="form-group">
                    <label>Rating</label>
                    <select name="rating" class="form-control">
                        <option value="5">★★★★★ (Excellent)</option>
                        <option value="4">★★★★☆ (Good)</option>
                        <option value="3">★★★☆☆ (Average)</option>
                        <option value="2">★★☆☆☆ (Poor)</option>
                        <option value="1">★☆☆☆☆ (Terrible)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Comments</label>
                    <textarea name="review_text" class="form-control" rows="4" placeholder="Tell us about the food and service..."></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" onclick="closeModal('reviewModal')" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-submit">Submit Review</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Booking Details Modal -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <h3>Reservation Details</h3>
            <div id="detailsContent" style="margin:1rem 0; line-height:1.6; color:#374151;">Loading...</div>
            <div class="modal-actions">
                <button type="button" onclick="closeModal('detailsModal')" class="btn-cancel" style="width:100%;">Close</button>
            </div>
        </div>
    </div>

    <script src="../js/customer_dashboard.js"></script>
</body>
</html>