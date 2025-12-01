<?php
session_start();
require_once "../controllers/booking_controller.php";

// Ensure Login
if (!isset($_SESSION['customer_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$bookings = get_customer_bookings_ctr($_SESSION['customer_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings | TasteConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css"> <!-- Reuse main styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root { --primary-orange: #ea580c; }
        body { background-color: #f9fafb; font-family: 'Segoe UI', sans-serif; }
        
        .container { max-width: 1000px; margin: 2rem auto; padding: 0 1rem; }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .page-header h1 { color: #111827; margin: 0; }

        .booking-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-left: 5px solid var(--primary-orange);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .booking-info h3 { margin: 0 0 0.5rem 0; color: #111827; }
        .booking-meta { color: #6b7280; font-size: 0.95rem; margin-bottom: 0.3rem; }
        .booking-meta i { width: 20px; color: var(--primary-orange); }

        .status-badge {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
        .status-confirmed { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .status-cancelled { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        .btn-nav { text-decoration: none; color: #111827; font-weight: 500; }
        .btn-nav:hover { color: var(--primary-orange); }
    </style>
</head>
<body>

    <div class="container">
        <div class="page-header">
            <h1>My Bookings</h1>
            <a href="../index.php" class="btn-nav"><i class="fas fa-home"></i> Home</a>
        </div>

        <?php if (empty($bookings)): ?>
            <div style="text-align:center; padding:4rem; background:white; border-radius:12px;">
                <i class="fas fa-calendar-times" style="font-size:3rem; color:#d1d5db; margin-bottom:1rem;"></i>
                <h3>No bookings yet</h3>
                <p style="color:#6b7280;">Discover amazing vendors and book your first table!</p>
                <a href="all_products.php" style="display:inline-block; margin-top:1rem; padding:0.8rem 1.5rem; background:var(--primary-orange); color:white; text-decoration:none; border-radius:6px; font-weight:bold;">Find Vendors</a>
            </div>
        <?php else: ?>
            <?php foreach ($bookings as $bk): 
                $statusClass = 'status-' . strtolower($bk['booking_status']);
            ?>
            <div class="booking-card">
                <div class="booking-info">
                    <h3><?= htmlspecialchars($bk['business_name']) ?></h3>
                    <div class="booking-meta">
                        <i class="fas fa-calendar"></i> <?= date('F j, Y', strtotime($bk['booking_datetime'])) ?>
                    </div>
                    <div class="booking-meta">
                        <i class="fas fa-clock"></i> <?= date('g:i A', strtotime($bk['booking_datetime'])) ?>
                    </div>
                    <div class="booking-meta">
                        <i class="fas fa-user-friends"></i> <?= $bk['number_of_people'] ?> People
                    </div>
                    <div class="booking-meta">
                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($bk['business_address']) ?>
                    </div>
                </div>
                
                <div style="text-align:right;">
                    <span class="status-badge <?= $statusClass ?>"><?= $bk['booking_status'] ?></span>
                    <!-- Optional: Add Cancel Button Logic later -->
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>
</html>