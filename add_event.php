<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event - EMS</title>
    <link rel="stylesheet" href="./styles.css">
    <style>/* Style for radio buttons */
input[type="radio"] {
    display: inline-block;
    width: 10%;
    /* padding: 100px; */
    /* margin-right: 0px; Add some space between the radio buttons */
}
/* input,
select {
    margin-bottom: 0px;
    border: 1px solid #ccc;
    border-radius: 4px;
} */
/* Optional: Style for labels to make the layout clearer */
label {
    display: inline-block;
    margin-right: 20px; /* Adjust the space between the label and next element */
}
</style>
</head>

<body>

    <!-- Navbar Section -->
    <nav class="navbar">
        <div class="logo">EMS</div>
        <ul class="navbar-links">
            <li><a href="c_dashboard.php">Dashboard</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Form Section -->
    <div class="form-container">
        <h2>Create Event</h2>
        <form action="submit_event.php" method="POST">
            <!-- <label for="c_id">Customer ID:</label>
            <input type="text" id="c_id" name="c_id" required> -->

            <label for="event_type">Event Type:</label>
            <input type="text" id="event_type" name="event_type" required>

            <label for="no_of_guests">Number of Guests:</label>
            <input type="number" id="no_of_guests" name="no_of_guests" required>

            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required>

            <label for="total_amount">Total Amount:</label>
            <input type="number" id="total_amount" name="total_amount" required>

            <p>Would you like to do the payment right now?</p>
            <input type="radio" id="payment_status_yes" name="payment_status" value="1">
            <label for="payment_status_yes">Yes</label>

            <input type="radio" id="payment_status_no" name="payment_status" value="0">
            <label for="payment_status_no">No</label>
            <button type="submit">Create Event</button>
        </form>
    </div>
    <!-- Footer -->
    <!-- <div class="footer">
        <p>&copy; 2024 EMS. All rights reserved.</p>
    </div> -->
</body>

</html>