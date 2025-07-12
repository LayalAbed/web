<?php
session_start();
include('database.inc.php'); // Include DB connection or functions
    include('header.php'); 


// Get search inputs from URL parameters, or set to null if not present
$fromDate = $_GET['from_date'] ?? null;
$toDate = $_GET['to_date'] ?? null;
$location = $_GET['location'] ?? null;
$availableDate = $_GET['available_date'] ?? null;
$ownerId = $_GET['owner_id'] ?? null;
$customerId = $_GET['customer_id'] ?? null;

// Build the SQL query with dynamic filters
$sql = "SELECT 
            a.ApartmentID, a.MonthlyRent, r.RentalStartDate, r.RentalEndDate,
            oa.City AS OwnerCity, ca.City AS CustomerCity,
            o.OwnerName, o.OwnerID,
            c.Name AS CustomerName, c.CustomerID
        FROM apartment_home a
        JOIN rental r ON a.ApartmentID = r.ApartmentID
        JOIN owner o ON a.OwnerID = o.OwnerID
        LEFT JOIN owneraddress oa ON o.OwnerID = oa.OwnerID
        LEFT JOIN customer c ON r.CustomerID = c.CustomerID
        LEFT JOIN customeraddress ca ON c.CustomerID = ca.CustomerID
        WHERE 1 = 1";

$params = [];

if ($fromDate && $toDate) {
    $sql .= " AND r.RentalStartDate >= ? AND r.RentalEndDate <= ?";
    $params[] = $fromDate;
    $params[] = $toDate;
}

if ($location) {
    $sql .= " AND oa.City = ?";
    $params[] = $location;
}

if ($availableDate) {
    $sql .= " AND r.RentalStartDate <= ? AND r.RentalEndDate >= ?";
    $params[] = $availableDate;
    $params[] = $availableDate;
}

if ($ownerId) {
    $sql .= " AND o.OwnerID = ?";
    $params[] = $ownerId;
}

if ($customerId) {
    $sql .= " AND c.CustomerID = ?";
    $params[] = $customerId;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$flats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apartment Search</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<section class="container">
    <?php include('navigation.php'); ?> <!-- Navigation menu -->

    <main>
 <section>
    <h2>Search Apartments</h2> <br></br>
    <form method="get" action="">
        <label for="from_date">From Date:</label>
        <input type="date" id="from_date" name="from_date" value="<?= $fromDate ?? '' ?>">

        <label for="to_date">To Date:</label>
        <input type="date" id="to_date" name="to_date" value="<?= $toDate ?? '' ?>">

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" value="<?= $location ?? '' ?>">

        <label for="available_date">Available Date:</label>
        <input type="date" id="available_date" name="available_date" value="<?= $availableDate ?? '' ?>">

        <label for="owner_id">Owner ID:</label>
        <input type="text" id="owner_id" name="owner_id" value="<?= $ownerId ?? '' ?>">

        <label for="customer_id">Customer ID:</label>
        <input type="text" id="customer_id" name="customer_id" value="<?= $customerId ?? '' ?>">

        <button type="submit" class="link-button" >Search</button>
    </form>
</section>


        <section>
            <br></br>  <h2>Rented apartments</h2> <br></br>
            <table>
                <thead>
                    <tr>
                        <th>Flat Reference</th>
                        <th>Monthly Rent</th>
                        <th>Rental Start</th>
                        <th>Rental End</th>
                        <th>Location</th>
                        <th>Owner Name</th>
                        <th>Customer Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($flats)): ?>
                        <?php foreach ($flats as $flat): ?>
                            <tr>
                                <td>
                                    <a href="FlatDetail.php?id=<?= $flat['ApartmentID'] ?>"  class="link-button">
                                        <?= $flat['ApartmentID'] ?>
                                    </a>
                                </td>
                                <td><?= $flat['MonthlyRent'] ?></td>
                                <td><?= $flat['RentalStartDate'] ?></td>
                                <td><?= $flat['RentalEndDate'] ?></td>
                                <td><?= $flat['OwnerCity'] ?></td>
                                <td>
    <a href="userProfile.php?id=<?= $flat['OwnerID'] ?>&type=owner"  class="link-button">
        <?= $flat['OwnerName'] ?>
    </a>
</td>
<td>
    <a href="userProfile.php?id=<?= $flat['CustomerID'] ?>&type=customer"  class="link-button">
        <?= $flat['CustomerName'] ?>
    </a>
</td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">No results found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</section>

<?php include('footer.php'); ?>

</body>
</html>
