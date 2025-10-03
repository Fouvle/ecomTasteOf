<?php
// category_class.php
include_once(dirname(__FILE__) . '/../settings/db_class.php');

class Category extends db_connection
{
    /**
     * Add a new category
     * @param string $cat_name
     * @param int $created_by
     * @return bool
     */
    public function add_category($cat_name, $created_by)
    {
        // prevent SQL injection
        $cat_name = mysqli_real_escape_string($this->db_conn(), $cat_name);

        $sql = "INSERT INTO categories (cat_name, created_by, is_approved) 
                VALUES ('$cat_name', $created_by, 0)"; // 0 = pending approval
        return $this->db_write_query($sql);
    }

    /**
     * Update category name
     * @param int $cat_id
     * @param string $new_name
     * @param int $created_by
     * @return bool
     */
    public function update_category($cat_id, $new_name, $created_by)
    {
        $new_name = mysqli_real_escape_string($this->db_conn(), $new_name);

        $sql = "UPDATE categories 
                SET cat_name = '$new_name' 
                WHERE cat_id = $cat_id AND created_by = $created_by";
        return $this->db_write_query($sql);
    }

    /**
     * Delete a category
     * @param int $cat_id
     * @param int $created_by
     * @return bool
     */
    public function delete_category($cat_id, $created_by)
    {
        $sql = "DELETE FROM categories 
                WHERE cat_id = $cat_id AND created_by = $created_by";
        return $this->db_write_query($sql);
    }

    /**
     * Get a single category by ID
     * @param int $cat_id
     * @return array|false
     */
    public function get_category($cat_id)
    {
        $sql = "SELECT * FROM categories WHERE cat_id = $cat_id";
        return $this->db_fetch_one($sql);
    }

    /**
     * Get all categories created by a specific user
     * @param int $created_by
     * @return array|false
     */
    public function get_user_categories($created_by)
    {
        $sql = "SELECT * FROM categories WHERE created_by = $created_by";
        return $this->db_fetch_all($sql);
    }

    /**
     * Get all approved categories (visible to all customers)
     * @return array|false
     */
    public function get_approved_categories()
    {
        $sql = "SELECT * FROM categories WHERE is_approved = 1";
        return $this->db_fetch_all($sql);
    }

    /**
     * Admin: Approve a category
     * @param int $cat_id
     * @return bool
     */
    public function approve_category($cat_id)
    {
        $sql = "UPDATE categories SET is_approved = 1 WHERE cat_id = $cat_id";
        return $this->db_write_query($sql);
    }

    /**
     * Admin: Reject (delete) a category
     * @param int $cat_id
     * @return bool
     */
    public function reject_category($cat_id)
    {
        $sql = "DELETE FROM categories WHERE cat_id = $cat_id";
        return $this->db_write_query($sql);
    }

    /**
     * Admin: View all pending categories
     * @return array|false
     */
    public function get_pending_categories()
    {
        $sql = "SELECT c.cat_id, c.cat_name, u.customer_name 
                FROM categories c
                JOIN customer u ON c.created_by = u.customer_id
                WHERE c.is_approved = 0";
        return $this->db_fetch_all($sql);
    }
}
