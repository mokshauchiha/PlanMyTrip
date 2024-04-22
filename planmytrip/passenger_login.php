<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #ffffff;
            text-align: center;
        }
        
        h1 {
            color: #fff;
            margin-bottom: 20px;
        }
        
        form {
            display: inline-block;
            text-align: left;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            color: #fff;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #333;
            background-color: #333;
            color: #fff;
            margin-bottom: 20px;
        }
        
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #0066ff;
            color: #fff;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #0052cc;
        }
        /* Session Message Styles */
.session-message {
    margin-top: 20px;
    padding: 10px;
    border-radius: 5px;
    background-color: #f0f0f0;
    color: #d20c0c;
    text-align: center;
}

    </style>
</head>
<body>
<h1 style="text-align: center;">PlanMyTrip</h1>
<div class="session-message">

    <?php
    session_start();
    if (isset($_SESSION['signup_message'])) {
        echo "<p>{$_SESSION['signup_message']}</p>";
        unset($_SESSION['signup_message']); // Clear the message after displaying
    }
    ?>
</div>
    <div class="container">
        <h1>Passenger Login</h1>
        <form action="login_process.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <p>Don't have an account? <a href="passenger_signup.html">Create one!</a></p>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
