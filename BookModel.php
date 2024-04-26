<?php
// Display all errors for debugging

// Include required files
require ('Database.php');
require ('Config.php');

class BookModel
{
  use Database;

  public function insertBook($book_id, $poster_image, $book_title, $genre, $publication_date, $author, $ratings, $category)
  {
    // Database connection parameters

    // Check connection
    if ($this->conn->connect_error) {
      die("Connection failed: " . $this->conn->connect_error);
    }
    // Prepare the INSERT statement
    $sql = "INSERT INTO books (book_id, poster_image, book_title, genre, publication_date, author, ratings, category)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    // Prepare and bind the statement
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ssssssds", $book_id, $poster_image, $book_title, $genre, $publication_date, $author, $ratings, $category);
    // Execute the statement
    if ($stmt->execute() === TRUE) {
      echo "<script>alert('New record inserted successfully'); window.location.href='home.php';</script>";
    } else {
      echo "Error: " . $sql . "<br>" . $this->conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $this->conn->close();
  }
  public function readBook($book_id, $username)
  {
    $sql = "SELECT * FROM books WHERE book_id='$book_id'";
    $result = $this->conn->query($sql);
    if ($result && $result->num_rows > 0) {
      $book = $result->fetch_assoc();

      // Insert the fetched book data into the bucket
      $this->insertIntoBucket($book_id, $username);
      return $book;
    } else {
      return null; 
    }
  }
  
  private function insertIntoBucket($book_id, $username)
  {
      try {
          // Assuming you have a table named 'bucket' to store the books
          $sql = "INSERT INTO continue_reading (book_id, username) VALUES ('$book_id', '$username')";
          $this->conn->query($sql);
      } catch (Exception $e) {
      }
  }
  

}
$books = new BookModel();

if (isset($_POST['submit'])) {
  // Extracting the details from $_POST
  $book_id = $_POST['book_id'];
  $book_title = $_POST['book_title'];
  $genre = $_POST['genre'];
  $publication_date = $_POST['publication_date'];
  $author = $_POST['author'];
  $ratings = $_POST['ratings'];
  $category = $_POST['category'];
  // Assuming you want to store the file name
  if ($_FILES['poster_image']['error'] === UPLOAD_ERR_OK) {
    // Move uploaded file to images folder
    $filename = $_FILES["poster_image"]["name"];
    $tempname = $_FILES["poster_image"]["tmp_name"];
    $folder = "images/" . $filename;
    move_uploaded_file($tempname, $folder);
  }
  $books->connection(SERVER_NAME, USER_NAME, PASSWORD, DB_NAME);
  $books->insertBook($book_id, $folder, $book_title, $genre, $publication_date, $author, $ratings, $category);
}
?>