<?php
session_start();
include '../../public/db.php';

$message = "";
$message2 = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message2 = "Email já cadastrado.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql_insert = "INSERT INTO Users (name, email, password) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt_insert->execute()) {
            $message = "Cadastro realizado com sucesso.";
        } else {
            $message2 = "Erro ao cadastrar. Tente novamente.";
        }
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
    <title>Cadastrar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/CSS/styleCa.css">
</head>

<body class="d-flex justify-content-center align-items-center min-vh-100">

    <div class="container ">
        <div>
            <h1 class="d-flex justify-content-center mt-5">Cadastrar-se</h1>
        </div>
        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if (!empty($message2)): ?>
            <div class="alert alert-danger"><?php echo $message2; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label mt-3">Nome</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="mt-5">
                <button type="submit" class="btn btn-success me-2">Cadastrar</button>
                <a href="login.php" class="btn btn-primary">Voltar para Login</a>
            </div>
        </form>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../../public/assets/JS/script.js"></script>
</body>

</html>