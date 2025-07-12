<?php
session_start();
include('header.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
      
<section class="container signup-section">
    <?php include('navigation.php'); ?>
    <main class="main-content">
        <h2 class="center-text">Choose Your Account Type</h2>
        <p class="center-text">Please click on your role to proceed with login or registration:</p>

        <div class="account-table">
            <button class="account-button" onclick="location.href='custmerStep1.php'">
                <img src="image/custome.jpg" alt="Customer">
                <strong>Customer</strong>
            </button>
            <button class="account-button" onclick="location.href='ownerStep1.php'">
                <img src="image/owner.jpg" alt="Owner">
                <strong>Owner</strong>
            </button>
        </div>
    </main>
</section>
<?php include('footer.php'); ?>
</body>
</html>
