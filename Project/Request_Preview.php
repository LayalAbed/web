<?php
session_start();
include('header.php');
include('database.inc.php');

if (!isset($_SESSION['customer_id'])) {
    die("You must be logged in to request a preview appointment.");
}

$customerID = $_SESSION['customer_id'];
$apartmentID = isset($_GET['apartmentID']) ? intval($_GET['apartmentID']) : 0;
if ($apartmentID == 0) {
    die("Apartment ID is missing.");
}

function dayNameToNum($dayName) {
    $days = ['Sunday'=>0, 'Monday'=>1, 'Tuesday'=>2, 'Wednesday'=>3, 'Thursday'=>4, 'Friday'=>5, 'Saturday'=>6];
    return $days[$dayName];
}

$todayNum = date('w'); 
$sql = "SELECT ScheduleID, PreviewDay, StartTime, EndTime 
        FROM apartmentpreviewschedule
        WHERE ApartmentID = :apartmentID";
$stmt = $pdo->prepare($sql);
$stmt->execute(['apartmentID' => $apartmentID]);
$schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sqlAllBookings = "SELECT ScheduleID FROM previewbookings WHERE Status IN ('Pending', 'Approved')";
$stmtAllBookings = $pdo->prepare($sqlAllBookings);
$stmtAllBookings->execute();
$allBookedSlots = $stmtAllBookings->fetchAll(PDO::FETCH_COLUMN);

$sqlBookings = "SELECT ScheduleID FROM previewbookings WHERE CustomerID = :customerID AND Status IN ('Pending', 'Approved')";
$stmtBookings = $pdo->prepare($sqlBookings);
$stmtBookings->execute(['customerID' => $customerID]);
$bookedSlots = $stmtBookings->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['schedule_id'])) {
    $scheduleID = intval($_POST['schedule_id']);
    if (!in_array($scheduleID, $bookedSlots) && !in_array($scheduleID, $allBookedSlots)) {
        $insertSql = "INSERT INTO previewbookings (CustomerID, ScheduleID, BookingDate, Status) 
                      VALUES (:customerID, :scheduleID, CURDATE(), 'Pending')";
        $stmtInsert = $pdo->prepare($insertSql);
        $success = $stmtInsert->execute([
            'customerID' => $customerID,
            'scheduleID' => $scheduleID
        ]);
 if ($success) {
    // 1. Find the Owner of the Apartment
    $stmtOwner = $pdo->prepare("
        SELECT o.OwnerID, o.OwnerName
        FROM apartment_homea
        JOIN owner o ON a.OwnerID = o.OwnerID
        WHERE a.ApartmentID = :apartmentID
    ");
    $stmtOwner->execute(['apartmentID' => $apartmentID]);
    $owner = $stmtOwner->fetch();

    if ($owner) {
        // 2. Prepare the message
        $title = "Preview Appointment Request";
        $body = "A customer has requested a preview for your flat (ID: $apartmentID). Please log in to accept or decline.";
        $senderType = "Customer";
        $senderID = $customerID;
        $recipientType = "Owner";
        $recipientID = $owner['OwnerID'];

        // 3. Insert message into Messages table
        $stmtMsg = $pdo->prepare("
            INSERT INTO messages 
            (Title, MessageBody, SenderType, SenderID, RecipientType, RecipientID, MessageType)
            VALUES
            (:title, :body, :senderType, :senderID, :recipientType, :recipientID, 'PreviewRequest')
        ");
        $stmtMsg->execute([
            ':title' => $title,
            ':body' => $body,
            ':senderType' => $senderType,
            ':senderID' => $senderID,
            ':recipientType' => $recipientType,
            ':recipientID' => $recipientID
        ]);
    }

    echo "<p class='color_masege_true'>Preview request sent to the owner. Awaiting approval.</p>";
    $bookedSlots[] = $scheduleID;
    $allBookedSlots[] = $scheduleID;
}
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Flat Preview Appointment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<section class="container">

    <?php include('navigation.php'); ?>

    <main>
<h2>Available Preview Slots for Apartment #<?= htmlspecialchars($apartmentID) ?></h2>

<table class="preview-table">
    <tr>
        <th>Day</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Booking</th>
    </tr>
    <?php
    $currentTime = date('H:i:s');
    foreach ($schedules as $row):
        $dayNum = dayNameToNum($row['PreviewDay']);

        if ($dayNum < $todayNum) continue;
        if ($dayNum == $todayNum && $currentTime > $row['EndTime']) continue;

        $isBooked = in_array($row['ScheduleID'], $allBookedSlots);
    ?>
    <tr class="<?= $isBooked ? 'booked' : '' ?>">
        <td><?= htmlspecialchars($row['PreviewDay']) ?></td>
        <td><?= substr($row['StartTime'], 0, 5) ?></td>
        <td><?= substr($row['EndTime'], 0, 5) ?></td>
        <td>
            <form method="post">
                <input type="hidden" name="schedule_id" value="<?= $row['ScheduleID'] ?>">
                <button type="submit" class="link-button" <?= $isBooked ? 'disabled' : '' ?>>Confirm</button>

                    <?= $isBooked ? 'Booked' : 'Book' ?>
                </button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
 </main>
</section>

<?php include('footer.php'); ?>
</body>
</html>
