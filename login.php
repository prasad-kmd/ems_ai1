<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f0f0f0;
            padding: 50px;
        }

        /* Position and Blur Video */
        .background-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            /* min-width: 100%; */
            /* min-height: 100%; */
            z-index: -1;
            /* Behind other elements */
            object-fit: cover;
            filter: blur(2.5px);
            /* Blur effect */
            transform: scale(1.1);
            /* Slight scale to ensure full coverage after blur */
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.2);
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #5bc0de;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #31b0d5;
        }
    </style>
</head>

<body>
    <!-- Background Video with Blur -->
    <video autoplay muted loop class="background-video">
        <source src="assets/videos/11381282-hd_1920_1080_24fps.mp4" type="video/mp4"> <!-- Replace with your video path -->
        Your browser does not support the video tag.
    </video>
    <h2>User Login</h2>
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" name="submit" value="Login">
    </form>
    <!-- Hidden Music Player -->
    <audio autoplay loop>
        <source src="assets/audio/nanosuit_showroom_fm.ogg" type="audio/ogg" /> <!-- Replace with your music file -->
        Your browser does not support the audio element.
    </audio>
</body>

</html>

<?php
session_start(); // Start session

if (isset($_POST['submit'])) {
    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'ems_ai2');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to retrieve the user data
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $row['password'])) {
            // Create session variables
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role'];

            // Redirect based on the role
            if ($row['role'] == 'admin') {
                header('Location: admin_dashboard.php'); // Redirect to admin dashboard
            } else {
                header('Location: user_dashboard.php');  // Redirect to user dashboard
            }
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found!";
    }

    $conn->close();
}
?>