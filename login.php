<!-- login.php -->
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establish database connection
    include('db_connection.php');

    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve user from database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Check user role
            if ($row['role'] == 'admin') {
                $_SESSION['username'] = $username;
                header("Location: admin_dashboard.php");
                exit();
            } elseif ($row['role'] == 'customer') {
                $_SESSION['username'] = $username;
                header("Location: customer_dashboard.php");
                exit();
            } else {
                echo "Invalid role";
            }
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User not found";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: flex-start; /* Aligning container to the left */
            align-items: center;
            height: 100vh;
            padding-left: 10in; /* Adjusting the left padding */
            background-image: url('https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/6e64b8f7-82f5-47e5-9319-e2e69ca6f56d/d9f6x59-83bc7697-99b5-4ab1-b56c-48286f982b2b.gif?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzZlNjRiOGY3LTgyZjUtNDdlNS05MzE5LWUyZTY5Y2E2ZjU2ZFwvZDlmNng1OS04M2JjNzY5Ny05OWI1LTRhYjEtYjU2Yy00ODI4NmY5ODJiMmIuZ2lmIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.Ecp8az9AAdUgHhqzZodMeTYiyjhki3Mbn-rK2JjN4MM');
            background-size: cover;
            background-position: center;
        }

        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: fadeInDown 1s ease;
        }

        .login-container h2 {
            margin-top: 0;
            text-align: center;
        }

        .login-form {
            display: flex;
            flex-direction: column;
        }

        .login-form label {
            margin-bottom: 8px;
        }

        .login-form input {
            padding: 10px;
            margin-bottom: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
            transition: border-color 0.3s;
        }

        .login-form input:focus {
            outline: none;
            border-color: dodgerblue;
        }

        .login-form input[type="submit"] {
            background-color: dodgerblue;
            color: #fff;
            cursor: pointer;
        }

        .login-form input[type="submit"]:hover {
            background-color: #007bff;
        }

        .register-btn {
            text-align: center;
            margin-top: 10px;
        }

        .register-btn a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }

        .register-btn a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-container animate__animated animate__fadeInDown">
        <h2>Login</h2>
        <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Login">
        </form>
        <div class="register-btn">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>

