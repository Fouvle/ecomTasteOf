<?php
require_once 'db_class.php';

class Category extends db_connection {

    // Escape helper (optional, safer for queries)
    private function db_escape($value) {
        return mysqli_real_escape_string($this->db_conn(), $value);
    }

    // Add new category (default pending = 0)
    public function addCategory($user_id, $category_name) {
        $category_name = $this->db_escape($category_name);
        $user_id = (int)$user_id;

        $query = "INSERT INTO categories (category_name, user_id) VALUES ('$category_name', $user_id)";
        return $this->db_write_query($query);
    }

    // Approve category
    public function approveCategory($catId) {
        $catId = intval($catId);
        $query = "UPDATE categories SET is_approved = 1 WHERE cat_id = $catId";
        return $this->db_write_query($query);
    }

    // Reject category
    public function rejectCategory($catId) {
        $catId = intval($catId);
        $query = "UPDATE categories SET is_approved = 2 WHERE cat_id = $catId";
        return $this->db_write_query($query);
    }

    // Update category name (users can edit before approval or resubmit)
    public function updateCategory($catId, $newName) {
        $catId = intval($catId);
        $newName = $this->db_escape($newName);
        $query = "UPDATE categories SET cat_name = '$newName', is_approved = 0 WHERE cat_id = $catId";
        return $this->db_write_query($query);
    }

    // Delete category
    public function deleteCategory($catId) {
        $catId = intval($catId);
        $query = "DELETE FROM categories WHERE cat_id = $catId";
        return $this->db_write_query($query);
    }

    // Get all categories (admin use)
    public function getAllCategories() {
        $query = "SELECT * FROM categories";
        return $this->db_fetch_all($query);
    }

    // Get all pending categories
    public function getPendingCategories() {
        $query = "SELECT * FROM categories WHERE is_approved = 0";
        return $this->db_fetch_all($query);
    }

    // Get all rejected categories
    public function getRejectedCategories() {
        $query = "SELECT * FROM categories WHERE is_approved = 2";
        return $this->db_fetch_all($query);
    }

    // Get approved categories
    public function getApprovedCategories() {
        $query = "SELECT * FROM categories WHERE is_approved = 1";
        return $this->db_fetch_all($query);
    }

    // Get categories submitted by a specific user
    public function getUserCategories($userId) {
        $userId = intval($userId);
        $query = "SELECT * FROM categories WHERE user_id = $userId";
        return $this->db_fetch_all($query);
    }

    // Get one category by ID
    public function getCategoryById($catId) {
        $catId = intval($catId);
        $query = "SELECT * FROM categories WHERE cat_id = $catId";
        return $this->db_fetch_one($query);
    }

    
}
?>
