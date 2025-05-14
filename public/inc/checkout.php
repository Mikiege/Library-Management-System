<?php
require 'config.php';
session_start();

if (!isset($_SESSION["user_id"]) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$booking_id = $_POST["booking_id"];


$stmt = $pdo->prepare("SELECT resource_id FROM bookings WHERE booking_id = ? AND user_id = ?");
$stmt->execute([$booking_id, $_SESSION["user_id"]]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("Booking not found.");
}

// this is where we mark booking as completed
$stmt = $pdo->prepare("UPDATE bookings SET status = 'completed' WHERE booking_id = ?");
if ($stmt->execute([$booking_id])) {
    // Make resource available again
    $pdo->prepare("UPDATE resources SET is_available = 1 WHERE resource_id = ?")->execute([$booking['resource_id']]);
    
    echo "Checked out successfully!";
} else {
    echo "Error checking out.";
}
?>
