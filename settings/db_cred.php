<?php
//db credentials

define('SERVER', 'localhost');
define('USERNAME', 'nana.nkrumah');
define('PASSWD', 'nkrucom187');
define('DATABASE', 'ecommerce_2025A_nana_nkrumah');

// Create connection
$conn = new mysqli(SERVER, USERNAME, PASSWD, DATABASE);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8");

?>
