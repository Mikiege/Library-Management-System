<?php
require 'inc/config.php';

session_start();

// Check if the user is logged in
$loggedIn = isset($_SESSION["user_id"]);

// Ensure only staff (admins) can access
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== 'staff') {
    die("Access Denied! Only staff can view this page.");
}

// Fetch all bookings
$stmt = $pdo->query("
    SELECT b.booking_id, u.username, r.resource_name, r.resource_type, b.booking_date, b.start_time, b.end_time, b.status
    FROM bookings b
    JOIN users u ON b.user_id = u.user_id
    JOIN resources r ON b.resource_id = r.resource_id
    ORDER BY b.booking_date DESC, b.start_time DESC
");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count active, completed, and cancelled bookings
$activeCount = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'active'")->fetchColumn();
$completedCount = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'completed'")->fetchColumn();
$cancelledCount = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'cancelled'")->fetchColumn();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
                <a href="inc/login.php">Login</a>
            <?php endif; ?>
            <?php if ($_SESSION["user_type"] === 'staff'): ?>
    <a href="view_logs.php">View Logs</a>

    




<?php endif; ?>
        </nav>

        <?php if (isset($_SESSION["username"])): ?>
    <div class="user-greeting">
        Logged in as: <strong><?= htmlspecialchars($_SESSION["username"]) ?></strong>
    </div>
<?php endif; ?>


    </header>

    <main>
        <h2>All Bookings</h2>
        <div class="summary-box">
            <p>Active Bookings: <strong><?= $activeCount ?></strong></p>
            <p>Completed Bookings: <strong><?= $completedCount ?></strong></p>
            <p>Cancelled Bookings: <strong><?= $cancelledCount ?></strong></p>
            <br><br>
        </div>
        <!-- Filter Dropdown -->
        <label for="statusFilter">Filter by Status:</label>
        <select id="statusFilter">
            <option value="all">All</option>
            <option value="active">Active</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>

        <br><br>
        <table class="admin-table">
            <tr>
                <th>Booking ID</th>
                <th>Username</th>
                <th>Resource</th>
                <th>Type</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($bookings as $booking): ?>
                <tr class="status-<?= $booking['status'] ?>">
                    <td><?= $booking['booking_id'] ?></td>
                    <td><?= $booking['username'] ?></td>
                    <td><?= $booking['resource_name'] ?></td>
                    <td><?= ucfirst($booking['resource_type']) ?></td>
                    <td><?= $booking['booking_date'] ?></td>
                    <td><?= $booking['start_time'] ?></td>
                    <td><?= $booking['end_time'] ?></td>
                    <td><?= ucfirst($booking['status']) ?></td>
                    <td>
                        <?php if ($booking['status'] == 'active'): ?>
                            <form action="inc/admin_checkout.php" method="POST" onsubmit="return confirm('Are you sure you want to check out this user?');">
                                <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
                                <button type="submit" class="checkout-btn">Check Out</button>
                            </form>
                                    
                            <form action="inc/admin_cancel.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
                                <button type="submit" class="cancel-btn">Cancel</button>
                            </form>             

                            
                            <a href="inc/update_bookings.php?booking_id=<?= htmlspecialchars($booking['booking_id']) ?>" class="edit-btn">Edit</a>




                        <?php else: ?>
                            <span>Completed</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
    <footer>
        <p>&copy; 2025 Hunter J. Francois Library</p>
    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Filter Bookings
            document.getElementById("statusFilter").addEventListener("change", function() {
                let filter = this.value;
                let rows = document.querySelectorAll(".admin-table tr");

                rows.forEach(row => {
                    if (filter === "all" || row.classList.contains("status-" + filter)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });

            // Confirm Check Out
            document.querySelectorAll(".checkout-btn").forEach(button => {
                button.addEventListener("click", function() {
                    let bookingId = this.getAttribute("data-id");
                    if (!confirm("Are you sure you want to check out this user?")) {
                        event.preventDefault();
                    }
                });
            });


            // Confirm Cancellation
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".cancel-btn").forEach(button => {
                    button.addEventListener("click", function() {
                        if (confirm("Are you sure you want to cancel this booking?")) {
                            this.closest("form").submit();
                        }
                    });
                });
            });

        });
    </script>
</body>

</html>