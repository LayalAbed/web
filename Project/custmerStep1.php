<?php
session_start();
include('header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Customer Registration - Step 1</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<section class="container">
<?php include('navigation.php'); ?>

<main>
    <h2>Customer Registration - Step 1</h2>
 
    <form method="POST" action="custmerStep2.php" class="Step1" enctype="multipart/form-data"> 
<fieldset>
    <legend>custmer information </legend>
        <!-- Basic Info -->
        <label for="nid">National ID Number:</label>
        <input id="nid" type="text" name="nid" pattern="\d{9}" title="Must be 9 digits" required /><br><br>

        <label for="name">Name:</label>
        <input id="name" type="text" name="name" pattern="[A-Za-z\s]+" title="Letters and spaces only" required /><br><br>

        <label for="flat">Flat/House No:</label>
        <input id="flat" type="text" name="flat" /><br><br>

        <label for="street">Street Name:</label>
        <input id="street" type="text" name="street" /><br><br>

        <label for="city">City:</label>
        <input id="city" type="text" name="city" /><br><br>

        <label for="postal">Postal Code:</label>
        <input id="postal" type="text" name="postal" /><br><br>

        <label for="dob">Date of Birth:</label>
        <input id="dob" type="date" name="dob" required /><br><br>

        <label for="email">Email:</label>
        <input id="email" type="email" name="email" required /><br><br>

        <label for="mobile">Mobile Number:</label>
        <input id="mobile" type="text" name="mobile" /><br><br>

        <label for="telephone">Telephone Number:</label>
        <input id="telephone" type="text" name="telephone" /><br><br>

        <label for="custmerImage">Upload Image:</label>
        <input type="file" id="custmerImage" name="custmerImage" accept="image/*" required><br><br>
        </fieldset>
        <fieldset>
            <legend>Credit Card Details</legend>

            <label for="cardNumber">Card Number (9 digits):</label>
            <input id="cardNumber" type="text" name="cardNumber" pattern="\d{9}" title="Must be 9 digits" required /><br><br>

            <label for="cardName">Name on Card:</label>
            <input id="cardName" type="text" name="cardName" required /><br><br>

            <label for="securityCode">Security Code:</label>
            <input id="securityCode" type="text" name="securityCode" required /><br><br>

            <label for="expiryDate">Expiry Date:</label>
            <input id="expiryDate" type="date" name="expiryDate" required /><br><br>
        </fieldset>

        <input type="submit" value="Next" class="link-button">
    </form>
</main>
</section>

<?php include('footer.php'); ?>
</body>
</html>
