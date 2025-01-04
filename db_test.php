<?php
// Database credentials
$host = 'localhost:3308';  // Change to your host
$dbname = 'user_data'; // Change to your database name
$username = 'root';    // Change to your database username
$password = 'root';        // Change to your database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to the database successfully!";
} catch (PDOException $e) {
    // If there is an error, catch it and display a message
    echo "Connection failed: " . $e->getMessage();
}
?>
