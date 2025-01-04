<?php
// Database credentials
// $host = 'localhost:3308';  // Change to your host
// $dbname = 'user_data'; // Change to your database name
// $username = 'root';    // Change to your database username
// $password = 'root';        // Change to your database password

 $db_type ='PDO';
 $db_host='localhost';
 $db_port ='3308';
 $db_user ='root';
 $db_pass ='root';
$db_name ='user_data';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name", $db_user, $db_pass);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to the database successfully!";
} catch (PDOException $e) {
    // If there is an error, catch it and display a message
    echo "Connection failed: " . $e->getMessage();
}

// try {
//     // Create a new PDO instance
//     $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
//     // Set the PDO error mode to exception
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
//     echo "Connected to the database successfully!";
// } catch (PDOException $e) {
//     // If there is an error, catch it and display a message
//     echo "Connection failed: " . $e->getMessage();
// }
?>
