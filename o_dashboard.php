<?php
session_start();
require_once ('dbconnect.php');
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Get the user ID and role from the session
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Fetch total events count
$sql_events = "SELECT * FROM events where o_id = '$user_id';";
$result_events = mysqli_query($conn, $sql_events);
$total_events = mysqli_num_rows($result_events);

// Fetch total unpaid
$sql_unpaid = "SELECT SUM(total_amount) AS unpaid FROM events where o_id = '$user_id' and payment_status=0;";
$result_unpaid = mysqli_query($conn, $sql_unpaid);
$total_unpaid = mysqli_fetch_assoc($result_unpaid)['unpaid'];
if (!$total_unpaid)
    {$total_unpaid = 0;}
// Fetch total unpaid
$sql_paid = "SELECT SUM(total_amount) AS paid FROM events where o_id = '$user_id' and payment_status=1;";
$result_paid = mysqli_query($conn, $sql_paid);
$total_paid = mysqli_fetch_assoc($result_paid)['paid'];
if (!$total_paid)
    {$total_paid = 0;}

$sql_info = "SELECT o_fname, o_lname, o_email, o_address FROM organizer where o_id = '$user_id';";
$result_info = mysqli_query($conn, $sql_info);
$total_info = mysqli_fetch_assoc($result_info);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - EMS</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <!-- Navbar Section -->
    <nav class="navbar">
        <div class="logo">EMS</div>
        <ul class="navbar-links">
        <li><a href="organizer_payment.php">See Details</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main>
        <section class="about">
            <div class="container">
                <h2><br>Dashboard</h2>
                <div class="feature-cards">
                    
                    <div class="feature-card">
                        <h3>Total Events</h3>
                        <p><?php echo $total_events; ?></p>
                    </div>                    
                   
                    <div class="feature-card">
                        <h3>Amount Paid</h3>
                        <p>$<?php echo $total_paid; ?></p>
                    </div><div class="feature-card">
                        <h3>Pending Amount</h3>
                        <p>$<?php echo $total_unpaid; ?></p>
                    </div>
                    </div>
                    <p></p>
                <div class="feature-cards">
                    
                    <div class="feature-card" style="width:400px;">
                        <h3>Information</h3>
                        <p>Name: <?php echo $total_info['o_fname'] . ' '. $total_info['o_lname']; ?>
</br>Email: <?php echo $total_info['o_email']; ?></br>
                        Address: <?php echo $total_info['o_address']; ?></p>
                    </div>
                </div>
                </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer
    <footer class="footer">
        <p>&copy; 2024 EMS. All Rights Reserved.</p>
    </footer> -->
</body>
</html>