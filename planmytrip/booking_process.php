<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: passenger_login.php");
    exit();
}

// Include database connection
require_once 'dbconn.php';

// Check if the form is submitted and all required fields are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['flight_id'], $_POST['seat'])) {
    // Retrieve form data
    $flight_id = $_POST['flight_id'];
    $seat_number = $_POST['seat'];

    // Fetch username
    $username = $_SESSION['username'];

    // Perform the database operation (inserting booking details)
    $sql = "INSERT INTO bookingdetails (flight_id, user_id, seat_number) VALUES (?, (SELECT user_id FROM users WHERE username = ?), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $flight_id, $username, $seat_number);
    $stmt->execute();

    // Check if the query executed successfully
    if ($stmt->affected_rows > 0) {
        // Booking successful
        $_SESSION['booking_success'] = true;
    } else {
        // Booking failed
        $_SESSION['booking_failed'] = true;
        // Log the error
        error_log("Booking failed: " . $stmt->error);
    }

    // Close the statement
    $stmt->close();
}

// Redirect back to the previous page or display a message
header("Location: review_ticket.php");
exit();
?>
