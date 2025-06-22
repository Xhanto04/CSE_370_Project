<?php

session_start();
require_once ('dbconnect.php');
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Get the user ID and role from the session
$c_id = $_SESSION['user_id'];

// Get form data
$event_type = $_POST['event_type'];
$no_of_guests = $_POST['no_of_guests'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$c_id = $_SESSION['user_id'];
$payment_status = $_POST['payment_status'];
$total_amount = $_POST['total_amount'];
// Insert query to store event data in the database
$sql = "INSERT INTO events (type, no_of_guests, start_date, end_date, total_amount, payment_status, c_id) 
        VALUES ('$event_type', '$no_of_guests', '$start_date', '$end_date', '$total_amount', $payment_status, '$c_id')";

if ($conn->query($sql) === TRUE) {
    // header("Location: add_event.php");
    echo "<script>alert('New event created successfully. Event ID: ". $conn->insert_id.". For Customer: ".$c_id."'); window.location.href='add_event.php';</script>";
    // echo "New event created successfully. Event ID: " . $conn->insert_id;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
