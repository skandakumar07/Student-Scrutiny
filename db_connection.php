<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "scrutinity_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $database,3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Set character set
$conn->set_charset("utf8mb4");
?>
