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
    <section class="homepage-about">
        <h2>About Al Rayyan Rental Company</h2>
        <p>
            Al Rayyan Rental Company is a leading Palestinian apartment and house rental company, serving customers looking for housing that suits their needs, whether on a monthly or annual rental basis. The company provides comprehensive and accurate information about each property, including the number of rooms and bathrooms, floor area, furniture availability, heating and air conditioning systems, and additional services.
        </p>
        <p>
            We also display clear photos of each apartment or house on our website, along with details of its location and nearby amenities, such as schools, universities, markets, public transportation, and healthcare centers. This information helps customers make the right decision based on their daily needs.
        </p>
        <p>
            We also offer direct online booking and pre-leasing property inspections to ensure a reliable and secure experience. Our company is headquartered in Birzeit, Palestine, and our services cover all areas of the West Bank and Gaza Strip, facilitating access to convenient housing solutions across the country.
        </p>
        
    </section>
  
    <section class="homepage-gallery">
        <div class="flat-gallery-flex">
            <img src="image/flat1.jpg" alt="Flat 1" />
            <img src="image/flat2.jpg" alt="Flat 2" />
            <img src="image/flat3.jpg" alt="Flat 3" />
            <img src="image/flat4.jpg" alt="Flat 4" />
            <img src="image/flat5.jpg" alt="Flat 5" />
            <img src="image/flat6.jpg" alt="Flat 6" />
        </div>
    </section>
</main>


</section>
<?php include('footer.php'); ?>
</body>
</html>