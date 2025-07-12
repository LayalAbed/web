<?php
include('dbconfig.inc.php');

// Variables 
$productName = '';
$category = '';
$description = '';
$price = '';
$quantity = '';
$rating = '';
$errorMessage = '';
$successMessage = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = trim($_POST['product_name']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);
    $rating = trim($_POST['rating']);

    // Input validation
    if (empty($productName)) {
        $errorMessage = 'Product Name is required';
    } elseif (!is_numeric($price) || $price <= 0) {
        $errorMessage = 'Price must be a positive number';
    } elseif (!is_numeric($quantity) || $quantity < 0) {
        $errorMessage = 'Quantity must be a non-negative number';
    } elseif (!is_numeric($rating) || $rating < 0 || $rating > 5) {
        $errorMessage = 'Rating must be between 0 and 5';
    } else {
        try {
            $pdo->beginTransaction();

            // Insert data into the database
            $insertQuery = "INSERT INTO products (product_name, category, description, price, quantity, rating) 
                            VALUES (:product_name, :category, :description, :price, :quantity, :rating)";
            $stmt = $pdo->prepare($insertQuery);
            $stmt->bindParam(':product_name', $productName);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':rating', $rating);
            $stmt->execute();

            $insertedProductId = $pdo->lastInsertId();

           // Handle image upload
if (isset($_FILES['product_photo']) && $_FILES['product_photo']['error'] == UPLOAD_ERR_OK) {
    $uploadedFile = $_FILES['product_photo'];

    $fileType = $uploadedFile['type'];
    $fileName = $uploadedFile['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (($fileType != 'image/jpeg' && $fileType != 'image/pjpeg') || $fileExtension != 'jpeg') {
        throw new Exception('Only JPEG images are allowed');
    }

    $newFileName = $insertedProductId . '.jpeg';
    $uploadPath = 'images/' . $newFileName;

    //  uploaded   to images
    if (!move_uploaded_file($uploadedFile['tmp_name'], $uploadPath)) {
        throw new Exception('Failed to upload image');
    }

    // Update image_name in the database
    $updateImageQuery = "UPDATE products SET image_name = :image_name WHERE id = :id";
    $updateStmt = $pdo->prepare($updateImageQuery);
    $updateStmt->bindParam(':image_name', $newFileName);
    $updateStmt->bindParam(':id', $insertedProductId);
    $updateStmt->execute();
}


            $pdo->commit();
            $successMessage = 'Product added successfully!';
            $productName = $category = $description = $price = $quantity = $rating = '';
        } catch (Exception $e) {
            $pdo->rollBack();
            $errorMessage = 'Error: ' . $e->getMessage();
        }
    }
}
?>

<!-- HTML code -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
</head>
<body>

<h2>Add New Product</h2>

<?php if (!empty($errorMessage)): ?>
    <p style="color: red;"><?php echo $errorMessage; ?></p>
<?php endif; ?>

<?php if (!empty($successMessage)): ?>
    <p style="color: green;">
        <img src="images/addedsuccessfully.jpg" alt="Success" width="30" height="30">
        <?php echo $successMessage; ?>
    </p>
<?php endif; ?>

<form method="POST" action="add.php" enctype="multipart/form-data">
    <fieldset>
        <legend>Product Details</legend>

        <label>Product Name:<br>
            <input type="text" name="product_name" value="<?php echo htmlspecialchars($productName); ?>" required>
        </label><br><br>

        <label>Category:<br>
            <select name="category" required>
                <option value="">Select Category</option>
                <option value="Jumpsuit" <?php echo ($category == 'Jumpsuit') ? 'selected' : ''; ?>>Jumpsuit</option>
                <option value="Dress" <?php echo ($category == 'Dress') ? 'selected' : ''; ?>>Dress</option>
                <option value="Shirt" <?php echo ($category == 'Shirt') ? 'selected' : ''; ?>>Shirt</option>
                <option value="Pants" <?php echo ($category == 'Pants') ? 'selected' : ''; ?>>Pants</option>
                <option value="Sweaters" <?php echo ($category == 'Sweaters') ? 'selected' : ''; ?>>Sweaters</option>
                <option value="T-shirt" <?php echo ($category == 'T-shirt') ? 'selected' : ''; ?>>T-shirt</option>
                <option value="Shorts" <?php echo ($category == 'Shorts') ? 'selected' : ''; ?>>Shorts</option>
            </select>
        </label><br><br>

        <label>Price:<br>
            <input type="number" name="price" value="<?php echo htmlspecialchars($price); ?>" required min="0">
        </label><br><br>

        <label>Quantity:<br>
            <input type="number" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>" required min="0">
        </label><br><br>

        <label>Rating (0-5):<br>
            <input type="number" name="rating" value="<?php echo htmlspecialchars($rating); ?>" required min="0" max="5" step="0.1">
        </label><br><br>

        <label>Description:<br>
            <textarea name="description" rows="4"><?php echo htmlspecialchars($description); ?></textarea>
        </label><br><br>

        <label>Product Photo (JPEG only):<br>
            <input type="file" name="product_photo" accept="image/jpeg">
        </label><br><br>

        <button type="submit">Add Product</button>
        <button type="button" onclick="window.location.href='products_table.php'">Cancel</button>
    </fieldset>
</form>

<footer>
    <table>
        <tr>
            <td>
                <p>
                    <b>Customer Support:</b><br>
                    <img src="images/Phone.jpg" alt="Phone" width="30" height="30">
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
    <p>&copy; 2025 LuckyYouAre. All rights reserved.</p>
</footer>

</body>
</html>