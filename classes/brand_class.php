<?php
// classes/brand_class.php
require_once __DIR__ . '/db_class.php';

class Brand {
    private $pdo;

    public function __construct() {
        $this->pdo = DB::pdo();
    }

    public function add($brand_name) {
        // Enforce uniqueness at application level
        $stmt = $this->pdo->prepare("SELECT brand_id FROM brands WHERE brand_name = ?");
        $stmt->execute([$brand_name]);
        if ($stmt->fetch()) {
            return ['success' => false, 'msg' => 'Brand already exists'];
        }

        $stmt = $this->pdo->prepare("INSERT INTO brands (brand_name) VALUES (?)");
        $stmt->execute([$brand_name]);
        return ['success' => true, 'brand_id' => $this->pdo->lastInsertId()];
    }

    public function edit($brand_id, $brand_name) {
        // Check unique name (exclude current id)
        $stmt = $this->pdo->prepare("SELECT brand_id FROM brands WHERE brand_name = ? AND brand_id != ?");
        $stmt->execute([$brand_name, $brand_id]);
        if ($stmt->fetch()) {
            return ['success' => false, 'msg' => 'Another brand uses that name'];
        }

        $stmt = $this->pdo->prepare("UPDATE brands SET brand_name = ? WHERE brand_id = ?");
        $stmt->execute([$brand_name, $brand_id]);
        return ['success' => true];
    }

    public function delete($brand_id) {
        $stmt = $this->pdo->prepare("DELETE FROM brands WHERE brand_id = ?");
        $stmt->execute([$brand_id]);
        return ['success' => true];
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT brand_id, brand_name FROM brands ORDER BY brand_name");
        return $stmt->fetchAll();
    }

    public function getById($brand_id) {
        $stmt = $this->pdo->prepare("SELECT brand_id, brand_name FROM brands WHERE brand_id = ?");
        $stmt->execute([$brand_id]);
        return $stmt->fetch();
    }
}

class DB {
    private static $pdo = null;
    
    public static function pdo() {
        if (self::$pdo === null) {
            self::$pdo = new PDO(
                "mysql:host=localhost;dbname=your_database_name",
                "your_username",
                "your_password",
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        }
        return self::$pdo;
    }
}