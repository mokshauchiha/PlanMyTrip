<?php
// Include the database connection file
require_once 'dbconn.php';

// Initialize variables
$flight_number = $search_error = "";
$passengers = array();

// Fetch available flight numbers
$sql_flights = "SELECT DISTINCT flight_number FROM flights";
$result_flights = $conn->query($sql_flights);
$available_flights = [];
if ($result_flights->num_rows > 0) {
    while ($row_flight = $result_flights->fetch_assoc()) {
        $available_flights[] = $row_flight['flight_number'];
    }
}

// Process search query
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["flight_number"])) {
        // Sanitize input
        $flight_number = mysqli_real_escape_string($conn, $_POST["flight_number"]);

        // Search for flight details and passengers
        $sql = "SELECT bd.booking_id, u.name, u.phone, u.email
                FROM bookingdetails bd
                INNER JOIN users u ON bd.user_id = u.user_id
                INNER JOIN flights f ON bd.flight_id = f.flight_id
                WHERE f.flight_number = '$flight_number'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $passengers = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            // No passengers found for the given flight number
            $search_error = "No passengers found for the given flight number.";
        }
    } else {
        // Flight number not provided
        $search_error = "Please enter a flight number.";
    }
}

// Process ticket cancellation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ticket_to_cancel"])) {
    $ticket_id = $_POST["ticket_to_cancel"];

    // Perform ticket cancellation operation
    $sql_cancel = "DELETE FROM bookingdetails WHERE booking_id = $ticket_id";
    $result_cancel = $conn->query($sql_cancel);
    if ($result_cancel) {
         // Output success message
         echo "<span class='success'>Ticket canceled successfully.</span>";
    } else {
         // Output error message
         echo "<span class='error'>Error canceling ticket.</span>";
    }
    exit(); // Prevent further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1 style="text-align: center;">PlanMyTrip</h1>
<div class="container">
    <h1>Passenger Management</h1>
    <a href="admin_dashboard.php">Bact to Admin Dashboard</a>
</div>
    <div class="container">
        <form id="searchForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="flight_number">Search Passengers by Flight Number:</label>
            <input type="text" id="flight_number" name="flight_number" value="<?php echo $flight_number; ?>">
            <button type="submit">Search</button>
            <span class="error"><?php echo $search_error; ?></span>
        </form>

        <!-- Display available flight numbers -->
        <h2>Available Flight Numbers</h2>
        <?php if (!empty($available_flights)): ?>
            <ul>
                <?php foreach ($available_flights as $flight): ?>
                    <li><?php echo $flight; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No flights available.</p>
        <?php endif; ?>

        <?php if (!empty($passengers)): ?>
            <h2>Passengers:</h2>
            <table>
                <tr>
                    <th>Booking ID</th>
                    <th>Name</th>
                    <th>Contact Information</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($passengers as $passenger): ?>
                    <tr id="passenger_<?php echo $passenger['booking_id']; ?>">
                        <td><?php echo $passenger['booking_id']; ?></td>
                        <td><?php echo $passenger['name']; ?></td>
                        <td><?php echo $passenger['phone']; ?><br><?php echo $passenger['email']; ?></td>
                        <td>
                            <button type="button" class="cancelBtn" data-ticket-id="<?php echo $passenger['booking_id']; ?>">Cancel Ticket</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listener to cancel buttons
            var cancelButtons = document.querySelectorAll('.cancelBtn');
            cancelButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var ticketId = this.getAttribute('data-ticket-id');
                    var confirmation = confirm("Are you sure you want to cancel this ticket?");
                    if (confirmation) {
                        // Send asynchronous request to cancel ticket
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState == XMLHttpRequest.DONE) {
                                if (xhr.status == 200) {
                                    // Remove the row from the table
                                    var row = document.getElementById('passenger_' + ticketId);
                                    if (row) {
                                        row.parentNode.removeChild(row);
                                    }
                                    // Display success message
                                    var successMessage = document.createElement('span');
                                    successMessage.textContent = 'Ticket canceled successfully.';
                                    successMessage.classList.add('success');
                                    document.querySelector('.container').appendChild(successMessage);
                                } else {
                                    // Display error message
                                    var errorMessage = document.createElement('span');
                                    errorMessage.textContent = 'Error canceling ticket.';
                                    errorMessage.classList.add('error');
                                    document.querySelector('.container').appendChild(errorMessage);
                                }
                            }
                        };
                        xhr.send('ticket_to_cancel=' + ticketId);
                    }
                });
            });
        });
    </script>
</body
