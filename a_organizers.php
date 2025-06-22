<?php
require_once ('dbconnect.php');

// Handle the delete action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // SQL query to delete the record
    $sql = "DELETE FROM organizer WHERE o_id = '$delete_id'";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Record deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting record');</script>";
    }
}

// SQL query to fetch organizer data
$sql = "SELECT o_id, o_fname, o_lname, o_address, o_email FROM organizer";
$result = $conn->query($sql);
// Close connection
$conn->close();
?>
<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Organizer Data - EMS</title>
        <link rel='stylesheet' type='text/css' href='styles.css'> <!-- Link to your CSS file -->
    </head>
    <body>
    <nav class="navbar">
        <div class="logo">EMS</div>
        <ul class="navbar-links">
            <!-- <li><a href="o_dashboard.php">Dashboard</a></li> -->
            <li><a href="a_dashboard.php">Dashboard</a></li>
            <li><a href="a_customers.php">Customers</a></li>
            <li><a href="event_mgmt.php">Event Management</a></li>
            <li><a href="edit.php">Edit User Info</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>
        
        <div class='hero'>
            <div class='hero-content'>
                <h1>Organizer Information</h1>
                <p>Below is the list of organizers stored in the database:</p>
            </div>
        </div>
        
        <div class='features'>
            <table border='1' cellpadding='10' cellspacing='0' style='width: 100%;'>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Actions</th> <!-- For Delete Button -->
                </tr>
                
                <!-- // Output data of each row -->
                <?php
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . $row["o_id"]. "</td>
                        <td>" . $row["o_fname"]." ".$row["o_lname"]. "</td>
                        <td>" . $row["o_address"]. "</td>
                        <td>" . $row["o_email"]. "</td>
                        <td>
                            
                            <a href='?delete_id=" . $row["o_id"] . "' 
                               onclick='return confirm(\"Are you sure you want to delete this " . $row["o_id"]. "?\")'>
                               <button>Delete</button>
                            </a>
                        </td>
                    </tr>";
                }?>

    </table>
        </div>
<!--         
        <div class='footer'>
            <p>&copy; 2024 EMS System</p>
        </div> -->
    </body>
    </html>