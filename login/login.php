<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | TasteConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css"> <!-- Assuming main styles exist here -->
    <style>
        :root {
            --primary-orange: #ea580c;
            --primary-hover: #c2410c;
            --dark-text: #111827;
            --white: #ffffff;
            --light-bg: #f9fafb;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .auth-card {
            background: var(--white);
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            border: 1px solid var(--border-color);
        }

        .auth-header { text-align: center; margin-bottom: 2rem; }
        .auth-header h2 { color: var(--primary-orange); margin: 0; font-size: 1.8rem; }
        .auth-header p { color: var(--dark-text); margin-top: 0.5rem; }

        .form-group { margin-bottom: 1.2rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem; color: var(--dark-text); }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
            box-sizing: border-box; 
        }
        .form-control:focus { outline: 2px solid var(--primary-orange); border-color: transparent; }

        .btn-auth {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary-orange);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-auth:hover { background-color: var(--primary-hover); }

        .auth-footer { text-align: center; margin-top: 1.5rem; font-size: 0.9rem; }
        .auth-footer a { color: var(--primary-orange); text-decoration: none; font-weight: 600; }
        
        .alert { padding: 0.8rem; border-radius: 6px; margin-bottom: 1rem; text-align: center; font-size: 0.9rem; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="auth-header">
        <h2>TasteConnect</h2>
        <p>Welcome Back</p>
    </div>

    <!-- Display Errors/Success Messages -->
    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <form action="../actions/login_action.php" method="POST">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
        
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>

        <button type="submit" name="login_btn" class="btn-auth">Sign In</button>
    </form>

    <div class="auth-footer">
        Don't have an account? <a href="register.php">Register</a>
    </div>
</div>

</body>
</html>