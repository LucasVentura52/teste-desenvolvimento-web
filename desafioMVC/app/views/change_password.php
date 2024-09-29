<?php
session_start();
include '../../public/db.php';

$message = "";
$message2 = "";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    $sql = "SELECT password FROM Users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (password_verify($current_password, $user['password'])) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql_update = "UPDATE Users SET password = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $hashed_password, $user_id);
        $stmt_update->execute();

        $message = "Senha alterada com sucesso.";
    } else {
        $message2 = "Senha atual incorreta.";
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
    <title>Alterar Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/CSS/styleSenha.css">
</head>

<body class="d-flex justify-content-center align-items-center min-vh-100">

    <div class="container">
        <div class="d-flex justify-content-center mt-5">
            <h1 class="">Alterar Senha</h1>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if (!empty($message2)): ?>
            <div class="alert alert-danger"><?php echo $message2; ?></div>
        <?php endif; ?>

        <form action="change_password.php" method="POST">
            <div class="mb-3">
                <label for="current_password" class="form-label">Senha Atual</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">Nova Senha</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="mt-5">
                <button type="submit" class="btn btn-success me-2">Alterar Senha</button>
                <a href="index.php" class="btn btn-primary">Voltar para Publicações</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/assets/JS/script.js"></script>
</body>

</html>