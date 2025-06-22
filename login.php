<?php
require_once('dbconnect.php');
session_start(); // Start the session

if (isset($_POST['login'])) {
    $category = $_POST['category']; // Role: organizer, customer, admin
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    // Use the first letter of the category for column prefix
    $category_column_prefix = substr($category, 0, 1);

    // Prepare the SQL query to prevent SQL injection
    $sql = "SELECT * FROM $category WHERE {$category_column_prefix}_id = ? AND {$category_column_prefix}_password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user_id, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Fetch the user data
        $user_data = $result->fetch_assoc();

        // Store user data in the session
        $_SESSION['user_id'] = $user_data["{$category_column_prefix}_id"];
        $_SESSION['user_name'] = $user_data["{$category_column_prefix}_name"];
        $_SESSION['role'] = $category;

        // Redirect based on role
        if ($category === 'admin') {
            header("Location: a_dashboard.php"); // Redirect to admin dashboard
        } elseif ($category === 'organizer') {
            header("Location: o_dashboard.php"); // Redirect to organizer dashboard
        } elseif ($category === 'customer') {
            header("Location: c_dashboard.php"); // Redirect to customer dashboard
        }
        exit();
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid Login Credentials!'); window.location.href='index.php';</script>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Event Management System</title>
    <link rel="stylesheet" href="./styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .navbar {
            background-color: #333;
            padding: 1rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .navbar ul li {
            margin-left: 20px;
        }

        .navbar ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        .navbar ul li a:hover {
            text-decoration: underline;
        }

        .form-body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full page height */
            background-color: #f4f4f4;
        }

        .form-container {
            width: 350px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-container label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            color: #555;
        }

        .form-container input,
        .form-container select,
        .form-container button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-container button {
            background-color: #333;
            color: white;
            cursor: pointer;
            border: none;
            font-weight: bold;
        }

        .form-container button:hover {
            background-color: #555;
        }
    </style>
</head>

<body>
    <!-- Navbar Section -->
    <nav class="navbar">
        <div class="logo">EMS</div>
        <ul class="navbar-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="signup.php">Signup</a></li>
        </ul>
    </nav>

    <div class="form-body">
        <!-- Form Section -->
        <div class="form-container">
            <h2>Login</h2>
            <form action="login.php" method="post">
                <label for="category">Choose Role:</label>
                <select name="category" id="category" required>
                    <option value="organizer">Organizer</option>
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                </select>

                <label for="user_id">User ID:</label>
                <input type="text" id="user_id" name="user_id" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </div>
</body>

</html>
