<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: passenger_login.php");
    exit();
}

// Include database connection
require_once 'dbconn.php';

// Check if flight ID is provided in the POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['flight_id'])) {
    $flight_id = $_POST['flight_id'];

    // Fetch flight details from the database based on flight ID
    $stmt = $conn->prepare("SELECT * FROM flights WHERE flight_id = ?");
    $stmt->bind_param("i", $flight_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if flight details are fetched successfully
    if ($result->num_rows == 1) {
        $flight = $result->fetch_assoc();
    } else {
        echo "Error: Flight not found!";
        exit();
    }
} else {
    header("Location: passenger_dashboard.php");
    exit();
}

// Dummy seat numbers
$available_seats = range(1, $flight['available_seats']);

// Close statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Review Ticket</title>
    <style>
        .container div {
    margin-bottom: 20px;
}

.container p {
    margin: 5px 0;
}

form {
    text-align: center;
}

label {
    display: block;
    margin-bottom: 10px;
    color: #fff;
}

select {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #333;
    background-color: #333;
    color: #fff;
    margin-bottom: 20px;
}

button[type="submit"], a.cancel-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    background-color: #0066ff;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s ease;
    text-decoration: none;
    margin-bottom: 92px;
}
a.cancel-btn
{
    text-align: center;
    background-color: red;
    margin-bottom:0px
}

button[type="submit"]:hover, a.cancel-btn:hover {
    background-color: #0052cc;
}
    </style>
</head>
<body>
<h1 style="text-align: center;">PlanMyTrip</h1>
<div class="container">
<h2>Review Ticket</h2>
</div>
<div class="container">
    <div>
        <p><strong>Flight Number:</strong> <?php echo $flight['flight_number']; ?></p>
        <p><strong>Departure:</strong> <?php echo $flight['departure']; ?></p>
        <p><strong>Arrival:</strong> <?php echo $flight['arrival']; ?></p>
        <p><strong>Departure Time:</strong> <?php echo $flight['departure_time']; ?></p>
        <p><strong>Arrival Time:</strong> <?php echo $flight['arrival_time']; ?></p>
        <form action="booking_process.php" method="post">
            <label for="seat">Select Seat Number:</label>
            <select name="seat" id="seat" required>
                <?php foreach ($available_seats as $seat): ?>
                    <option value="<?php echo "A$seat"; ?>"><?php echo "A$seat"; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="flight_id" value="<?php echo $flight_id; ?>">
            <button type="submit" name="confirm_booking">Confirm Booking</button>
            <?php $_SESSION['ticket_booked'] = true; ?>
        </form>
        <a href="booking.php" class="cancel-btn">Cancel</a>
    </div>
    </div>
</body>
</html>
