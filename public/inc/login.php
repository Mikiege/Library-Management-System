<?php
require 'config.php';
session_start();
require_once 'mail_functions.php'; 






if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username_or_email = trim($_POST["username_or_email"]);
    $password = trim($_POST["password"]);

    
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE username = ? OR email = ?");
    $stmt->execute([$username_or_email, $username_or_email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["user_type"] = $user["user_type"];
        $_SESSION["first_name"] = $user["first_name"];
        $_SESSION["last_name"] = $user["last_name"];
        $_SESSION["email"] = $user["email"];

        
        if ($user["user_type"] === "staff") {
            header("Location: ../admin_dashboard.php"); 
        } else {
            header("Location: ../booking.php"); 

        }
        $subject = "Login Notification";
        $body = "
            Hello <strong>{$user['first_name']} {$user['last_name']}</strong>,<br><br>
            Your account was just logged into on the <strong>Library Management System</strong>.<br>
            <br>If this wasn't you, please reset your password or contact the administrator immediately.<br>
            <br><small>Time: " . date('Y-m-d H:i:s') . "</small>
        ";
        sendEmail($user['email'], $user['first_name'] . ' ' . $user['last_name'], $subject, $body);

        exit();
    } else {
        // Redirect to index.php with error message
        header("Location: ../index.php?error=Invalid username or password");

        exit();
    }
}
