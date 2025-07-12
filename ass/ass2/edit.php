<header>
    <table width="100%">
        <tr>
            <td align="left" valign="top">
                <img src="images/Lucky.jpg" alt="Lucky-you-Are Logo" height="100"><br>
                <h1>Lucky-you-Are</h1>
            </td>
            <td align="right" valign="top">
                <nav>
                    <a href="products.php">Home</a> |
                    <a href="add.php">Add Product</a> |
                    <a href="ContactUs.html">Contact Us</a>
                </nav>
            </td>
        </tr>
    </table>
    <hr>
</header>

<?php
include('dbconfig.inc.php');

// Check if product ID is passed in the query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Product ID is required");
}

$product_id = $_GET['id'];
$error = '';
$success = '';

// Fetch product data from the database
try {
    $sql = "SELECT * FROM products WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        die("Product not found");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);
    $description = trim($_POST['description']);
    
    // Validate inputs
    if (!is_numeric($price) || $price <= 0) {
        $error = 'Price must be a positive number';
    } elseif (!is_numeric($quantity) || $quantity < 0) {
        $error = 'Quantity must be a non-negative number';
    } else {
        try {
            $pdo->beginTransaction();
            
            // Update product details
            $update_sql = "UPDATE products SET 
                          price = :price,
                          quantity = :quantity,
                          description = :description
                          WHERE id = :id";
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->bindParam(':price', $price);
            $update_stmt->bindParam(':quantity', $quantity);
            $update_stmt->bindParam(':description', $description);
            $update_stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
            $update_stmt->execute();
            
            // Handle file upload if provided
            if (isset($_FILES['product_photo']) && $_FILES['product_photo']['error'] == UPLOAD_ERR_OK) {
                $file = $_FILES['product_photo'];
                
                // Validate image type
                $file_type = exif_imagetype($file['tmp_name']);
                if ($file_type != IMAGETYPE_JPEG) {
                    throw new Exception('Only JPEG images are allowed');
                }
                
                // Create directory if not exists
                if (!file_exists('images')) {
                    mkdir('images', 0777, true);
                }
                
                // Remove old image if exists
                if (!empty($product['image_name']) && file_exists('images/' . $product['image_name'])) {
                    unlink('images/' . $product['image_name']);
                }
                
                // Rename image to product_id.jpeg
                $new_filename = $product_id . '.jpeg';
                $destination = 'images/' . $new_filename;
                
                // Move uploaded file to the directory
                if (!move_uploaded_file($file['tmp_name'], $destination)) {
                    throw new Exception('Failed to upload image');
                }
                
                // Update image name in database
                $image_sql = "UPDATE products SET image_name = :image_name WHERE id = :id";
                $image_stmt = $pdo->prepare($image_sql);
                $image_stmt->bindParam(':image_name', $new_filename);
                $image_stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
                $image_stmt->execute();
            }
            
            $pdo->commit();
            $success = 'Product updated successfully!';
            
            // Reload the product data after update
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product: <?php echo htmlspecialchars($product['product_name']); ?></title>
</head>
<body>

<h2>Edit Product</h2>

<?php if (!empty($error)): ?>
    <p><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p>
        <?php echo htmlspecialchars($success); ?>
        <img src="images/updateit.jpg" alt="Update Icon" width="40" height="30">
    </p>
<?php endif; ?>



<!-- Form to edit an existing product -->
<form method="POST" action="edit.php?id=<?php echo $product_id; ?>" enctype="multipart/form-data">
    <fieldset>
        <legend>Product Record</legend>
        <table border="1">
            <!--   ID (not editable) -->
            <tr>
                <th>Product ID:</th>
                <td><?php echo htmlspecialchars($product['id']); ?></td>
            </tr>

            <!--   Name not editable -->
            <tr>
                <th>Product Name:</th>
                <td><?php echo htmlspecialchars($product['product_name']); ?></td>
            </tr>

            <!--  Category not edite -->
            <tr>
                <th>Category:</th>
                <td><?php echo htmlspecialchars($product['category']); ?></td>
            </tr>

            <!-- edite Price  -->
            <tr>
                <th>Price:</th>
                <td><input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required min="0"></td>
            </tr>

            <!-- edite Quantity  -->
            <tr>
                <th>Quantity:</th>
                <td><input type="number" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required min="0"></td>
            </tr>

            <!--  Rating (not edite) -->
            <tr>
                <th>Rating:</th>
                <td><?php echo htmlspecialchars($product['rating']); ?></td>
            </tr>

            <!-- edite Description  -->
            <tr>
                <th>Description:</th>
                <td><textarea name="description" rows="6"><?php echo htmlspecialchars($product['description']); ?></textarea></td>
            </tr>

            <!-- File  upload new  image  -->
            <tr>
                <th>New Photo:</th>
                <td><input type="file" name="product_photo" accept="image/jpeg"></td>
            </tr>

            <!-- Buttons  submit or go back to products page -->
            <tr>
                <td colspan="2" align="center">
                    <button type="submit">Update Product</button>
                    <a href="products.php"><button type="button">Back to Products</button></a>
                </td>
            </tr>
        </table>
    </fieldset>
</form>

<hr> </hr>

<!--  footer  -->
<footer>
    <table>
        <tr>
            <td>
                <p>
                    <b>Customer Support:</b><br>
                    <img src="images/phone.jpg" alt="Phone" width="30" height="30">
                    +972-599-314087<br>
                    <a href="mailto:support@luckyyouare.com">
                        <img src="images/email.jpg" alt="Email" width="30" height="30">
                        support@luckyyouare.com
                    </a>
                </p>
                <p>
                    <a href="ContactUs.html">
                        <img src="images/contatus.jpg" alt="Contact Us" width="30" height="30">
                        Contact Us
                    </a>
                </p>
            </td>
        </tr>
    </table>
    <p>
        &copy; 2025 LuckyYouAre. All rights reserved.
    </p>
</footer>

</body>
</html>

