<?php
session_start();
require_once "../controllers/review_controller.php";

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$reviews = get_customer_reviews_ctr($_SESSION['customer_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Reviews | TasteConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/dashboard_style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <div class="top-header">
        <a href="../index.php" class="exit-link"><i class="fas fa-arrow-left"></i> Home</a>
        <div class="header-title">My Reviews</div>
        <div style="width: 100px;"></div>
    </div>

    <div class="dashboard-wrapper">
        <!-- Sidebar (Reused from Customer Dashboard) -->
        <aside class="sidebar">
            <nav class="side-nav">
                <a href="customer_dashboard.php" class="nav-item"><i class="fas fa-th-large"></i> Overview</a>
                <a href="my_bookings.php" class="nav-item"><i class="fas fa-calendar-alt"></i> My Bookings</a>
                <a href="my_reviews.php" class="nav-item active"><i class="fas fa-star"></i> My Reviews</a>
                <a href="#" class="nav-item"><i class="fas fa-cog"></i> Settings</a>
            </nav>
        </aside>

        <main class="content-area">
            <h2>My Reviews History</h2>
            
            <?php if (empty($reviews)): ?>
                <div style="text-align:center; padding:3rem; color:gray;">
                    <i class="far fa-comment-dots" style="font-size:3rem; margin-bottom:1rem;"></i>
                    <p>You haven't written any reviews yet.</p>
                    <a href="my_bookings.php" style="color:#ea580c;">Go to bookings to leave a review</a>
                </div>
            <?php else: ?>
                <div style="display:grid; gap:1.5rem;">
                    <?php foreach ($reviews as $r): ?>
                    <div class="stat-card" style="padding:1.5rem; position:relative;" id="review-<?= $r['review_id'] ?>">
                        <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                            <h3 style="margin:0;"><?= htmlspecialchars($r['business_name']) ?></h3>
                            <div style="color:#f59e0b;">
                                <?php for($i=0; $i<$r['rating']; $i++) echo '★'; ?>
                                <?php for($i=$r['rating']; $i<5; $i++) echo '☆'; ?>
                            </div>
                        </div>
                        <small style="color:gray;"><?= date('M d, Y', strtotime($r['created_at'])) ?></small>
                        <p style="margin:1rem 0; color:#4b5563; line-height:1.5;"><?= htmlspecialchars($r['review_text']) ?></p>
                        
                        <div style="border-top:1px solid #eee; padding-top:1rem; display:flex; gap:1rem;">
                            <button class="action-btn btn-white" onclick="openEditModal(<?= $r['review_id'] ?>, <?= $r['rating'] ?>, '<?= addslashes($r['review_text']) ?>')"><i class="fas fa-edit"></i> Edit</button>
                            <button class="action-btn btn-white" style="color:red;" onclick="deleteReview(<?= $r['review_id'] ?>)"><i class="fas fa-trash"></i> Delete</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <!-- Edit Review Modal -->
    <div id="editReviewModal" class="modal">
        <div class="modal-content">
            <h3>Edit Review</h3>
            <form id="editReviewForm">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="review_id" id="edit_review_id">
                
                <div class="form-group">
                    <label>Rating</label>
                    <select name="rating" id="edit_rating" class="form-control">
                        <option value="5">★★★★★ (Excellent)</option>
                        <option value="4">★★★★☆ (Good)</option>
                        <option value="3">★★★☆☆ (Average)</option>
                        <option value="2">★★☆☆☆ (Poor)</option>
                        <option value="1">★☆☆☆☆ (Terrible)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Review</label>
                    <textarea name="review_text" id="edit_text" class="form-control" rows="4"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" onclick="document.getElementById('editReviewModal').style.display='none'" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-submit">Update Review</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../js/my_reviews.js"></script>
</body>
</html>