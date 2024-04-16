<?php
session_start();

// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "ordering";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in as customer
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


// Function to insert log entry
function insertLog($conn, $username, $role, $operation, $date_log, $time_log) {
    $sql = "INSERT INTO user_log (username, role, operation, date_log, time_log) VALUES ('$username', '$role', '$operation', '$date_log', '$time_log')";
    $conn->query($sql);
}

// Handle order submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $customer_username = $_SESSION['username'];
        
        // Retrieve product details
        $sql_product = "SELECT name, price FROM products WHERE id='$product_id'";
        $result_product = $conn->query($sql_product);
        if ($result_product->num_rows > 0) {
            $row_product = $result_product->fetch_assoc();
            $product_name = $row_product['name'];
            $product_price = $row_product['price'];

            // Calculate total cost
            $total_cost = $product_price * $quantity;

            // Insert order into database
            $sql_order = "INSERT INTO orders (product_id, product_name, quantity, total_cost, customer_username) 
                          VALUES ('$product_id', '$product_name', '$quantity', '$total_cost', '$customer_username')";
            if ($conn->query($sql_order) === TRUE) {
                // Log order placement
                date_default_timezone_set('Asia/Manila'); // Set timezone to Philippines
                $date_log = date("Y-m-d");
                $time_log = date("h:i:s A");
                $role = 'customer'; // Assuming the role is 'customer'
                $operation = "Placed order for $quantity $product_name";
                insertLog($conn, $customer_username, $role, $operation, $date_log, $time_log);
                
                echo "Order placed successfully!";
            } else {
                echo "Error: " . $sql_order . "<br>" . $conn->error;
            }
        } else {
            echo "Product not found!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order</title>
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
            background-image: url('https://64.media.tumblr.com/f66dfd01f417a4218b0a201a29075d23/tumblr_oz33fyW5kr1soe71po1_1280.gif');
            background-size: cover;
            background-position: center;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            max-width: 500px;
            margin: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        select, input[type="number"] {
            width: calc(100% - 40px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"], .btn {
            background-color: #4CAF50;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            width: calc(100% - 40px);
            text-align: center;
        }

        input[type="submit"]:hover, .btn:hover {
            background-color: #45a049;
        }

        p {
            text-align: center;
            margin-top: 20px;
        }

        p a, .btn {
            color: #fff;
            text-decoration: none;
            display: inline-block;
        }

        .btn-back {
            background-color: #4CAF50;
        }

        .btn-back:hover {
            background-color: #555;
        }
        .back-btn {
            position: absolute; 
            top: 20px; 
            left: 20px; /
        }
        .back-btn a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .back-btn a:hover {
            background-color: #555;
        }
    </style>
</head>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order</title>
    <style>
        /* Your CSS styles */
    </style>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>Place Order</h2>
        <label for="product_id">Select Product:</label>
        <select id="product_id" name="product_id">
            <?php
            // Retrieve products from the database
            $sql_products = "SELECT id, name, price FROM products";
            $result_products = $conn->query($sql_products);
            if ($result_products->num_rows > 0) {
                while($row = $result_products->fetch_assoc()) {
                    echo "<option value='".$row['id']."'>".$row['name']." - $".$row['price']."</option>";
                }
            }
            ?>
        </select>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1" value="1" required>
        <input type="submit" value="Order">
    </form>
    <div class="back-btn">
        <a href="customer_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>