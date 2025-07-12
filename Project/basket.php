<?php
session_start();
include('header.php');
include('database.inc.php');


if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit();
}

$customerID = $_SESSION['customer_id'];
$sql = "
    SELECT A.ApartmentID, A.ApartmentNumber, A.MonthlyRent, R.RentalStartDate, R.RentalEndDate
    FROM rental R
    JOIN apartment_home A ON R.ApartmentID = A.ApartmentID
    WHERE R.CustomerID = :customerID
    AND EXISTS (
        SELECT 1 FROM messages M
        WHERE M.SenderType = 'Customer'
          AND M.SenderID = R.CustomerID
          AND M.RecipientType = 'Owner'
          AND M.RecipientID = A.OwnerID
          AND M.MessageType = 'RentApprovalRequest'
    )
    AND NOT EXISTS (
        SELECT 1 FROM messages M2
        WHERE M2.SenderType = 'Owner'
          AND M2.SenderID = A.OwnerID
          AND M2.RecipientType = 'Customer'
          AND M2.RecipientID = R.CustomerID
          AND M2.MessageType = 'ApprovalConfirm'
    )
";




$stmt = $pdo->prepare($sql);
$stmt->execute(['customerID' => $customerID]);
$apartments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Rental Basket</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
   
  <div class="container">
      <?php include('navigation.php'); ?>
 
    <main>
<h2>Rental Basket</h2>

<?php if (empty($apartments)): ?>
    <p>Your basket is empty. You have no pending rentals.</p>
<?php else: ?>
    <table border="1" cellpadding="10">
        <tr>
            <th>Apartment ID</th>
            <th>Apartment Number</th>
            <th>Monthly Rent</th>
            <th>Start Date</th>
            <th>End Date</th>
        </tr>
        <?php foreach ($apartments as $apt): ?>
            <tr>
                <td><?= htmlspecialchars($apt['ApartmentID']) ?></td>
                <td><?= htmlspecialchars($apt['ApartmentNumber']) ?></td>
                <td><?= htmlspecialchars($apt['MonthlyRent']) ?> JD</td>
                <td><?= htmlspecialchars($apt['RentalStartDate']) ?></td>
                <td><?= htmlspecialchars($apt['RentalEndDate']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

 </main>
  </div>
<?php include('footer.php'); ?>
</body>
</html>
