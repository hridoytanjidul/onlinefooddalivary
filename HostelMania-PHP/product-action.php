<?php
// Cart actions shared by dishes.php and checkout.php.
// Expects connection/connect.php (which starts the session) to be included first.
if (!empty($_GET["action"])) {
    $productId = (int) ($_GET['id'] ?? 0);
    $quantity = (int) ($_POST['quantity'] ?? 0);

    if (!isset($_SESSION["cart_item"]) || !is_array($_SESSION["cart_item"])) {
        $_SESSION["cart_item"] = array();
    }

    switch ($_GET["action"]) {
        case "add":
            if ($productId > 0 && $quantity > 0) {
                $stmt = $db->prepare("SELECT d_id, title, price FROM dishes WHERE d_id = ?");
                $stmt->bind_param('i', $productId);
                $stmt->execute();
                $productDetails = $stmt->get_result()->fetch_object();

                if ($productDetails) {
                    $id = (int) $productDetails->d_id;
                    if (isset($_SESSION["cart_item"][$id])) {
                        $_SESSION["cart_item"][$id]["quantity"] += $quantity;
                    } else {
                        $_SESSION["cart_item"][$id] = array(
                            'title'    => $productDetails->title,
                            'd_id'     => $id,
                            'quantity' => $quantity,
                            'price'    => $productDetails->price,
                        );
                    }
                }
            }
            break;

        case "remove":
            unset($_SESSION["cart_item"][$productId]);
            break;

        case "empty":
            $_SESSION["cart_item"] = array();
            break;

        case "check":
            header("Location: checkout.php");
            exit;
    }
}
