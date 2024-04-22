<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Book a Flight</title>
</head>
<body>
<h1 style="text-align: center;">PlanMyTrip</h1>
<div class="container">
<h2>Book a Flight</h2>
<a href="passenger_dashboard.php" class="back-btn">Return to Homepage</a>
</div>
<div class="container">
    <div class="flights">
            <?php
            // Include the database connection file
            require_once 'dbconn.php';

            // Fetch all available flights from the database
            $sql = "SELECT * FROM flights";
            $result = $conn->query($sql);

            // Check if there are any flights available
            if ($result->num_rows > 0) {
                // Display each flight as a card with details and book button
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="flight-card">';
                    echo '<h3>' . $row['flight_number'] . '</h3>';
                    echo '<p>Departure: ' . $row['departure'] . '</p>';
                    echo '<p>Arrival: ' . $row['arrival'] . '</p>';
                    echo '<p>Departure Time: ' . $row['departure_time'] . '</p>';
                    echo '<p>Arrival Time: ' . $row['arrival_time'] . '</p>';
                    echo '<p>Available Seats: ' . $row['available_seats'] . '</p>';
                    echo '<form action="review_ticket.php" method="post">';
                    echo '<input type="hidden" name="flight_id" value="' . $row['flight_id'] . '">';
                    echo '<button type="submit">Book Ticket</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo '<p>No flights available.</p>';
            }

            // Close the database connection
            $conn->close();
            ?>
        </div>
</div>
</body>
</html>

