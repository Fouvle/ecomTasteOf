<?php
session_start();
require_once "../Settings/core.php";
require_once "../Controllers/category_controller.php";

// Ensure user is logged in
if (!isLoggedIn()) {
    header("Location: ../Login/login.php");
    exit();
}

// Fetch categories owned by logged-in user
$categories = getUserCategories($_SESSION['id']);
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
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Category Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($categories as $index => $category): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($category['category_name']) ?></td>
                <td>
                    <?php 
                        if ($category['is_approved'] == 1) echo "<span class='badge bg-success'>Approved</span>";
                        elseif ($category['is_approved'] == 2) echo "<span class='badge bg-danger'>Rejected</span>";
                        else echo "<span class='badge bg-warning text-dark'>Pending</span>";
                    ?>
                </td>
                <td>
                    <button class="btn btn-info btn-sm view-category-btn" 
                            data-id="<?= $category['category_id'] ?>" 
                            data-name="<?= htmlspecialchars($category['category_name']) ?>">
                        View / Edit
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
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
    <script>
        let selectedCategoryId = null;

        // Open modal with category details
        $(document).on("click", ".view-category-btn", function() {
            selectedCategoryId = $(this).data("id");
            $("#catId").val($(this).data("id"));
            $("#catName").val($(this).data("name"));

            let modal = new bootstrap.Modal(document.getElementById('categoryModal'));
            modal.show();
        });

        // Add Category
        $("#addCategoryForm").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "../Actions/add_category_action.php",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    alert(response.message);
                    if (response.status === "success") location.reload();
                },
                error: function(xhr, status, error) {
                    alert("Add failed: " + error);
                }
            });
        });

        // Save category changes
        $("#saveCategoryBtn").click(function() {
            $.ajax({
                url: "../Actions/update_category_action.php",
                type: "POST",
                data: $("#editCategoryForm").serialize(),
                dataType: "json",
                success: function(response) {
                    alert(response.message);
                    if (response.status === "success") location.reload();
                },
                error: function(xhr, status, error) {
                    alert("Update failed: " + error);
                }
            });
        });

        // Delete category
        $("#deleteCategoryBtn").click(function() {
            if (!confirm("Are you sure you want to delete this category?")) return;

            $.ajax({
                url: "../Actions/delete_category_action.php",
                type: "POST",
                data: { category_id: selectedCategoryId },
                dataType: "json",
                success: function(response) {
                    alert(response.message);
                    if (response.status === "success") location.reload();
                },
                error: function(xhr, status, error) {
                    alert("Delete failed: " + error);
                }
            });
        });
    </script>
</body>
</html>
