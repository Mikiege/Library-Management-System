<?php
$mysqli = new mysqli("localhost", "root", "", "library_management");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Only cancel bookings with:
// - status = 'active'
// - check_in_time is NULL
// - start time + 20 minutes has already passed
// - and today is the booking_date
$sql = "
    SELECT b.booking_id, b.user_id, b.booking_date, b.start_time, u.email
    FROM bookings b
    JOIN users u ON b.user_id = u.user_id
    WHERE b.STATUS = 'active'
      AND b.check_in_time IS NULL
      AND CONCAT(b.booking_date, ' ', b.start_time) <= NOW() - INTERVAL 20 MINUTE
";

$result = $mysqli->query($sql);

while ($row = $result->fetch_assoc()) {
    $booking_id = $row['booking_id'];
    $user_email = $row['email'];

    // Cancel the booking
    $update = "UPDATE bookings SET STATUS = 'cancelled' WHERE booking_id = $booking_id";
    $mysqli->query($update);

    // Send email
    $subject = "Library Booking Cancelled - No Check-In";
    $message = "Dear user,\n\nYour booking (ID: $booking_id) has been automatically cancelled because you did not check in within 20 minutes of your scheduled start time.\n\nIf you still need the resource, please make a new booking.\n\nThanks,\nSALCC Smart Library";
    $headers = "From: no-reply@salcclibrary.com";

    mail($user_email, $subject, $message, $headers);
}

$mysqli->close();
?>
