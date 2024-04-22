<?php
session_start();

// Include the database connection file
require_once 'dbconn.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the username and password are provided
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Retrieve the username and password from the form
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Prepare and execute a SQL statement to fetch admin credentials
        $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a row is returned
        if ($result->num_rows == 1) {
            // Authentication successful, set session variable
            $_SESSION['admin_logged_in'] = true;
            // Redirect to admin dashboard or any other page
            header("Location: admin_dashboard.php");
            exit;
        } else {
            // Authentication failed, redirect back to login page with error message
            $_SESSION['login_error'] = "Invalid username or password.";
            header("Location: admin_login.php");
            exit;
        }
    }
}
?>
