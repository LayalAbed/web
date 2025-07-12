<?php
session_start();
include('header.php');
include('database.inc.php');

if (!isset($_SESSION['owner_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (count($_FILES['images']['name']) < 3) {
            throw new Exception("Please upload at least 3 images for the flat.");
        }

        $apartmentNumber = $_POST['ApartmentNumber'];

        // Insert flat into PendingApartments
        $stmt = $pdo->prepare("
            INSERT INTO pendingapartments (
                ApartmentNumber, NumberOfRooms, NumberOfBathrooms, IsFurnished,
                MonthlyRent, Area, HeatingSystem_AirConditioning, AccessControl,
                ExtraAmenities, Description, OwnerID,
                MarketingTitle, MarketingDesc, MarketingURL
            ) VALUES (
                :apt_num, :rooms, :bathrooms, :furnished,
                :rent, :area, :heating_ac, :access_control,
                :extras, :description, :owner_id,
                :m_title, :m_desc, :m_url
            )
        ");

        $stmt->execute([
            ':apt_num' => $apartmentNumber,
            ':rooms' => $_POST['NumberOfRooms'],
            ':bathrooms' => $_POST['NumberOfBathrooms'],
            ':furnished' => isset($_POST['IsFurnished']) ? 1 : 0,
            ':rent' => $_POST['MonthlyRent'],
            ':area' => $_POST['Area'],
            ':heating_ac' => isset($_POST['HeatingSystem_AirConditioning']) ? 1 : 0,
            ':access_control' => $_POST['AccessControl'],
            ':extras' => $_POST['ExtraAmenities'],
            ':description' => $_POST['Description'],
            ':owner_id' => $_SESSION['owner_id'],
            ':m_title' => $_POST['MarketingTitle'],
            ':m_desc' => $_POST['MarketingDesc'],
            ':m_url' => $_POST['MarketingURL']
        ]);

        $pendingId = $pdo->lastInsertId(); // Still needed for DB reference

        // Store images as: ApartmentNumber-1.jpg, ApartmentNumber-2.jpg, ...
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $originalName = $_FILES['images']['name'][$key];
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);

            $imageName = $apartmentNumber . "-" . ($key + 1) . "." . $extension;
            move_uploaded_file($tmp_name, "pending_images/" . $imageName);

            $stmtImg = $pdo->prepare("
                INSERT INTO pendingapartmentimages (TempID, ImageNumber, ImageName)
                VALUES (:tempid, :imgnum, :imgname)
            ");
            $stmtImg->execute([
                ':tempid' => $pendingId,
                ':imgnum' => $key + 1,
                ':imgname' => $imageName
            ]);
        }

        // Send message to manager
        $managerID = 100005;
        $title = "New Flat Pending Approval";
        $body = "Owner #{$_SESSION['owner_id']} has submitted flat #$apartmentNumber (Pending ID: $pendingId). Please review.";
        $stmtMsg = $pdo->prepare("
            INSERT INTO messages
            (Title, MessageBody, SenderType, SenderID, RecipientType, RecipientID, MessageType) 
            VALUES
            (:title, :body, 'Owner', :ownerid, 'Manager', :managerid, 'Approval')
        ");
        $stmtMsg->execute([
            ':title' => $title,
            ':body' => $body,
            ':ownerid' => $_SESSION['owner_id'],
            ':managerid' => $managerID
        ]);
$formSubmitted = true;

       echo "<p class='color_masege_true '>YPending apartment not found.</p>";

    } catch (Exception $e) {
      echo "<p class='color_masege_false'>Error: " . $e->getMessage() . "</p>";
    }
}
?>


<!-- HTML FORM -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Offer Flat for Rent</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<section class="container">
    <?php include('navigation.php'); ?>
    <main>
        <h2>Offer Flat for Rent</h2>

        <?php if (!isset($formSubmitted)): ?>
<form action="Offer_Flat_for_Rent.php" method="POST" enctype="multipart/form-data">

  <table cellpadding="6">
    <tr>
      <td><label>Apartment Number:</label></td>
      <td><input type="text" name="ApartmentNumber" required></td>
    </tr>
    <tr>
      <td><label>Number of Bedrooms:</label></td>
      <td><input type="number" name="NumberOfRooms" required></td>
    </tr>
    <tr>
      <td><label>Number of Bathrooms:</label></td>
      <td><input type="number" name="NumberOfBathrooms" required></td>
    </tr>
    <tr>
      <td><label>Monthly Rent ($):</label></td>
      <td><input type="number" name="MonthlyRent" required></td>
    </tr>
    <tr>
      <td><label>Area (m²):</label></td>
      <td><input type="number" name="Area" required step="0.01"></td>
    </tr>
    <tr>
      <td><label>Furnished:</label></td>
      <td><input type="checkbox" name="IsFurnished"></td>
    </tr>
    <tr>
      <td><label>Heating/Air Conditioning:</label></td>
      <td><input type="checkbox" name="HeatingSystem_AirConditioning"></td>
    </tr>
    <tr>
      <td><label>Access Control:</label></td>
      <td><input type="text" name="AccessControl"></td>
    </tr>
    <tr>
      <td><label>Extra Features:</label></td>
      <td><input type="text" name="ExtraAmenities"></td>
    </tr>
    <tr>
      <td><label>Description:</label></td>
      <td><textarea name="Description" required></textarea></td>
    </tr>

    <tr>
      <td colspan="2"><h3>Optional Marketing Info</h3></td>
    </tr>
    <tr>
      <td><label>Marketing Title:</label></td>
      <td><input type="text" name="MarketingTitle"></td>
    </tr>
    <tr>
      <td><label>Short Description:</label></td>
      <td><textarea name="MarketingDesc"></textarea></td>
    </tr>
    <tr>
      <td><label>URL Link:</label></td>
      <td><input type="url" name="MarketingURL"></td>
    </tr>
    <tr>
      <td><label>Flat Images (minimum 3):</label></td>
      <td><input type="file" name="images[]" multiple required ></td>
    </tr>
    <tr>
      <td colspan="2" >
        <input type="submit" value="Submit Apartment" class="link-button">

      </td>
    </tr>
  </table>
</form>
<?php endif; ?>
    </main>
</section>

<?php include('footer.php'); ?>
</body>
</html>
