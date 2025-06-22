<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('dbconnect.php');

    $role = $_POST['role'];

    if ($role === 'customer') {
        $c_id = $_POST['c_id'];
        $c_fname = $_POST['c_fname'];
        $c_lname = $_POST['c_lname'];
        $c_email = $_POST['c_email'];
        $c_address = $_POST['c_address'];
        $c_password = $_POST['c_password']; // Secure password
        $c_phones = $_POST['c_phone'];

        // Insert into Customer table
        $sql_customer = "INSERT INTO Customer (c_id, c_fname, c_lname, c_email, c_address, c_password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_customer);
        $stmt->bind_param('ssssss', $c_id, $c_fname, $c_lname, $c_email, $c_address, $c_password);

        if ($stmt->execute()) {
            // Insert into CustomerPhone table
            $phone_sql = "INSERT INTO CustomerPhone (c_id, c_phone) VALUES (?, ?)";
            $phone_stmt = $conn->prepare($phone_sql);

            foreach ($c_phones as $phone) {
                $phone_stmt->bind_param('ss', $c_id, $phone);
                $phone_stmt->execute();
            }
            echo "<p style='color: green; text-align: center;'>Customer signup successful!</p>";
        } else {
            echo "<p style='color: red; text-align: center;'>Error: " . $stmt->error . "</p>";
        }
    } elseif ($role === 'organizer') {
        $o_id = $_POST['o_id'];
        $o_fname = $_POST['o_fname'];
        $o_lname = $_POST['o_lname'];
        $o_email = $_POST['o_email'];
        $o_address = $_POST['o_address'];
        $o_password = $_POST['o_password'];
        $o_phones = $_POST['o_phone'];

        // Insert into Organizer table
        $sql_organizer = "INSERT INTO Organizer (o_id, o_fname, o_lname, o_email, o_address, o_password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_organizer);
        $stmt->bind_param('ssssss', $o_id, $o_fname, $o_lname, $o_email, $o_address, $o_password);

        if ($stmt->execute()) {
            // Insert into OrganizerPhone table
            $phone_sql = "INSERT INTO OrganizerPhone (o_id, o_phone) VALUES (?, ?)";
            $phone_stmt = $conn->prepare($phone_sql);

            foreach ($o_phones as $phone) {
                $phone_stmt->bind_param('ss', $o_id, $phone);
                $phone_stmt->execute();
            }
            echo "<p style='color: green; text-align: center;'>Organizer signup successful!</p>";
        } else {
            echo "<p style='color: red; text-align: center;'>Error: " . $stmt->error . "</p>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Event Management System</title>
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

<body>
<nav class="navbar">
        <div class="logo">EMS</div>
        <ul class="navbar-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>
<div class="form-container">
        <h2 style="text-align: center;">Signup</h2>
        <form action="" method="post">
            <table>
                <tr>
                    <td><label for="role">Choose Role:</label></td>
                    <td>
                        <select name="role" id="role" required>
                            <option value="customer">Customer</option>
                            <option value="organizer">Organizer</option>
                        </select>
                    </td>
                </tr>

                <!-- Customer Fields -->
                <tbody id="customer-fields">
                    <tr>
                        <td><label for="c_id">Customer ID:</label></td>
                        <td><input type="text" id="c_id" name="c_id" placeholder="Enter your Customer ID"></td>
                    </tr>
                    <tr>
                        <td><label for="c_fname">First Name:</label></td>
                        <td><input type="text" id="c_fname" name="c_fname" placeholder="Enter your First Name"></td>
                    </tr>
                    <tr>
                        <td><label for="c_lname">Last Name:</label></td>
                        <td><input type="text" id="c_lname" name="c_lname" placeholder="Enter your Last Name"></td>
                    </tr>
                    <tr>
                        <td><label for="c_email">Email:</label></td>
                        <td><input type="email" id="c_email" name="c_email" placeholder="Enter your Email"></td>
                    </tr>
                    <tr>
                        <td><label for="c_address">Address:</label></td>
                        <td><textarea id="c_address" name="c_address" placeholder="Enter your Address"></textarea></td>
                    </tr>
                    <tr>
                        <td><label for="c_password">Password:</label></td>
                        <td><input type="password" id="c_password" name="c_password" placeholder="Enter your Password"></td>
                    </tr>
                    <tr>
                        <td><label for="c_phone[]">Phone Numbers:</label></td>
                        <td>
                            <input type="text" id="c_phone" name="c_phone[]" placeholder="Enter your Phone Number">
                            <div id="additionalPhones"></div>
                            <button type="button" onclick="addPhoneField('customer')">Add Another Phone</button>
                        </td>
                    </tr>
                </tbody>

                <!-- Organizer Fields -->
                <tbody id="organizer-fields" style="display:none;">
                    <tr>
                        <td><label for="o_id">Organizer ID:</label></td>
                        <td><input type="text" id="o_id" name="o_id" placeholder="Enter your Organizer ID"></td>
                    </tr>
                    <tr>
                        <td><label for="o_fname">First Name:</label></td>
                        <td><input type="text" id="o_fname" name="o_fname" placeholder="Enter your First Name"></td>
                    </tr>
                    <tr>
                        <td><label for="o_lname">Last Name:</label></td>
                        <td><input type="text" id="o_lname" name="o_lname" placeholder="Enter your Last Name"></td>
                    </tr>
                    <tr>
                        <td><label for="o_email">Email:</label></td>
                        <td><input type="email" id="o_email" name="o_email" placeholder="Enter your Email"></td>
                    </tr>
                    <tr>
                        <td><label for="o_address">Address:</label></td>
                        <td><textarea id="o_address" name="o_address" placeholder="Enter your Address"></textarea></td>
                    </tr>
                    <tr>
                        <td><label for="o_password">Password:</label></td>
                        <td><input type="password" id="o_password" name="o_password" placeholder="Enter your Password"></td>
                    </tr>
                    <tr>
                        <td><label for="o_phone[]">Phone Numbers:</label></td>
                        <td>
                            <input type="text" id="o_phone" name="o_phone[]" placeholder="Enter your Phone Number">
                            <div id="additionalPhonesOrganizer"></div>
                            <button type="button" onclick="addPhoneField('organizer')">Add Another Phone</button>
                        </td>
                    </tr>
                </tbody>

                <tr>
                    <td colspan="2" style="text-align: center;">
                        <button type="submit">Signup</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <!-- <div class="footer">
        <p>&copy; 2024 EMS. All rights reserved.</p>
    </div> -->
    <script>
        const roleSelect = document.getElementById('role');
        const customerFields = document.getElementById('customer-fields');
        const organizerFields = document.getElementById('organizer-fields');

        roleSelect.addEventListener('change', () => {
            if (roleSelect.value === 'customer') {
                customerFields.style.display = 'table-row-group';
                organizerFields.style.display = 'none';
            } else if (roleSelect.value === 'organizer') {
                customerFields.style.display = 'none';
                organizerFields.style.display = 'table-row-group';
            }
        });

        function addPhoneField(role) {
            const container = role === 'customer' ? document.getElementById('additionalPhones') : document.getElementById('additionalPhonesOrganizer');

            // Create new input field for phone number
            const newField = document.createElement('input');
            newField.setAttribute('type', 'text');
            newField.setAttribute('name', `${role === 'customer' ? 'c_phone[]' : 'o_phone[]'}`);
            newField.setAttribute('placeholder', 'Enter another Phone Number');

            // Create the remove button
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.textContent = 'Remove';
            removeButton.style.marginTop = '5px';
            removeButton.onclick = function() {
                container.removeChild(newField);
                container.removeChild(removeButton);
            };

            // Append both the phone input field and remove button to the container
            container.appendChild(newField);
            container.appendChild(removeButton);
        }
    </script>

</body>

</html>
