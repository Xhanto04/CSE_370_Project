<?php
require_once ('dbconnect.php');

// Handle the delete action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // SQL query to delete the record
    $sql = "DELETE FROM customer WHERE c_id = '$delete_id'";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Record deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting record');</script>";
    }
}

// SQL query to fetch customer data
$sql = "SELECT c_id, c_fname, c_lname, c_address, c_email FROM customer";
$result = $conn->query($sql);
// Close connection
$conn->close();
?>
<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Customer Data - EMS</title>
        <link rel='stylesheet' type='text/css' href='styles.css'> <!-- Link to your CSS file -->
    </head>
    <body>
    <nav class="navbar">
        <div class="logo">EMS</div>
        <ul class="navbar-links">
            <!-- <li><a href="o_dashboard.php">Dashboard</a></li> -->
            <li><a href="a_dashboard.php">Dashboard</a></li>
            <li><a href="a_organizers.php">Organizers</a></li>
            <li><a href="event_mgmt.php">Event Management</a></li>
            <li><a href="edit.php">Edit User Info</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>
        
        <div class='hero'>
            <div class='hero-content'>
                <h1>Customer Information</h1>
                <p>Below is the list of customers stored in the database:</p>
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
                        <td>" . $row["c_id"]. "</td>
                        <td>" . $row["c_fname"]." ".$row["c_lname"]. "</td>
                        <td>" . $row["c_address"]. "</td>
                        <td>" . $row["c_email"]. "</td>
                        <td>
                            
                            <a href='?delete_id=" . $row["c_id"] . "' 
                               onclick='return confirm(\"Are you sure you want to delete this " . $row["c_id"]. "?\")'>
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

