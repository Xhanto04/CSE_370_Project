<?php
include('dbconnect.php'); // Database connection file

$role = '';
$data = null;
$phones = [];

// Handle the request for fetching and editing details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? 'customer'; // Default role is 'customer'
    $id = $_POST['id'] ?? ''; // Use the default 'id' field

    // Determine table and ID column based on role
    $table = $role === 'customer' ? 'Customer' : 'Organizer';
    $id_field = $role === 'customer' ? 'c_id' : 'o_id';

    // Fetch information based on role
    $sql = "SELECT * FROM $table WHERE $id_field = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        // Fetch phone numbers
        $phone_table = $role === 'customer' ? 'CustomerPhone' : 'OrganizerPhone';
        $phone_column = $role === 'customer' ? 'c_phone' : 'o_phone';
        $phone_sql = "SELECT $phone_column FROM $phone_table WHERE $id_field = ?";
        $phone_stmt = $conn->prepare($phone_sql);
        $phone_stmt->bind_param('s', $id);
        $phone_stmt->execute();
        $phone_result = $phone_stmt->get_result();
        while ($row = $phone_result->fetch_assoc()) {
            $phones[] = $row[$phone_column];
        }
    }
}

// Handle update requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $role = $_POST['role'];
    $id = $_POST['id'];

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $phone_numbers = $_POST['phone'];

    $table = $role === 'customer' ? 'Customer' : 'Organizer';
    $id_field = $role === 'customer' ? 'c_id' : 'o_id';
    $phone_table = $role === 'customer' ? 'CustomerPhone' : 'OrganizerPhone';
    $phone_column = $role === 'customer' ? 'c_phone' : 'o_phone';

    // Update main table
    $update_sql = "UPDATE $table SET " . ($role === 'customer' ? "c_fname" : "o_fname") . " = ?, " . ($role === 'customer' ? "c_lname" : "o_lname") . " = ?, " . ($role === 'customer' ? "c_email" : "o_email") . " = ?, " . ($role === 'customer' ? "c_address" : "o_address") . " = ?, " . ($role === 'customer' ? "c_password" : "o_password") . " = ? WHERE $id_field = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('ssssss', $fname, $lname, $email, $address, $password, $id);

    if ($stmt->execute()) {
        // Update phone numbers
        $delete_phone_sql = "DELETE FROM $phone_table WHERE $id_field = ?";
        $delete_stmt = $conn->prepare($delete_phone_sql);
        $delete_stmt->bind_param('s', $id);
        $delete_stmt->execute();

        $insert_phone_sql = "INSERT INTO $phone_table ($id_field, $phone_column) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_phone_sql);
        foreach ($phone_numbers as $phone) {
            $insert_stmt->bind_param('ss', $id, $phone);
            $insert_stmt->execute();
        }
        echo "<p>Information updated successfully!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Information - EMS</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .form-container {
            max-width: 90%;
            width: 700px;
        }

        table td {
            border: none;
            vertical-align: center;
        }

        table td:first-child {
            text-align: right;
            font-weight: bold;
            width: 30%;
        }

        input,
        select,
        textarea,
        button {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        textarea {
            resize: none;
        }

    </style>
</head>
<body>    <nav class="navbar">
        <div class="logo">EMS</div>
        <ul class="navbar-links">
            <li><a href="a_dashboard.php">Dashboard</a></li>
            <li><a href="a_customers.php">Customers</a></li>
            <li><a href="a_organizers.php">Organizers</a></li>
            <li><a href="event_mgmt.php">Event Management</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>
<div class="form-container">
    <h2>Edit User Information</h2>
    <form action="" method="post">
        <table align="center" cellpadding="5" cellspacing="5">
            <tr>
                <td><label for="role">Role:</label></td>
                <td>
                    <select name="role" id="role" required>
                        <option value="customer" <?php echo $role === 'customer' ? 'selected' : ''; ?>>Customer</option>
                        <option value="organizer" <?php echo $role === 'organizer' ? 'selected' : ''; ?>>Organizer</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="id">ID:</label></td>
                <td><input type="text" name="id" id="id" placeholder="Enter ID" required></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><button type="submit">Fetch Details</button></td>
            </tr>
        </table>
    </form>

<?php if ($data): ?>
    <form action="" method="post">
        <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

        <table align="center" cellpadding="5" cellspacing="5">
            <tr>
                <td><label for="fname">First Name:</label></td>
                <td><input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($data[$role === 'customer' ? 'c_fname' : 'o_fname']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="lname">Last Name:</label></td>
                <td><input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($data[$role === 'customer' ? 'c_lname' : 'o_lname']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="email">Email:</label></td>
                <td><input type="email" id="email" name="email" value="<?php echo htmlspecialchars($data[$role === 'customer' ? 'c_email' : 'o_email']); ?>" required></td>
            </tr>
            <tr>
                <td><label for="address">Address:</label></td>
                <td><textarea id="address" name="address" required><?php echo htmlspecialchars($data[$role === 'customer' ? 'c_address' : 'o_address']); ?></textarea></td>
            </tr>
            <tr>
                <td><label for="password">Password:</label></td>
                <td><input type="password" id="password" name="password" placeholder="Enter new password" required></td>
            </tr>
            <tr>
                <td><label for="phone[]">Phone Numbers:</label></td>
                <td>
                    <?php foreach ($phones as $index => $phone): ?>
                        <div id="phone-<?php echo $index; ?>">
                            <input type="text" name="phone[]" value="<?php echo htmlspecialchars($phone); ?>" required>
                            <button type="button" onclick="removePhoneField(<?php echo $index; ?>)">Remove</button><br>
                        </div>
                    <?php endforeach; ?>
                    <div id="additionalPhones"></div>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><button type="button" onclick="addPhoneField()">Add Another Phone</button></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><button type="submit" name="update">Update Information</button></td>
            </tr>
        </table>
    </form>
<?php else: ?>
    <p style="text-align: center; color: red;">User Not Found.</p>
<?php endif; ?>

<script>
    // Function to remove a phone number field
    function removePhoneField(index) {
        const phoneField = document.getElementById("phone-" + index);
        if (phoneField) {
            phoneField.remove();
        }
    }

    // Function to add a new phone number input field
    function addPhoneField() {
        const container = document.getElementById('additionalPhones');
        const phoneIndex = container.children.length; // New index for added phone field

        // Create new phone input field
        const newFieldDiv = document.createElement('div');
        newFieldDiv.id = 'phone-' + phoneIndex;

        const newField = document.createElement('input');
        newField.setAttribute('type', 'text');
        newField.setAttribute('name', 'phone[]');
        newField.setAttribute('placeholder', 'Enter another Phone Number');
        newFieldDiv.appendChild(newField);

        // Create remove button for the new phone field
        const removeButton = document.createElement('button');
        removeButton.setAttribute('type', 'button');
        removeButton.innerText = 'Remove';
        removeButton.setAttribute('onclick', 'removePhoneField(' + phoneIndex + ')');
        newFieldDiv.appendChild(removeButton);

        // Append the new phone field to the container
        container.appendChild(newFieldDiv);
    }
</script>

</body>
</html>
