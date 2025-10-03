<?php
session_start();
require_once "../Settings/core.php"; 
require_once "../Settings/db_connection.php"; // make sure this connects to DB

// Redirect to login if not logged in
if (!isLoggedIn()) {
    header("Location: ../Login/login_register.php");
    exit;
}

// Get filter values
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$created_filter = isset($_GET['created']) ? $_GET['created'] : 'all';

// Build query
$sql = "SELECT c.id, c.name, c.status, u.username AS created_by 
        FROM categories c 
        JOIN users u ON c.user_id = u.id 
        WHERE 1=1";

// Filter by status
if ($status_filter !== 'all') {
    $sql .= " AND c.status = :status";
}

// Filter by created
if ($created_filter === 'mine') {
    $sql .= " AND c.user_id = :userid";
}

$stmt = $pdo->prepare($sql);

// Bind filters
if ($status_filter !== 'all') {
    $stmt->bindValue(':status', $status_filter, PDO::PARAM_STR);
}
if ($created_filter === 'mine') {
    $stmt->bindValue(':userid', $_SESSION['id'], PDO::PARAM_INT);
}

$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer View - Categories</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h2>Browse Categories</h2>

        <!-- Filters -->
        <form method="GET" class="filters">
            <label>Status:</label>
            <select name="status">
                <option value="all" <?= $status_filter=='all'?'selected':'' ?>>All</option>
                <option value="Approved" <?= $status_filter=='Approved'?'selected':'' ?>>Approved</option>
                <option value="Pending" <?= $status_filter=='Pending'?'selected':'' ?>>Pending</option>
                <option value="Rejected" <?= $status_filter=='Rejected'?'selected':'' ?>>Rejected</option>
            </select>

            <label>Created By:</label>
            <select name="created">
                <option value="all" <?= $created_filter=='all'?'selected':'' ?>>All Users</option>
                <option value="mine" <?= $created_filter=='mine'?'selected':'' ?>>My Categories</option>
            </select>

            <button type="submit">Apply Filters</button>
        </form>

        <!-- Display Categories -->
        <table>
            <thead>
                <tr>
                    <th>Category ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Created By</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($categories): ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?= htmlspecialchars($cat['id']); ?></td>
                            <td><?= htmlspecialchars($cat['name']); ?></td>
                            <td><?= htmlspecialchars($cat['status']); ?></td>
                            <td><?= htmlspecialchars($cat['created_by']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">No categories found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Add New Category -->
        <h3>Suggest a New Category</h3>
        <form method="POST" action="add_category.php">
            <input type="text" name="category_name" placeholder="Enter category name" required>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
