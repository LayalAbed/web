<?php
require_once 'DataBace.php';

$stmt = $pdo->prepare("SELECT id FROM products WHERE rating = :rating");
$stmt->execute(['rating' => 4.0]);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// طباعة النتائج
if ($results) {
    echo "Products with rating = 4.0:<br>";
    foreach ($results as $row) {
        echo "Product ID: " . $row['id'] . "<br>";
    }
} else {
    echo "No products found with rating 4.0.";
}
?>
