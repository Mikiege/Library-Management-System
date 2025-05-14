<?php

session_start();
require 'inc/config.php'; 
include 'inc/mail_functions.php'; // we're including the mail functions file to use the sendEmail function


// here, we checking if the user is logged in
$loggedIn = isset($_SESSION["user_id"]);

if (!isset($_SESSION["user_id"])) {
    die("You must be logged in to send a message.");
    
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $message = htmlspecialchars($_POST["message"]);

    if (!empty($message)) {
        
        $success = "Thank you, your message has been received!";
        $subject = "Contact Us - Library Management System";
        $body = "
            Hello <strong>{$_SESSION['first_name']} {$_SESSION['last_name']}</strong>,<br><br>
            We have received your message regarding the Library Management System.<br>
            <br>Your message: <strong>$message</strong><br>
            <br>We will get back to you as soon as possible.<br>
            <br>If you have any questions, please contact the administrator.<br>
            <br><small>Time: " . date('Y-m-d H:i:s') . "</small>
        ";
        sendEmail($_SESSION['email'], $_SESSION['first_name'] . ' ' . $_SESSION['last_name'], $subject, $body);
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Library Management System</title>
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
    <?php if (isset($_SESSION["username"])): ?>
    <div class="user-greeting">
        Logged in as: <strong><?= htmlspecialchars($_SESSION["username"]) ?></strong>
    </div>
<?php endif; ?>
</header>


    <main>
        <h2>Get in Touch</h2>
        
        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <form action="contact.php" method="POST" class="contact-form">
        <p>If you have any questions, feel free to reach out!</p>
            

            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>
           
    
            


            <button type="submit">Send Message</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Hunter J. Francois Library</p>
    </footer>
</body>
</html>