<?php
// Include the database connection file
require_once 'dbconn.php';

// Check if success parameter is present in the URL
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo '<div class="alert alert-success" role="alert">Flight successfully added.</div>';
}

// Define variables and initialize with empty values
$flight_number = $departure = $arrival = $departure_time = $arrival_time = $available_seats = "";
$flight_number_err = $departure_err = $arrival_err = $departure_time_err = $arrival_time_err = $available_seats_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate flight number
    if (empty(trim($_POST["flight_number"]))) {
        $flight_number_err = "Please enter the flight number.";
    } else {
        $flight_number = trim($_POST["flight_number"]);
    }
    
    // Validate departure
    if (empty(trim($_POST["departure"]))) {
        $departure_err = "Please enter the departure location.";
    } else {
        $departure = trim($_POST["departure"]);
    }
    
    // Validate arrival
    if (empty(trim($_POST["arrival"]))) {
        $arrival_err = "Please enter the arrival location.";
    } else {
        $arrival = trim($_POST["arrival"]);
    }
    
    // Validate departure time
    if (empty(trim($_POST["departure_time"]))) {
        $departure_time_err = "Please enter the departure time.";
    } else {
        $departure_time = trim($_POST["departure_time"]);
    }
    
    // Validate arrival time
    if (empty(trim($_POST["arrival_time"]))) {
        $arrival_time_err = "Please enter the arrival time.";
    } else {
        $arrival_time = trim($_POST["arrival_time"]);
    }
    
    // Validate available seats
    if (empty(trim($_POST["available_seats"]))) {
        $available_seats_err = "Please enter the available seats.";
    } else {
        $available_seats = trim($_POST["available_seats"]);
    }
    
    // Check input errors before inserting into database
    if (empty($flight_number_err) && empty($departure_err) && empty($arrival_err) && empty($departure_time_err) && empty($arrival_time_err) && empty($available_seats_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO flights (flight_number, departure, arrival, departure_time, arrival_time, available_seats) VALUES (?, ?, ?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssssi", $param_flight_number, $param_departure, $param_arrival, $param_departure_time, $param_arrival_time, $param_available_seats);
            
            // Set parameters
            $param_flight_number = $flight_number;
            $param_departure = $departure;
            $param_arrival = $arrival;
            $param_departure_time = $departure_time;
            $param_arrival_time = $arrival_time;
            $param_available_seats = $available_seats;
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to admin_dashboard.php after successful insertion
                header("location: admin_dashboard.php?success=1");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }


            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Add Flight</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1 style="text-align: center;">PlanMyTrip</h1>
<div class="container">
        <h1>Admin Dashboard</h1>
        <p>Welcome, Admin!</p>
        <!-- Add your dashboard content here -->
        <a href="flight_management.php">Flight Management</a>
        <a href="passenger_management.php">Passenger Management</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <h3>Add Flight</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($flight_number_err)) ? 'has-error' : ''; ?>">
                <label>Flight Number</label>
                <input type="text" name="flight_number" class="form-control" value="<?php echo $flight_number; ?>">
                <span class="help-block"><?php echo $flight_number_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($departure_err)) ? 'has-error' : ''; ?>">
                <label>Departure</label>
                <input type="text" name="departure" class="form-control" value="<?php echo $departure; ?>">
                <span class="help-block"><?php echo $departure_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($arrival_err)) ? 'has-error' : ''; ?>">
                <label>Arrival</label>
                <input type="text" name="arrival" class="form-control" value="<?php echo $arrival; ?>">
                <span class="help-block"><?php echo $arrival_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($departure_time_err)) ? 'has-error' : ''; ?>">
                <label>Departure Time</label>
                <input type="time" name="departure_time" class="form-control" value="<?php echo $departure_time; ?>">
                <span class="help-block"><?php echo $departure_time_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($arrival_time_err)) ? 'has-error' : ''; ?>">
                <label>Arrival Time</label>
                <input type="time" name="arrival_time" class="form-control" value="<?php echo $arrival_time; ?>">
                <span class="help-block"><?php echo $arrival_time_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($available_seats_err)) ? 'has-error' : ''; ?>">
                <label>Available Seats</label>
                <input type="number" name="available_seats" class="form-control" value="<?php echo $available_seats; ?>">
                <span class="help-block"><?php echo $available_seats_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Add Flight">
            </div>
        </form>
    </div>
</body>
</html>
