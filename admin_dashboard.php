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

// If the edit form is submitted, update the event
if (isset($_POST['save_event'])) {
    $event_id = $_POST['event_id'];
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_venue = $_POST['event_venue'];

    $updateEventQuery = "UPDATE events SET event_name='$event_name', event_date='$event_date', event_venue='$event_venue' WHERE event_id=$event_id";

    if ($conn->query($updateEventQuery) === TRUE) {
        echo "<script>alert('Event updated successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch event details for editing
$editEvent = null;
if (isset($_GET['edit_event'])) {
    $event_id = $_GET['edit_event'];
    $editEventQuery = "SELECT * FROM events WHERE event_id=$event_id";
    $editEventResult = $conn->query($editEventQuery);
    $editEvent = $editEventResult->fetch_assoc();
}
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
    </style>
</head>
<body>
    <!-- Background Video with Blur -->
    <video autoplay muted loop class="background-video">
        <source src="assets/videos/18069166-uhd_3840_2160_24fps.mp4" type="video/mp4"> <!-- Replace with your video path -->
        Your browser does not support the video tag.
    </video>
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
            <th>Action</th>
        </tr>
        <?php while ($eventRow = $eventResult->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $eventRow['event_id']; ?></td>
            <td><?php echo $eventRow['event_name']; ?></td>
            <td><?php echo $eventRow['event_date']; ?></td>
            <td><?php echo $eventRow['event_venue']; ?></td>
            <td>
                <a href="admin_dashboard.php?edit_event=<?php echo $eventRow['event_id']; ?>">Edit</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <?php if ($editEvent) { ?>
    <h3>Edit Event</h3>
    <form action="admin_dashboard.php" method="POST">
        <input type="hidden" name="event_id" value="<?php echo $editEvent['event_id']; ?>">
        <input type="text" name="event_name" value="<?php echo $editEvent['event_name']; ?>" required>
        <input type="date" name="event_date" value="<?php echo $editEvent['event_date']; ?>" required>
        <input type="text" name="event_venue" value="<?php echo $editEvent['event_venue']; ?>" required>
        <input type="submit" name="save_event" value="Save">
    </form>
    <?php } ?>

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
    $insertEventQuery = "INSERT INTO events (event_name, event_date, event_venue) VALUES ('$event_name', '$event_date', '$event_venue')";
    
    if ($conn->query($insertEventQuery) === TRUE) {
        echo "<script>alert('Event added successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "Error: " . $insertEventQuery . "<br>" . $conn->error;
    }
}

$conn->close();
?>
