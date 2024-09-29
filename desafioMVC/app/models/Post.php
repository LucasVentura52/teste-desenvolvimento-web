<?php
class Post
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllPosts()
    {
        $sql = "SELECT posts.*, users.name AS author_name FROM Posts AS posts 
            JOIN Users AS users ON posts.author = users.id";
        return $this->conn->query($sql);
    }
}
?>