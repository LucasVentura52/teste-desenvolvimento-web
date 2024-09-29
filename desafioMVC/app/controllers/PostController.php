<?php
class PostController
{
    private $postModel;

    public function __construct($postModel)
    {
        $this->postModel = $postModel;
    }

    public function index()
    {
        return $this->postModel->getAllPosts();
    }
    public function getAllPosts()
    {
        global $conn;
        $query = "SELECT * FROM posts";
        $result = $conn->query($query);
        return $result;
    }

}
?>