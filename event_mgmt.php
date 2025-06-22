
<?php
include 'dbconnect.php';

// SQL query to fetch events where o_id is not NULL (approved events)
$sql = "SELECT e_id, type, no_of_guests, start_date, end_date, o_id, c_id, payment_status, total_amount FROM events WHERE o_id IS NOT NULL";
$result = mysqli_query($conn, $sql);
// Fetch pending events where o_id is NULL
$sql_events = "SELECT e_id, type, no_of_guests, start_date, end_date, c_id, payment_status, total_amount FROM events WHERE o_id IS NULL";
$result_events = mysqli_query($conn, $sql_events);

// Fetch all organizers for the dropdown
// $sql_organizers = "SELECT o_id FROM organizer";
$sql_organizers = "SELECT o_id, o_fname, o_lname FROM organizer";
$result_organizers = mysqli_query($conn, $sql_organizers);

// Handle form submission (assign organizer to event)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign'])) {
    $event_id = $_POST['event_id'];
    $organizer_id = $_POST['organizer_id'];

    // Update the event with the selected organizer
    $sql_update = "UPDATE events SET o_id = '{$organizer_id}' WHERE e_id = '{$event_id}'";
    echo($sql_update);
    mysqli_query($conn, $sql_update);

    // Redirect to the same page to refresh the data
    header("Location: event_mgmt.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management - EMS</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>

    <!-- Navbar Section -->
    <div class="navbar">
        <div class="logo">EMS</div>
        <ul class="navbar-links">
            <li><a href="a_dashboard.php">Dashboard</a></li>
            <li><a href="a_customers.php">Customers</a></li>
            <li><a href="a_organizers.php">Organizers</a></li>
            <li><a href="edit.php">Edit User Info</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </div>

    <!-- Hero Section -->
    <section class="hero";>
        <div class="hero-content">
            <h1>Unassigned Events</h1>
            <p>Here are the events that are unassigned to an organizer.</p>
        </div>
    </section>

    <!-- Events Table Section -->
    <section class="features">
        <h2>Pending Events</h2>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
            <thead>
                <tr>
                    <th>Event ID</th>
                    <th>Event Type</th>
                    <th>No. of Guests</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Customer ID</th>
                    <th>Total Budget</th>
                    <th>Payment Status</th>
                    <th>Assign Organizer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display pending events
                if ($result_events->num_rows > 0) {
                    while($row = $result_events->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['e_id']}</td>
                                <td>{$row['type']}</td>
                                <td>{$row['no_of_guests']}</td>
                                <td>{$row['start_date']}</td>
                                <td>{$row['end_date']}</td>
                                <td>{$row['c_id']}</td>
                                <td>{$row['total_amount']}</td>
                                <td>"; if ($row['payment_status']) {echo 'Paid';} else {echo 'Unpaid';}echo "</td>
                                <td>
                                    <form method='POST' action=''>
                                        <select name='organizer_id' required>
                                            <option value=''>Select Organizer</option>";
                                            
                                            // Display organizers in the dropdown
                                            $result_organizers->data_seek(0); // Reset pointer to the first row
                                            while($organizer = $result_organizers->fetch_assoc()) {
                                                echo "<option value='{$organizer['o_id']}'>{$organizer['o_fname']} {$organizer['o_lname']}</option>";
                                            }
                                    
                        echo "</select>
                              <input type='hidden' name='event_id' value='{$row['e_id']}'>
                              <button type='submit' name='assign'>Assign</button>
                              </form>
                              </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No pending events found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Assigned Events</h1>
            <p>Here are the events that have been assigned to an organizer.</p>
        </div>
    </section>

    <!-- Events Table Section -->
    <section class="features">
        <h2>Upcoming Events</h2>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
            <thead>
                <tr>
                    <th>Event ID</th>
                    <th>Event Type</th>
                    <th>No. of Guests</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Organizer ID</th>
                    <th>Customer ID</th>
                    <th>Total Budget</th>
                    <th>Payment Status</th>
                </tr>
            </thead>
            <tbody>
            <?php
// Display the fetched event data in the table
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['e_id']}</td>
                <td>{$row['type']}</td>
                <td>{$row['no_of_guests']}</td>
                <td>{$row['start_date']}</td>
                <td>{$row['end_date']}</td>
                <td>{$row['o_id']}</td>
                <td>{$row['c_id']}</td>
                <td>{$row['total_amount']}</td>
                <td>"; if ($row['payment_status']) {echo 'Paid';} else {echo 'Unpaid';}echo "</td>
              </tr>";                              
    }
} else {
    echo "<tr><td colspan='7'>No events found</td></tr>";
}
?>

            </tbody>
        </table>
    </section>

    <!-- Footer Section -->
    <!-- <div class="footer">
        <p>&copy; 2024 EMS. All rights reserved.</p>
    </div> -->

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
