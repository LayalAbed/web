<?php
session_start();
include('header.php');
include('database.inc.php');

// Redirect if data is incomplete
if (
    !isset(
        $_SESSION['username'],
        $_SESSION['password'],
        $_SESSION['name'],
        $_SESSION['nationalID'],
        $_SESSION['dob'],
        $_SESSION['email'],
        $_SESSION['mobile'],
        $_SESSION['telephone'],
        $_SESSION['custmerImage'],
        $_SESSION['cardNumber'],
        $_SESSION['cardName'],
        $_SESSION['securityCode'],
        $_SESSION['expiryDate']
    )
) {
    header("Location: custmerStep1.php");
    exit;
}

$error = '';
$success = '';
$customerID = null;
$customerName = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Move image from pending_images to final destination
        $pendingPath = 'pending_images/' . $_SESSION['custmerImage'];
        $finalDir = 'uploads/customers/';
        if (!is_dir($finalDir)) {
            mkdir($finalDir, 0777, true);
        }

        $finalPath = $finalDir . $_SESSION['custmerImage'];
        rename($pendingPath, $finalPath); // Move file

        // Insert new customer into the database
        $stmt = $pdo->prepare("INSERT INTO customer (
            NationalID, Name, Password, DateOfBirth, Email,
            MobileNumber, TelephoneNumber, Username, custmerImage
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $_SESSION['nationalID'],
            $_SESSION['name'],
            $_SESSION['password'],
            $_SESSION['dob'],
            $_SESSION['email'],
            $_SESSION['mobile'],
            $_SESSION['telephone'],
            $_SESSION['username'],
            $_SESSION['custmerImage']
        ]);

        $customerID = $pdo->lastInsertId();
        $customerName = $_SESSION['name'];

        // Insert credit card data
 $stmtCard = $pdo->prepare("INSERT INTO creditcard (
    CustomerID, CardNumber, CardName, SecurityCode, ExpiryDate
) VALUES (?, ?, ?, ?, ?)");

$stmtCard->execute([
    $customerID,
    $_SESSION['cardNumber'],
    $_SESSION['cardName'],
    $_SESSION['securityCode'],
    $_SESSION['expiryDate']
]);


        $success = "Account created successfully!<br>Customer Name: " .
                    htmlspecialchars($customerName) .
                    "<br>Customer ID: " . str_pad($customerID, 9, '0', STR_PAD_LEFT);

        session_destroy();
    } catch (PDOException $e) {
        $error = "Error saving data: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Customer Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<section class="container">
<?php include('navigation.php'); ?>

<main>
    <h2>Confirm Your Details</h2>

    <?php if ($success): ?>
        <div class="color_masege_true"><?= $success ?></div>
    <?php else: ?>
        <form method="POST" action="">
            <p><strong>Name:</strong> <?= htmlspecialchars($_SESSION['name']) ?></p>
            <p><strong>National ID:</strong> <?= htmlspecialchars($_SESSION['nationalID']) ?></p>
            <p><strong>Date of Birth:</strong> <?= htmlspecialchars($_SESSION['dob']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['email']) ?></p>
            <p><strong>Mobile Number:</strong> <?= htmlspecialchars($_SESSION['mobile']) ?></p>
            <p><strong>Telephone Number:</strong> <?= htmlspecialchars($_SESSION['telephone']) ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>

            <p><strong>Card Number:</strong> <?= htmlspecialchars($_SESSION['cardNumber']) ?></p>
            <p><strong>Cardholder Name:</strong> <?= htmlspecialchars($_SESSION['cardName']) ?></p>
            <p><strong>Security Code:</strong> <?= str_repeat('*', strlen($_SESSION['securityCode'])) ?></p>
            <p><strong>Expiry Date:</strong> <?= htmlspecialchars($_SESSION['expiryDate']) ?></p>

            <p><strong>Uploaded Image:</strong><br>
                <img src="pending_images/<?= htmlspecialchars($_SESSION['custmerImage']) ?>" alt="Customer Image" width="150" />
            </p>

            <?php if ($error): ?>
                <div class="color_masege_false"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <button type="submit" class="link-button">Confirm</button>
        </form>
    <?php endif; ?>
</main>
</section>

<?php include('footer.php'); ?>
</body>
</html>
