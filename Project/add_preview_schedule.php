<?php
session_start();
include('header.php');
include('database.inc.php');

if (!isset($_SESSION['owner_id'])) {
    header("Location: login.php");
    exit();
}

$ownerID = $_SESSION['owner_id'];
$message = "";

// get the  flat for the owner 
$stmt = $pdo->prepare("SELECT ApartmentID, ApartmentNumber FROM apartment_home WHERE OwnerID = ?");
$stmt->execute([$ownerID]);
$apartments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// when set the form 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apartmentID = $_POST['apartment_id'] ?? '';
    $previewDay = $_POST['preview_day'] ?? '';
    $startTime = $_POST['start_time'] ?? '';
    $endTime = $_POST['end_time'] ?? '';

    if ($apartmentID && $previewDay && $startTime && $endTime) {
        if ($startTime >= $endTime) {
            $message = "Start time must be before end time.";
        } else {
            // chick if we dont have anther review in this time 
            $stmtCheck = $pdo->prepare("
                SELECT COUNT(*) FROM apartmentpreviewschedule
                WHERE ApartmentID = ? AND PreviewDay = ? AND StartTime = ? AND EndTime = ?
            ");
            $stmtCheck->execute([$apartmentID, $previewDay, $startTime, $endTime]);

            if ($stmtCheck->fetchColumn() > 0) {
                $message = "This preview schedule already exists.";
            } else {
                // inter to the data base
                $stmt = $pdo->prepare("INSERT INTO apartmentpreviewschedule (ApartmentID, PreviewDay, StartTime, EndTime) VALUES (?, ?, ?, ?)");
                $stmt->execute([$apartmentID, $previewDay, $startTime, $endTime]);
                $message = "Preview schedule added successfully.";
            }
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Preview Schedule</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <?php include('navigation.php'); ?>

    <main>
        <h2>Add Preview Schedule</h2>
        <form method="POST">
            <label>Select Apartment:</label><br>
            <select name="apartment_id" required>
                <option value="">-- Choose Apartment --</option>
                <?php foreach ($apartments as $apt): ?>
                    <option value="<?= $apt['ApartmentID'] ?>"><?=($apt['ApartmentNumber']) ?> (ID: <?= $apt['ApartmentID'] ?>)</option>
                <?php endforeach; ?>
            </select><br><br>
           
            <label>Preview Day:</label><br>
            <select name="preview_day" required>
                <option value="">-- Choose Day --</option>
                <option value="Sunday">Sunday</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
            </select><br><br>

            <label>Start Time:</label><br>
            <input type="time" name="start_time" required><br><br>

            <label>End Time:</label><br>
            <input type="time" name="end_time" required><br><br>

            <button class= "link-button" type="submit">Add Schedule</button>

            <?php if ($message): ?>
                <?php if (strpos($message, 'successfully') !== false): ?>
                    <p class="color_masege_true"><?= htmlspecialchars($message) ?></p>
                <?php else: ?>
                    <p class="color_masege_false"><?= htmlspecialchars($message) ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </form>
    </main>
</div>
<?php include('footer.php'); ?>
</body>
</html>

