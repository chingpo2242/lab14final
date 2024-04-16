<!-- delete_product.php -->
<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if user is admin
include('db_connection.php');
$username = $_SESSION['username'];
$sql = "SELECT role FROM users WHERE username='$username'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
if ($row['role'] != 'admin') {
    echo "Unauthorized access!";
    exit();
}

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Product ID is missing!";
    exit();
}

// Get the product ID from the URL parameter
$product_id = $_GET['id'];

// Delete the product from the database
$sql_delete = "DELETE FROM products WHERE id='$product_id'";
if ($conn->query($sql_delete) === TRUE) {
    echo "Product deleted successfully!";
} else {
    echo "Error deleting product: " . $conn->error;
}
?>
