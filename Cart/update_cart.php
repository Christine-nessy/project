<?php
session_start();
include '../database.php';

$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = 1;

    /*if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }*/
    $stmt = $db->prepare("UPDATE shopping_cart SET quantity = quantity + :quantity WHERE  product_id = :product_id");
                $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                //$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
             $stmt->execute();
}

header("Location: cart.php");
exit;
?>
