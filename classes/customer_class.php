<?php
require_once '../settings/db_class.php';

class Customer extends db_connection
{
    private $customer_id;
    private $name;
    private $email;
    private $password;
    private $country;
    private $city;
    private $role;
    private $date_created;
    private $phone_number;

    public function __construct($customer_id = null)
    {
        parent::db_connect();
        if ($customer_id) {
            $this->customer_id = $customer_id;
            $this->loadUser();
        }
    }

    private function loadUser($customer_id = null)
    {
        if ($customer_id) {
            $this->customer_id = $customer_id;
        }
        if (!$this->customer_id) {
            return false;
        }

        $stmt = $this->db->prepare("SELECT * FROM customer WHERE customer_id = ?");
        $stmt->bind_param("i", $this->customer_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result) {
            $this->name = $result['customer_name'];
            $this->email = $result['customer_email'];
            $this->role = $result['user_role'];
            $this->date_created = $result['date_created'] ?? null;
            $this->phone_number = $result['customer_contact'];
        }
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM customer WHERE customer_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Register user
    public function registerUser($name, $email, $password, $country, $city, $phone_number, $role)
    {
        // check if email already exists
        $stmt = $this->db->prepare("SELECT customer_id FROM customer WHERE customer_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return false; // email already exists
        }

        // insert new customer
        $stmt = $this->db->prepare("INSERT INTO customer 
            (customer_name, customer_email, customer_pass, customer_contact, user_role, customer_country, customer_city) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $name, $email, $password, $phone_number, $role, $country, $city);

        if ($stmt->execute()) {
            return $this->db->insert_id; // return new customer ID
        }
        return false;
    }

    // Change password
    public function changePassword($old_password, $new_password)
    {
        if (!$this->customer_id) return false;

        $user = $this->getUserByEmail($this->email);
        if ($user && password_verify($old_password, $user['customer_pass'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE customer SET customer_pass = ? WHERE customer_id = ?");
            $stmt->bind_param("si", $hashed_password, $this->customer_id);
            return $stmt->execute();
        }
        return false;
    }

    // Verify password
    public function verifyPassword($password)
    {
        if (!$this->customer_id) return false;

        $user = $this->getUserByEmail($this->email);
        if ($user && password_verify($password, $user['customer_pass'])) {
            return true;
        }
        return false;
    }
}
?>
