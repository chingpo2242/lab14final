<?php
session_start();

// Include database connection
include('db_connection.php');

// Function to log user activity
function logActivity($conn, $username, $role, $operation) {
    date_default_timezone_set('Asia/Manila');
    $current_date = date('Y-m-d');
      $time_log = date("h:i:s A");

    $sql = "INSERT INTO user_log (username, role, date_log, time_log, operation) VALUES ('$username', '$role', '$current_date', '$time_log', '$operation')";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {    
        return false;
    }
}

// Check if user is logged in as admin
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Log admin activity
$username = $_SESSION['username'];
$role = "admin"; 
$operation = "View Product List";
if(!logActivity($conn, $username, $role, $operation)) {
    echo "Error logging activity";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <style>
        /* Body styles */
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

        /* Header styles */
        h2 {
            color: #fff;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        /* Table styles */
        table {
            width: 90%; /* Adjusted width */
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Added background color */
        }

        th, td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        /* Edit and delete link styles */
        .edit-btn, .delete-btn {
            padding: 5px 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .delete-btn {
            background-color: #dc3545;
            margin-left: 5px;
        }

        .edit-btn:hover, .delete-btn:hover {
            background-color: #218838;
            transition: background-color 0.3s ease;
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

        /* Search bar styles */
        .search-container {
            margin-bottom: 20px;
            display: flex; /* Added */
            justify-content: center; /* Added */
        }

        .search-container input[type=text] {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-container input[type=submit] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-container input[type=submit]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>List of Products</h2>

    <!-- Search bar -->
    <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            <input type="text" placeholder="Search by ID or Name" name="search">
            <input type="submit" value="Search">
        </form>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
       
            
            <th>Action</th>
            <th>Action</th>
        </tr>
        <?php
        // Retrieve products from the database

        // Default SQL query to retrieve all products
        $sql = "SELECT * FROM products";

        // Check if a search query is submitted
        if(isset($_GET['search']) && !empty(trim($_GET['search']))){
            $search = trim($_GET['search']);
            // Modify the SQL query to include search functionality
            $sql = "SELECT * FROM products WHERE id LIKE '%$search%' OR name LIKE '%$search%'";
        }

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["id"]."</td>";
                echo "<td>".$row["name"]."</td>";
                echo "<td>".$row["price"]."</td>";
                echo "<td><a class='edit-btn' href='edit_product.php?id=".$row["id"]."'>Edit</a></td>";
                echo "<td><a class='delete-btn' href='delete_product.php?id=".$row["id"]."' onclick='return confirm(\"Are you sure you want to delete this product?\")'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No products found</td></tr>";
        }
        ?>
    </table>
    <div class="back-btn">
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
