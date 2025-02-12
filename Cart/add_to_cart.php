<?php
session_start();
include '../database.php'; // Include database connection

// Ensure user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$user_id) {
    header("Location: ../login_form.php?error=Please log in to add items to the cart");
    exit;
}

// Check if request is POST and required data is set , $_POST['quantity']
if ($_SERVER['REQUEST_METHOD']=='POST' &&  isset($_POST['product_id'])) {
   $quantity=1;
   
   $date=  date("Y-m-d");
   
    $product_id = intval($_POST['product_id']);
   // $quantity = intval($_POST['quantity']);

   /* if ($quantity < 1) {
        header("Location: ../products.php?error=Invalid quantity selected");
        exit;
    }*/

    $db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
    $db = $db_instance->getConnection();

    try {
        // Fetch product details (name, price, image)
        $stmt = $db->prepare("SELECT product_id, name, price, image_url FROM products WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetchAll(PDO::FETCH_ASSOC);
       $pdtprice=$product['price'];
       $_SESSION["image"]=$product['image_url'];

       // if ($product_id) {
            // Check if product already exists in the cart user_id = :user_id AND
            $stmt = $db->prepare("SELECT product_id FROM shopping_cart WHERE product_id = :product_id ");
           // $stmt->bindParam(':user', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
            $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);
            $quant= $cart_item['product_id'];

            if ($cart_item) {
                // Update quantity in the cart if item exists
                $stmt = $db->prepare("UPDATE shopping_cart SET quantity = quantity + :quantity WHERE  product_id = :product_id");
                $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                //$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
             $stmt->execute();
                header("Location:cart.php?success=Product added to cart");
                
        

            } else {
                // Insert new item into the cart, including product price
                
                $stmt = $db->prepare("INSERT INTO shopping_cart (user_id, product_id, quantity, price,date_added) VALUES (:user, :product_id, :quantity, :price,:date_added)");
                $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                $stmt->bindParam(':price', $pdtprice, PDO::PARAM_STR);
                $stmt->bindParam(':user', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
         $stmt->bindParam(':date_added', $date , PDO::PARAM_STR);
           if( $stmt->execute()){
            header("Location:cart.php?success=Product added to cart");
             exit;
           }
           else{
            print_r($stmt->errorInfo());
           }
           

            }
            
            
         /*}else {
            header("Location: ../products.php?error=Product not found");
            exit;
        }*/
    } catch (PDOException $e) {
        //header("Location: ../products.php?error=Database error: " . urlencode($e->getMessage()));
        $e->getMessage();
       // exit;
    }
}


//header("Location: ../products.php?error=Invalid request");
//exit;
