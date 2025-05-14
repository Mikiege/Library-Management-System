<?php
require 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = trim($_POST["user_id"]);  
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirmPassword"]);
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $user_type = $_POST["user_type"];
    $barcode_number = trim($_POST["barcode_number"]);



    // Ensuring only emails ending with @salcc.edu.lc are allowed
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@salcc\.edu\.lc$/", $email)) {
        header("Location: ../index.php?error=Only SALCC emails (@salcc.edu.lc) are allowed.");
        exit();
    }
    

    if (empty($user_id) || empty($username) || empty($password) || empty($confirmPassword) || empty($first_name) || empty($last_name) || empty($email)) {
        header("Location: ../index.php?error=All fields are required");
        exit();
    }

    // Ensure passwords match
    if ($password !== $confirmPassword) {
        header("Location: ../index.php?error=Passwords do not match");
        exit();
    }

    // **Check if the user ID, username, or email already exists**
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE user_id = ? OR username = ? OR email = ?");
    $stmt->execute([$user_id, $username, $email]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        if ($existingUser["user_id"] === $user_id) {
            header("Location: ../index.php?error=Student ID is already registered");
        } elseif ($existingUser["username"] === $username) {
            header("Location: ../index.php?error=Username is already taken");
        } elseif ($existingUser["email"] === $email) {
            header("Location: ../index.php?error=An account with this email already exists");
        }
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $pdo->prepare("INSERT INTO Users (user_id, username, password, first_name, last_name, email, user_type, barcode_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $success = $stmt->execute([$user_id, $username, $hashedPassword, $first_name, $last_name, $email, $user_type, $barcode_number]);

    if ($success) {
        $_SESSION["user_id"] = $user_id;
        $_SESSION["username"] = $username;
        $_SESSION["user_type"] = $user_type;

       
        if ($user_type === 'staff') {
            header("Location: ../admin_dashboard.php?message=Registration successful");
        } else {
            header("Location: ../my_bookings.php?message=Registration successful");
        }
        exit();
    } else {
        header("Location: ../index.php?error=Registration failed. Please try again.");
        exit();
    }
}
?>
