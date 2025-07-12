<?php
session_start();
include('header.php');
include('database.inc.php');

$maxPrice = isset($_GET['maxPrice']) && is_numeric($_GET['maxPrice']) ? intval($_GET['maxPrice']) : null;
$city = isset($_GET['city']) ? trim($_GET['city']) : '';
$bedrooms = isset($_GET['bedrooms']) && is_numeric($_GET['bedrooms']) ? intval($_GET['bedrooms']) : null;
$bathrooms = isset($_GET['bathrooms']) && is_numeric($_GET['bathrooms']) ? intval($_GET['bathrooms']) : null;
$furnished = isset($_GET['furnished']) ? $_GET['furnished'] : '';

$allowedSortColumns = ['ApartmentID', 'MonthlyRent', 'City', 'NumberOfRooms', 'NumberOfBathrooms'];

if (isset($_GET['sort']) && in_array($_GET['sort'], $allowedSortColumns)) {
    setcookie('sortColumn', $_GET['sort'], time() + 3600, "/");
}
if (isset($_GET['order']) && in_array(strtolower($_GET['order']), ['asc', 'desc'])) {
    setcookie('sortOrder', $_GET['order'], time() + 3600, "/");
}

$sortColumn = isset($_GET['sort']) && in_array($_GET['sort'], $allowedSortColumns)
    ? $_GET['sort']
    : (isset($_COOKIE['sortColumn']) && in_array($_COOKIE['sortColumn'], $allowedSortColumns) ? $_COOKIE['sortColumn'] : 'MonthlyRent');

$sortOrder = (isset($_GET['order']) && strtolower($_GET['order']) === 'desc')
    ? 'DESC'
    : (isset($_COOKIE['sortOrder']) && strtolower($_COOKIE['sortOrder']) === 'desc' ? 'DESC' : 'ASC');

$whereClauses = [];
$params = [];

if ($maxPrice !== null) {
    $whereClauses[] = "a.MonthlyRent <= :maxPrice";
    $params[':maxPrice'] = $maxPrice;
}
if ($city !== '') {
    $whereClauses[] = "oa.City LIKE :city";
    $params[':city'] = "%$city%";
}
if ($bedrooms !== null) {
    $whereClauses[] = "a.NumberOfRooms = :bedrooms";
    $params[':bedrooms'] = $bedrooms;
}
if ($bathrooms !== null) {
    $whereClauses[] = "a.NumberOfBathrooms = :bathrooms";
    $params[':bathrooms'] = $bathrooms;
}
if ($furnished === 'yes') {
    $whereClauses[] = "a.IsFurnished = 1";
} elseif ($furnished === 'no') {
    $whereClauses[] = "a.IsFurnished = 0";
}

// if the apartment dos not isext in rentel table or the rent date ecd or the rent date not start yte
$sql = "
SELECT
    a.ApartmentID,
    a.MonthlyRent,
    oa.City,
    a.NumberOfRooms,
    a.NumberOfBathrooms,
    ai.ImageName,
    COALESCE(
        (SELECT MIN(r.RentalStartDate)
         FROM rental r 
         WHERE r.ApartmentID = a.ApartmentID
           AND r.RentalStartDate > CURDATE()
        ),
        CURDATE()
    ) AS AvailabilityDate
FROM apartment_home a
LEFT JOIN owneraddress oa ON a.OwnerID = oa.OwnerID
LEFT JOIN apartment_images ai ON a.ApartmentID = ai.ApartmentID AND ai.ImageNumber = 1
WHERE NOT EXISTS (
    SELECT 1 FROM rental r
    WHERE r.ApartmentID = a.ApartmentID
      AND r.RentalStartDate <= CURDATE()
      AND r.RentalEndDate >= CURDATE()
)
";

if (count($whereClauses) > 0) {
    $sql .= " AND " . implode(" AND ", $whereClauses);
}

$sql .= " ORDER BY $sortColumn $sortOrder";


$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

<?php include('navigation.php'); ?>
    <main>
        <section>
            <br></br>
            <h2>Search Apartments</h2> 
            <form method="GET" action="SearchFlat.php">
                <label for="maxPrice">Max Price:</label>
                <input type="number" id="maxPrice" name="maxPrice" value="<?= htmlspecialchars($maxPrice ?? '') ?>" min="0" >

                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?= htmlspecialchars($city ?? '') ?>" >

                <label for="bedrooms">Bedrooms:</label>
                <input type="number" id="bedrooms" name="bedrooms" value="<?= htmlspecialchars($bedrooms ?? '') ?>" min="0" >

                <label for="bathrooms">Bathrooms:</label>
                <input type="number" id="bathrooms" name="bathrooms" value="<?= htmlspecialchars($bathrooms ?? '') ?>" min="0" >

                <label for="furnished">Furnished:</label>
                <select id="furnished" name="furnished">
                    <option value="" <?= $furnished === '' ? 'selected' : '' ?>>Any</option>
                    <option value="yes" <?= $furnished === 'yes' ? 'selected' : '' ?>>Yes</option>
                    <option value="no" <?= $furnished === 'no' ? 'selected' : '' ?>>No</option>
                </select>

                <button type="submit" class="link-button">Search</button>
            </form>
        </section>

        <section>
            <br></br>
            <h2>Available Apartments</h2> 
            <table>
                <thead>
                    <tr>
                        <?php
                        $columns = [
                            'ApartmentID' => 'Apartment ID',
                            'MonthlyRent' => 'Monthly Rent',
                            'AvailabilityDate' => 'Availability Date',
                            'City' => 'City',
                            'NumberOfRooms' => 'Bedrooms',
                            'Image' => 'Image'
                        ];
                        foreach ($columns as $colKey => $colTitle) {
                            if ($colKey === 'Image') {
                                echo "<th>$colTitle</th>";
                                continue;
                            }
                            $icon = '';
                            if ($sortColumn === $colKey) {
                                $icon = $sortOrder === 'ASC' ? '▲ ' : '▼ ';
                            }
                            $newOrder = ($sortColumn === $colKey && $sortOrder === 'ASC') ? 'desc' : 'asc';
                            $queryParams = $_GET;
                            $queryParams['sort'] = $colKey;
                            $queryParams['order'] = $newOrder;
                            $url = htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($queryParams));
                            echo "<th><a href=\"$url\">$icon$colTitle</a></th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($results): ?>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['ApartmentID']) ?></td>
                                <td><?= htmlspecialchars($row['MonthlyRent']) ?></td>
                                <td><?= htmlspecialchars($row['AvailabilityDate']) ?></td>
                                <td><?= htmlspecialchars($row['City']) ?></td>
                                <td><?= htmlspecialchars($row['NumberOfRooms']) ?></td>
                                <td>
                                    <?php if ($row['ImageName']): ?>
                                        <a href="FlatDetail.php?id=<?= htmlspecialchars($row['ApartmentID']) ?>" target="_blank">
                                           <img src="image/<?= htmlspecialchars($row['ImageName']) ?>" alt="Apartment Image" class="clickable" />
                                        </a>
                                    <?php else: ?>
                                        No image
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No results found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</section>

<?php include('footer.php'); ?>

</body>
</html>
