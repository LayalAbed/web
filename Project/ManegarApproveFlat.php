<?php
session_start();
include('database.inc.php');

if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['pending_id'], $_GET['message_id'])) {
    die("Invalid access.");
}

$pendingId = (int)$_GET['pending_id'];
$messageId = (int)$_GET['message_id'];
$managerId = $_SESSION['manager_id'];

// Fetch pending apartment data
$stmt = $pdo->prepare("SELECT * FROM pendingapartments WHERE TempID = ?");
$stmt->execute([$pendingId]);
$pending = $stmt->fetch();

if (!$pending) {
    die("Pending apartment not found.");
}

// Insert apartment into permanent table
$insert = $pdo->prepare("
    INSERT INTO apartment_home (
        ApartmentNumber, NumberOfRooms, NumberOfBathrooms, IsFurnished,
        MonthlyRent, Area, HeatingSystem_AirConditioning, AccessControl,
        ExtraAmenities, Description, OwnerID, IsApproved
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
");

$insert->execute([
    $pending['ApartmentNumber'],
    $pending['NumberOfRooms'],
    $pending['NumberOfBathrooms'],
    $pending['IsFurnished'],
    $pending['MonthlyRent'],
    $pending['Area'],
    $pending['HeatingSystem_AirConditioning'],
    $pending['AccessControl'],
    $pending['ExtraAmenities'],
    $pending['Description'],
    $pending['OwnerID']
]);

$newApartmentId = $pdo->lastInsertId(); // Get the new ApartmentID

// Insert marketing info
$insertMarketing = $pdo->prepare("
    INSERT INTO marketing_info (ApartmentID, Title, Description, URL)
    VALUES (?, ?, ?, ?)
");
$insertMarketing->execute([
    $newApartmentId,
    $pending['MarketingTitle'],
    $pending['MarketingDesc'],
    $pending['MarketingURL']
]);

// Process and rename images
$images = $pdo->prepare("SELECT * FROM pendingapartmentimages WHERE TempID = ?");
$images->execute([$pendingId]);

$sourceDir = 'pending_images/';
$targetDir = 'image/';

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

foreach ($images as $img) {
    $originalName = $img['ImageName'];
    $imageNumber = $img['ImageNumber'];
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);

    // Rename image: ApartmentID-ImageNumber.extension
    $newImageName = $newApartmentId . "-" . $imageNumber . "." . $extension;

    $source = $sourceDir . $originalName;
    $destination = $targetDir . $newImageName;

    // Insert renamed image info into permanent table
    $pdo->prepare("
        INSERT INTO apartment_images (ApartmentID, ImageNumber, ImageName)
        VALUES (?, ?, ?)
    ")->execute([
        $newApartmentId,
        $imageNumber,
        $newImageName
    ]);

    // Move image to permanent folder
    if (file_exists($source)) {
        copy($source, $destination);
        // unlink($source); // Uncomment to delete the original image
    }
}

// Delete temporary records
$pdo->prepare("DELETE FROM pendingapartments WHERE TempID = ?")->execute([$pendingId]);
$pdo->prepare("DELETE FROM pendingapartmentimages WHERE TempID = ?")->execute([$pendingId]);

// Mark original message as read
$pdo->prepare("UPDATE messages SET IsRead = 1 WHERE MessageID = ?")->execute([$messageId]);

// Send approval notification to owner
$messageBody = "Your apartment has been approved by the manager and is now listed on the platform.";

$insertMsg = $pdo->prepare("
    INSERT INTO messages (
        Title, MessageBody, SenderType, SenderID,
        RecipientType, RecipientID, MessageType
    ) VALUES (?, ?, ?, ?, ?, ?, ?)
");

$insertMsg->execute([
    "Apartment Approved",
    $messageBody,
    "Manager",
    $managerId,
    "Owner",
    $pending['OwnerID'],
    "ApprovalNotification"
]);

header("Location: masseges.php");
exit();
?>
