<?php

require 'inc/config.php';
session_start();

$loggedIn = isset($_SESSION["user_id"]);

if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] === 'staff') {
    die("Access Denied! Only students and visitors can view this page.");
}

$user_id = $_SESSION["user_id"];


$stmt = $pdo->prepare("SELECT b.booking_id, r.resource_name, r.resource_type, b.booking_time, b.check_in_time
                       FROM bookings b
                       JOIN resources r ON b.resource_id = r.resource_id
                       WHERE b.user_id = ? AND b.status = 'active'");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
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
        <h2>My Bookings</h2>

        <?php if (count($bookings) > 0): ?>
            <ul>
                <?php foreach ($bookings as $booking): ?>
                    <li>
    <?= $booking['resource_name'] ?> (<?= ucfirst($booking['resource_type']) ?>) - <?= $booking['booking_time'] ?>

    <?php if (empty($booking['check_in_time'])): ?>
        <a href="inc/check_in.php?booking_id=<?= $booking['booking_id'] ?>" class="checkin-button">Check In</a>
    <?php else: ?>
        <span class="checked-in">✅ Checked in at <?= date("h:i A", strtotime($booking['check_in_time'])) ?></span>
    <?php endif; ?>

    | <a href="inc/cancel_booking.php?booking_id=<?= $booking['booking_id'] ?>">Cancel</a>
</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No active bookings.</p>
        <?php endif; ?>
    </main>


    <footer>
        <p>&copy; 2023 Hunter J. Francois Library</p>
    </footer>
</body>

</html>