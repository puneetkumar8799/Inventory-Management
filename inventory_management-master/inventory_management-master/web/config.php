<?php

// Database configuration
$servername = "localhost";
$username = "root";
$password = "QWERT!@#$%";
$dbname = "automotive_inventory";

session_start();
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Base URL configuration
$base_url = 'http://localhost/inventory_management/web/';

// Other configuration options...

?>
