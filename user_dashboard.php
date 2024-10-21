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

// Get the logged-in user's ID
$username = $_SESSION['username'];
$userQuery = "SELECT id FROM users WHERE username='$username'";
$userResult = $conn->query($userQuery);
$userRow = $userResult->fetch_assoc();
$userId = $userRow['id'];

// Fetch all events and check if the user has booked them
$eventQuery = "
    SELECT e.event_id, e.event_name, e.event_date, e.event_venue,
           IF(b.user_id IS NULL, 'Available', 'Booked') AS booking_status
    FROM events e
    LEFT JOIN bookings b ON e.event_id = b.event_id AND b.user_id = $userId
";
$eventResult = $conn->query($eventQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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
    </style>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>

    <h3>Available Events</h3>
    <table>
        <tr>
            <th>Event Name</th>
            <th>Date</th>
            <th>Venue</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $eventResult->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['event_name']; ?></td>
            <td><?php echo $row['event_date']; ?></td>
            <td><?php echo $row['event_venue']; ?></td>
            <td><?php echo $row['booking_status']; ?></td>
            <td>
                <?php if ($row['booking_status'] == 'Available') { ?>
                    <form action="user_dashboard.php" method="POST">
                        <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
                        <input type="submit" name="book" value="Book Now">
                    </form>
                <?php } else { ?>
                    <button disabled>Already Booked</button>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>

    <h3>My Bookings</h3>
    <?php
    // Fetch user's bookings
    $bookingQuery = "
        SELECT e.event_name, e.event_date, e.event_venue, b.booking_date
        FROM bookings b
        JOIN events e ON b.event_id = e.event_id
        WHERE b.user_id = $userId
    ";
    $bookingResult = $conn->query($bookingQuery);
    ?>
    <table>
        <tr>
            <th>Event Name</th>
            <th>Date</th>
            <th>Venue</th>
            <th>Booking Date</th>
        </tr>
        <?php while ($bookingRow = $bookingResult->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $bookingRow['event_name']; ?></td>
            <td><?php echo $bookingRow['event_date']; ?></td>
            <td><?php echo $bookingRow['event_venue']; ?></td>
            <td><?php echo $bookingRow['booking_date']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <a href="logout.php">Logout</a>
</body>
</html>

<?php
// Handle event booking
if (isset($_POST['book'])) {
    $eventId = $_POST['event_id'];

    // Check if the user has already booked the event
    $checkQuery = "SELECT * FROM bookings WHERE user_id = $userId AND event_id = $eventId";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows == 0) {
        // Insert the booking
        $insertQuery = "INSERT INTO bookings (user_id, event_id) VALUES ($userId, $eventId)";
        if ($conn->query($insertQuery) === TRUE) {
            echo "<script>alert('Event booked successfully!'); window.location.href='user_dashboard.php';</script>";
        } else {
            echo "Error: " . $insertQuery . "<br>" . $conn->error;
        }
    } else {
        echo "You have already booked this event!";
    }
}

$conn->close();
?>
