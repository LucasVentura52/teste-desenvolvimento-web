<?php
include_once 'app/models/User.php';
include '../models/User.php';


class UserController
{
    private $userModel;

    public function __construct($db)
    {
        $this->userModel = new User($db);
    }
}
?>