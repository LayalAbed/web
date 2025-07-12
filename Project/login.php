<?php
session_start();
include('database.inc.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $user = null;
    $userType = '';

    //Customer
    $stmt = $pdo->prepare("SELECT CustomerID AS userID, Password FROM customer WHERE Username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $userType = 'customer';
    } else {
        // Owner
        $stmt = $pdo->prepare("SELECT OwnerID AS userID, Password FROM owner WHERE Username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $userType = 'owner';
        } else {
            // Manager
            $stmt = $pdo->prepare("SELECT ManagerID AS userID, Password FROM manager WHERE Username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $userType = 'manager';
            }
        }
    }

    if ($user) {
      
        if ($password === $user['Password']) {
            if ($userType == 'customer') {
                $_SESSION['customer_id'] = $user['userID'];
                $redirect = !empty($_SESSION['last_page']) ? $_SESSION['last_page'] : 'HomePage.php';
                unset($_SESSION['last_page']);
                header("Location: $redirect");
                exit();
            } elseif ($userType == 'owner') {
                $_SESSION['owner_id'] = $user['userID'];
                header("Location: HomePage.php");
                exit();
            } elseif ($userType == 'manager') {
                $_SESSION['manager_id'] = $user['userID'];
                header("Location: HomePage.php");  
                exit();
            }
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
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
<section class="container">

    <?php include('navigation.php'); ?>

    <main>
        <h2>Member Login</h2>

        <?php if (isset($error)): ?>
            <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="post" action="login.php" class="login-form">
            <label for="username">Username:</label><br>
            <input type="text" name="username" id="username" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required><br><br>

            <input type="submit" value="Login"> <br><br>
            <button type="button" onclick="window.location.href='Sign-up.php'">Sign Up</button>
        </form>
    </main>
</section>

<?php include('footer.php'); ?>

</body>
</html>
