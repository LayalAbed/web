<?php
session_start();
include('header.php');
include('database.inc.php');

if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit();
}

// Get all pending apartments
$stmt = $pdo->prepare("SELECT * FROM pendingapartments");
$stmt->execute();
$flats = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Apartments</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<section class="container">
    <?php include('navigation.php'); ?>

    <main>
        <h2>Pending Apartments Details</h2>
        <?php if (empty($flats)): ?>
            <p class="color_masege_false">No pending apartments found.</p>
        <?php else: ?>
            <table border="1" cellpadding="6">
                <tr>
                    <th>Apartment Number</th>
                    <th>Rooms</th>
                    <th>Bathrooms</th>
                    <th>Rent</th>
                    <th>Owner ID</th>
                </tr>
                <?php foreach ($flats as $flat): ?>
                    <tr>
                        <td><?= htmlspecialchars($flat['ApartmentNumber']) ?></td>
                        <td><?= $flat['NumberOfRooms'] ?></td>
                        <td><?= $flat['NumberOfBathrooms'] ?></td>
                        <td><?= $flat['MonthlyRent'] ?> JD</td>
                        <td><?= $flat['OwnerID'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </main>
</section>

<?php include('footer.php'); ?>
</body>
</html>
