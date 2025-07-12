<?php
session_start();
include('header.php');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Owner  Registration - Step 1</title>
     <link rel="stylesheet" href="style.css">
</head>

<body>

<section class="container">
<?php include('navigation.php'); ?>

 <main>
    <h2>Owner Registration - Step 1</h2>

<form method="POST" action="ownerStep2.php" class="Step1" enctype="multipart/form-data">

    <label for="nid">National ID (9 digits):</label>
    <input type="text" id="nid" name="NationalID" maxlength="9" required><br>

    <label for="ownerName">Owner Name:</label>
    <input type="text" id="ownerName" name="OwnerName" required><br>

    <label for="dob">Date of Birth:</label>
    <input type="date" id="dob" name="DateOfBirth" required><br>

    <label for="email">Email Address:</label>
    <input type="email" id="email" name="EmailAddress" required><br>

    <label for="mobile">Mobile Number:</label>
    <input type="text" id="mobile" name="MobileNumber" required><br>

    <label for="telephone">Telephone Number:</label>
    <input type="text" id="telephone" name="PhoneNumber"><br>

    <label for="bankName">Bank Name:</label>
    <input type="text" id="bankName" name="BankName"><br>

    <label for="bankBranch">Bank Branch:</label>
    <input type="text" id="bankBranch" name="BankBranch"><br>

    <label for="accountNumber">Account Number:</label>
    <input type="text" id="accountNumber" name="AccountNumber"><br>

    <label for="flat">Flat/House No:</label>
    <input type="text" id="flat" name="FlatOrHouseNo"><br>

    <label for="street">Street Name:</label>
    <input type="text" id="street" name="StreetName"><br>

    <label for="city">City:</label>
    <input type="text" id="city" name="City"><br>

    <label for="postal">Postal Code:</label>
    <input type="text" id="postal" name="PostalCode" maxlength="6"><br>

    <label for="ownerImage">Upload Image:</label>
<input type="file" id="ownerImage" name="ownerImage" accept="image/*" required><br></br>


    <input type="submit" value="Next Step" class="link-button">
</form>


    </main>
</section>
<?php include('footer.php'); ?>
</body>
</html>
