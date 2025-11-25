<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | TasteConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- jQuery for interactivity -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            padding: 2rem 0;
        }

        .auth-card {
            background: var(--white);
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 550px; /* Wider for register form */
            border: 1px solid var(--border-color);
        }

        .auth-header { text-align: center; margin-bottom: 2rem; }
        .auth-header h2 { color: var(--primary-orange); margin: 0; font-size: 1.8rem; }

        .form-row { display: flex; gap: 1rem; }
        .form-col { flex: 1; }

        .form-group { margin-bottom: 1.2rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem; color: var(--dark-text); }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
            box-sizing: border-box; 
            font-family: inherit;
        }
        .form-control:focus { outline: 2px solid var(--primary-orange); border-color: transparent; }

        /* Vendor Toggle Section */
        .vendor-section {
            background-color: #fff7ed;
            border: 1px solid #fed7aa;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1.5rem;
        }

        .toggle-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-weight: bold;
            color: var(--primary-hover);
            margin: 0;
        }
        
        .toggle-label input { width: 18px; height: 18px; accent-color: var(--primary-orange); }

        #vendor-fields { display: none; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #fed7aa; }

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
            margin-top: 1rem;
        }
        .btn-auth:hover { background-color: var(--primary-hover); }

        .auth-footer { text-align: center; margin-top: 1.5rem; font-size: 0.9rem; }
        .auth-footer a { color: var(--primary-orange); text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="auth-header">
        <h2>TasteConnect</h2>
        <p>Create an Account</p>
    </div>

    <?php if(isset($_GET['error'])): ?>
        <div style="background:#fee2e2; color:#991b1b; padding:0.8rem; border-radius:6px; margin-bottom:1rem; text-align:center;">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <form action="../actions/register_action.php" method="POST" enctype="multipart/form-data">
        <!-- Customer Information -->
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="customer_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="customer_email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="customer_pass" class="form-control" required>
        </div>

        <div class="form-row">
            <div class="form-col form-group">
                <label>Country</label>
                <input type="text" name="customer_country" class="form-control" placeholder="Ghana" required>
            </div>
            <div class="form-col form-group">
                <label>City</label>
                <input type="text" name="customer_city" class="form-control" placeholder="Accra" required>
            </div>
        </div>

        <div class="form-group">
            <label>Contact Number</label>
            <input type="text" name="customer_contact" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Profile Image (Optional)</label>
            <input type="file" name="customer_image" class="form-control" accept="image/*">
        </div>

        <!-- Vendor Toggle -->
        <div class="vendor-section">
            <label class="toggle-label">
                <input type="checkbox" id="is_vendor" name="is_vendor" value="1">
                I want to sell food (Register as Vendor)
            </label>

            <!-- Hidden Vendor Fields -->
            <div id="vendor-fields">
                <div class="form-group">
                    <label>Business Name</label>
                    <input type="text" name="business_name" id="business_name" class="form-control" placeholder="e.g. Mama Esi's Kitchen">
                </div>
                <div class="form-group">
                    <label>Business Address</label>
                    <input type="text" name="business_address" id="business_address" class="form-control" placeholder="e.g. 123 Osu Road">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="business_description" class="form-control" rows="2" placeholder="Short description..."></textarea>
                </div>
            </div>
        </div>

        <button type="submit" name="register_btn" class="btn-auth">Create Account</button>
    </form>

    <div class="auth-footer">
        Already have an account? <a href="login.php">Login</a>
    </div>
</div>

<!-- Script for Vendor Toggle -->
<script src="../js/register.js"></script>

</body>
</html>