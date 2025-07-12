<?php
session_start();
include('database.inc.php');

if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['message_id'])) {
    die("Missing message ID.");
}

$messageID = intval($_GET['message_id']);

// Fetch the message
$stmt = $pdo->prepare("SELECT * FROM messages WHERE MessageID = :id AND MessageType = 'RentNotification'");
$stmt->execute([':id' => $messageID]);
$message = $stmt->fetch();

if (!$message) {
    die("Message not found or invalid type.");
}

// Mark as read
$pdo->prepare("UPDATE messages SET IsRead = 1 WHERE MessageID = :id")->execute([':id' => $messageID]);

// Extract Customer ID
$customerID = $message['SenderID'];
$apartmentID = 0;

// Fetch latest rental for this customer
$stmtRental = $pdo->prepare("
    SELECT * FROM rental
    WHERE CustomerID = :customerID 
    ORDER BY RentalStartDate DESC

");
$stmtRental->execute([':customerID' => $customerID]);
$rental = $stmtRental->fetch();

if (!$rental) {
    die("No rental found.");
}
$apartmentID = $rental['ApartmentID'];

// Fetch apartment
$stmtApt = $pdo->prepare("SELECT * FROM apartment_home WHERE ApartmentID = ?");
$stmtApt->execute([$apartmentID]);
$apartment = $stmtApt->fetch();

// Fetch owner
$stmtOwner = $pdo->prepare("SELECT * FROM owner WHERE OwnerID = ?");
$stmtOwner->execute([$apartment['OwnerID']]);
$owner = $stmtOwner->fetch();

// Fetch customer
$stmtCustomer = $pdo->prepare("SELECT * FROM customer WHERE CustomerID = ?");
$stmtCustomer->execute([$customerID]);
$customer = $stmtCustomer->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rental Details</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        h2 { color: #333; }
        fieldset { margin-bottom: 20px; border: 1px solid #ccc; padding: 15px; }
        legend { font-weight: bold; }
    </style>
</head>
<body>

<h2>Rental Details</h2>

<fieldset>
    <legend>Flat Details</legend>
    <p><strong>ID:</strong> <?= $apartment['ApartmentID'] ?></p>
    <p><strong>Apartment Number:</strong> <?= htmlspecialchars($apartment['ApartmentNumber']) ?></p>
    <p><strong>Number of Rooms:</strong> <?= $apartment['NumberOfRooms'] ?></p>
    <p><strong>Number of Bathrooms:</strong> <?= $apartment['NumberOfBathrooms'] ?></p>
    <p><strong>Monthly Rent:</strong> <?= $apartment['MonthlyRent'] ?> JD</p>
    <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($apartment['Description'])) ?></p>
</fieldset>

<fieldset>
    <legend>Owner Details</legend>
    <p><strong>Name:</strong> <?= htmlspecialchars($owner['OwnerName']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($owner['EmailAddress']) ?></p>
    <p><strong>Mobile Number:</strong> <?= htmlspecialchars($owner['MobileNumber']) ?></p>
    <p><strong>National ID:</strong> <?= htmlspecialchars($owner['NationalID']) ?></p>
</fieldset>

<fieldset>
    <legend>Customer Details</legend>
    <p><strong>Name:</strong> <?= htmlspecialchars($customer['Name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($customer['Email']) ?></p>
    <p><strong>Mobile Number:</strong> <?= htmlspecialchars($customer['MobileNumber']) ?></p>
    <p><strong>National ID:</strong> <?= htmlspecialchars($customer['NationalID']) ?></p>
</fieldset>

<a href="masseges.php">Back to Inbox</a>

</body>
</html>
