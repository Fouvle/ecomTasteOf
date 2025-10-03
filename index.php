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

        /* Navbar */
        .navbar {
            background: #2c3e50;
            padding: 1rem 2rem;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            position: relative;
        }

        .navbar a, .navbar button {
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

        .navbar a:hover, .navbar button:hover {
            background: #2980b9;
        }

        /* Dropdown */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #3498db;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 5px;
        }

        .dropdown-content a, .dropdown-content form button {
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            display: block;
            background: #3498db;
            border: none;
            text-align: left;
            width: 100%;
            box-sizing: border-box;
        }

        .dropdown-content a:hover, .dropdown-content form button:hover {
            background: #2980b9;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Welcome Section */
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
            <!-- Not logged in -->
            <a href="register/register.php">Register</a>
            <a href="login/login.php">Login</a>
        <?php else: ?>
            <!-- Logged in users -->
            <div class="dropdown">
                <button>Hello, <?php echo htmlspecialchars($_SESSION['customer_name']); ?> ▼</button>
                <div class="dropdown-content">
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="admin/category.php">Category Management</a>
                    <?php endif; ?>
                    <form action="logout.php" method="POST">
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
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
