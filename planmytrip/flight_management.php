<?php
session_start();

// Check if the user is not logged in as admin, redirect to login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

// Include the database connection file
include 'dbconn.php';

// Fetch flights from the database
$sql = "SELECT * FROM flights";
$result = $conn->query($sql);

$flights = array(); // Initialize $flights array
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $flights[] = $row; // Add fetched row to $flights array
    }
}

// Initialize message variables
$cancel_message = '';
$error_message = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if flight_to_cancel is set
    if (isset($_POST['flight_to_cancel'])) {
        // Get the flight id to cancel
        $flight_to_cancel = $_POST['flight_to_cancel'];
        
        // Prepare and execute SQL statement to delete associated bookings
        $stmt = $conn->prepare("DELETE FROM bookingdetails WHERE flight_id = ?");
        $stmt->bind_param("i", $flight_to_cancel);
        if ($stmt->execute()) {
            // After deleting associated bookings, delete the flight
            $stmt = $conn->prepare("DELETE FROM flights WHERE flight_id = ?");
            $stmt->bind_param("i", $flight_to_cancel);
            if ($stmt->execute()) {
                // Flight cancellation successful
                $cancel_message = "Flight successfully cancelled.";
                // Redirect to refresh the page
                header("Refresh:2");
            } else {
                // Error occurred during flight cancellation
                $error_message = "Error cancelling flight: " . $conn->error;
            }
        } else {
            // Error occurred while deleting associated bookings
            $error_message = "Error deleting associated bookings: " . $conn->error;
        }
        // Close prepared statement
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Management - PlanMyTrip</title>
    <link rel="stylesheet" href="style.css">
    <style>
        h1, h2 {
    margin-bottom: 20px;
}

/* Message Styles */
.success-message {
    color: #4CAF50;
}

.error-message {
    color: #f44336;
}

/* Form Styles */
form {
    display: inline-block;
    margin-bottom: 20px;
}

select, button {
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

select {
    margin-right: 10px;
}

button {
    margin-left: 10px;
}

button:hover {
    background-color: #f44336;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f2f2f2;
}
    </style>
</head>
<body>
    <h1 style="text-align: center;">PlanMyTrip</h1>
    <div class="container">
        <h1>Flight Management</h1>
        <!-- Link back to Dashboard -->
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
    <div class="container">
        <!-- Cancel Flight Option -->
        <h2>Cancel Flight</h2>
        <?php if ($cancel_message != ''): ?>
            <p class="success-message"><?php echo $cancel_message; ?></p>
        <?php endif; ?>
        <?php if ($error_message != ''): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <select name="flight_to_cancel">
                <?php foreach ($flights as $flight): ?>
                    <option value="<?php echo $flight['flight_id']; ?>">
                        <?php echo $flight['flight_number'] . " - " . $flight['departure'] . " to " . $flight['arrival']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Cancel Flight</button>
        </form>

        <!-- Table of Flights -->
        <h2>Flight Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Flight Number</th>
                    <th>Departure</th>
                    <th>Arrival</th>
                    <th>Departure Time</th>
                    <th>Arrival Time</th>
                    <th>Available Seats</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($flights as $flight): ?>
                    <tr>
                        <td><?php echo $flight['flight_number']; ?></td>
                        <td><?php echo $flight['departure']; ?></td>
                        <td><?php echo $flight['arrival']; ?></td>
                        <td><?php echo $flight['departure_time']; ?></td>
                        <td><?php echo $flight['arrival_time']; ?></td>
                        <td><?php echo $flight['available_seats']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
