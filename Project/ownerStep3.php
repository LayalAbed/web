<?php
session_start();
include('header.php');
include('database.inc.php');

// Redirect to Step 1 if data is incomplete
if (
    !isset(
        $_SESSION['owner']['Username'],
        $_SESSION['owner']['Password'],
        $_SESSION['owner']['OwnerName'],
        $_SESSION['owner']['NationalID'],
        $_SESSION['owner']['DateOfBirth'],
        $_SESSION['owner']['EmailAddress'],
        $_SESSION['owner']['MobileNumber'],
        $_SESSION['owner']['PhoneNumber'],
        $_SESSION['owner']['ownerImage']
    )
) {
    header("Location: ownerStep1.php");
    exit;
}

$error = '';
$success = '';
$ownerID = null;
$ownerName = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // move image from pending_images to final destination
        $pendingPath = 'pending_images/' . $_SESSION['owner']['ownerImage'];
        $finalDir = 'uploads/owners/';
        $finalPath = $finalDir . $_SESSION['owner']['ownerImage'];

        if (!is_dir($finalDir)) {
            mkdir($finalDir, 0777, true);
        }

        if (file_exists($pendingPath)) {
            rename($pendingPath, $finalPath);
        } else {
            throw new Exception("Image file not found in pending_images.");
        }

        // insert into Owner table
        $stmt = $pdo->prepare("INSERT INTO owner (
            NationalID, OwnerName, Username, EmailAddress, PhoneNumber,
            MobileNumber, Password, BankName, BankBranch, AccountNumber, ownerImage
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $_SESSION['owner']['NationalID'],
            $_SESSION['owner']['OwnerName'],
            $_SESSION['owner']['Username'],
            $_SESSION['owner']['EmailAddress'],
            $_SESSION['owner']['PhoneNumber'],
            $_SESSION['owner']['MobileNumber'],
            $_SESSION['owner']['Password'],
            $_SESSION['owner']['BankName'],
            $_SESSION['owner']['BankBranch'],
            $_SESSION['owner']['AccountNumber'],
            $_SESSION['owner']['ownerImage']
        ]);

        $ownerID = $pdo->lastInsertId();
        $ownerName = $_SESSION['owner']['OwnerName'];

        // insert into OwnerAddress
        $stmt = $pdo->prepare("INSERT INTO owneraddress (
            OwnerID, FlatOrHouseNo, StreetName, City, PostalCode
        ) VALUES (?, ?, ?, ?, ?)");

        $stmt->execute([
            $ownerID,
            $_SESSION['ownerAddress']['FlatOrHouseNo'],
            $_SESSION['ownerAddress']['StreetName'],
            $_SESSION['ownerAddress']['City'],
            $_SESSION['ownerAddress']['PostalCode']
        ]);

        $success = "Account created successfully!<br>Owner Name: " .
            htmlspecialchars($ownerName) .
            "<br>Owner ID: " . str_pad($ownerID, 9, '0', STR_PAD_LEFT);

        session_destroy();
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Owner Details</title>
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
            <p><strong>Owner Name:</strong> <?= htmlspecialchars($_SESSION['owner']['OwnerName']) ?></p>
            <p><strong>National ID:</strong> <?= htmlspecialchars($_SESSION['owner']['NationalID']) ?></p>
            <p><strong>Date of Birth:</strong> <?= htmlspecialchars($_SESSION['owner']['DateOfBirth']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['owner']['EmailAddress']) ?></p>
            <p><strong>Mobile Number:</strong> <?= htmlspecialchars($_SESSION['owner']['MobileNumber']) ?></p>
            <p><strong>Telephone Number:</strong> <?= htmlspecialchars($_SESSION['owner']['PhoneNumber']) ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($_SESSION['owner']['Username']) ?></p>
            <p><strong>Bank Info:</strong>
                <?= htmlspecialchars($_SESSION['owner']['BankName'] . " - " .
                    $_SESSION['owner']['BankBranch'] . " - " .
                    $_SESSION['owner']['AccountNumber']) ?></p>
            <p><strong>Address:</strong>
                <?= htmlspecialchars($_SESSION['ownerAddress']['FlatOrHouseNo'] . ", " .
                    $_SESSION['ownerAddress']['StreetName'] . ", " .
                    $_SESSION['ownerAddress']['City'] . ", " .
                    $_SESSION['ownerAddress']['PostalCode']) ?></p>

            <p><strong>Uploaded Image:</strong><br>
                <img src="uploads/owners/<?= htmlspecialchars($_SESSION['owner']['ownerImage']) ?>" alt="Owner Image" width="150">
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
