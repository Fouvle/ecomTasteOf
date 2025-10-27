<?php
require_once 'classes/brand_class.php';
require_once 'classes/category_class.php';

/**
 * Add a new brand
 */
function add_brand_ctr($brand_name, $cat_id) {
    $brand = new Brand();
    return $brand->add_brand_cls($brand_name, $cat_id);
}

/**
 * Update an existing brand
 */
function update_brand_ctr($brand_id, $brand_name) {
    $brand = new Brand();
    return $brand->update_brand_cls($brand_id, $brand_name);
}

/**
 * Delete a brand
 */
function delete_brand_ctr($brand_id) {
    $brand = new Brand();
    return $brand->delete_brand_cls($brand_id);
}

/**
 * Fetch all brands
 */
function fetch_all_brands_ctr() {
    $brand = new Brand();
    return $brand->fetch_all_brands();
}

/**
 * Fetch brands by category ID
 */
function fetch_brands_by_category_ctr($cat_id) {
    $brand = new Brand();
    return $brand->fetch_brands_by_category_cls($cat_id);
}

/**
 * get all categories for dropdown population
 */
function fetch_all_categories_ctr() {
    $category = new Category();
    return $category->fetch_all_categories_cls();
}