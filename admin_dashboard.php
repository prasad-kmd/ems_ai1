<?php
session_start();

// Redirect to login if not logged in or if not an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'ems_ai2');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users
$userQuery = "SELECT * FROM users";
$userResult = $conn->query($userQuery);

// Fetch all events
$eventQuery = "SELECT * FROM events";
$eventResult = $conn->query($eventQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f0f0f0;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #5bc0de;
            color: white;
        }
        a {
            text-decoration: none;
            padding: 10px;
            background-color: #5bc0de;
            color: white;
            border-radius: 5px;
        }
        a:hover {
            background-color: #31b0d5;
        }
    </style>
</head>
<body>
    <h2>Admin Dashboard</h2>
    
    <h3>Manage Users</h3>
    <table>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
        </tr>
        <?php while ($row = $userResult->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['role']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <h3>Add New Event</h3>
    <form action="admin_dashboard.php" method="POST">
        <input type="text" name="event_name" placeholder="Event Name" required>
        <input type="date" name="event_date" required>
        <input type="text" name="event_venue" placeholder="Event Venue" required>
        <input type="submit" name="add_event" value="Add Event">
    </form>

    <h3>Manage Events</h3>
    <table>
        <tr>
            <th>Event ID</th>
            <th>Event Name</th>
            <th>Date</th>
            <th>Venue</th>
        </tr>
        <?php while ($eventRow = $eventResult->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $eventRow['event_id']; ?></td>
            <td><?php echo $eventRow['event_name']; ?></td>
            <td><?php echo $eventRow['event_date']; ?></td>
            <td><?php echo $eventRow['event_venue']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <a href="logout.php">Logout</a>
</body>
</html>

<?php
// Handle adding new events
if (isset($_POST['add_event'])) {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_venue = $_POST['event_venue'];

    // Insert new event into the database
    $insertQuery = "INSERT INTO events (event_name, event_date, event_venue) VALUES ('$event_name', '$event_date', '$event_venue')";
    
    if ($conn->query($insertQuery) === TRUE) {
        echo "<script>alert('Event added successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
    }
}
$conn->close();
?>
