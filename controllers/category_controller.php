<?php
require_once '../classes/category_class.php';

/**
 * Add a new category
 * @param array $kwargs ['name' => category_name, 'user_id' => logged_in_user_id]
 * @return true|string Returns true on success or error message on failure
 */
function add_category_ctr($kwargs) {
    if (!isset($kwargs['name']) || !isset($kwargs['user_id'])) {
        return "Invalid parameters provided.";
    }

    $categoryName = trim($kwargs['name']);
    $userId = intval($kwargs['user_id']);

    if (empty($categoryName)) {
        return "Category name cannot be empty.";
    }

    $category = new Category();

    // Call the addCategory method from Category class
    $result = $category->addCategory($categoryName, $userId);

    if ($result) {
        return true;
    } else {
        return "Failed to add category. It might already exist.";
    }
}

function getUserCategories($user_id) {
    $category = new Category();
    return $category->getAllCategories();
}

?>
