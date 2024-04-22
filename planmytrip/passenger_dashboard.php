<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect the user to the login page or display an error message
    header("Location: passenger_login.php");
    exit();
}

// Include the database connection file
require_once 'dbconn.php';

// Check if the cancel success session variable is set and display the message
if (isset($_SESSION['cancel_success'])) {
    echo '<p class="success">Ticket cancelled successfully.</p>';
    unset($_SESSION['cancel_success']); // Remove the session variable after displaying the message
}

// Check if the cancel success session variable is set and display the message
if (isset($_SESSION['ticket_booked'])) {
    echo '<p class="success">Ticket Booked successfully.</p>';
    unset($_SESSION['ticket_booked']); // Remove the session variable after displaying the message
}

// Fetch user details from the database
$username = $_SESSION['username'];
$sql_user = "SELECT name FROM users WHERE username = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $username);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

$user_details = "";
if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $user_details = $row_user['name'];
} else {
    echo "Error: User not found!";
}

// Fetch active booked tickets with booking details and flight details
$sql_tickets = "SELECT bd.booking_id, f.flight_number, f.departure, f.arrival, f.departure_time, f.arrival_time, bd.seat_number
                FROM bookingdetails bd
                INNER JOIN flights f ON bd.flight_id = f.flight_id
                INNER JOIN users u ON bd.user_id = u.user_id
                WHERE u.username = ?";
$stmt_tickets = $conn->prepare($sql_tickets);
$stmt_tickets->bind_param("s", $username);
$stmt_tickets->execute();
$result_tickets = $stmt_tickets->get_result();

// Close the prepared statements
$stmt_user->close();
$stmt_tickets->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1 style="text-align: center;">PlanMyTrip</h1>

    <div class="container">
        <h1>Welcome, <?php echo $user_details; ?></h1>
        <div class="menu">
            <a href="booking.php">Book a Flight</a>
            <a href="cancel_ticket.php">Cancel Bookings</a>
            <a href="logout.php">Logout</a>
        </div>

        <h2>Active Booked Tickets</h2>
        <?php if ($result_tickets->num_rows > 0): ?>
            <table class="tickets">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Flight Number</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                        <th>Seat Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_tickets->fetch_assoc()): ?>
                        <tr class="ticket">
                            <td><?php echo $row['booking_id']; ?></td>
                            <td><?php echo $row['flight_number']; ?></td>
                            <td><?php echo $row['departure']; ?></td>
                            <td><?php echo $row['arrival']; ?></td>
                            <td><?php echo $row['departure_time']; ?></td>
                            <td><?php echo $row['arrival_time']; ?></td>
                            <td><?php echo $row['seat_number']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No active booked tickets.</p>
        <?php endif; ?>
    </div>
</body>
</html>
