<?php
include('header.php');
include('database.inc.php');

$user_id = $_GET['id'] ?? null;      
$type = $_GET['type'] ?? null;       

if (!$user_id || !$type) {
    echo "Missing user ID or type.";
    exit;
}

switch ($type) {
    case 'owner':
        $stmt = $pdo->prepare("
            SELECT o.*, a.FlatOrHouseNo, a.StreetName, a.City, a.PostalCode
            FROM owner o
            LEFT JOIN owneraddress a ON o.OwnerID = a.OwnerID
            WHERE o.OwnerID = ?
            LIMIT 1
        ");
        break;

    case 'customer':
        $stmt = $pdo->prepare("
            SELECT c.*, a.FlatOrHouseNo, a.StreetName, a.City, a.PostalCode
            FROM customer c
            LEFT JOIN customeraddress a ON c.CustomerID = a.CustomerID
            WHERE c.CustomerID = ?
            LIMIT 1
        ");
        break;

    case 'manager':
        $stmt = $pdo->prepare("
            SELECT * FROM manager
            WHERE ManagerID = ?
            LIMIT 1
        ");
        break;

    default:
        echo "Invalid user type.";
        exit;
}

$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile - <?= htmlspecialchars($user['Name']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
      <?php include('navigation.php'); ?>
 
    <main class="main-content-profile-card">
      <h2>User Profile</h2>

      <?php if ($type == 'owner' || $type == 'customer'): ?>
        <ul class="profile-card">
          <li><strong>ID:</strong> <?= $type == 'owner' ? htmlspecialchars($user['OwnerID']) : htmlspecialchars($user['CustomerID']) ?></li>
          <li><strong>Name:</strong> <?= htmlspecialchars($user['Name'] ?? $user['OwnerName']) ?></li>
          <li><strong>National ID:</strong> <?= htmlspecialchars($user['NationalID']) ?></li>
          <li><strong>Username:</strong> <?= htmlspecialchars($user['Username']) ?></li>
          <li><strong>Email:</strong> <?= htmlspecialchars($user['Email'] ?? $user['EmailAddress']) ?></li>
          <li><strong>Mobile Number:</strong> <?= htmlspecialchars($user['MobileNumber']) ?></li>
          <li><strong>Telephone:</strong> <?= htmlspecialchars($user['TelephoneNumber'] ?? $user['PhoneNumber']) ?></li>

          <?php if ($type == 'owner'): ?>
            <li><strong>Bank Name:</strong> <?= htmlspecialchars($user['BankName']) ?></li>
            <li><strong>Bank Branch:</strong> <?= htmlspecialchars($user['BankBranch']) ?></li>
            <li><strong>Account Number:</strong> <?= htmlspecialchars($user['AccountNumber']) ?></li>
          <?php elseif ($type == 'customer'): ?>
            <li><strong>Date of Birth:</strong> <?= htmlspecialchars($user['DateOfBirth']) ?></li>
          <?php endif; ?>

          <li><strong>Address:</strong>
            <?= htmlspecialchars("{$user['FlatOrHouseNo']} - {$user['StreetName']}, {$user['City']} ({$user['PostalCode']})") ?>
          </li>
        </ul>

      <?php elseif ($type == 'manager'): ?>
        <ul class="profile-card">
          <li><strong>Manager ID:</strong> <?= htmlspecialchars($user['ManagerID']) ?></li>
          <li><strong>Name:</strong> <?= htmlspecialchars($user['Name']) ?></li>
          <li><strong>ID Number:</strong> <?= htmlspecialchars($user['IDNumber']) ?></li>
          <li><strong>Phone:</strong> <?= htmlspecialchars($user['Phone']) ?></li>
          <li><strong>Username:</strong> <?= htmlspecialchars($user['Username']) ?></li>
          <li><strong>Email:</strong> <?= htmlspecialchars($user['Email'] ?? 'N/A') ?></li>

          <?php if (!empty($user['Image'])): ?>
            <li><strong>Image:</strong><br>
              <img src="<?= htmlspecialchars($user['Image']) ?>" alt="Manager Photo" width="150">
            </li>
          <?php endif; ?>
        </ul>
      <?php endif; ?>

     <?php if (
    $type == 'customer' &&
    isset($_SESSION['customer_id']) &&
    $_SESSION['customer_id'] == $user_id
): ?>
  <a href="edit_profileCustmer.php?id=<?= urlencode($user_id) ?>&type=<?= urlencode($type) ?>">
    <button class="link-button">Update My Information</button>
  </a>
<?php endif; ?>

    </main>
  </div>

<?php include('footer.php'); ?>
</html>
