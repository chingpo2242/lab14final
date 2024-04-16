<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
       body {
            font-family: Arial, sans-serif;
            background-image: url('https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/6e64b8f7-82f5-47e5-9319-e2e69ca6f56d/d9f6x59-83bc7697-99b5-4ab1-b56c-48286f982b2b.gif?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzZlNjRiOGY3LTgyZjUtNDdlNS05MzE5LWUyZTY5Y2E2ZjU2ZFwvZDlmNng1OS04M2JjNzY5Ny05OWI1LTRhYjEtYjU2Yy00ODI4NmY5ODJiMmIuZ2lmIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.Ecp8az9AAdUgHhqzZodMeTYiyjhki3Mbn-rK2JjN4MM');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: fadeInDown 1s ease;
        }

        .register-container h2 {
            margin-top: 0;
            text-align: center;
        }

        .register-form {
            display: flex;
            flex-direction: column;
        }

        .register-form label {
            margin-bottom: 8px;
        }

        .register-form input,
        .register-form select {
            padding: 10px;
            margin-bottom: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
            transition: border-color 0.3s;
        }

        .register-form input:focus,
        .register-form select:focus {
            outline: none;
            border-color: dodgerblue;
        }

        .register-form input[type="submit"] {
            background-color: dodgerblue;
            color: #fff;
            cursor: pointer;
        }

        .register-form input[type="submit"]:hover {
            background-color: #007bff;
        }

        .login-btn {
            text-align: center;
            margin-top: 10px;
        }

        .login-btn a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }

        .login-btn a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="register-container animate__animated animate__fadeInDown">
    <?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establish database connection
    include('db_connection.php');

    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // New role field

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Set timezone to Philippines
    date_default_timezone_set('Asia/Manila');
    
    // Get current date and time
    $current_date = date('Y-m-d');
    $current_time = date('h:i:s A'); // Format time in 12-hour format with AM/PM
    
    // Operation
    $operation = "register";

    // Prepare and execute SQL query to insert data into the database
    $sql = "INSERT INTO users (username, password, role, date_log, time_log, operation) VALUES ('$username', '$hashed_password', '$role', '$current_date', '$current_time', '$operation')";

    if ($conn->query($sql) === TRUE) {
        // Log the registration activity
        $sql_log = "INSERT INTO user_log (username, role, date_log, time_log, operation) VALUES ('$username', '$role', '$current_date', '$current_time', '$operation')";
        if ($conn->query($sql_log) === TRUE) {
            echo "Registration successful";
        } else {
            echo "Error: " . $sql_log . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

      <body>
      <div class="register-container animate__animated animate__fadeInDown">
        <h2>Register</h2>
        <form class="register-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="customer">Customer</option>
            </select>

            <input type="submit" value="Register">
        </form>
        <div class="login-btn">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
