<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>TasteConnect - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@700&family=Pacifico&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Playfair Display', serif;
            background: #faf4ed;
            background-image: url('../images/boho-pattern.jpg');
            background-size: cover;
            background-blend-mode: overlay;
            background-color: rgba(250, 244, 237, 0.95);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: #fffaf0;
            border: 2px solid #d7b89c;
            border-radius: 20px;
            box-shadow: 0px 8px 18px rgba(0, 0, 0, 0.15);
            padding: 30px;
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        .card-header {
            background: linear-gradient(135deg, #d77a61, #f6bd60);
            border-radius: 15px 15px 0 0;
            color: #fffaf0;
            font-family: 'Pacifico', cursive;
            font-size: 1.8rem;
            padding: 15px;
        }

        .form-label {
            font-weight: 600;
            color: #35524a;
        }

        .btn-custom {
            background: linear-gradient(135deg, #d77a61, #f6bd60);
            border: none;
            border-radius: 30px;
            padding: 12px;
            color: #fffaf0;
            font-weight: bold;
            font-family: 'Amatic SC', cursive;
            font-size: 20px;
            transition: transform 0.2s;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #a64b2a, #d49a4a);
        }

        .highlight {
            color: #d77a61;
            font-weight: bold;
        }

        .highlight:hover {
            color: #814c4c;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-header">TasteConnect Login</div>
        <div class="card-body">
            <form method="POST" id="login-form">
                <div class="mb-3 text-start">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-4 text-start">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-custom w-100">Login</button>
            </form>
        </div>
        <div class="card-footer">
            <p>Don't have an account? <a href="register.php" class="highlight">Register here</a></p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/login.js"></script>
</body>

</html>