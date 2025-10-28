<?php
session_start();
require_once '../settings/core.php';
require_once '../controllers/brand_controller.php';
require_once '../controllers/category_controller.php';

// 1. Security Check: Redirect if not logged in or not admin
redirectIfNotLoggedIn();
// if (!isAdmin()) {
//     header('Location: ../login/login.php');
//     exit();
// }

// 2. RETRIEVE Categories for the "Add Brand" dropdown
$categories = get_all_categories_ctr();
// The brand list will be loaded dynamically via JS (fetch_brand_action.php)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Brands</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header-bg { background: linear-gradient(135deg, #35524a, #d1b97f); color: white; border-radius: 15px 15px 0 0; padding: 20px; }
        .btn-custom { background-color: #35524a; border-color: #35524a; }
        .btn-custom:hover { background-color: #55726a; border-color: #55726a; }
        .brand-list-container { max-height: 60vh; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="card">
            <div class="header-bg">
                <h1 class="mb-0">Brand Management</h1>
                <p class="text-white-50">Create, Update, and Delete product brands.</p>
            </div>
            <div class="card-body">

                <h3 class="mb-3">Add New Brand</h3>
                <form id="addBrandForm" class="row g-3 mb-5">
                    <div class="col-md-5">
                        <input type="text" name="brand_name" id="newBrandName" class="form-control" placeholder="Enter brand name" required>
                    </div>
                    <div class="col-md-5">
                        <select name="cat_id" id="brandCategory" class="form-select" required>
                            <option value="">Select Category...</option>
                            <?php if ($categories): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['cat_id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No categories found. Add one first!</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-custom w-100">Add Brand</button>
                    </div>
                </form>

                <h3 class="mb-4">Existing Brands (Organized by Category)</h3>
                <div id="brandListContainer" class="brand-list-container p-3 border rounded">
                    <p class="text-center text-muted">Loading brands...</p>
                    <div id="brandAccordion" class="accordion">
                        </div>
                </div>

            </div>
        </div>
    </div>
    
    <div class="modal fade" id="brandModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Brand</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
              <form id="editBrandForm">
                  <input type="hidden" id="brandId" name="brand_id">
                  <div class="mb-3">
                      <label for="brandName" class="form-label">Brand Name (Only Name is editable)</label>
                      <input type="text" class="form-control" id="brandName" name="brand_name" required>
                  </div>
              </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" id="saveBrandBtn">Save Changes</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/brand.js"></script>
</body>
</html>