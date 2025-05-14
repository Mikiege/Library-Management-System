FORGOT_PASSWORD.PHP

<?php
session_start();
require 'inc/config.php';
require 'inc/mail_functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = trim($_POST["identifier"]);

    $stmt = $pdo->prepare("SELECT * FROM Users WHERE username = ? OR user_id = 
?");
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$user['user_id'], $token, $expires]);

        $resetLink =
            "http://localhost/library_management/public/reset_password.php?token=$token";
        $body = "Hi {$user['first_name']},<br><br> 
        You requested a password reset. <a href='$resetLink'>Click here to reset your password</a><br><br> 
        If you did not request this, please ignore this email.";

        sendEmail(
            $user['email'],
            $user['first_name'],
            'Password Reset Request - Library System',
            $body
        );

        header("Location: forgot_password.php?success=Reset link sent to your email.");
        exit();
    } else {
        header("Location: forgot_password.php?error=User not found.");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>Library Management System</h1>
    </header>

    <main>
        <h2>Forgot Your Password?</h2>

        <?php if (isset($_GET["success"])): ?>
            <p class="success-message"><?= htmlspecialchars($_GET["success"]) ?></p>
        <?php elseif (isset($_GET["error"])): ?>
            <p class="error-message"><?= htmlspecialchars($_GET["error"]) ?></p> <?php endif; ?>

        <form method="POST" action="forgot_password.php">
            <label for="identifier">Username or Student ID:</label>
            <input type="text" id="identifier" name="identifier" required>

            <button type="submit">Send Reset Link</button>
        </form>

        <p><a href="index.php">Back to Login</a></p>
    </main>

    <footer>
        <p>&copy; 2025 Hunter J. Francois Library</p>
    </footer>
</body>

</html>