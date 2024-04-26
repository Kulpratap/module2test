<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['post_id'])) {
    $postId = $_POST['post_id'];
    $username=$_SESSION['username'];
    $conn = new mysqli("localhost", "kul", "Kul@123456", "USERS");
    // Perform the delete operation in your database
    $sql = "DELETE FROM bucket WHERE book_id = '$postId' AND user_name='$username'";
    $conn->query($sql);
} else {
    http_response_code(400);
    echo 'Post ID is required.';
}
?>