<?php
session_start();
require_once '../settings/core.php';
require_once '../controllers/product_controller.php';

$productId = intval($_GET['id'] ?? 0);

if ($productId === 0) {
    // Handle case where no ID is provided
    header("Location: all_product.php");
    exit();
}

$product = view_single_product_ctr($productId);

if (!$product) {
    // Handle product not found
    $pageTitle = "Product Not Found";
} else {
    $pageTitle = htmlspecialchars($product['product_title']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TasteConnect - <?= $pageTitle ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .product-detail-card { border-radius: 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .product-main-image { max-width: 100%; height: auto; border-radius: 15px; }
        .price-large { font-size: 3rem; color: #d77a61; font-weight: bold; }
    </style>
</head>
<body>
    <?php include 'header_nav.php'; // Assume a reusable navigation header ?>

    <div class="container my-5">
        <?php if (!$product): ?>
            <div class="alert alert-danger text-center" role="alert">
                Sorry, the product you are looking for was not found.
            </div>
        <?php else: 
            // Assuming the image path is relative to the project root
            $imagePath = $product['product_image'] ? '../' . $product['product_image'] : '../images/placeholder.png';
        ?>
            <div class="row product-detail-card bg-white p-4">
                <div class="col-md-6">
                    <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($product['product_title']) ?>" class="product-main-image">
                </div>
                <div class="col-md-6">
                    <h1 class="display-4 mb-3"><?= htmlspecialchars($product['product_title']) ?></h1>
                    
                    <p class="text-muted small">ID: #<?= $product['product_id'] ?> | Keywords: <?= htmlspecialchars($product['product_keywords'] ?? 'N/A') ?></p>
                    
                    <div class="d-flex align-items-center mb-4">
                        <span class="price-large">GHS <?= number_format($product['product_price'], 2) ?></span>
                    </div>

                    <p class="mb-4"><?= nl2br(htmlspecialchars($product['product_desc'])) ?></p>

                    <table class="table table-bordered table-sm mb-4">
                        <tr>
                            <th>Category</th>
                            <td><?= htmlspecialchars($product['cat_name']) ?></td>
                        </tr>
                        <tr>
                            <th>Brand/Vendor</th>
                            <td><?= htmlspecialchars($product['brand_name']) ?></td>
                        </tr>
                    </table>

                    <button class="btn btn-lg btn-success w-75" id="addToCartBtn" data-id="<?= $product['product_id'] ?>">
                        Add to Cart (Placeholder)
                    </button>
                    
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>