<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'ems_ai2');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in user's ID and current data
$username = $_SESSION['username'];
$userQuery = "SELECT * FROM users WHERE username='$username'";
$userResult = $conn->query($userQuery);
$user = $userResult->fetch_assoc();

// Update user details
if (isset($_POST['update_profile'])) {
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];
    
    // If password is set, hash it
    if (!empty($_POST['password'])) {
        $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $updateQuery = "UPDATE users SET username='$newUsername', email='$newEmail', password='$newPassword' WHERE id=" . $user['id'];
    } else {
        $updateQuery = "UPDATE users SET username='$newUsername', email='$newEmail' WHERE id=" . $user['id'];
    }

    if ($conn->query($updateQuery) === TRUE) {
        $_SESSION['username'] = $newUsername; // Update session with new username
        echo "<script>alert('Profile updated successfully!'); window.location.href='user_profile.php';</script>";
    } else {
        echo "Error: " . $updateQuery . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f0f0f0;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
        }
        input[type="text"], input[type="email"], input[type="password"] {
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
    <h2>Customize Your Information</h2>
    <form action="user_profile.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
        
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
        
        <label for="password">Password (leave blank if you don't want to change it):</label>
        <input type="password" name="password" placeholder="New Password">

        <input type="submit" name="update_profile" value="Update Profile">
    </form>

    <a href="user_dashboard.php">Back to Dashboard</a>
</body>
</html>

<?php
$conn->close();
?>
