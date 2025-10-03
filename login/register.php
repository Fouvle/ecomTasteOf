<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <style>
    .btn-custom {
      background-color: #D19C97;
      border-color: #D19C97;
      color: #fff;
      transition: background-color 0.3s, border-color 0.3s;
    }
    .btn-custom:hover {
      background-color: #b77a7a;
      border-color: #b77a7a;
    }
    .highlight { color: #D19C97; transition: color 0.3s; }
    .highlight:hover { color: #b77a7a; }

    body {
      background-color: #f8f9fa;
      background-image:
        repeating-linear-gradient(0deg, #7ab793ff, #b77a7a 1px, transparent 1px, transparent 20px),
        repeating-linear-gradient(90deg, #b77a7a, #b77a7a 1px, transparent 1px, transparent 20px),
        linear-gradient(rgba(183, 122, 122, 0.1), rgba(183, 122, 122, 0.1));
      background-blend-mode: overlay;
      background-size: 20px 20px;
      min-height: 100vh;
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .register-container { margin-top: 50px; }
    .card { border: none; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .card-header { background-color: #D19C97; color: #fff; }

    .animate-pulse-custom { animation: pulse 2s infinite; }
    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }

    /* Loading spinner */
    .loading-spinner {
      display: none;
      margin-top: 15px;
      text-align: center;
      color: #D19C97;
    }
  </style>
</head>

<body>
  <div class="container register-container">
    <div class="row justify-content-center animate__animated animate__fadeInDown">
      <div class="col-md-6">
        <div class="card animate__animated animate__zoomIn">
          <div class="card-header text-center highlight">
            <h4>Register</h4>
          </div>
          <div class="card-body">
            <!-- No action attribute (handled in register.js) -->
            <form method="POST" id="register-form" enctype="multipart/form-data" class="mt-4">
              
              <div class="mb-3">
                <label for="name" class="form-label">Full Name <i class="fa fa-user"></i></label>
                <input type="text" class="form-control" id="name" name="name" required maxlength="100">
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email <i class="fa fa-envelope"></i></label>
                <input type="email" class="form-control" id="email" name="email" required maxlength="100">
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Password <i class="fa fa-lock"></i></label>
                <input type="password" class="form-control" id="password" name="password" required minlength="6">
              </div>

              <div class="mb-3">
                <label for="country" class="form-label">Country <i class="fa fa-flag"></i></label>
                <input type="text" class="form-control" id="country" name="country" required maxlength="50">
              </div>

              <div class="mb-3">
                <label for="city" class="form-label">City <i class="fa fa-building"></i></label>
                <input type="text" class="form-control" id="city" name="city" required maxlength="50">
              </div>

              <div class="mb-3">
                <label for="phone_number" class="form-label">Contact Number <i class="fa fa-phone"></i></label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" required pattern="^[0-9]{7,15}$">
              </div>

              <div class="mb-3">
                <label for="image" class="form-label">Profile Image (optional)</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
              </div>

              <!-- Hidden user role, defaults set in backend (SQL level) -->
              <input type="hidden" name="role" value="2">

              <button type="submit" class="btn btn-custom w-100 animate-pulse-custom">Register</button>
              <div class="loading-spinner" id="loading-spinner">
                <i class="fa fa-spinner fa-spin"></i> Processing...
              </div>
            </form>
          </div>
          <div class="card-footer text-center">
            Already have an account? <a href="login.php" class="highlight">Login here</a>.
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap + jQuery + SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Register logic -->
  <script src="../js/register.js"></script>
</body>
</html>
