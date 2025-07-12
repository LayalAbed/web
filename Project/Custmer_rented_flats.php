<?php
session_start();  
include('header.php');
include('database.inc.php');  

// Check if customer_id is set in the session
if (!isset($_SESSION['customer_id'])) {
    die("Please log in to view your rented flats.");
}

$customerID = $_SESSION['customer_id'];

// Query to get rentals for this customer, sorted by RentalStartDate descending
$sql = "
SELECT r.RentalStartDate, r.RentalEndDate,
       a.ApartmentID, a.ApartmentNumber, a.MonthlyRent, a.OwnerID,
       o.OwnerName, o.EmailAddress, o.PhoneNumber,
       oa.City
FROM rental r
JOIN apartment_home a ON r.ApartmentID = a.ApartmentID
JOIN owner o ON a.OwnerID = o.OwnerID
LEFT JOIN owneraddress oa ON o.OwnerID = oa.OwnerID
WHERE r.CustomerID = :customerID
ORDER BY r.RentalStartDate DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['customerID' => $customerID]);
$rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);

$today = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Rented Flats</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<section class="container">
<?php include('navigation.php'); ?>

<main>
    <h2>My Rented Flats</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
        <tr>
            <th>Flat Ref. Number</th>
            <th>Monthly Rent</th>
            <th>Rental Start Date</th>
            <th>Rental End Date</th>
            <th>Location</th>
            <th>Owner Name</th>
            <th>Rental Status</th>
        </tr>
        </thead>
        <tbody>
       <?php foreach ($rentals as $rental): 
    $rentalStatus = ($rental['RentalStartDate'] <= $today && $today <= $rental['RentalEndDate']) ? 'Current' : 'Past';
?>
    <tr>
        <td>
            <form action="FlatDetail.php" method="get">
                <input type="hidden" name="id" value="<?php echo $rental['ApartmentID']; ?>">
                <input type="submit" value="<?php echo $rental['ApartmentID']; ?> "class="link-button">
            </form>
        </td>
        <td><?php echo $rental['MonthlyRent']; ?></td>
        <td><?php echo $rental['RentalStartDate']; ?></td>
        <td><?php echo $rental['RentalEndDate']; ?></td>
        <td><?php echo $rental['City'] ?? ''; ?></td>
        <td>
     <a href="userProfile.php?id=<?= $rental['OwnerID'] ?>&type=owner" class="link-button">
  <?= $rental['OwnerName'] ?>
</a>


            </a>
        </td>
        <td><?php echo $rentalStatus; ?></td>
    </tr>
<?php endforeach; ?>

        </tbody>
    </table>
</main>

</section>

<?php include('footer.php'); ?>

</body>
</html>

