<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    require_once "dbconn.php";

    // Get username and password from form (trimming whitespace)
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Prepare SQL statement to fetch user details
    $sql = "SELECT * FROM Users WHERE username = ?";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);

    // Execute the query
    if ($stmt->execute()) {
        // Get result
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Verify password
            if ($password === $row["password"]) { // Assuming passwords are stored in plaintext
                // Password is correct, set session variables
                $_SESSION["username"] = $username;
                $_SESSION["user_id"] = $row["user_id"];
                // Redirect to passenger_dashboard.php
                header("Location: passenger_dashboard.php");
                exit();
            } else {
                // Incorrect password
                echo "Invalid username or password.";
            }
        } else {
            // User does not exist
            echo "Invalid username or password.";
        }
    } else {
        // Error executing query
        echo "Error: " . $conn->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
