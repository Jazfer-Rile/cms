<?php
$host = "localhost"; // Change if your MySQL server is remote
$user = "root"; // Default XAMPP username
$pass = ""; // Default XAMPP password (leave blank)
$dbname = "cms_db"; // Your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
