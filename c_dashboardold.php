<?php
session_start();
require_once('dbconnect.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Get the user ID and role from the session
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Determine the column prefix based on the role
$column_prefix = substr($role, 0, 1);

// Initialize variables
$total_events = 0;
$total_paid = 0;
$total_pending = 0;
$user_info = [];
$show_user_info = false; // Initially, do not show user info

// Check if the "profile" parameter is set
if (isset($_GET['profile']) && $_GET['profile'] === 'show') {
    $show_user_info = true;

    // Fetch user information based on the role
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
}

// Fetch dashboard data for the logged-in user
$sql = "SELECT 
            COUNT(e_id) AS total_events, 
            SUM(CASE WHEN payment_status = 1 THEN total_amount ELSE 0 END) AS total_paid,
            SUM(CASE WHEN payment_status = 0 THEN total_amount ELSE 0 END) AS total_pending
        FROM events
        WHERE {$column_prefix}_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $total_events = $row['total_events'];
    $total_paid = $row['total_paid'];
    $total_pending = $row['total_pending'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - EMS</title>
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
        .dashboard-stats {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .stat-card {
            width: 30%;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .stat-card h2 {
            margin: 10px 0;
            color: #555;
        }
        .stat-card p {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }
        .pay-link {
            display: block;
            text-align: center;
            margin: 20px 0;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
        }
        .pay-link:hover {
            background-color: #45a049;
        }
        .user-info {
            margin: 20px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">EMS</div>
        <ul>
            <li><a href="?profile=show">Profile</a></li>
            <li><a href="add_event.php">Apply for Event</a></li> <!-- Added Apply for Event Link -->
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Dashboard Container -->
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <div class="dashboard-stats">
            <div class="stat-card">
                <h2>Total Events</h2>
                <p><?php echo $total_events; ?></p>
            </div>
            <div class="stat-card">
                <h2>Amount Paid</h2>
                <p>$<?php echo number_format($total_paid, 2); ?></p>
            </div>
            <div class="stat-card">
                <h2>Pending Amount</h2>
                <p>$<?php echo number_format($total_pending, 2); ?></p>
            </div>
        </div>
        <!-- Payment Link -->
        <a href="pay.php?user_id=<?php echo urlencode($user_id); ?>" class="pay-link">
            Pay Your Unpaid Events
        </a>
    </div>

    <!-- User Info Section -->
    <?php if ($show_user_info): ?>
        <div class="container user-info">
            <h2>User Information</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user_info['fname'] . " " . $user_info['lname']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_info['email']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user_info['address']); ?></p>
        </div>
    <?php endif; ?>
</body>
</html>
