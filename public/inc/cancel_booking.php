<?php
require 'config.php';
session_start();
include_once 'mail_functions.php'; 

if (!isset($_SESSION["user_id"]) || !isset($_GET["booking_id"])) {
    die("Invalid request.");
}

$booking_id = $_GET["booking_id"];

// Get resource ID from booking
$stmt = $pdo->prepare("SELECT resource_id FROM bookings WHERE booking_id = ? AND user_id = ?");
$stmt->execute([$booking_id, $_SESSION["user_id"]]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("Booking not found.");
}

// Cancelling the booking hereeeee
$stmt = $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE booking_id = ?");


if ($stmt->execute([$booking_id])) {
    // Making resource available again
    $pdo->prepare("UPDATE resources SET is_available = 1 WHERE resource_id = ?")->execute([$booking['resource_id']]);

    $subject = "Booking Cancelled Notification";
        $body = "
            Hello <strong>{$_SESSION['first_name']} {$_SESSION['last_name']}</strong>,<br><br>
            Your booking for the Library Management System has been cancelled by admin.<br>
            <br>If you have any questions, please contact the administrator.<br>
            <br><small>Time: " . date('Y-m-d H:i:s') . "</small>
        ";
        sendEmail($_SESSION['email'], $_SESSION['first_name'] . ' ' . $_SESSION['last_name'], $subject, $body);

    echo "<script>alert('Booking cancelled successfully!'); window.location.href='../my_bookings.php';</script>";
} else {
    echo "<script>alert('Error cancelling booking.'); window.location.href='../my_bookings.php';</script>";
}
?>
