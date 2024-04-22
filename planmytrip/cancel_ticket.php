<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect the user to the login page
    header("Location: passenger_login.php");
    exit();
}

// Include the database connection file
require_once 'dbconn.php';

// Check if the cancel button is clicked
if (isset($_POST['cancel'])) {
    // Get the booking ID to cancel
    $booking_id = $_POST['booking_id'];

    // Delete the booking from the database
    $sql = "DELETE FROM bookingdetails WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();

    // Check if the booking is successfully cancelled
    if ($stmt->affected_rows > 0) {
        $_SESSION['cancel_success'] = true;
    } else {
        $_SESSION['cancel_failed'] = true;
    }

    // Redirect back to the dashboard
    header("Location: passenger_dashboard.php");
    exit();
}

// Fetch booked flight details for the logged-in user
$username = $_SESSION['username'];
$sql = "SELECT bd.booking_id, f.flight_number, f.departure, f.arrival, f.departure_time, f.arrival_time, bd.seat_number
        FROM bookingdetails bd
        INNER JOIN flights f ON bd.flight_id = f.flight_id
        INNER JOIN users u ON bd.user_id = u.user_id
        WHERE u.username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Close the prepared statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Cancel Ticket</title>
    <style>
        .container table {
    width: 100%;
    border-collapse: collapse;
}

.container button {
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    background-color: #ff3333;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.container button:hover {
    background-color: #cc0000;
}
    </style>
</head>
<body>
<h1 style="text-align: center;">PlanMyTrip</h1>

    <div class="container">
    <h2>Cancel Ticket</h2>
    <a href="passenger_dashboard.php">Back to Dashboard</a>
    </div>
    <div class="container">
        
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Flight Number</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                        <th>Seat Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['booking_id']; ?></td>
                            <td><?php echo $row['flight_number']; ?></td>
                            <td><?php echo $row['departure']; ?></td>
                            <td><?php echo $row['arrival']; ?></td>
                            <td><?php echo $row['departure_time']; ?></td>
                            <td><?php echo $row['arrival_time']; ?></td>
                            <td><?php echo $row['seat_number']; ?></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                                    <button type="submit" name="cancel">Cancel</button>
                                    <?php $_SESSION['cancel_success'] = true; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No booked tickets found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
