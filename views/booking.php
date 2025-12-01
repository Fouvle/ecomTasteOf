<?php
session_start();
require_once "../settings/db_cred.php";

// Ensure User is Logged In
if (!isset($_SESSION['customer_id'])) {
    header("Location: ../login/login.php?error=Please login to book");
    exit();
}

// Get Vendor Details
$vendor_id = isset($_GET['vendor_id']) ? (int)$_GET['vendor_id'] : 0;
$sql = "SELECT * FROM vendors WHERE vendor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$vendor = $stmt->get_result()->fetch_assoc();

if (!$vendor) {
    die("Vendor not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Table | <?= htmlspecialchars($vendor['business_name']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-orange: #ea580c;
            --primary-hover: #c2410c;
            --dark-text: #111827;
            --light-bg: #f9fafb;
            --white: #ffffff;
            --border-color: #e5e7eb;
            --font-main: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--light-bg);
            color: var(--dark-text);
            margin: 0;
            padding: 0;
        }

        /* Navbar (Reused) */
        .navbar {
            background: var(--white);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
        }
        .logo { font-weight: bold; font-size: 1.2rem; color: var(--primary-orange); text-decoration: none; }
        .logo span { background: var(--primary-orange); color: white; padding: 4px 8px; border-radius: 6px; margin-right: 8px; }

        /* Booking Container */
        .booking-container {
            max-width: 800px;
            margin: 3rem auto;
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .booking-header {
            background: var(--primary-orange);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .booking-header h2 { margin: 0; font-size: 1.8rem; }
        .booking-header p { opacity: 0.9; margin-top: 0.5rem; }

        .booking-body { padding: 2rem; }

        /* Form Grid */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .form-full { grid-column: 1 / -1; }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark-text);
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
            font-family: inherit;
        }
        .form-control:focus { outline: 2px solid var(--primary-orange); border-color: transparent; }

        .btn-submit {
            background: var(--primary-orange);
            color: white;
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 1rem;
        }
        .btn-submit:hover { background: var(--primary-hover); }

        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .booking-container { margin: 1rem; }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="../index.php" class="logo"><span>TC</span> TasteConnect</a>
    <a href="all_products.php" style="color:var(--gray-text); text-decoration:none; font-weight:500;">Back to Discover</a>
</nav>

<div class="booking-container">
    <div class="booking-header">
        <h2>Book a Table</h2>
        <p>at <?= htmlspecialchars($vendor['business_name']) ?></p>
    </div>

    <div class="booking-body">
        <form id="bookingForm">
            <input type="hidden" name="vendor_id" value="<?= $vendor['vendor_id'] ?>">
            
            <div class="form-grid">
                <div class="form-group">
                    <label><i class="fas fa-calendar-alt"></i> Date</label>
                    <input type="date" name="date" class="form-control" required min="<?= date('Y-m-d') ?>">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-clock"></i> Time</label>
                    <input type="time" name="time" class="form-control" required>
                </div>

                <div class="form-group form-full">
                    <label><i class="fas fa-user-friends"></i> Number of People</label>
                    <select name="people" class="form-control" required>
                        <option value="1">1 Person</option>
                        <option value="2" selected>2 People</option>
                        <option value="3">3 People</option>
                        <option value="4">4 People</option>
                        <option value="5">5 People</option>
                        <option value="6">6 People</option>
                        <option value="8">8+ People (Large Group)</option>
                    </select>
                </div>

                <div class="form-group form-full">
                    <label><i class="fas fa-comment-alt"></i> Special Requests (Optional)</label>
                    <textarea class="form-control" rows="3" placeholder="Allergies, special occasion, etc..."></textarea>
                </div>
            </div>

            <button type="submit" class="btn-submit">Confirm Reservation</button>
        </form>
    </div>
</div>

<script src="../js/booking.js"></script>

</body>
</html>