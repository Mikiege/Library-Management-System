<?php
require 'config.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("You must be logged in to book a resource.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $resource_id = $_POST["resource_id"];
    $booking_date = $_POST["booking_date"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];

    // Ensure end_time is later than start_time
    if (strtotime($end_time) <= strtotime($start_time)) {
        echo "<script>alert('End time must be after start time!'); window.location.href='booking.php';</script>";
        exit();
    }

    // Check if the resource is available
    $stmt = $pdo->prepare("
        SELECT * FROM bookings 
        WHERE resource_id = ? 
        AND booking_date = ? 
        AND (
            (start_time <= ? AND end_time > ?) OR 
            (start_time < ? AND end_time >= ?) OR 
            (start_time >= ? AND start_time < ?)
        )
    ");
    $stmt->execute([$resource_id, $booking_date, $start_time, $start_time, $end_time, $end_time, $start_time, $end_time]);
    
    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Resource is already booked for this time!'); window.location.href='../booking.php';</script>";
        exit();
    }

   
    $stmt = $pdo->prepare("
        INSERT INTO bookings (user_id, resource_id, booking_date, start_time, end_time) 
        VALUES (?, ?, ?, ?, ?)
    ");
    if ($stmt->execute([$user_id, $resource_id, $booking_date, $start_time, $end_time])) {
        echo "<script>alert('Booking successful!'); window.location.href='../my_bookings.php';</script>";
    } else {
        echo "<script>alert('Error booking resource.'); window.location.href='../booking.php';</script>";
    }
}
?>
