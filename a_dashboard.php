<?php
require_once ('dbconnect.php');

// Fetch total customers count
$sql_customers = "SELECT * FROM customer";
$result_customers = mysqli_query($conn, $sql_customers);
$total_customers = mysqli_num_rows($result_customers);

// Fetch total organizers count
$sql_organizers = "SELECT * FROM organizer";
$result_organizers = mysqli_query($conn, $sql_organizers);
$total_organizers = mysqli_num_rows($result_organizers);

// Fetch total events count
$sql_events = "SELECT * FROM events";
$result_events = mysqli_query($conn, $sql_events);
$total_events = mysqli_num_rows($result_events);

// Fetch total budget
$sql_budget = "SELECT SUM(total_amount) AS total_budget FROM events";
$result_budget = mysqli_query($conn, $sql_budget);
$total_budget = mysqli_fetch_assoc($result_budget)['total_budget'];

// Fetch total unpaid
$sql_unpaid = "SELECT * FROM events WHERE payment_status = 0";
$result_unpaid = mysqli_query($conn, $sql_unpaid);
$total_unpaid = mysqli_num_rows($result_unpaid);

// Fetch total unpaid
$sql_paid = "SELECT * FROM events WHERE payment_status = 1";
$result_paid = mysqli_query($conn, $sql_paid);
$total_paid = mysqli_num_rows($result_paid);

$sql_sum_paid = "SELECT SUM(total_amount) AS sum_paid FROM events WHERE payment_status = 1";
$result_sum_paid = mysqli_query($conn, $sql_sum_paid);
$total_sum_paid = mysqli_fetch_assoc($result_sum_paid)['sum_paid'];

$sql_sum_unpaid = "SELECT SUM(total_amount) AS sum_unpaid FROM events WHERE payment_status = 0";
$result_sum_unpaid = mysqli_query($conn, $sql_sum_unpaid);
$total_sum_unpaid = mysqli_fetch_assoc($result_sum_unpaid)['sum_unpaid'];

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
            <li><a href="a_customers.php">Customers</a></li>
            <li><a href="a_organizers.php">Organizers</a></li>
            <li><a href="event_mgmt.php">Event Management</a></li>
            <li><a href="edit.php">Edit User Info</a></li>
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
                        <h3>Users Info</h3>
                        <p>Total Customers: <?php echo $total_customers; ?></br>Total Organizers: <?php echo $total_organizers; ?></p>
                    </div>
                    <div class="feature-card">
                        <h3>Events Info</h3>
                        <p>Total Events: <?php echo $total_events; ?></br>Paid Events: <?php echo $total_paid; ?></br>Unpaid Events: <?php echo $total_unpaid; ?></p>
                    </div>
                <div class="feature-card">
                        <h3>Budget</h3>
                        <p>Total Budget: $<?php echo $total_budget; ?></br>Total Paid: $<?php echo $total_sum_paid; ?></br>Total Unpaid: $<?php echo $total_sum_unpaid; ?></p>
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
