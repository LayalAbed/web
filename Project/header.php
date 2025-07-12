<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('database.inc.php'); 

$isCustomerLoggedIn = isset($_SESSION['customer_id']);
$isOwnerLoggedIn = isset($_SESSION['owner_id']);
$isManagerLoggedIn = isset($_SESSION['manager_id']);
$username = ''; 
$userProfileLink = ''; 

if ($isCustomerLoggedIn) {
    $customerID = $_SESSION['customer_id'];
    $stmt = $pdo->prepare("SELECT Name FROM customer WHERE CustomerID = ?");
    $stmt->execute([$customerID]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $username = $row['Name'];
        $userProfileLink = "userProfile.php?id={$customerID}&type=customer";
    }
} elseif ($isOwnerLoggedIn) {
    $ownerID = $_SESSION['owner_id'];
    $stmt = $pdo->prepare("SELECT OwnerName FROM owner WHERE OwnerID = ?");
    $stmt->execute([$ownerID]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $username = $row['OwnerName'];
        $userProfileLink = "userProfile.php?id={$ownerID}&type=owner";
    }
} elseif ($isManagerLoggedIn) {
    $managerID = $_SESSION['manager_id'];
    $stmt = $pdo->prepare("SELECT Name FROM manager WHERE ManagerID = ?");
    $stmt->execute([$managerID]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $username = $row['Name'];
        $userProfileLink = "userProfile.php?id={$managerID}&type=manager";
    }
}
?>

<header>
    <section class="logo-name">
        <img src="image/logo.png" alt="Logo" class="logo">
        <h1>Al Rayyan Rental Company</h1>
    </section>
    <section class="header-links">
        <a href="AboutUs.php">About Us</a>

        <?php if ($isCustomerLoggedIn || $isOwnerLoggedIn || $isManagerLoggedIn): ?>
           <div class="user_card">
    <?php
        $userImage = 'image/maneger.jpg'; // Default image

        if ($isCustomerLoggedIn) {
            $customerID = $_SESSION['customer_id'];
            $customImagePath = "pending_images/C{$customerID}.jpg";
            if (file_exists($customImagePath)) {
                $userImage = $customImagePath;
            }
        } elseif ($isOwnerLoggedIn) {
            $ownerID = $_SESSION['owner_id'];
            $customImagePath = "pending_images/O{$ownerID}.jpg";
            if (file_exists($customImagePath)) {
                $userImage = $customImagePath;
            }
        }
    ?>
    <a href="<?= $userProfileLink ?>">
        <img src="<?= htmlspecialchars($userImage) ?>" alt="User" class="user-photo">
    </a>
    <p><?= htmlspecialchars($username); ?></p>
</div>

            <?php if ($isCustomerLoggedIn): ?>
                <a href="basket.php" class="customer-only">Basket</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="Sign-up.php">Sign-up</a>
            <a href="login.php">Login</a>
        <?php endif; ?>

        <?php if ($isCustomerLoggedIn || $isOwnerLoggedIn || $isManagerLoggedIn): ?>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </section>
</header>
