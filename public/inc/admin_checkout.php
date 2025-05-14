<?php
require 'config.php';
require 'logger.php'; 
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== 'staff') {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST["booking_id"];

    // Getting resource ID from booking
    $stmt = $pdo->prepare("SELECT resource_id FROM bookings WHERE booking_id = ?");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        die("Booking not found.");
    }

    // Mark booking as completed
    $stmt = $pdo->prepare("UPDATE bookings SET status = 'completed' WHERE booking_id = ?");
    if ($stmt->execute([$booking_id])) {

        // Making resource available again
        $pdo->prepare("UPDATE resources SET is_available = 1 WHERE resource_id = ?")->execute([$booking['resource_id']]);

        
        logAction($_SESSION["user_id"], "Admin Checkout", "Checked out booking ID: $booking_id");

        echo "<script>alert('User checked out successfully!'); window.location.href='../admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error checking out.'); window.location.href='../admin_dashboard.php';</script>";
}
}
?>