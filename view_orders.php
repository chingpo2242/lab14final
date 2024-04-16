<?php
session_start();

// Include database connection
include('db_connection.php');

// Function to log user activity
function logActivity($conn, $username, $role, $operation) {
    date_default_timezone_set('Asia/Manila'); // Set timezone to Philippines
    $date_log = date("Y-m-d");
    $time_log = date("h:i:s A");

    $sql = "INSERT INTO user_log (username, role, operation, date_log, time_log) VALUES ('$username', '$role', '$operation', '$date_log', '$time_log')";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error; // Print error message for debugging
        return false;
    }
}

// Check if user is logged in as customer
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Log customer activity for viewing order history
$username = $_SESSION['username'];
$role = "customer"; 
$operation = "Viewed order history";
if(!logActivity($conn, $username, $role, $operation)) {
    echo "Error logging activity";
}

// Retrieve orders for the current customer
$sql_orders = "SELECT * FROM orders WHERE customer_username='$username'";
$result_orders = $conn->query($sql_orders);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <style>
       body {
            font-family: Arial, sans-serif;
            background-image: url('https://cdn.wallpapersafari.com/93/26/Stkyof.gif');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column; /* Added */
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 20px;
            color: #fff;
        }

        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Back button styles */
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
<body>
    <div class="container">
        <h2>Order History</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Cost</th>
            </tr>
            <?php
            if ($result_orders && $result_orders->num_rows > 0) {
                while($row = $result_orders->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row["order_id"]."</td>";
                    echo "<td>".$row["product_name"]."</td>";
                    echo "<td>".$row["quantity"]."</td>"; // Display quantity
                    echo "<td>".$row["total_cost"]."</td>"; // Display total cost
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No orders found</td></tr>";
            }
            ?>
        </table>
        <div class="back-btn">
            <a href="customer_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
