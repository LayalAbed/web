<?php
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

   <main class="about-page">
    <!-- Section 1: The Agency -->
    <section class="about-section">
        <h2>About Al Rayyan Rental Company</h2>
        <p>
            Al Rayyan Rental Company is a leading Palestinian apartment and house rental company, established in 2015 in Birzeit, Palestine. With years of experience, the company has gained a reputation for trust and reliability in the housing market.
        </p>
        <p>
            Our agency has received awards for customer satisfaction and innovation in online rental services. We are managed by a professional team consisting of a CEO, operations manager, property managers, and customer support specialists.
        </p>
    </section>

 <!-- Section 2: The City -->
<section class="about-section">
    <h2>About Birzeit & Ramallah</h2>
    <p>
        Our headquarters is located in the beautiful town of <strong>Birzeit</strong>, which is situated north of <strong>Ramallah</strong> in the West Bank. Birzeit is known for its prestigious Birzeit University and cultural significance. The town has a population of approximately 6,000 people and enjoys a Mediterranean climate with mild, rainy winters and hot, dry summers.
    </p>
    <p>
        Nearby <strong>Ramallah</strong> is a bustling city and one of the most important cultural, political, and economic centers in Palestine. It is home to numerous restaurants, shopping centers, and government institutions. Notable figures from Ramallah include poet Mahmoud Darwish and composer Marcel Khalife.
    </p>

    <div class="city-gallery">
        <div class="city-photo">
            <img src="image/birzeit.jpeg" alt="Birzeit">
            <p class="photo-caption">Birzeit</p>
        </div>
        <div class="city-photo">
            <img src="image/Ramallah.jpeg" alt="Ramallah">
            <p class="photo-caption">Ramallah</p>
        </div>
    </div>

    <p>
        To learn more about the city, you can visit: 
        <a href="https://en.wikipedia.org/wiki/Ramallah" target="_blank">Wikipedia - Ramallah</a>
    </p>
</section>


    <!-- Section 3: Business Activities -->
    <section class="about-section">
        <h2>Main Business Activities</h2>
        <ul>
            <li>Apartment and house rental services</li>
            <li>Online flat searching and reservation</li>
            <li>Providing detailed property information</li>
            <li>Arranging property inspections and visits</li>
            <li>Customer support and rental consulting</li>
            <li>Marketing property listings on behalf of landlords</li>
        </ul>
    </section>
</main>

</section>
<?php include('footer.php'); ?>
</body>
</html>