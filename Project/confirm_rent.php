<?php
session_start();
include('header.php');
include('database.inc.php');

// Check if customer is logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit();
}

// Check if rental data exists in the session
if (!isset($_SESSION['rental_request'])) {  /* data of rentel*/
    echo "No rental data found. Please go back and fill the form.";
    exit();
}

$rental = $_SESSION['rental_request'];
$apartmentID = $rental['apartmentID'];
$start_date = $rental['start_date'];
$end_date = $rental['end_date'];
$totalAmount = $rental['totalAmount'];

$error = '';
$successMessage = '';
$apartment = null;

// Fetch apartment data
$stmt = $pdo->prepare("SELECT ApartmentNumber, MonthlyRent, OwnerID FROM apartment_home WHERE ApartmentID = ?");
$stmt->execute([$apartmentID]);
$apartment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$apartment) {
    $error = "Apartment not found.";
}

// handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    $cardNumber = $_POST['cardNumber'] ?? '';

    // validate credit card number (exactly 9 digits)
    if (!preg_match('/^\d{9}$/', $cardNumber)) {
        $error = "Credit card number must be exactly 9 digits.";
    } else {
        // check if the credit card exists and belongs to the customer
        $stmtCard = $pdo->prepare("SELECT * FROM creditcard WHERE CardNumber = ? AND CustomerID = ?");
        $stmtCard->execute([$cardNumber, $_SESSION['customer_id']]);
        $existingCard = $stmtCard->fetch(PDO::FETCH_ASSOC);

        if (!$existingCard) {
            $error = "Credit card not found or does not belong to you.";
        } else {
            // Send approval request to owner instead of direct rental insert
            try {
                $messageBody = "Customer #" . $_SESSION['customer_id'] . 
                    " requests to rent apartment #" . $apartmentID .
                    " from $start_date to $end_date. Total rent: $" . number_format($totalAmount, 2) .
                    ". Please approve the rental.";

                $stmtMsg = $pdo->prepare("
                    INSERT INTO messages 
                    (Title, MessageBody, SenderType, SenderID, RecipientType, RecipientID, MessageType)
                    VALUES 
                    (:title, :body, 'Customer', :senderID, 'Owner', :recipientID, 'RentApprovalRequest')
                ");
                $stmtMsg->execute([
                    ':title' => "Rental Approval Needed",
                    ':body' => $messageBody,
                    ':senderID' => $_SESSION['customer_id'],
                    ':recipientID' => $apartment['OwnerID']
                ]);

                // clear rental session data after request sent
                unset($_SESSION['rental_request']);

                // Store feedback message to customer
                $successMessage = "Your rental request has been sent to the apartment owner. Please wait for approval.";

            } catch (PDOException $e) {
                $error = "Error sending rental request: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Rental</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <?php include('navigation.php'); ?>
    <main>
        <h2>Confirm Rental</h2>

        <?php if (!empty($successMessage)): ?>
            <div class="color_masege_true"><?= $successMessage ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="color_masege_false"><?= $error ?></div>
        <?php endif; ?>

        <?php if (!$error && empty($successMessage)): ?>
            <p><strong>Apartment Number:</strong> <?= htmlspecialchars($apartment['ApartmentNumber'] ?? 'N/A'); ?></p>
            <p><strong>Rental Period:</strong> From <?= htmlspecialchars($start_date); ?> To <?= htmlspecialchars($end_date); ?></p>
            <p><strong>Total Amount:</strong> $<?= number_format($totalAmount, 2); ?></p>

            <form method="post">
                <label>Credit Card Number (9 digits):</label><br>
                <input type="text" name="cardNumber" required pattern="\d{9}" title="Exactly 9 digits"><br><br>

                <button type="submit" name="confirm">Submit Rental Request</button>
            </form>
        <?php endif; ?>
    </main>
</div>
<?php include('footer.php'); ?>
</body>
</html>
