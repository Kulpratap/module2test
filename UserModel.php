<?php
require('Database.php');
require('Config.php');
 class UserModel {
  use Database;
  public function getNames($username) {
    // SQL query to fetch first and last names from the 'users' table
    $sql = "SELECT first_name, last_name FROM user where UserName='$username'";

    $result = $this->conn->query($sql);

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Fetch rows
        $names = array();
        while($row = $result->fetch_assoc()) {
            $names[] = array(
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name']
            );
        }

        // Return the names array
        return $names;
    } else {
        return null;
    }
}
public function readDataFromContinueReading(){
    $sql = "SELECT * FROM continue_reading";
    $result = $this->conn->query($sql);

    if ($result === false) {
        // Handle query error
        die("Error executing query: " . $this->conn->error);
    }

    // Fetch data from the result
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Free the result set
    $result->free();

    return $data;
}
public function readDataFromBucket(){
    $sql = "SELECT * FROM bucket";
    $result = $this->conn->query($sql);

    if ($result === false) {
        // Handle query error
        die("Error executing query: " . $this->conn->error);
    }

    // Fetch data from the result
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Free the result set
    $result->free();

    return $data;
}
// Destructor to close the database connection
public function __destruct() {
    // Close connection
    $this->conn->close();
}
 }
?>