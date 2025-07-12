<?php
session_start();
include('database.inc.php');

$error = '';

// Handle Step 1 Data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nid'])) {
    $_SESSION['nationalID'] = $_POST['nid'];
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['flat'] = $_POST['flat'];
    $_SESSION['street'] = $_POST['street'];
    $_SESSION['city'] = $_POST['city'];
    $_SESSION['postal'] = $_POST['postal'];
    $_SESSION['dob'] = $_POST['dob'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['mobile'] = $_POST['mobile'];
    $_SESSION['telephone'] = $_POST['telephone'];

    $_SESSION['cardNumber'] = $_POST['cardNumber'];
    $_SESSION['cardName'] = $_POST['cardName'];
    $_SESSION['securityCode'] = $_POST['securityCode'];
    $_SESSION['expiryDate'] = $_POST['expiryDate'];

    // Handle image upload
    if (isset($_FILES['custmerImage']) && $_FILES['custmerImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'pending_images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($_FILES['custmerImage']['name'], PATHINFO_EXTENSION);

        // Get next auto-increment Customer ID
        $stmt = $pdo->query("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'customer'");
        $nextId = $stmt->fetchColumn();
        $imageName = 'C' . $nextId . '.jpg';
        $imagePath = $uploadDir . $imageName;

        move_uploaded_file($_FILES['custmerImage']['tmp_name'], $imagePath);

        $_SESSION['custmerImage'] = $imageName;
    }
}

// Handle username and password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM customer WHERE NationalID = ?");
    $stmt->execute([$_SESSION['nationalID']]);
    $idExists = $stmt->fetchColumn();

    if ($idExists > 0) {
        $error = "An account with this National ID already exists.";
    } elseif (empty($username)) {
        $error = "Please enter a username.";
    } elseif (strlen($password) < 6 || strlen($password) > 15) {
        $error = "Password length must be between 6 and 15 characters.";
    } elseif (!preg_match('/^\d/', $password)) {
        $error = "Password must start with a digit.";
    } elseif (!preg_match('/[a-z]$/', $password)) {
        $error = "Password must end with a lowercase letter.";
    } elseif ($password !== $confirm_password) {
        $error = "Password and confirm password do not match.";
    } else {
        // Check username duplication
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM customer  WHERE Username = ?");
        $stmt->execute([$username]);
        $usernameExists = $stmt->fetchColumn();

        if ($usernameExists > 0) {
            $error = "Username already exists. Please choose another.";
        } else {
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;

            header("Location: custmerStep3.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Account Registration - Step 2</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<section class="container">
<?php include('navigation.php'); ?>
<main>
    <h2>E-Account Registration - Step 2</h2>

    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password" required><br><br>

        <?php if ($error): ?>
            <div class="color_masege_false"><?= htmlspecialchars($error) ?></div><br>
        <?php endif; ?>

        <input type="submit" value="Create Account" class="link-button">
    </form>
</main>
</section>
<?php include('footer.php'); ?>
</body>
</html>
