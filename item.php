<?php
require('BookModel.php');
$book_id=$_GET['id'];
$book_model=new BookModel();
$book_model->connection(SERVER_NAME, USER_NAME, PASSWORD, DB_NAME);
$username=$_SESSION['username'];
$best_book=$book_model->readBook($book_id,$username);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/item.css">
  <link rel="stylesheet" href="./styles/home.css">
  <title>Document</title>
</head>
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
        <div class="book-details">
    <h2><?php echo $best_book['book_title']; ?></h2>
    <img src="<?php echo $best_book['poster_image']; ?>" alt="Book Poster">
    <div class="book-info">
        <p><strong>Genre:</strong> <?php echo $best_book['genre']; ?></p>
        <p><strong>Publication Date:</strong> <?php echo $best_book['publication_date']; ?></p>
        <p><strong>Author:</strong> <?php echo $best_book['author']; ?></p>
        <p><strong>Ratings:</strong> <?php echo $best_book['ratings']; ?></p>
        <p><strong>Category:</strong> <?php echo $best_book['category']; ?></p>
    </div>
</div> 
</div>
   
    
</body>
</html>