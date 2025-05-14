<?php
require 'config.php';

// Find expired bookings (past end time)
$stmt = $pdo->query("
    SELECT booking_id, resource_id 
    FROM bookings 
    WHERE CONCAT(booking_date, ' ', end_time) <= NOW() 
    AND status = 'active'
");
$expiredBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($expiredBookings as $booking) {
    // Mark booking as completed
    $pdo->prepare("UPDATE bookings SET status = 'completed' WHERE booking_id = ?")->execute([$booking['booking_id']]);

    // Make resource available again
    $pdo->prepare("UPDATE resources SET is_available = 1 WHERE resource_id = ?")->execute([$booking['resource_id']]);
}

echo count($expiredBookings) . " expired bookings have been cleaned up!";
?>
