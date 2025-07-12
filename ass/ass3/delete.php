
<?php
include('dbconfig.inc.php');
include('header.php'); 


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




<footer>
    <hr>
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
