<?php
session_start();
include('Product.php');
include('header.php');

if (!isset($_POST['productID']) || !is_numeric($_POST['productID'])) {
    header('Location: products.php');
    exit;
}

$productId = (int) $_POST['productID'];
$product = Product::getProductById($productId);

if (!$product) {
    echo "<html>
<head><title>Product Not Found</title></head>
<body>
    <h2>Product Not Found</h2>
    <p>The requested product does not exist.</p>
    <p><a href='products.php'>Back to Products</a></p>
</body>
</html>";
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$productKey = (string)$product->getProductID();

if (isset($_SESSION['cart'][$productKey])) {
    $_SESSION['cart'][$productKey]['quantity'] += 1;
} else {
    $_SESSION['cart'][$productKey] = [
        'name'     => $product->getProductName(),
        'price'    => $product->getPrice(),
        'quantity' => 1
    ];
}

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Shopping Cart</title>
</head>
<body>

<main>
    <h2>Your Shopping Cart</h2>

    <table border="1" cellpadding="8">
        <tr>
            <th>Title</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>

        <?php foreach ($_SESSION['cart'] as $item): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td><?php echo number_format($item['price'], 2); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
        </tr>
        <?php endforeach; ?>

        <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td><strong><?php echo number_format($total, 2); ?></strong></td>
        </tr>
    </table>

    <p><a href="products.php">Continue Shopping</a></p>
</main>
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

