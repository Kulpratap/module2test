<?php
session_start();
class Add_to_cart
{   
    private $conn;
    public function insert_into_cart($postId, $username) {
      try {
          $this->conn = new mysqli("localhost", "kul", "Kul@123456", "USERS");
  
          // Check for connection errors
          if ($this->conn->connect_error) {
              throw new Exception("Connection failed: " . $this->conn->connect_error);
          }
  
          $stmt = $this->conn->prepare("INSERT INTO bucket (book_id, user_name) VALUES (?, ?)");
  
          // Check if the prepare() call succeeded
          if ($stmt === false) {
              throw new Exception("Error preparing statement: " . $this->conn->error);
          }
  
          // Bind parameters and execute the statement
          $stmt->bind_param("ss", $postId, $username);
  
          if ($stmt->execute()) {
              // Cart insertion successful
              echo json_encode(array('success' => true));
          } else {
              // Failed to insert into cart
              echo json_encode(array('success' => false, 'error' => 'Failed to insert into cart'));
          }
  
          // Close the statement and connection
          $stmt->close();
          $this->conn->close();
      } catch (Exception $e) {
          // Handle exceptions
          echo json_encode(array('success' => false, 'error' => $e->getMessage()));
      }
  }
  
  
}

$add_to_cart = new Add_to_cart();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['postId'])) {
        $postId = $_POST['postId'];
        $username = $_SESSION['username'];
        $add_to_cart->insert_into_cart($postId, $username);
    }
} else {
  echo json_encode(array('success' => false, 'error' => 'Invalid request method'));
}
