<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        /* Video Background Styling with Blur */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Position and Blur Video */
        .background-video {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1; /* Behind other elements */
            object-fit: cover;
            filter: blur(10px); /* Blur effect */
            transform: scale(1.1); /* Slight scale to ensure full coverage after blur */
        }

        /* Glassmorphism Form Styling */
        .registration-form {
            background: rgba(255, 255, 255, 0.15); /* Transparent background */
            border-radius: 15px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px); /* Blur effect for glassmorphism */
            -webkit-backdrop-filter: blur(10px);
            padding: 40px;
            width: 350px;
            border: 1px solid rgba(255, 255, 255, 0.3); /* Soft border for glassmorphism */
        }

        /* Form Input Styling */
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        /* Fluent Design-Inspired Button */
        .button {
            background-color: #0078D7; /* Fluent Design Blue */
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .button:hover {
            background-color: #005a9e; /* Darker blue on hover */
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
        }

        /* Form Header Styling */
        .registration-form h2 {
            text-align: center;
            color: white;
            margin-bottom: 20px;
        }

        /* Music Player Hidden */
        audio {
            display: none;
        }
    </style>
</head>
<body>

    <!-- Background Video with Blur -->
    <video autoplay muted loop class="background-video">
        <source src="background-video.mp4" type="video/mp4"> <!-- Replace with your video path -->
        Your browser does not support the video tag.
    </video>

    <div class="registration-form">
        <h2>Create an Account</h2>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <input type="submit" name="submit" value="Register" class="button">
        </form>
    </div>

    <!-- Hidden Music Player -->
    <audio autoplay loop>
        <source src="background-music.mp3" type="audio/mpeg"> <!-- Replace with your music file -->
        Your browser does not support the audio element.
    </audio>

</body>
</html>

<?php
if (isset($_POST['submit'])) {
    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'ems_ai2');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security

    // Insert user data into the database
    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'user')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
