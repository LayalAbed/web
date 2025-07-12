<?php
session_start();
include('database.inc.php');

$error = '';
$success = '';

// Handle data from Step 1 and store it in session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['NationalID'])) {
    $_SESSION['owner'] = [
        'NationalID' => $_POST['NationalID'] ?? '',
        'OwnerName' => $_POST['OwnerName'] ?? '',
        'DateOfBirth' => $_POST['DateOfBirth'] ?? '',
        'EmailAddress' => $_POST['EmailAddress'] ?? '',
        'MobileNumber' => $_POST['MobileNumber'] ?? '',
        'PhoneNumber' => $_POST['PhoneNumber'] ?? '',
        'BankName' => $_POST['BankName'] ?? '',
        'BankBranch' => $_POST['BankBranch'] ?? '',
        'AccountNumber' => $_POST['AccountNumber'] ?? '',
    ];

    $_SESSION['ownerAddress'] = [
        'FlatOrHouseNo' => $_POST['FlatOrHouseNo'] ?? '',
        'StreetName' => $_POST['StreetName'] ?? '',
        'City' => $_POST['City'] ?? '',
        'PostalCode' => $_POST['PostalCode'] ?? '',
    ];

    // Handle image upload
    if (isset($_FILES['ownerImage']) && $_FILES['ownerImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'pending_images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Get next auto-increment ID for Owner
        $stmt = $pdo->query("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'owner'");
        $nextId = $stmt->fetchColumn();

        $imageName = 'O' . $nextId . '.jpg';
        $imagePath = $uploadDir . $imageName;

        // Move uploaded file to destination
        move_uploaded_file($_FILES['ownerImage']['tmp_name'], $imagePath);

        // Save image name to session
        $_SESSION['owner']['ownerImage'] = $imageName;
    }
}

// Handle username and password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Check for duplicate National ID
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM owner WHERE NationalID = ?");
    $stmt->execute([$_SESSION['owner']['NationalID']]);
    $idCount = $stmt->fetchColumn();

    if ($idCount > 0) {
        $error = "An account with this National ID already exists.";
    } elseif (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!preg_match('/^[a-zA-Z0-9_@.]+$/', $username)) {
        $error = "Username can only contain letters, numbers, dots, @, or underscores.";
    } else {
        // Check for duplicate username
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM owner WHERE Username = ?");
        $stmt->execute([$username]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $error = "Username already exists. Please choose another one.";
        } elseif (strlen($password) < 6 || strlen($password) > 15) {
            $error = "Password length must be between 6 and 15 characters.";
        } elseif (!preg_match('/^\d/', $password)) {
            $error = "Password must start with a digit.";
        } elseif (!preg_match('/[a-z]$/', $password)) {
            $error = "Password must end with a lowercase letter.";
        } elseif ($password !== $confirm_password) {
            $error = "Password and confirm password do not match.";
        } else {
            $_SESSION['owner']['Username'] = $username;
            $_SESSION['owner']['Password'] = $password;

            header("Location: ownerStep3.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Owner Registration - Step 2</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<section class="container">
<?php include('navigation.php'); ?>

<main>
    <h2>Owner Registration - Step 2</h2>

    <form method="POST" action="" enctype="multipart/form-data">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password" required><br><br>

        <?php if ($error): ?>
            <p class="color_masege_false"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <input type="submit" value="Next" class="link-button">
    </form>
</main>
</section>

<?php include('footer.php'); ?>
</body>
</html>
