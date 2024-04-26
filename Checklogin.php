<?php
session_start();
// Display all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include required files
require('Database.php');
require('Config.php');

// Define the Checklogin class
class Checklogin {
  
  // Use the Database trait
  use Database;
  
  /**
   * Checks user credentials against the database.
   *
   * @param string $username
   *   The username provided by the user.
   * @param string $password
   *   The password provided by the user.
   *
   * @return void
   *   Redirects the user to the home page upon successful login.
   */
  public function checkUserCredentails($username, $password,$role) {
    // Begin a database transaction
    $this->conn->begin_transaction();
    
    // SQL query to retrieve the username and hashed password
    $sql = "SELECT UserName, hashed_password FROM user WHERE username = ?";
    
    try {
      // Prepare and execute the SQL query
      $stmt = $this->conn->prepare($sql);
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $stmt->store_result();
      
      // Check if a row was found with the given username
      if ($stmt->num_rows > 0) {
        $hashed_password = NULL;
        
        // Retrieve the hashed password
        $stmt->bind_result($username, $hashed_password);
        $stmt->fetch();
        
        // Verify the password
        if (password_verify($password, $hashed_password)) {
          // Password is correct, set session variables and redirect to home page
          $_SESSION['loggedin'] = true;
          $_SESSION['username'] = $username;
          if($role=="admin"){
            $_SESSION['admin']=true;
            header("Location:/addBook.php");
          }else{
            $_SESSION['admin']=false;
            header("Location:/overview.php");
          }
        } else {
          // Password is incorrect, display error message and redirect to login page
          echo "<script>alert('Incorrect Password');  window.location.href ='/login.php';</script>";
        }
      } else {
        // Username not found, display error message and redirect to login page
        echo "<script>alert('Username Not Found');  window.location.href ='/login.php';</script>";
      }
    } catch (\mysqli_sql_exception $e) {
      // Rollback transaction and return error message
      $this->conn->rollback();
      return "Error: " . $e->getMessage();
    }
  }
  public function getRoleByUsername($username) {
    // Prepare SQL statement
    $stmt = $this->conn->prepare("SELECT role FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['role'];
  }
}

// Create an instance of the Checklogin class
$login = new Checklogin();

// Check if the login form is submitted
if (isset($_POST['submit'])) {
  // Connect to the database
  $login->connection(SERVER_NAME, USER_NAME, PASSWORD, DB_NAME);
  
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];
  $role=$login->getRoleByUsername($username);
  // Check user credentials
  $login->checkUserCredentails($username, $password,$role);

  // Close the database connection
  $login->closeConnection();
}
