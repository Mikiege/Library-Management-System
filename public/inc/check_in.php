<?php
require_once 'config.php';
require_once 'logger.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION["user_id"];
$booking_id = $_GET["booking_id"] ?? null;


if ($booking_id) {
    // Ensure the booking belongs to the logged-in user
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE booking_id = ? AND user_id = ?");
    $stmt->execute([$booking_id, $user_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($booking) {
        // Updating the booking to set check-in time
        $update = $pdo->prepare("UPDATE bookings SET check_in_time = NOW() WHERE booking_id = ?");
        $update->execute([$booking_id]);

        // Logging in the check-in action
        logAction($user_id, "Checked In", "Booking ID: $booking_id");
        
        //verify that the barcode scanned is equal to the barcode info in the users table for that student
        //enable barcoding fo check in
        //same eventlistener i used for registrartion process is the same eventlistener i will use for check in process
         

        header("Location: ../my_bookings.php?message=checked_in");
        exit();
    } else {
        die("Booking not found or access denied.");
    }
} else {
    die("Invalid booking ID.");
}