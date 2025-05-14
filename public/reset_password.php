RESET_PASSWORD.PHP

<?php
session_start();
require 'inc/config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["token"])) {
    $token = $_POST["token"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if ($new_password !== $confirm_password) {
        header("Location: reset_password.php?token=$token&error=Passwords do not match");
        exit();
    }

    
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at >= NOW()");
    $stmt->execute([$token]);
    $reset = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reset) {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE Users SET password = ? WHERE user_id = ?");
        $update->execute([$hashed, $reset['user_id']]);

        
        $pdo->prepare("DELETE FROM password_resets WHERE token = ?")->execute([$token]);

        header("Location: index.php?success=Password reset successful.");
        exit();
    } else {
        header("Location: reset_password.php?error=Invalid or expired token");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .error-message {
            color: red !important;
            /* Ensures text is red */
            font-weight: bold !important;
            /* Ensures text is bold */
            background-color: #ffecec !important;
            /* Light red background */
            border: 2px solid red !important;
            /* Ensure visibility */
            padding: 10px !important;
            border-radius: 5px !important;
            text-align: center !important;
            display: block !important;
            width: 100% !important;
            font-size: 16px !important;
            margin-bottom: 10px !important;
        }

        .success-message {
            color: green !important;
            /* Ensures text is green */
            font-weight: bold !important;
            /* Ensures text is bold */
            background-color: rgb(242, 255, 236) !important;
            /* Light red background */
            border: 2px solid green !important;
            /* Ensure visibility */
            padding: 10px !important;
            border-radius: 5px !important;
            text-align: center !important;
            display: block !important;
            width: 100% !important;
            font-size: 16px !important;
            margin-bottom: 10px !important;
        }
    </style>
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
        <h2>Reset Your Password</h2>

        <?php if (isset($_GET["error"])): ?>
            <p class="error-message"><?= htmlspecialchars($_GET["error"]); ?></p> <?php endif; ?>

        <form action="reset_password.php" method="POST">
            <label for="identifier">Student ID or Username:</label>
            <input type="text" id="identifier" name="identifier" required>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

            <button type="submit">Reset Password</button>
        </form>

        <p><a href="index.php">Back to Login</a></p>
    </main>

    <footer>
        <p>&copy; 2025 Hunter J. Francois Library</p>
    </footer>
</body>

</html>