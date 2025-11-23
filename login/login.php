<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | TasteConnect</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .auth-container { max-width: 400px; margin: 100px auto; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="auth-container">
        <h2 style="text-align:center; color: var(--primary-orange);">TasteConnect</h2>
        <h3 style="text-align:center;">Welcome Back</h3>
        
        <form action="../actions/login_customer_action.php" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login_btn" class="btn btn-primary" style="width:100%;">Login</button>
        </form>
        <p style="text-align:center; margin-top:1rem;">
            New here? <a href="register.php" style="color:var(--primary-orange);">Create an account</a>
        </p>
    </div>
</body>
</html>