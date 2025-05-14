<?php



$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";
?>

<nav style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background-color: #333;">
    <div>
        <a href="index.php" style="color: white; text-decoration: none; font-size: 18px;">Home</a>
        <a href="booking.php" style="color: white; text-decoration: none; font-size: 18px;">Booking</a>
        <a href="contact.php" style="color: white; text-decoration: none; font-size: 18px;">Contact Us</a>

        <?php if (isset($_SESSION["user_type"])): ?>
            <?php if ($_SESSION["user_type"] === 'staff'): ?>
                <a href="admin_dashboard.php" style="color: white; text-decoration: none; font-size: 18px;">Admin Dashboard</a>
            <?php else: ?>
                <a href="my_bookings.php" style="color: white; text-decoration: none; font-size: 18px;">My Bookings</a>
            <?php endif; ?>
            <a href="inc/logout.php" style="color: white; text-decoration: none; font-size: 18px;">Logout</a>
        <?php else: ?>
            <a href="index.php" style="color: white; text-decoration: none; font-size: 18px;">Login</a>
        <?php endif; ?>
    </div>

   
    <div>
        <a href="index.php" style="color: white; text-decoration: none; font-size: 18px;">
            <?php echo htmlspecialchars($username); ?> 😊
        </a>
    </div>
</nav>