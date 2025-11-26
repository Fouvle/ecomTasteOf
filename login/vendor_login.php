<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vendor Login | TasteConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Internal CSS to match the TasteConnect Orange Theme */
        :root {
            --primary-orange: #ea580c;
            --primary-hover: #c2410c;
            --dark-text: #111827;
            --gray-text: #6b7280;
            --light-bg: #f9fafb;
            --white: #ffffff;
            --border-color: #e5e7eb;
            --font-main: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--light-bg);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: var(--dark-text);
        }

        .auth-container {
            background: var(--white);
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 400px;
            border: 1px solid var(--border-color);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-orange);
            text-decoration: none;
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        h3 { margin: 0; color: var(--dark-text); }
        
        .vendor-badge {
            background-color: #fff7ed;
            color: var(--primary-orange);
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid #fed7aa;
            margin-top: 0.5rem;
            display: inline-block;
        }

        .form-group { margin-bottom: 1.2rem; }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.1);
        }

        .btn {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary-orange);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn:hover { background-color: var(--primary-hover); }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--gray-text);
        }

        .auth-footer a {
            color: var(--primary-orange);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover { text-decoration: underline; }
        
        .alert {
            padding: 0.75rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            text-align: center;
        }
        .alert-error { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <a href="../index.php" class="logo">TasteConnect</a>
            <h3>Vendor Portal</h3>
            <div class="vendor-badge">Business Account</div>
        </div>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>

        <form action="../actions/vendor_login_action.php" method="POST">
            <div class="form-group">
                <label>Business Email</label>
                <input type="email" name="email" class="form-control" placeholder="owner@business.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="vendor_login_btn" class="btn">Access Dashboard</button>
        </form>
        
        <div class="auth-footer">
            Not a vendor yet? <a href="vendor_register.php">Register your business</a>
            <br><br>
            <a href="login.php" style="color: #6b7280; font-size: 0.85rem;">Looking for customer login?</a>
        </div>
    </div>
</body>
</html>