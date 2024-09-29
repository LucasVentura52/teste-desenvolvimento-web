<?php
session_start();
include '../../public/db.php';
include '../models/User.php';
include '../controllers/AuthController.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['login_message'] = "Você está logado!";
            header("Location: index.php");
            exit();
        } else {
            $message = "Usuário ou senha inválidos!";
        }
    } else {
        $message = "Usuário ou senha inválidos!";
    }
}
?>

<style>
    body {
        opacity: 0;
        transition: opacity 0.8s ease-in-out;
    }

    body.loaded {
        opacity: 1;
    }
</style>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/CSS/style.css">
</head>

<body>
    <div class="container form-container">
        <div class="form-box">
            <h1 class="d-flex justify-content-center mt-5">Login</h1>
            <?php if ($message): ?>
                <div class="alert alert-danger"><?php echo $message; ?></div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label mt-3">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="d-flex justify-content-center mt-5">
                    <button type="submit" class="btn btn-success me-2">Entrar</button>
                    <a href="register.php" class="btn btn-warning">Cadastrar-se</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/assets/JS/script.js"></script>
</body>

</html>