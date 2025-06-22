<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "ems_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$organizer_id = "";
$message = "";
$events = [];
$organize_successful = false;

// Check if the organizer search form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_organizer'])) {
    if (isset($_POST['organizer_id']) && !empty($_POST['organizer_id'])) {
        $organizer_id = $_POST['organizer_id'];

        // Fetch events for the given organizer ID
        $sql = "SELECT * FROM events WHERE o_id = '$organizer_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $events[] = $row;
            }
        } else {
            $message = "No events found for Organizer ID: $organizer_id";
        }
    } else {
        $message = "Please enter a Organizer ID.";
    }
}

// Check if the organize form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['organize_event'])) {
    $event_id = $_POST['organize_event'];
    $organizer_id = $_POST['organizer_id'];

    // Update o_status to "1" (Organized)
    $sql = "UPDATE events SET o_status = 1 WHERE e_id = '$event_id'";

    if ($conn->query($sql) === TRUE) {
        $organize_successful = true;
        $message = "Organize updated successfully!";
    } else {
        $message = "Error updating organize: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organize - EMS</title>
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
    </style>
</head>
<body>
    <!-- Navbar Section -->
    <div class="navbar">
        <div class="logo">EMS</div>
        
        <ul>
            <li><a href="o_dashboard.php">Dashboard</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Container -->
    <div class="container">
        <h1>Search Organizer Events</h1>

        <!-- Search Form -->
        <form action="" method="POST" class="form-container">
            <label for="organizer_id">Enter Organizer ID:</label>
            <input type="text" name="organizer_id" id="organizer_id" value="<?php echo htmlspecialchars($organizer_id); ?>" required>
            <button type="submit" name="search_organizer" class="button">Search</button>
        </form>

        <hr>

        <?php
        // Display success or error messages
        if (!empty($message)) {
            echo "<p class='message'>$message</p>";
        }

        // Display events if found
        if (!empty($events)) {
        ?>
            <h2>Organizer Events</h2>
            <form action="" method="POST">
                <input type="hidden" name="organizer_id" value="<?php echo htmlspecialchars($organizer_id); ?>">
                <table>
                    <thead>
                        <tr>
                            <th>Event ID</th>
                            <th>Event Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Amount</th>
                            <th>Payment Status</th>
                            <th>Organize Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($events as $row) {
                            echo "<tr>
                                <td>{$row['e_id']}</td>
                                <td>{$row['type']}</td>
                                <td>{$row['start_date']}</td>
                                <td>{$row['end_date']}</td>
                                <td>{$row['total_amount']}</td>
                                <td>" . ($row['payment_status'] == 1 ? "Paid" : "Unpaid") . "</td>
                                <td>" . ($row['o_status'] == 1 ? "Organized" : "Not Organized") . "</td>
                                <td>";
                            if ($row['o_status'] == 0) {
                                echo "<button type='submit' name='organize_event' value='{$row['e_id']}' class='button'>Organize Now</button>";
                            } else {
                                echo "<span style='color: gray;'>Organized</span>";
                            }
                            echo "</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </form>
        <?php
        }

        // Display thank you message after organize
        ?>
    </div>

    <?php $conn->close(); ?>
</body>
</html>
