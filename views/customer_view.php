<?php
session_start();
require_once "../settings/core.php";

// Check if user is logged in
if (!isLoggedIn()) {
    header("Location: ../login/login.php");
    exit();
}

// Redirect admin users to admin dashboard
if (isAdmin()) {
    header("Location: ../admin/admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories - TasteConnect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #d77a61, #f6bd60);
            margin-bottom: 2rem;
        }
        .welcome-message {
            color: #fff;
            font-weight: bold;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #d77a61, #f6bd60);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #c56952, #e5a952);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">TasteConnect</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text welcome-message me-3">
                    Welcome, <?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?>
                </span>
                <a href="../actions/logout_action.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card p-4">
            <h2 class="mb-4 text-center">Your Categories</h2>

            <!-- Add Category Form -->
            <form id="addCategoryForm" class="row g-3 mb-4">
                <div class="col-md-8">
                    <input type="text" name="category_name" id="newCategoryName" class="form-control" placeholder="Enter new category name" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Add Category</button>
                </div>
            </form>

            <!-- Categories Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="categoryTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Populated dynamically via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Category Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
              <form id="editCategoryForm">
                  <input type="hidden" id="catId" name="category_id">
                  <div class="mb-3">
                      <label for="catName" class="form-label">Category Name</label>
                      <input type="text" class="form-control" id="catName" name="category_name" required>
                  </div>
              </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" id="saveCategoryBtn">Save Changes</button>
            <button type="button" class="btn btn-danger" id="deleteCategoryBtn">Delete</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="customerview.js"></script>
</body>
</html>