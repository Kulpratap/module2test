<?php
session_start();
if (!isset($_SESSION['loggedin']) && ($_SESSION['loggedin'] == false)) {
    header('Location:/');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMDb</title>
    <link rel="stylesheet" href="../styles/home.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<body>

    <div class="container">
        <div class="navbar">
            <a href="/home.php">Home</a>
            <a href="overview.php">Bucket List</a>
            <a href="wishlist.php">wishlist</a>
            <?php
            if (isset($_SESSION['loggedin']) && ($_SESSION['loggedin'] == true)) {
                echo "<a href='logout.php' class='nav-link logout-btn'>Logout</a>";
            } else {
                echo "<a href='/' class='nav-link logout-btn'>Login / SignUp</a>";
            }
            ?>

        </div>
        <section class="main container">
            <h1>Welcome to GoodReadBooks.</h1>
        </section>
    </div>
    <div class="posts-container" id="load-more">
        </div>
        <div class="container-plant button-container" style="display: none;">
            <button id="load-more-btn">Load More</button>
        </div>
</body>

</html>
<script src="../script/home.js"></script>