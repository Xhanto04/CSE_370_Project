<?php
session_start();
require_once('dbconnect.php');

// Check if the user is logged in and has the customer role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php"); // Redirect to login if not logged in or role is not customer
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $password = isset($_POST['password']) ? $_POST['password'] : ''; // Check if the password key exists

    // Update customer information
    $sql = "UPDATE Customer SET c_fname = ?, c_lname = ?, c_email = ?, c_address = ? WHERE c_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $fname, $lname, $email, $address, $user_id);

    if ($stmt->execute()) {
        // Update customer phone number
        $phone_sql = "UPDATE CustomerPhone SET c_phone = ? WHERE c_id = ?";
        $phone_stmt = $conn->prepare($phone_sql);
        $phone_stmt->bind_param("ss", $phone, $user_id);
        $phone_stmt->execute();

        // Update password if provided
        if (!empty($password)) {
            $password_sql = "UPDATE Customer SET c_password = ? WHERE c_id = ?";
            $password_stmt = $conn->prepare($password_sql);
            $password_stmt->bind_param("ss", $password, $user_id);
            $password_stmt->execute();
        }

        $success_message = "Information updated successfully!";
    } else {
        $error_message = "Failed to update information. Please try again.";
    }
}

// Fetch customer information
$sql = "SELECT c_fname, c_lname, c_email, c_address FROM Customer WHERE c_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

// Fetch customer phone number
$phone_sql = "SELECT c_phone FROM CustomerPhone WHERE c_id = ?";
$phone_stmt = $conn->prepare($phone_sql);
$phone_stmt->bind_param("s", $user_id);
$phone_stmt->execute();
$phone_result = $phone_stmt->get_result();
$phone = $phone_result->fetch_assoc()['c_phone'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - EMS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        form input, form textarea, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        textarea {
            resize: none;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            margin-bottom: 15px;
        }
        .message.success {
            color: green;
        }
        .message.error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Your Profile</h1>
        <?php if (isset($success_message)): ?>
            <p class="message success"><?php echo $success_message; ?></p>
        <?php elseif (isset($error_message)): ?>
            <p class="message error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="fname">First Name:</label>
            <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($customer['c_fname']); ?>" required>

            <label for="lname">Last Name:</label>
            <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($customer['c_lname']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($customer['c_email']); ?>" required>

            <label for="address">Address:</label>
            <textarea id="address" name="address" required><?php echo htmlspecialchars($customer['c_address']); ?></textarea>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>

            <label for="password">New Password (optional):</label>
            <input type="text" id="password" name="password" placeholder="Enter new password (leave blank to keep current password)">

            <button type="submit" name="update">Update Profile</button>
        </form>
    </div>
</body>
</html>
