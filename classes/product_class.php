<?php
require_once 'classes/db_class.php';

class Product extends db_connection{

    private function db_escape($data) {
        return mysqli_real_escape_string($this->connection, $data);
    }

    //create new product 
    public function addProduct($product_name, $brand_id, $category_id, $price, $description, $created_by){
        $product_name = $this->db_escape($product_name);
        $brand_id = (int)$brand_id;
        $category_id = (int)$category_id;
        $price = (float)$price;
        $description = $this->db_escape($description);
        $created_by = (int)$created_by;

        $query = "INSERT INTO products (product_name, brand_id, category_id, price, description, created_by) 
                  VALUES ('$product_name', $brand_id, $category_id, $price, '$description', $created_by)";

        if ($this -> db_write_query($query)){
            //return the id of the newly created product
            return $this -> last_insert_id();
        }
        return false;
    }

    //Update product details
    public function updateProduct($product_id, $product_name, $brand_id, $category_id, $price, $description){
        $product_id = (int)$product_id;
        $product_name = $this->db_escape($product_name);
        $brand_id = (int)$brand_id;
        $category_id = (int)$category_id;
        $price = (float)$price;
        $description = $this->db_escape($description);

        $query = "UPDATE products 
                  SET product_name='$product_name', brand_id=$brand_id, category_id=$category_id, price=$price, description='$description' 
                  WHERE product_id=$product_id";

        return $this -> db_write_query($query);
    }

    //Update product image path only
    public function updateProductImage($product_id, $image_path){
        $product_id = (int)$product_id;
        $image_path = $this->db_escape($image_path);

        $query = "UPDATE products 
                  SET image_path='$image_path' 
                  WHERE product_id=$product_id";

        return $this -> db_write_query($query);
    }

    //Get all products joined with brand and category names for display (Retrieve)
    public function getAllProducts(){
        $query = "SELECT p.*, b.brand_name, c.category_name 
                  FROM products p
                  JOIN brands b ON p.brand_id = b.brand_id
                  JOIN categories c ON p.category_id = c.category_id";

        return $this -> db_fetch_all($query);
    }

    //Get a single product by ID
    public function getProductById($product_id){
        $product_id = (int)$product_id;
        $query = "SELECT p.*, b.brand_name, c.category_name 
                  FROM products p
                  JOIN brands b ON p.brand_id = b.brand_id
                  JOIN categories c ON p.category_id = c.category_id
                  WHERE p.product_id = $product_id";

        return $this -> db_fetch_one($query);
    }
}