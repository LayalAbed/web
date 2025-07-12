


<?php
include_once('Product.php');  
include('header.php'); 

if(isset($_GET['id']) && !empty($_GET['id'])) {
    Product::displayProductPage($_GET['id']);
} else {
    echo "<p>Invalid product ID.</p>";
}
?>

<hr ></hr>
<footer>
    <table>
        <tr>
            <td>
                <p>
                    <b>Customer Support:</b><br>
                    <img src="images/phone.jpg" alt="Phone" width="30" height="30">
                    +972-599-314087<br>
                    <a href="mailto:support@luckyyouare.com">
                        <img src="images/email.jpg" alt="Email" width="30" height="30">
                        support@luckyyouare.com
                    </a>
                </p>
                <p>
                    <a href="ContactUs.html">
                        <img src="images/contatus.jpg" alt="Contact Us" width="30" height="30">
                        Contact Us
                    </a>
                </p>
            </td>
        </tr>
    </table>
    <p>
        &copy; 2025 LuckyYouAre. All rights reserved.
    </p>
</footer>
