<?php
session_start();
include('header.php');
include('database.inc.php');

$user_id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? null;

if (!$user_id || $type !== 'customer') {
    echo "Invalid access.";
    exit;
}

$stmt = $pdo->prepare("
    SELECT c.*, a.FlatOrHouseNo, a.StreetName, a.City, a.PostalCode
    FROM customer c
    LEFT JOIN customeraddress a ON c.CustomerID = a.CustomerID
    WHERE c.CustomerID = :id
    LIMIT 1
");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Customer not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updateCustomer = $pdo->prepare("
        UPDATE customer SET 
            Name = :name, 
            NationalID = :nid, 
            Email = :email, 
            MobileNumber = :mobile, 
            TelephoneNumber = :telephone, 
            DateOfBirth = :dob
        WHERE CustomerID = :id
    ");

    $updateAddress = $pdo->prepare("
        UPDATE customeraddress SET 
            FlatOrHouseNo = :flat,
            StreetName = :street,
            City = :city,
            PostalCode = :postal
        WHERE CustomerID = :id
    ");

    $updateCustomer->execute([
        'name' => $_POST['name'],
        'nid' => $_POST['national_id'],
        'email' => $_POST['email'],
        'mobile' => $_POST['mobile'],
        'telephone' => $_POST['telephone'],
        'dob' => $_POST['dob'],
        'id' => $user_id
    ]);

    $updateAddress->execute([
        'flat' => $_POST['flat'],
        'street' => $_POST['street'],
        'city' => $_POST['city'],
        'postal' => $_POST['postal'],
        'id' => $user_id
    ]);

    header("Location: userProfile.php?id=$user_id&type=customer&updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Customer Info</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<section class="container">
    <?php include('navigation.php'); ?>

    <main>
        <h2>Edit Your Information</h2>

        <form method="post" class="Step1">
            <label for="name">Name:</label>
            <input id="name" type="text" name="name" value="<?= htmlspecialchars($user['Name'] ?? '') ?>" required><br></br>

            <label for="national_id">National ID:</label>
            <input id="national_id" type="text" name="national_id" value="<?= htmlspecialchars($user['NationalID'] ?? '') ?>" required><br></br>

            <label for="email">Email:</label>
            <input id="email" type="email" name="email" value="<?= htmlspecialchars($user['Email'] ?? '') ?>" required><br></br>

            <label for="mobile">Mobile Number:</label>
            <input id="mobile" type="text" name="mobile" value="<?= htmlspecialchars($user['MobileNumber'] ?? '') ?>"><br></br>

            <label for="telephone">Telephone Number:</label>
            <input id="telephone" type="text" name="telephone" value="<?= htmlspecialchars($user['TelephoneNumber'] ?? '') ?>"><br></br>

            <label for="dob">Date of Birth:</label>
            <input id="dob" type="date" name="dob" value="<?= htmlspecialchars($user['DateOfBirth'] ?? '') ?>"><br></br>

            <label for="flat">Flat/House No:</label>
            <input id="flat" type="text" name="flat" value="<?= htmlspecialchars($user['FlatOrHouseNo'] ?? '') ?>"><br></br>

            <label for="street">Street Name:</label>
            <input id="street" type="text" name="street" value="<?= htmlspecialchars($user['StreetName'] ?? '') ?>"><br></br>

            <label for="city">City:</label>
            <input id="city" type="text" name="city" value="<?= htmlspecialchars($user['City'] ?? '') ?>"><br></br>

            <label for="postal">Postal Code:</label>
            <input id="postal" type="text" name="postal" value="<?= htmlspecialchars($user['PostalCode'] ?? '') ?>"><br></br>

            <input type="submit" value="Save Changes" class="link-button"><br></br>
        </form>
    </main>
</section>

<?php include('footer.php'); ?>
</body>
</html>
