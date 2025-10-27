<?php
require_once '../classes/product_class.php';
// Reuse controllers for categories and brands
require_once 'brand_controller.php'; 
require_once 'category_controller.php'; 

/**
 * Add a new product
 */
function add_product_ctr($catId, $brandId, $title, $price, $desc, $keywords) {
    $product = new Product();
    return $product->addProduct($catId, $brandId, $title, $price, $desc, $keywords); // Returns new product_id or false
}

/**
 * Update an existing product
 */
function update_product_ctr($productId, $catId, $brandId, $title, $price, $desc, $keywords) {
    $product = new Product();
    return $product->updateProduct($productId, $catId, $brandId, $title, $price, $desc, $keywords);
}

/**
 * Update product image path
 */
function update_product_image_ctr($productId, $imagePath) {
    $product = new Product();
    return $product->updateProductImage($productId, $imagePath);
}

/**
 * Get all products with category and brand names
 */
function get_all_products_ctr() {
    $product = new Product();
    return $product->getAllProducts();
}

/**
 * Get a single product
 */
function get_product_ctr($productId) {
    $product = new Product();
    return $product->getProduct($productId);
}