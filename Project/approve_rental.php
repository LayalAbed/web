<?php
session_start();
include('database.inc.php');

if (!isset($_SESSION['owner_id'])) {
    header("Location: login.php");
    exit();
}

$ownerID = $_SESSION['owner_id'];

if (!isset($_POST['message_id'])) {
    echo "No message selected.";
    exit();
}

$messageID = $_POST['message_id'];

$stmt = $pdo->prepare("SELECT * FROM messages WHERE MessageID = ? AND RecipientType = 'Owner' AND RecipientID = ?");
$stmt->execute([$messageID, $ownerID]);
$message = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$message || $message['MessageType'] != 'RentApprovalRequest') {
    echo "Invalid or unauthorized message.";
    exit();
}

preg_match('/Customer #(\d+).*apartment #(\d+).*from ([\d\-]+) to ([\d\-]+)/', $message['MessageBody'], $matches);

if (count($matches) != 5) {
    echo "Failed to parse rental request.";
    exit();
}

$customerID = $matches[1];
$apartmentID = $matches[2];
$startDate = $matches[3];
$endDate = $matches[4];

$stmtApt = $pdo->prepare("SELECT MonthlyRent, ApartmentNumber, Area FROM apartment_home WHERE ApartmentID = ?");
$stmtApt->execute([$apartmentID]);
$apartment = $stmtApt->fetch(PDO::FETCH_ASSOC);

$start = new DateTime($startDate);
$end = new DateTime($endDate);
$interval = $start->diff($end);

$months = $interval->m + ($interval->y * 12);
if ($interval->d > 0) $months++;
if ($months == 0) $months = 1;

$monthlyRent = (float)($apartment['MonthlyRent'] ?? 0);
$totalAmount = $months * $monthlyRent;

$insertRental = $pdo->prepare("INSERT INTO rental (CustomerID, ApartmentID, RentalStartDate, RentalEndDate, TotalAmount) VALUES (?, ?, ?, ?, ?)");
$insertRental->execute([$customerID, $apartmentID, $startDate, $endDate, $totalAmount]);

$stmtOwner = $pdo->prepare("SELECT OwnerName, MobileNumber FROM owner WHERE OwnerID = ?");
$stmtOwner->execute([$ownerID]);
$owner = $stmtOwner->fetch(PDO::FETCH_ASSOC);

$stmtCustomer = $pdo->prepare("SELECT Name, MobileNumber FROM customer WHERE CustomerID = ?");
$stmtCustomer->execute([$customerID]);
$customer = $stmtCustomer->fetch(PDO::FETCH_ASSOC);

$confirmationMessage = "Your apartment has been approved and successfully rented. You can collect the key from the owner: "
    . htmlspecialchars($owner['OwnerName']) . ", Mobile: " . htmlspecialchars($owner['MobileNumber']) . ".";

$managerMessage = "A flat has been rented!\n\n"
    . "Apartment #: " . $apartment['ApartmentNumber'] . "\n"
    . "Area: " . $apartment['Area'] . " m²\n"
    . "Monthly Rent: " . $apartment['MonthlyRent'] . "\n"
    . "Rental Period: $startDate to $endDate\n\n"
    . "Owner: " . $owner['OwnerName'] . ", Mobile: " . $owner['MobileNumber'] . "\n"
    . "Customer: " . $customer['Name'] . ", Mobile: " . $customer['MobileNumber'];

$managerID = 100005;


$stmtMsg = $pdo->prepare("INSERT INTO messages (Title, MessageBody, SenderType, SenderID, RecipientType, RecipientID, MessageType) VALUES 
    (:title, :body, 'Owner', :ownerid, :rtype, :rid, :mtype)");

$stmtMsg->execute([
    ':title' => "Rental Approved",
    ':body' => $confirmationMessage,
    ':ownerid' => $ownerID,
    ':rtype' => 'Customer',
    ':rid' => $customerID,
    ':mtype' => 'RentConfirm'
]);

$stmtMsg->execute([
    ':title' => "Flat Rented Notification",
    ':body' => $managerMessage,
    ':ownerid' => $ownerID,
    ':rtype' => 'Manager',
    ':rid' => $managerID,
    ':mtype' => 'RentNotification'
]);

$pdo->prepare("UPDATE messages SET IsRead = 1 WHERE MessageID = ?")->execute([$messageID]);

header("Location: masseges.php");
exit();
