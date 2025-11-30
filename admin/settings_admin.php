<?php
session_start();
if (!isset($_SESSION['vendor_id'])) { header("Location: ../login/vendor_login.php"); exit; }
require_once "../settings/db_cred.php";

$vendor_id = $_SESSION['vendor_id'];

// Handle Update
if(isset($_POST['update_settings'])) {
    $name = $_POST['business_name'];
    $phone = $_POST['business_phone'];
    $desc = $_POST['business_description'];
    
    $sql = "UPDATE vendors SET business_name=?, business_phone=?, business_description=? WHERE vendor_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $phone, $desc, $vendor_id);
    if($stmt->execute()) $msg = "Settings updated successfully.";
}

// Fetch Current Settings
$sql = "SELECT * FROM vendors WHERE vendor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$v = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings | Vendor Dashboard</title>
    <link rel="stylesheet" href="../assets/dashboard_style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar">
            <nav class="side-nav">
                <!-- Nav Links -->
                <a href="vendor_dashboard.php" class="nav-item"><i class="fas fa-th-large"></i> Overview</a>
                <a href="settings_admin.php" class="nav-item active"><i class="fas fa-cog"></i> Settings</a>
            </nav>
        </aside>

        <main class="content-area">
            <h2>Account Settings</h2>
            <?php if(isset($msg)) echo "<p style='color:green;'>$msg</p>"; ?>
            
            <form method="POST" style="max-width:600px; background:white; padding:2rem; border-radius:12px; border:1px solid #e5e7eb;">
                <div class="form-group">
                    <label>Business Name</label>
                    <input type="text" name="business_name" value="<?= htmlspecialchars($v['business_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="business_phone" value="<?= htmlspecialchars($v['business_phone']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="business_description" rows="4"><?= htmlspecialchars($v['business_description']) ?></textarea>
                </div>
                <button type="submit" name="update_settings" class="btn-submit">Save Changes</button>
            </form>
        </main>
    </div>
</body>
</html>