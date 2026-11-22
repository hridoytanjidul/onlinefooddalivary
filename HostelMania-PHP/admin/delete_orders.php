<?php
include("auth.php");

// Only accept a POST from our own delete button (with its CSRF token).
$id = (int) ($_POST['order_del'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_valid() && $id > 0) {
    $stmt = $db->prepare("DELETE FROM users_orders WHERE o_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header("Location: all_orders.php");
exit;
