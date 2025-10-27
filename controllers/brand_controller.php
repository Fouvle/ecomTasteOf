<?php
// classes/brand_controller.php
require_once __DIR__ . '/brand_class.php';

class BrandController {
    private $brandModel;

    public function __construct() {
        $this->brandModel = new Brand();
    }

    public function add_brand_ctr($brand_name) {
        return $this->brandModel->add($brand_name);
    }

    public function edit_brand_ctr($id, $name) {
        return $this->brandModel->edit($id, $name);
    }

    public function delete_brand_ctr($id) {
        return $this->brandModel->delete($id);
    }

    public function fetch_all_brands_ctr() {
        return $this->brandModel->getAll();
    }

    public function get_brand_ctr($id) {
        return $this->brandModel->getById($id);
    }
}
