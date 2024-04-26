<?php
class PostLoader
{
    private $conn;
    public function loadMorePosts($offset)
    {
        $this->conn = new mysqli("localhost", "kul", "Kul@123456", "USERS");
        $sql = "SELECT * FROM books ORDER BY publication_date DESC LIMIT 9";
        // Prepare the statement
        $stmt = $this->conn->prepare($sql);
        // Execute the statement
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return json_encode($posts);
        
    }
}
$postLoader = new PostLoader();
if (isset($_GET['offset']) && is_numeric($_GET['offset'])) {
    $offset = $_GET['offset'];
    $posts = $postLoader->loadMorePosts($offset);
    echo $posts;
} else {
    echo json_encode(["error" => "Invalid offset"]);
}