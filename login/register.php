<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>TasteConnect - Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@700&family=Pacifico&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Playfair Display', serif;
            background: #faf4ed url('../images/boho-texture.jpg') no-repeat center/cover;
            background-blend-mode: multiply;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: #fffaf0;
            border: 2px solid #c98c70;
            border-radius: 20px;
            box-shadow: 0px 8px 18px rgba(0, 0, 0, 0.15);
            padding: 30px;
            width: 100%;
            max-width: 500px;
        }

        .card-header {
            background: linear-gradient(135deg, #35524a, #d1b97f);
            border-radius: 15px 15px 0 0;
            color: #fffaf0;
            font-family: 'Pacifico', cursive;
            font-size: 1.8rem;
            text-align: center;
            padding: 15px;
        }

        .form-label {
            font-weight: 600;
            color: #3b2c2c;
        }

        .btn-custom {
            background: linear-gradient(135deg, #35524a, #d1b97f);
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
            background: linear-gradient(135deg, #2a3e38, #a68b47);
        }

        .highlight {
            color: #35524a;
            font-weight: bold;
        }

        .highlight:hover {
            color: #a64b2a;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-header">Create Your TasteConnect Account</div>
        <div class="card-body">
            <form method="POST" action="" id="register-form">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" class="form-control" id="country" name="country" required>
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" required>
                </div>
                <div class="mb-3">
                    <label for="contact" class="form-label">Contact</label>
                    <input type="text" class="form-control" id="contact" name="phone_number" required>
                </div>
                <button type="submit" class="btn btn-custom w-100">Register</button>
            </form>
        </div>
        <div class="card-footer text-center">
            <p>Already have an account? <a href="login.php" class="highlight">Login</a></p>
        </div>
    </div>
</body>

</html>