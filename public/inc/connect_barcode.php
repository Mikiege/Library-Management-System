<?php

$conn = new mysqli("localhost", "root", "", "library_management");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get barcode from form or scanner input
$barcode = $_POST['barcode'] ?? '';

if (!empty($barcode)) {
    // Get user by barcode
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE barcode_number = ?");
    $stmt->bind_param("s", $barcode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['user_id'];

        // Insert check-in log
        $logStmt = $conn->prepare("INSERT INTO logs (user_id, action) VALUES (?, 'Check-in')");
        $logStmt->bind_param("i", $user_id);
        $logStmt->execute();

        echo "✅ Check-in logged for User ID: $user_id";
    } else {
        echo "❌ No user found with that barcode.";
    }

    $stmt->close();
} else {
    echo "❗ Barcode not provided.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>barcode_checkin</title>
</head>
<body>
<form method="POST" action="barcode_checkin.php">
    <label for="barcode">Scan Barcode:</label>
    <input type="text" id="barcode" name="barcode" autofocus required>
    <button type="submit">Submit</button>
</form>

</body>
</html>