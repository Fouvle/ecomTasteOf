<?php
session_start();
require_once "../settings/core.php";

// Ensure user is logged in
if (!isLoggedIn()) {
    header("Location: ../login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="p-4">

    <h2 class="mb-4">Your Categories</h2>

    <!-- Add Category Form -->
    <form id="addCategoryForm" class="row g-3 mb-4">
        <div class="col-md-6">
            <input type="text" name="category_name" id="newCategoryName" class="form-control" placeholder="Enter new category">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Add Category</button>
        </div>
    </form>

    <!-- Categories Table -->
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
                      <input type="text" class="form-control" id="catName" name="category_name">
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
