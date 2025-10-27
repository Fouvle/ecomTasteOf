<?php
require_once 'db_class.php';

class Brand extends db_connection{

    private function db_escape($value) {
        return mysqli_real_escape_string($this->db_conn(), $value);
    }

    /**
     * Add a new brand
     * @param string $brand_name
     * @param int $cat_id
     * @param true|string Returns true on success, or an error message
     */

    public function add_brand_cls($brand_name, $cat_id) {
        $brand_name = $this->db_escape($brand_name);
        $cat_id = (int)$cat_id;

        // Check for duplicate brand in the same category
        $check_query = "SELECT * FROM brands WHERE brand_name = '$brand_name' AND cat_id = $cat_id";
        $check_result = mysqli_query($this->db_conn(), $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            return "Brand already exists in this category.";
        }

        $query = "INSERT INTO brands (brand_name, cat_id) VALUES ('$brand_name', $cat_id)";
        if (mysqli_query($this->db_conn(), $query)) {
            return true;
        } else {
            return "Error adding brand: " . mysqli_error($this->db_conn());
        }
    }

    /**
     * Update an existing brand name
     * @param int $brand_id
     * @param string $brand_name
     * @return true|string Returns true on success, or an error message
     */
    public function update_brand_cls($brand_id, $brand_name) {
        $brand_id = (int)$brand_id;
        $brand_name = $this->db_escape($brand_name);

        $query = "UPDATE brands SET brand_name = '$brand_name' WHERE brand_id = $brand_id";
        if (mysqli_query($this->db_conn(), $query)) {
            return true;
        } else {
            return "Error updating brand: " . mysqli_error($this->db_conn());
        }
    }
    
    /**
     * Delete a brand
     * @param int $brand_id
     * @return true|string Returns true on success, or an error message
     */
    public function delete_brand_cls($brand_id) {
        $brand_id = (int)$brand_id;

        $query = "DELETE FROM brands WHERE brand_id = $brand_id";
        if (mysqli_query($this->db_conn(), $query)) {
            return true;
        } else {
            return "Error deleting brand: " . mysqli_error($this->db_conn());
        }
    }

    /**
     * Fetch all brands with their categories
     * @return array
     */
    public function fetch_all_brands() {
        $query = "SELECT b.brand_id, b.brand_name, c.cat_name FROM brands b JOIN categories c ON b.cat_id = c.cat_id";
        $result = mysqli_query($this->db_conn(), $query);

        $brands = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $brands[] = $row;
        }
        return $brands;
    }
}