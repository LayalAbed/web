<?php
include('database.inc.php');

?>
<nav>
    <ul>
        <li><a href="HomePage.php">Home</a></li>
        <li><a href="SearchFlat.php">Flat Search</a></li>

        <?php if (isset($_SESSION['manager_id'])): ?>
            <li><a href="masseges.php">View Messages</a></li>
            <li><a href="SearchManager.php">SearchManager</a></li>
            <li><a href="ManegarShowFlatwontRent.php"> Flat nead aprove </a></li>

        <?php elseif (isset($_SESSION['owner_id'])): ?>
            <li><a href="Offer_Flat_for_Rent.php">Offer Flat</a></li>
            <li><a href="masseges.php">View Messages</a></li>
            <li><a href="add_preview_schedule.php">addpreview schedule</a></li>

        <?php elseif (isset($_SESSION['customer_id'])): ?>
            <li><a href="masseges.php">View Messages</a></li>
            <li><a href="Custmer_rented_flats.php"> Rented apartments</a></li>
            

            <?php if (isset($_GET['id'])): ?>
    <li><a href="rent_flat.php?apartmentID=<?= ($_GET['id']) ?>">Rent Flat</a></li>
    <li><a href="Request_Preview.php?apartmentID=<?= ($_GET['id']) ?>">Request Flat Preview Appointment</a></li>
<?php endif; ?>


        <?php endif; ?>
    </ul>
</nav>
