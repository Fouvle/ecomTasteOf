<?php
session_start();
require_once '../classes/db_class.php';

$db = new db_connection();

// Check if logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: ../login/login.php');
    exit();
}

$isAdmin = ($_SESSION['role'] === 'admin');

// Fetch all categories
$categories = $db->db_fetch_all("SELECT * FROM categories ORDER BY cat_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Customer View</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #2c3e50;
            color: white;
        }
        tr:nth-child(even) {
            background: #f8f8f8;
        }
        .approve-btn {
            background: #27ae60;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
        }
        .approve-btn:hover {
            background: #2ecc71;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Available Categories</h2>
        <?php if ($categories): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <?php if ($isAdmin): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= htmlspecialchars($cat['cat_id']) ?></td>
                        <td><?= htmlspecialchars($cat['cat_name']) ?></td>
                        <?php if ($isAdmin): ?>
                            <td>
                                <form method="POST" action="approve_category.php" style="display:inline;">
                                    <input type="hidden" name="cat_id" value="<?= $cat['cat_id'] ?>">
                                    <button type="submit" class="approve-btn">Approve</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No categories found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
