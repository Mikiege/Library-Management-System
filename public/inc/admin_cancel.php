<?php
require 'config.php';
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== 'staff') {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["booking_id"])) {
    $booking_id = $_POST["booking_id"];

    
    $stmt = $pdo->prepare("SELECT resource_id FROM bookings WHERE booking_id = ?");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        die("Booking not found.");
    }

    // Cancel the booking
    $stmt = $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE booking_id = ?");
    if ($stmt->execute([$booking_id])) {
        // Making the resource available again
        $pdo->prepare("UPDATE resources SET is_available = 1 WHERE resource_id = ?")->execute([$booking['resource_id']]);

        // Redirecting after successful cancellation
        header("Location: ../admin_dashboard.php?message=Booking cancelled successfully");
        exit();
    } else {
        header("Location: ../admin_dashboard.php?error=Error cancelling booking");
        exit();
    }
}
?>
