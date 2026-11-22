<?php
include("auth.php");

// Only accept a POST from our own delete button (with its CSRF token).
$id = (int) ($_POST['res_del'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_valid() && $id > 0) {
    // Remove the restaurant's dishes too, otherwise they keep showing on the
    // home page and link to a restaurant that no longer exists.
    $stmt = $db->prepare("DELETE FROM dishes WHERE rs_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt = $db->prepare("DELETE FROM restaurant WHERE rs_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header("Location: all_restaurant.php");
exit;
