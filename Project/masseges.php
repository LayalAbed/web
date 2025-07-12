<?php
session_start();
include('header.php');
include('database.inc.php');

if (isset($_SESSION['customer_id'])) {
    $userType = "Customer";
    $userID = $_SESSION['customer_id'];
} elseif (isset($_SESSION['owner_id'])) {
    $userType = "Owner";
    $userID = $_SESSION['owner_id'];
} elseif (isset($_SESSION['manager_id'])) {
    $userType = "Manager";
    $userID = $_SESSION['manager_id'];
} else {
    header("Location: login.php");
    exit();
}

function extract_pending_id($body) {
    preg_match('/Pending ID:\s*(\d+)/i', $body, $matches);
    return isset($matches[1]) ? $matches[1] : null;
}

$stmt = $pdo->prepare("
    SELECT * FROM messages
    WHERE RecipientType = :userType AND RecipientID = :userID 
    ORDER BY MessageDate DESC
");
$stmt->execute([
    ':userType' => $userType,
    ':userID' => $userID
]);
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<section class="container">
    <?php include('navigation.php'); ?>
    <main>
        <h2>Messages</h2>

        <?php if (empty($messages)): ?>
            <p>No messages found.</p>
        <?php else: ?>
            <table border="1" cellpadding="8">
                <tr>
                    <th>Status</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Sender</th>
                    <th>Message</th>
                    <?php if ($userType == "Manager" || $userType == "Owner") echo '<th>Action</th>'; ?>
                </tr>

                <?php foreach ($messages as $msg): ?>
                    <tr>
                        <td>
                            <?php if ($msg['IsRead']): ?>
                                <span class="color_masege_true">Read</span>
                            <?php else: ?>
                                <span class="color_masege_false">New</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($msg['Title']) ?></td>
                        <td><?= htmlspecialchars($msg['MessageDate']) ?></td>
                        <td><?= htmlspecialchars($msg['SenderType']) . " #" . htmlspecialchars($msg['SenderID']) ?></td>
                        <td><?= nl2br(htmlspecialchars($msg['MessageBody'])) ?></td>

                        <?php if ($userType == "Manager" || $userType == "Owner"): ?>
                            <td>
                                <?php 
                                $pendingId = extract_pending_id($msg['MessageBody']);
                                $hasAction = false;
                                ?>

                                <?php if ($pendingId && !$msg['IsRead']): ?>
                                    <form method="GET" action="ManegarApproveFlat.php">
                                        <input type="hidden" name="message_id" value="<?= $msg['MessageID'] ?>">
                                        <input type="hidden" name="pending_id" value="<?= $pendingId ?>">
                                        <button type="submit">Approve Flat</button>
                                    </form>
                                    <?php $hasAction = true; ?>
                                <?php endif; ?>

                                <?php if (
                                    $userType == "Manager" &&
                                    !empty($msg['MessageType']) &&
                                    stripos($msg['MessageType'], 'rentnotification') !== false
                                ): ?>
                                    <form method="get" action="rental_details_forManegar.php">
                                        <input type="hidden" name="message_id" value="<?= $msg['MessageID'] ?>">
                                        <button type="submit">Rental Details</button>
                                    </form>
                                    <?php $hasAction = true; ?>
                                <?php endif; ?>

                                <?php if (
                                    $userType == "Owner" &&
                                    $msg['MessageType'] === "RentApprovalRequest" &&
                                    !$msg['IsRead']
                                ): ?>
                                    <form method="post" action="approve_rental.php">
                                        <input type="hidden" name="message_id" value="<?= $msg['MessageID'] ?>">
                                        <button type="submit">Approve Rental</button>
                                    </form>
                                    <?php $hasAction = true; ?>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </main>
</section>

<?php include('footer.php'); ?>
</body>
</html>
