<?php
require 'config.php';

echo "<h3>Rehashing Passwords...</h3>";

$stmt = $pdo->query("SELECT user_id, password FROM Users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    // Checking if the password is not already hashed
    if (!password_get_info($user["password"])["algo"]) {
        $hashed_password = password_hash($user["password"], PASSWORD_BCRYPT);
        $update_stmt = $pdo->prepare("UPDATE Users SET password = ? WHERE user_id = ?");
        $update_stmt->execute([$hashed_password, $user["user_id"]]);
        echo "✅ Updated password for user_id: " . $user["user_id"] . "<br>";
    } else {
        echo "🔹 Password already hashed for user_id: " . $user["user_id"] . "<br>";
    }
}

echo "<h3>All passwords are now securely hashed!</h3>";
?>
