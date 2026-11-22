<?php
include("auth.php");

// Only accept a POST from our own delete button (with its CSRF token).
$id = (int) ($_POST['cat_del'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_valid() && $id > 0) {
    $stmt = $db->prepare("DELETE FROM res_category WHERE c_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header("Location: add_category.php");
exit;
