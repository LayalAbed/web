<?php
session_start();
include('database.inc.php');

if (!isset($_SESSION['customer_id'])) {
    $_SESSION['last_page'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit();
}

if (!isset($_GET['apartmentID'])) {
    echo "Apartment ID is missing.";
    exit();
}

$apartmentID = $_GET['apartmentID'];

$sql = "SELECT A.ApartmentID, A.ApartmentNumber, A.Description, 
               A.ExtraAmenities, A.MonthlyRent,
               M.Title AS MarketingTitle, M.Description AS MarketingDescription,
               OA.FlatOrHouseNo, OA.StreetName, OA.City, OA.PostalCode,
               O.OwnerName, O.NationalID, O.EmailAddress
        FROM apartment_home A
        JOIN owner O ON A.OwnerID = O.OwnerID
        JOIN owneraddress OA ON O.OwnerID = OA.OwnerID
        LEFT JOIN marketing_info M ON A.ApartmentID = M.ApartmentID
        WHERE A.ApartmentID = :apartmentID";

$stmt = $pdo->prepare($sql);
$stmt->execute(['apartmentID' => $apartmentID]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "Apartment not found.";
    exit();
}

$error = '';
$success = '';

function isOverlapping($start1, $end1, $start2, $end2) {
    return !($end1 < $start2 || $start1 > $end2);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    if (!$start_date || !$end_date) {
        $error = "Please enter both start and end dates.";
    } elseif ($start_date > $end_date) {
        $error = "Start date must be before end date.";
    } else {
        $sqlRentals = "SELECT RentalStartDate, RentalEndDate FROM rental WHERE ApartmentID = :apartmentID";
        $stmtRentals = $pdo->prepare($sqlRentals);
        $stmtRentals->execute(['apartmentID' => $apartmentID]);
        $rentals = $stmtRentals->fetchAll(PDO::FETCH_ASSOC);

        $isAvailable = true;
        foreach ($rentals as $rental) {
            if (isOverlapping($start_date, $end_date, $rental['RentalStartDate'], $rental['RentalEndDate'])) { //8  to chik if  has any eroro for start and end date*/
                $isAvailable = false;
                break;
            }
        }

        if (!$isAvailable) {
            $error = "The apartment is already rented during the selected period.";
        } else { /* if they not avalible it save in sscion */
            $start = new DateTime($start_date);
            $end = new DateTime($end_date);
            $interval = $start->diff($end);

            $months = $interval->m + ($interval->y * 12);
            if ($interval->d > 0) {
                $months++;
            }
            if ($months == 0) {
                $months = 1;
            }

            $monthlyRent = (float)($row['MonthlyRent'] ?? 0);
            $totalAmount = $months * $monthlyRent;

            $_SESSION['rental_request'] = [
                'apartmentID' => $apartmentID,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'months' => $months,
                'totalAmount' => $totalAmount
            ];
            header('Location: confirm_rent.php');
            exit();
        }
    }
}

include('header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Rent Apartment Form</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<section class="container">
    <?php include('navigation.php'); ?>

    <main>
        <h2>Rent Apartment Form</h2>

        <form action="" method="post" class="Step1">
            <fieldset>
                <legend>Apartment Details</legend>
                <p><strong>Reference ID:</strong> <em><?= htmlspecialchars($row['ApartmentID'] ?? ''); ?></em></p>
                <input type="hidden" name="apartmentID" value="<?= htmlspecialchars($row['ApartmentID'] ?? ''); ?>">
                <p><strong>Flat Number:</strong> <em><?= htmlspecialchars($row['ApartmentNumber'] ?? ''); ?></em></p>
                <p><strong>Description:</strong><br><em><?= htmlspecialchars($row['Description'] ?? ''); ?></em></p>
                <p><strong>Extra Amenities:</strong><br><em><?= htmlspecialchars($row['ExtraAmenities'] ?? ''); ?></em></p>
                <p><strong>Marketing Info Title:</strong><br><em><?= htmlspecialchars($row['MarketingTitle'] ?? ''); ?></em></p>
                <p><strong>Marketing Info Description:</strong><br><em><?= htmlspecialchars($row['MarketingDescription'] ?? ''); ?></em></p>
                <p><strong>Address:</strong><br>
                    <em><?= htmlspecialchars(($row['FlatOrHouseNo'] ?? '') . ', ' . ($row['StreetName'] ?? '') . ', ' . ($row['City'] ?? '') . ' - ' . ($row['PostalCode'] ?? '')); ?></em></p>
            </fieldset><br>

            <fieldset>
                <legend>Owner Details</legend>
                <p><strong>Owner Name:</strong> <em><?= htmlspecialchars($row['OwnerName'] ?? ''); ?></em></p>
                <p><strong>National ID:</strong> <em><?= htmlspecialchars($row['NationalID'] ?? ''); ?></em></p>
                <p><strong>Email Address:</strong> <em><?= htmlspecialchars($row['EmailAddress'] ?? ''); ?></em></p>
            </fieldset><br>

            <fieldset>
                <legend>Rental Period</legend>
                <label>Start Date:</label>
                <input type="date" name="start_date" required>
                <br><br>
                <label>End Date:</label>
                <input type="date" name="end_date" required>
            </fieldset><br>

            <?php if ($error): ?>
                <div class="color_masege_false"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="color_masege_true"><?= htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <button type="submit" name="submit" class="link-button">Rent</button>
        </form>
    </main>
</section>

<?php include('footer.php'); ?>
</body>
</html>
