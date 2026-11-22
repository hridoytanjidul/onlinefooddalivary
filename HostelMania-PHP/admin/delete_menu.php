<?php
include("auth.php");

// Only accept a POST from our own delete button (with its CSRF token).
$id = (int) ($_POST['menu_del'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_valid() && $id > 0) {
    $stmt = $db->prepare("DELETE FROM dishes WHERE d_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header("Location: all_menu.php");
exit;
