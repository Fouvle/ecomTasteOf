<?php
session_start();
$reference = $_GET['reference'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verifying Payment | TasteConnect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f9fafb; margin: 0; }
        .card { background: white; padding: 3rem; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); text-align: center; max-width: 400px; width: 90%; }
        .spinner { font-size: 3rem; color: #ea580c; animation: spin 1s linear infinite; margin-bottom: 1.5rem; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        h2 { color: #111827; margin-bottom: 0.5rem; }
        p { color: #6b7280; }
        .success-icon { font-size: 4rem; color: #10b981; margin-bottom: 1rem; display: none; }
        .error-icon { font-size: 4rem; color: #ef4444; margin-bottom: 1rem; display: none; }
    </style>
</head>
<body>

    <div class="card">
        <!-- Loading State -->
        <div id="loading">
            <i class="fas fa-circle-notch spinner"></i>
            <h2>Verifying Payment</h2>
            <p>Please wait while we confirm your transaction...</p>
        </div>

        <!-- Success State -->
        <div id="success" style="display:none;">
            <i class="fas fa-check-circle success-icon" style="display:block;"></i>
            <h2>Payment Successful!</h2>
            <p>Your booking has been confirmed.</p>
            <p>Redirecting...</p>
        </div>

        <!-- Error State -->
        <div id="error" style="display:none;">
            <i class="fas fa-times-circle error-icon" style="display:block;"></i>
            <h2>Verification Failed</h2>
            <p id="errorMsg">Something went wrong.</p>
            <a href="customer_dashboard.php" style="display:inline-block; margin-top:1rem; text-decoration:none; color:#ea580c; font-weight:bold;">Return to Dashboard</a>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const ref = "<?= htmlspecialchars($reference) ?>";
            
            if(!ref) {
                showError("No reference provided.");
                return;
            }

            $.ajax({
                url: '../actions/verify_payment.php',
                method: 'POST',
                data: JSON.stringify({ reference: ref }),
                contentType: 'application/json',
                success: function(res) {
                    if(res.status === 'success') {
                        $('#loading').hide();
                        $('#success').show();
                        setTimeout(() => window.location.href = 'customer_dashboard.php', 2500);
                    } else {
                        showError(res.message);
                    }
                },
                error: function() {
                    showError("Server connection error.");
                }
            });

            function showError(msg) {
                $('#loading').hide();
                $('#errorMsg').text(msg);
                $('#error').show();
            }
        });
    </script>
</body>
</html>