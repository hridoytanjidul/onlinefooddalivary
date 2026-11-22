<?php
include("connection/connect.php");

if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Customers may only cancel their own orders, only from the cancel button
// (POST + CSRF token), and only while the restaurant has not started on them.
// Orders that are on the way or delivered stay in the admin's records.
$orderId = (int) ($_POST['order_del'] ?? 0);
$userId = (int) $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_valid() && $orderId > 0) {
    $stmt = $db->prepare("DELETE FROM users_orders WHERE o_id = ? AND u_id = ? AND (status IS NULL OR status = '' OR status = 'NULL')");
    $stmt->bind_param("ii", $orderId, $userId);
    $stmt->execute();
}
header("Location: your_orders.php");
exit;
