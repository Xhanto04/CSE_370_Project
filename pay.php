<?php
session_start();
require_once('dbconnect.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "ems_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : "";
$events = [];
$message = "";
$payment_successful = false;
$show_user_info = isset($_GET['show_info']); // Flag to check if user info should be shown

// Fetch user information if "Information" button is clicked
$user_info = [];
if ($show_user_info) {
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];
    $column_prefix = substr($role, 0, 1); // Get column prefix based on role

    $sql = "SELECT 
                {$column_prefix}_fname AS fname, 
                {$column_prefix}_lname AS lname, 
                {$column_prefix}_email AS email, 
                {$column_prefix}_address AS address 
            FROM $role 
            WHERE {$column_prefix}_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_info = $result->fetch_assoc();
    } else {
        $user_info = [
            'fname' => 'Unknown',
            'lname' => 'Unknown',
            'email' => 'Unknown',
            'address' => 'Unknown',
        ];
    }
    $stmt->close();
}

// Check if the customer search form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_customer'])) {
    if (!empty($customer_id)) {
        // Fetch events for the given customer ID
        $sql = "SELECT * FROM events WHERE c_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $events[] = $row;
            }
        } else {
            $message = "No events found for Customer ID: $customer_id";
        }
        $stmt->close();
    } else {
        $message = "Please enter a Customer ID.";
    }
}

// Check if the payment form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_event'])) {
    $event_id = $_POST['pay_event'];

    // Update payment_status to "1" (Paid)
    $sql = "UPDATE events SET payment_status = 1 WHERE e_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $event_id);

    if ($stmt->execute()) {
        $payment_successful = true;
        $message = "Payment updated successfully!";
    } else {
        $message = "Error updating payment: " . $stmt->error;
    }
    $stmt->close();
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Payment</title>
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
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #333;
        }
        .message {
            color: green;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        table th {
            background-color: #f4f4f9;
            color: #333;
            font-weight: bold;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #45a049;
        }
        .form-container {
            margin: 20px 0;
        }
        .user-info {
            margin: 20px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .user-info h2 {
            margin-bottom: 15px;
        }
        .user-info p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <!-- Navbar Section -->
    <div class="navbar">
        <div class="logo">EMS</div>
        <ul>
            <li><a href="?show_info=1">Information</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Container -->
    <div class="container">
        <h1>Search Customer Events</h1>

        <!-- Search Form -->
        <form action="" method="POST" class="form-container">
            <label for="customer_id">Enter Customer ID:</label>
            <input type="text" name="customer_id" id="customer_id" value="<?php echo htmlspecialchars($customer_id); ?>" required>
            <button type="submit" name="search_customer" class="button">Search</button>
        </form>

        <hr>

        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if (!empty($events)): ?>
            <h2>Customer Events</h2>
            <form action="" method="POST">
                <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($customer_id); ?>">
                <table>
                    <thead>
                        <tr>
                            <th>Event ID</th>
                            <th>Event Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Amount</th>
                            <th>Payment Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $row): ?>
                            <tr>
                                <td><?php echo $row['e_id']; ?></td>
                                <td><?php echo $row['type']; ?></td>
                                <td><?php echo $row['start_date']; ?></td>
                                <td><?php echo $row['end_date']; ?></td>
                                <td><?php echo $row['total_amount']; ?></td>
                                <td><?php echo $row['payment_status'] == 1 ? "Paid" : "Unpaid"; ?></td>
                                <td>
                                    <?php if ($row['payment_status'] == 0): ?>
                                        <button type="submit" name="pay_event" value="<?php echo $row['e_id']; ?>" class="button">Pay Now</button>
                                    <?php else: ?>
                                        <span style="color: gray;">Paid</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        <?php endif; ?>

        <?php if ($payment_successful): ?>
            <h2 style="color: green;">Thank you for the payment! Payment successful.</h2>
        <?php endif; ?>

        <!-- User Information Section -->
        <?php if ($show_user_info): ?>
            <div class="user-info">
                <h2>User Information</h2>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user_info['fname'] . " " . $user_info['lname']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user_info['email']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($user_info['address']); ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
