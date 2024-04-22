<?php
// Include the database connection file
include 'dbconn.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the SQL statement to check if the user already exists
    $check_sql = "SELECT * FROM Users WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // User already exists
        session_start();
        $_SESSION['signup_message'] = "User already exists. Login now.";
        header("Location: passenger_login.php");
        exit();
    } else {
        // User doesn't exist, proceed with registration
        $sql = "INSERT INTO Users (name, phone, gender, email, username, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $phone, $gender, $email, $username, $password);

        // Check if the query executed successfully
        if ($stmt->execute()) {
            // User registration successful
            session_start();
            $_SESSION['signup_message'] = "User account created successfully. Login now.";
            header("Location: passenger_login.php");
            exit();
        } else {
            // User registration failed
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close statement
        $stmt->close();
    }

    // Close check statement
    $check_stmt->close();
}

// Close connection
$conn->close();
?>