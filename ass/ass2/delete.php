<?php
include('dbconfig.inc.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $productID = $_GET['id'];

    //  Get  image name of the product
    $query = "SELECT image_name FROM products WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$productID]);
    $product = $stmt->fetch();

    if ($product && !empty($product['image_name'])) {
        $imagePath = "images/" . $product['image_name'];
        //  Delete  image file if it exists
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    //  Delete  product from  database
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$productID])) {
        header("Location: products.php?message=Product+deleted+successfully");
        exit;
    } else {
        echo "<p>Error: Unable to delete product. Please try again.</p>";
    }
} else {
    echo "<p>Product ID is missing.</p>";
}
?>
