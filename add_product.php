<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include('db_connection.php');

// Function to insert log entry
function insertLog($conn, $username, $role, $operation) {
    date_default_timezone_set('Asia/Manila'); // Set timezone to Philippines
    $date_log = date("Y-m-d");
    $time_log = date("h:i:s A");
    $sql = "INSERT INTO user_log (username, role, operation, date_log, time_log) VALUES ('$username', '$role', '$operation', '$date_log', '$time_log')";
    $conn->query($sql);
}

// Check if form is submitted to add a product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['product_name']) && isset($_POST['price'])) {
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        
        // Insert product into database
        $sql_product = "INSERT INTO products (name, price) VALUES ('$product_name', '$price')";
        if ($conn->query($sql_product) === TRUE) {
            // Log the operation
            $username = $_SESSION['username'];
            $role = 'admin'; // Assuming the role is 'admin'
            insertLog($conn, $username, $role, 'Add Product: ' . $product_name); // Call insertLog function with appropriate parameters
            echo "Product added successfully!";
        } else {
            echo "Error: " . $sql_product . "<br>" . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center; 
            align-items: center;
            height: 100vh;
            background-image: url('https://cdn.wallpapersafari.com/93/26/Stkyof.gif');
            background-size: cover;
            background-position: center;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            width: 100%;
            display: inline-block;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Product</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" required><br>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" min="0" step="0.01" required><br>
            <input type="submit" value="Add Product">
        </form>
    </div>
</body>
</html>
