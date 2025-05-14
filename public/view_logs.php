<?php
require_once 'inc/config.php';
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== 'staff') {
    die("Access denied");
}

$stmt = $pdo->query("SELECT l.*, u.username FROM logs l JOIN users u ON l.user_id = u.user_id ORDER BY l.created_at DESC");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Activity Logs</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>



    <h2>System Activity Logs</h2>
    <table border="1" class="admin-table">
        <tr>
            <th>User</th>
            <th>Action</th>
            <th>Details</th>
            <th>Timestamp</th>
        </tr>
        <?php foreach ($logs as $log): ?>
            <tr>
                <td><?= htmlspecialchars($log["username"]) ?></td>
                <td><?= htmlspecialchars($log["action"]) ?></td>
                <td><?= htmlspecialchars($log["details"]) ?></td>
                <td><?= $log["created_at"] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>