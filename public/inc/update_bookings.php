<?php
require 'config.php';
session_start();




if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== 'staff') {
    die("Access Denied! Only staff can edit bookings.");
}


if (!isset($_GET['booking_id'])) {
    die("No booking ID provided.");
}

$booking_id = $_GET['booking_id'];


$stmt = $pdo->prepare("SELECT * FROM bookings WHERE booking_id = ?");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("Booking not found.");
}


$resourceStmt = $pdo->query("SELECT resource_id, resource_name FROM resources");
$resources = $resourceStmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_resource_id = $_POST['resource_id'];
    $new_start_time = $_POST['start_time'];
    $new_end_time = $_POST['end_time'];
    $new_status = $_POST['status'];

    
    $updateStmt = $pdo->prepare("
        UPDATE bookings 
        SET resource_id = ?, start_time = ?, end_time = ?, status = ? 
        WHERE booking_id = ?
    ");
    $updateStmt->execute([$new_resource_id, $new_start_time, $new_end_time, $new_status, $booking_id]);

    echo "<script>alert('Booking updated successfully!'); window.location.href='../admin_dashboard.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header>
        <h1>Edit Booking</h1>
        <nav>
            <a href="../admin_dashboard.php">Back to Dashboard</a>
        </nav>
    </header>

    <main>
        <form method="POST">
            <label for="resource_id">Resource:</label>
            <select name="resource_id" required>
                <?php foreach ($resources as $resource): ?>
                    <option value="<?= $resource['resource_id'] ?>" <?= $resource['resource_id'] == $booking['resource_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($resource['resource_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="start_time">Start Time:</label>
            <input type="time" name="start_time" value="<?= htmlspecialchars($booking['start_time']) ?>" required>

            <label for="end_time">End Time:</label>
            <input type="time" name="end_time" value="<?= htmlspecialchars($booking['end_time']) ?>" required>

            <label for="status">Status:</label>
            <select name="status" required>
                <option value="active" <?= isset($booking['status']) && $booking['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                <option value="completed" <?= $booking['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="cancelled" <?= $booking['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>

            <button type="submit">Update Booking</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Hunter J. Francois Library</p>
    </footer>
</body>
</html>
