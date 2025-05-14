<?php
session_start();
require_once 'config.php'; // This must create a PDO instance as $pdo
require_once 'logger.php'; // This must include the logAction function

$barcode_number = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['barcode_number'])) {
    $barcode_number = trim($_POST['barcode_number']);

    try {
        $stmt = $pdo->prepare("SELECT user_id, first_name, last_name FROM users WHERE barcode_number = :barcode_number");
        $stmt->bindParam(':barcode_number', $barcode_number);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $message = "User found: ID = " . htmlspecialchars($user['user_id']) . ", Name = " . htmlspecialchars($user['first_name']) . " " . htmlspecialchars($user['last_name']);

            //If there is a user with that barcode number and a booking that status is active    
            $bookingStmt = $pdo->prepare("SELECT * FROM bookings WHERE user_id = :user_id AND status = 'active'");
            $bookingStmt->bindParam(':user_id', $user['user_id']);
            $bookingStmt->execute();
            //Check if the user has an active booking
            if ($bookingStmt->rowCount() > 0) {
                $message .= " - User has an active booking.";

                //Checking whether the user is current checked in or no
                $checkInStmt = $pdo->prepare("SELECT * FROM bookings WHERE user_id = :user_id AND check_in_time IS NOT NULL AND check_out_time IS NULL");
                $checkInStmt->bindParam(':user_id', $user['user_id']);
                $checkInStmt->execute();
                if ($checkInStmt->rowCount() > 0) {
                    $message .= " - User is currently checked in.";
                    //iF user is logged in, then check the user out 
                    //Update stataus to completed
                    $checkoutStmt = $pdo->prepare("UPDATE bookings SET check_out_time = NOW() WHERE user_id = :user_id AND check_out_time IS NULL");
                    $checkoutStmt->bindParam(':user_id', $user['user_id']);

                    if ($checkoutStmt->execute()) {
                        //Make resource available again
                        $updateStmt = $pdo->prepare("UPDATE bookings SET status = 'completed' WHERE user_id = :user_id AND check_out_time IS NOT NULL");
                        $updateStmt->bindParam(':user_id', $user['user_id']);
                        $updateStmt->execute();
                        $resourceStmt = $pdo->prepare("UPDATE resources SET is_available = 1 WHERE resource_id IN (SELECT resource_id FROM bookings WHERE user_id = :user_id AND check_out_time IS NOT NULL)");
                        $resourceStmt->bindParam(':user_id', $user['user_id']);
                        $resourceStmt->execute();
                        //Log the action
                        logAction($user['user_id'], "Checked Out", "Booking ID: $booking_id");
                        ////Update status to completed


                        $message .= " - User checked out successfully.";
                    } else {
                        $message .= " - Error checking out user.";
                    }
                } else {
                    //if user in not checked in, then check user in 
                    $checkInStmt = $pdo->prepare("UPDATE bookings SET check_in_time = NOW() WHERE user_id = :user_id AND check_in_time IS NULL");
                    $checkInStmt->bindParam(':user_id', $user['user_id']);
                    if ($checkInStmt->execute()) {
                        //Log the action
                        logAction($user['user_id'], "Checked In", "Booking ID: $booking_id");
                        $message .= " - User checked in successfully.";
                    } else {
                        $message .= " - Error checking in user.";
                    }
                }
            } else {
                $message .= " - User does not have an active booking.";
            }
        } else {
            $message = "No user found with that barcode number.";
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Scan Barcode - Library Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>Library Management System</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="booking.php">Booking</a>
            <a href="contact.php">Contact Us</a>
            
  <?php if (isset($_SESSION["user_type"])): ?>
            <?php if ($_SESSION["user_type"] === 'staff'): ?>
                <a href="admin_dashboard.php">Admin Dashboard</a>
            <?php else: ?>
                <a href="my_bookings.php">My Bookings</a>
            <?php endif; ?>
            <a href="inc/logout.php">Logout</a>
        <?php else: ?>
            <a href="index.php">Login</a>
        <?php endif; ?>

        </nav>
    </header>

    <main>
        <h2>Scan or Enter Barcode</h2>
        <form method="POST" action="scan.php">
            <label for="barcode_number">Barcode Number:</label>
            <input type="text" id="barcode_number" name="barcode_number" required>
            <button type="submit">Check-In/Out</button>
        </form>

        <?php if (!empty($message)): ?>
            <p><strong><?php echo $message; ?></strong></p>
        <?php endif; ?>
    </main>
</body>

</html>