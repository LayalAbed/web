<?php
$host = 'localhost'; 
$dbname = 'web1220480_clothingStore'; 
$username = 'web1220480_dbuser';  
$password = '5$hwERTah5';       

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     //echo "Database connection successful";
} catch (PDOException $e) {
  // echo "Connection failed: " . $e->getMessage();
}
?>
