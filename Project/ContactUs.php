<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared Knowledge Web Course</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <section class="container">
        <?php include('navigation.php'); ?>

        <main>
            <h2>You Can Contact Us At:</h2>
            <address>
                <b>Customer Support:</b><br></br>
                <img src="image/Phone.jpg" alt="Phone" width="30" height="30">
                +972 531 4079<br></br>
                <a href="mailto:support@birzeitflats.com">
                    <img src="image/email.jpg" alt="Email" width="30" height="30">
                    birzeitflats@contact.com
                </a><br></br>
            </address>

            <hr>

            <h2>Input Your Information:</h2><br></br>
            <form action="http://yhassouneh.studentprojects.ritaj.ps/util/process.php" method="post" enctype="multipart/form-data" class="Step1">
                <p>
                    <strong>Enter your Name:</strong><br></br>
                    <input type="text" name="name" required placeholder="Enter your name"><br></br>
                </p>
                <hr>

                <p>
                    <strong>Enter your Email:</strong><br></br>
                    <input type="email" name="email" required placeholder="Enter your email"><br></br>
                </p>
                <hr>

                <p>
                    <strong>Enter your Location:</strong><br></br>
                    <input type="text" name="city" required placeholder="Enter your city"><br></br>
                </p>
                <hr>

                <p>
                    <strong>Enter your Message Subject:</strong><br></br>
                    <input type="text" name="subject" required placeholder="Enter subject of your message"><br></br>
                </p>
                <hr>

                <p>
                    <strong>Enter your Message Body:</strong><br></br>
                    <textarea name="message" required placeholder="Write your message here"></textarea><br></br>
                </p>
                <hr><br></br>

                <p>
                    <button class="link-button" type="submit">Send Message</button>
                    <button class="link-button" type="reset">Reset Data</button>
                </p>
            </form>

            <br></br>
            <hr><hr>
        </main>
    </section>

    <?php include('footer.php'); ?>
</body>

</html>
