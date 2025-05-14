<?php
require 'config.php';

// current date and time
$current_date = date('Y-m-d');
$current_time = date('H:i:s');

// bookings that need to be auto-checked out
$stmt = $pdo->prepare("SELECT booking_id, resource_id FROM bookings 
    WHERE status = 'active' 
    AND (booking_date < ? OR (booking_date = ? AND end_time <= ?))");
$stmt->execute([$current_date, $current_date, $current_time]);
$bookings_to_checkout = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($bookings_to_checkout) {
    foreach ($bookings_to_checkout as $booking) {
        // Update the booking status to completed
        $updateBooking = $pdo->prepare("UPDATE bookings SET status = 'completed' WHERE booking_id = ?");
        $updateBooking->execute([$booking['booking_id']]);

        // Making the resource available again
        $updateResource = $pdo->prepare("UPDATE resources SET is_available = 1 WHERE resource_id = ?");
        $updateResource->execute([$booking['resource_id']]);
    }

    echo count($bookings_to_checkout) . " bookings automatically checked out.";

} else {
    echo "No bookings to auto-checkout right now.";
}
?>

