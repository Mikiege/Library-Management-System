<?php
require 'inc/config.php';

session_start();

// Check if the user is logged in
$loggedIn = isset($_SESSION["user_id"]);

if (!isset($_SESSION["user_id"])) {
    die("You must be logged in to book a resource.");
}

// Free up expired bookings dynamically
$pdo->query("
    UPDATE bookings 
    SET status = 'completed' 
    WHERE CONCAT(booking_date, ' ', end_time) <= NOW() 
    AND status = 'active'
");

$pdo->query("
    UPDATE resources 
    SET is_available = 1 
    WHERE resource_id IN (
        SELECT resource_id FROM bookings 
        WHERE status = 'completed'
    )
");

// Fetch available seats and computers
$stmt = $pdo->query("SELECT * FROM resources WHERE is_available = 1");
$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);






?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - Library Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
<header>
    <h1>Library Management System</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="booking.php">Booking</a>
        <a href="contact.php">Contact Us</a>

        <?php if (isset($_SESSION["user_type"])): ?>
            <?php if ($_SESSION["user_type"] === 'staff'): ?>
                <a href="admin_dashboard.php">Admin Dashboard</a>
            <?php else: ?>
                <a href="my_bookings.php">My Bookings</a>
            <?php endif; ?>
            <a href="inc/logout.php">Logout</a>
        <?php else: ?>
            <a href="index.php">Login</a>
        <?php endif; ?>
    </nav>
    <?php if (isset($_SESSION["username"])): ?>
    <div class="user-greeting">
        Logged in as: <strong><?= htmlspecialchars($_SESSION["username"]) ?></strong>
    </div>
<?php endif; ?>
</header>

    <main>
        <h2>Book a Study Seat or Computer</h2>

        <form action="inc/book_resource.php" method="POST">
        <label for="resource_id">Select Resource:</label>
        <select name="resource_id" id="resource_id" required>
            <?php foreach ($resources as $resource): ?>
                <option value="<?= $resource['resource_id'] ?>">
                    <?= $resource['resource_name'] ?> (<?= ucfirst($resource['resource_type']) ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label for="booking_date">Select Date:</label>
        <input type="date" id="booking_date" name="booking_date" required>

        <label for="start_time">Start Time:</label>
        <input type="time" id="start_time" name="start_time" required>

        <label for="end_time">End Time:</label>
        <input type="time" id="end_time" name="end_time" required>

        <button type="submit">Book Now</button>
    </form>
    </main>
    <footer>
        <p>&copy; 2023 Hunter J. Francois Library</p>
    </footer>
</body>

</html>