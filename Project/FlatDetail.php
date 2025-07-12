<?php
session_start();
include('header.php');
include('database.inc.php');

$apartmentID = isset($_GET['id']) ? (int)$_GET['id'] : 0; /* save the id*/ 
if ($apartmentID <= 0) {
    die("Invalid apartment ID.");
}

$sql = "
SELECT 
    a.*,
    oa.FlatOrHouseNo, oa.StreetName, oa.City, oa.PostalCode,
    o.OwnerName,
    (SELECT GROUP_CONCAT(ImageName ORDER BY ImageNumber SEPARATOR ',') FROM apartment_images WHERE ApartmentID = a.ApartmentID) AS Images,
    (
        SELECT COUNT(*) 
        FROM rental
        WHERE ApartmentID = a.ApartmentID
          AND CURRENT_DATE >= RentalStartDate
          AND CURRENT_DATE < RentalEndDate
    ) AS CurrentRentalsCount
FROM apartment_home a
LEFT JOIN owner o ON a.OwnerID = o.OwnerID
LEFT JOIN owneraddress oa ON o.OwnerID = oa.OwnerID
WHERE a.ApartmentID = :apartmentID
LIMIT 1
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':apartmentID' => $apartmentID]);
$flat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$flat) {
    die("Apartment not found.");
}

$images = $flat['Images'] ? explode(',', $flat['Images']) : [];
while (count($images) < 3) {
    $images[] = 'no-image.jpg';
}

$isRented = $flat['CurrentRentalsCount'] > 0;

$sqlMarketing = "
SELECT Title, Description, URL 
FROM marketing_info
WHERE ApartmentID = :apartmentID
";

// $sqlMarketing = "
// SELECT MarketingTitle AS Title, MarketingDesc AS Description, MarketingURL AS URL 
// FROM PendingApartments 
// WHERE TempID = :pendingID
// ";

$stmtMarketing = $pdo->prepare($sqlMarketing);
$stmtMarketing->execute([':apartmentID' => $apartmentID]);
$marketingInfos = $stmtMarketing->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Apartment Detail - <?= htmlspecialchars($flat['ApartmentNumber']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
<?php include('navigation.php'); ?>

    <main>
        <section class="flatcard">
            <section class="flat-images">
                <?php foreach ($images as $i => $img): ?>
                    <figure>
                        <img src="image/<?= htmlspecialchars($img); ?>" alt="Apartment Photo <?= $i + 1 ?>">
                        <figcaption>Photo <?= $i + 1 ?></figcaption>
                    </figure>
                <?php endforeach; ?>
            </section>

            <section class="main-content-profile-card">
                <h2>Apartment #<?= htmlspecialchars($flat['ApartmentNumber']); ?></h2>
                <p><strong>Address:</strong> <?= htmlspecialchars($flat['FlatOrHouseNo'] . ', ' . $flat['StreetName'] . ', ' . $flat['City'] . ', ' . $flat['PostalCode']); ?></p>
                <p><strong>Price:</strong> $<?= number_format($flat['MonthlyRent']); ?> / month</p>
                <p><strong>Rental conditions:</strong> <?= $flat['IsFurnished'] ? 'Furnished' : 'Unfurnished'; ?></p>
                <p><strong>Bedrooms:</strong> <?= (int)$flat['NumberOfRooms']; ?></p>
                <p><strong>Bathrooms:</strong> <?= (int)$flat['NumberOfBathrooms']; ?></p>
                <p><strong>Size:</strong> <?= (float)$flat['Area']; ?> m²</p>
                <p><strong>Heating / Air Conditioning:</strong> <?= $flat['HeatingSystem_AirConditioning'] ? 'Available' : 'Not Available'; ?></p>
                <p><strong>Access Control:</strong> <?= htmlspecialchars($flat['AccessControl']); ?></p>
                <p><strong>Additional Features:</strong> <?= htmlspecialchars($flat['ExtraAmenities']); ?></p>
                <p><strong>Status:</strong>
                    <?php if ($isRented): ?>
                        <span class="rented">Rented</span>
                    <?php else: ?>
                        <span class="available">Available</span>
                    <?php endif; ?>
                </p>
            </section>
        </section>

        <aside>
            <?php
           $links = array_filter(explode(',', $flat['NearbyLinks'] ?? ''));
            if (!empty($links)):
            ?>
                <h3>Nearby Landmarks</h3>
                <ul>
                    <?php foreach ($links as $i => $link): ?>
                        <li><a href="<?= htmlspecialchars(trim($link)); ?>" target="_blank">Nearby Place <?= $i + 1 ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <div class="main-content-profile-card">
                <?php if (!empty(trim($flat['Description']))): ?>
                    <h3>Description</h3>
                    <p><?= nl2br(htmlspecialchars($flat['Description'])); ?></p>
                <?php endif; ?>
            </div>

            <?php if (!empty($marketingInfos)): ?>
                <br>
                <?php foreach ($marketingInfos as $info): ?>
                    <div class="main-content-profile-card">
                        <h3>Marketing Information</h3>
                        <h4><?= htmlspecialchars($info['Title']); ?></h4>
                        <p><?= nl2br(htmlspecialchars($info['Description'])); ?></p>
                        <?php if (!empty($info['URL'])): ?>
                            <p><a href="<?= htmlspecialchars($info['URL']); ?>" target="_blank">More Info</a></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </aside>

    </main>
</div>

<?php include('footer.php'); ?>

</body>
</html>
