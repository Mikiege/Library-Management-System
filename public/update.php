<?php
require 'config.php';
session_start();

if (!isset($_SESSION["user_id"]) || !isset($_GET["booking_id"])) {
    die("Invalid request.");
}

$booking_id = $_GET["booking_id"];

// Get the current booking details
$stmt = $pdo->prepare("SELECT * FROM bookings WHERE booking_id = ? AND user_id = ?");
$stmt->execute([$booking_id, $_SESSION["user_id"]]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("Booking not found.");
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the new values from the form
    $new_start_time = $_POST["start_time"];
    $new_end_time = $_POST["end_time"];
    $new_resource_id = $_POST["resource_id"];

    // Update the booking
    $stmt = $pdo->prepare("UPDATE bookings SET start_time = ?, end_time = ?, resource_id = ? WHERE booking_id = ?");
    if ($stmt->execute([$new_start_time, $new_end_time, $new_resource_id, $booking_id])) {
        echo "<script>alert('Booking updated successfully!'); window.location.href='../my_bookings.php';</script>";
    } else {
        echo "<script>alert('Error updating booking.'); window.location.href='../my_bookings.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
</head>
<body>

<h2>Edit Booking</h2>
<form method="POST">
    <label for="start_time">Start Time:</label>
    <input type="time" name="start_time" id="start_time" value="<?= htmlspecialchars($booking['start_time']) ?>" required>
    <br>
    <label for="end_time">End Time:</label>
    <input type="time" name="end_time" id="end_time" value="<?= htmlspecialchars($booking['end_time']) ?>" required>
    <br>
    <label for="resource_id">Resource:</label>
    <select name="resource_id" id="resource_id" required>
        <?php
        // Fetch resources to populate the dropdown
        $resources = $pdo->query("SELECT * FROM resources WHERE is_available = 1")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($resources as $resource) {
            $selected = ($resource['resource_id'] == $booking['resource_id']) ? 'selected' : '';
            echo "<option value=\"{$resource['resource_id']}\" $selected>{$resource['resource_name']}</option>";
        }
        ?>
    </select>
    <br>
    <button type="submit">Update Booking</button>
</form>

</body>
</html>