<!-- edit_product.php -->
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

// Retrieve current product details from the database
$sql = "SELECT * FROM products WHERE id='$product_id'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $current_product_name = $row["name"];
    $current_product_price = $row["price"];
    $current_product_quantity = $row["quantity"];
} else {
    echo "Product not found!";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $new_product_name = $_POST['name'];
    $new_product_price = $_POST['price'];
    $new_product_quantity = $_POST['quantity'];

    // Update product details in the database
    $sql_update = "UPDATE products SET name='$new_product_name', price='$new_product_price', quantity='$new_product_quantity' WHERE id='$product_id'";
    if ($conn->query($sql_update) === TRUE) {
        // Update total cost of orders containing this product
        $sql_update_orders = "UPDATE orders SET total_cost = quantity * '$new_product_price' WHERE product_name = '$current_product_name'";
        $conn->query($sql_update_orders);
        
        echo "Product updated successfully!";
    } else {
        echo "Error updating product: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
</head>
<body>
    <h2>Edit Product</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=$product_id"); ?>" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $current_product_name; ?>" required><br><br>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" value="<?php echo $current_product_price; ?>" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="<?php echo $current_product_quantity; ?>" required><br><br>

        <input type="submit" value="Update">
    </form>
</body>
</html>
