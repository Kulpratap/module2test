<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
session_start();
if(!isset($_SESSION['loggedin'])&&($_SESSION['loggedin']==false)){
    header('Location:/');
}
require('UserModel.php');
$user=new UserModel();
$username=$_SESSION['username'];
$user->connection(SERVER_NAME, USER_NAME, PASSWORD, DB_NAME);
$names=$user->getNames($username);
$continue_reading=$user->readDataFromContinueReading();
$bucketList=$user->readDataFromBucket();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../styles/overview.css">
    <title>Overview Page</title>
</head>

<body>
<div class="container">
        <div class="navbar">
            <a href="/home.php">Home</a>
            <a href="/overview.php">Bucket List</a>
            <a href="/wishlist.php" >Wishlist <i class="fa-solid fa-cart-shopping"></i></a>
            <?php
            if (isset($_SESSION['loggedin']) && ($_SESSION['loggedin'] == true)) {
                echo "<a href='/logout.php' class='nav-link logout-btn'>Logout</a>";
            } else {
                echo "<a href='/' class='nav-link logout-btn'>Login / SignUp</a>";
            }
            ?>
        </div>
        <section class="main container">
            <h1>Welcome <?php echo $names[0]['first_name'] . " " . $names[0]['last_name'] ?></h1>
            <h2>Continue Reading Books</h2>
        </section>
        <table class="reading-books">
            <thead>
                <tr>
                    <th>ID</th>
                    <!-- Add more headers if needed -->
                </tr>
            </thead>
            <tbody id="reading-books-body">
                <?php
                // Loop through reading data and generate table rows
                foreach ($continue_reading as $reading) {
                    echo "<tr>";
                    echo "<td>{$reading['book_id']}</td>";
                    // Add more columns if needed
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <h2>Bucket List</h2>
        <table class="reading-books">
            <thead>
                <tr>
                    <th>ID</th>
                    <!-- Add more headers if needed -->
                </tr>
            </thead>
            <tbody id="reading-books-body">
                <?php
                // Loop through reading data and generate table rows
                foreach ($bucketList as $reading) {
                    echo "<tr>";
                    echo "<td>{$reading['book_id']}</td>";
                    // Add more columns if needed
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
</body>

</html>