<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "ordering"; // Change this to your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
