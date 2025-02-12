<?php
include '../database.php'; // Include your database connection

$db_instance = new Database('PDO', 'localhost', '3308', 'root', 'root', 'user_data');
$db = $db_instance->getConnection();

// Fetch order status counts
$stmt = $db->prepare("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
$stmt->execute();
$order_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for Chart.js
$labels = [];
$counts = [];

foreach ($order_data as $row) {
    $labels[] = $row['status'];
    $counts[] = $row['count'];
}

// Return JSON response
echo json_encode(['labels' => $labels, 'counts' => $counts]);
?>
