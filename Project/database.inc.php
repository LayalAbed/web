<?php
$host = 'localhost'; 
$dbname = 'web1220480_proj'; 
$username = 'web1220480_dbuser';  
$password = '5$hwERTah5';       

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
