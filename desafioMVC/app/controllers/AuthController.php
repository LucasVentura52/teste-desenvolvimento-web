<?php
class AuthController
{
    private $userModel;

    public function __construct($userModel)
    {
        $this->userModel = $userModel;
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userModel->login($email, $password);
            if ($user) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: /public/index.php");
                exit();
            } else {
                return "Usuário ou senha inválidos!";
            }
        }
        return "";
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: /public/login.php");
        exit();
    }

}
?>