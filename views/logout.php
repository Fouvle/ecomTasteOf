<?php
// logout.php
session_start();

// Unset all session variables
$_SESSION = array();

// If there's a session cookie, delete it
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3;url=login.php">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out - TasteConnect</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8e1c1, #f6c6d1, #cbe8d8);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #3e2723;
        }
        .logout-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            max-width: 500px;
            width: 90%;
            animation: fadeIn 1s ease-in-out;
        }
        h1 {
            font-size: 2em;
            margin-bottom: 10px;
            color: #7b3f00;
        }
        p {
            font-size: 1.1em;
            margin-bottom: 20px;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #7b3f00;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <h1>✨ You've been logged out ✨</h1>
        <p>Thank you for being part of TasteConnect.<br>
        You'll be redirected to the login page shortly.</p>
        <div class="spinner"></div>
    </div>
    <script>
            window.location.href = '../login/login.php';
    </script>
</body>
</html>
