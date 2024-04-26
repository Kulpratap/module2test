<?php
session_start();
if (!isset($_SESSION['loggedin']) && ($_SESSION['loggedin'] == false)) {
  header('Location:/');
}
if (($_SESSION['admin'] == false)) {
  header('Location:/');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IMDb</title>
  <link rel="stylesheet" href="../styles/addBook.css">

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
    <div class="post-form">
    <form action="BookModel.php" id="book-form" method="POST" enctype="multipart/form-data">
    <div class="form-group">
    <h2>Add Book to GoodReads Book.</h1>
        <label for="book_id">Alphanumeric Book ID</label>
        <input type="text" name="book_id" placeholder="Enter Alphanumeric Book ID" required>
    </div>
    <div class="form-group">
        <label for="poster_image">Select Poster Image</label>
        <input type="file" name="poster_image" required>
    </div>
    <div class="form-group">
        <label for="book_title">Enter Book Title</label>
        <input type="text" name="book_title" placeholder="Enter Book Title" required>
    </div>
    <div class="form-group">
        <label for="genre">Enter Genre</label>
        <input type="text" name="genre" placeholder="Enter Genre" required>
    </div>
    <div class="form-group">
        <label for="publication_date">Enter Date of Publication</label>
        <input type="date" name="publication_date" required>
    </div>
    <div class="form-group">
        <label for="author">Enter Author</label>
        <input type="text" name="author" placeholder="Enter Author" required>
    </div>
    <div class="form-group">
        <label for="ratings">Enter Ratings</label>
        <input type="number" name="ratings" class="ratings-input" step="1" min="1" max="5" placeholder="Enter Ratings" required>
    </div>
    <div class="form-group">
        <label for="category">Enter Category</label>
        <input type="text" name="category" placeholder="Enter Category" required>
    </div>
    <input class="create_button" name="submit" type="submit" value="Add Book">
</form>

    </div>
    </section>
  </div>

</body>

</html>