<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: #2c3e50;
            padding: 1rem 2rem;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .navbar a, .navbar form button {
            margin-left: 1rem;
            text-decoration: none;
            padding: 0.5rem 1rem;
            background: #3498db;
            color: #fff;
            border-radius: 5px;
            transition: 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .navbar a:hover, .navbar form button:hover {
            background: #2980b9;
        }

        .container {
            text-align: center;
            margin-top: 10%;
        }

        h2 {
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <!-- Menu Tray -->
    <div class="navbar">
        <?php if (!isset($_SESSION['customer_id'])): ?>
            <!-- Not logged in: show Register / Login -->
            <a href="register/register.php">Register</a>
            <a href="login/login.php">Login</a>
        <?php else: ?>
            <!-- Logged in: show Logout -->
            <form action="logout.php" method="POST" style="display:inline;">
                <button type="submit">Logout</button>
            </form>
        <?php endif; ?>
    </div>

    <!-- Welcome Section -->
    <div class="container">
        <?php if (isset($_SESSION['customer_name'])): ?>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>!</h2>
        <?php else: ?>
            <h2>Welcome to Our Website</h2>
            <p>Please register or login to continue.</p>
        <?php endif; ?>
    </div>
</body>
</html>
