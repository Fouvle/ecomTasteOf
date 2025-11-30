<?php
session_start();
if (!isset($_SESSION['vendor_id'])) { header("Location: ../login/vendor_login.php"); exit; }
require_once "../settings/db_cred.php";

$vendor_id = $_SESSION['vendor_id'];

// Fetch Reviews
$sql = "SELECT r.*, c.customer_name 
        FROM reviews r 
        JOIN customer c ON r.customer_id = c.customer_id 
        WHERE r.vendor_id = ? 
        ORDER BY r.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reviews | Vendor Dashboard</title>
    <link rel="stylesheet" href="../assets/dashboard_style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <nav class="side-nav">
                <!-- Nav Links (Same as above) -->
                <a href="vendor_dashboard.php" class="nav-item"><i class="fas fa-th-large"></i> Overview</a>
                <a href="reviews_admin.php" class="nav-item active"><i class="fas fa-comment-alt"></i> Reviews</a>
                <!-- ... other links ... -->
            </nav>
        </aside>

        <main class="content-area">
            <h2>Customer Reviews</h2>
            <div class="booking-list">
                <?php foreach($reviews as $r): ?>
                <div class="booking-item" style="align-items:flex-start;">
                    <div>
                        <div class="bk-name"><?= htmlspecialchars($r['customer_name']) ?></div>
                        <div style="color:#f59e0b; margin:0.3rem 0;">
                            <?php for($i=0; $i<$r['rating']; $i++) echo '★'; ?>
                        </div>
                        <p style="color:#4b5563; font-size:0.9rem;"><?= htmlspecialchars($r['review_text']) ?></p>
                        <small style="color:#9ca3af;"><?= date('M d, Y', strtotime($r['created_at'])) ?></small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>