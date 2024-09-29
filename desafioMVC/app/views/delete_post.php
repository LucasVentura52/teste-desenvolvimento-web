<?php
session_start();
include '../../public/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM Posts WHERE id = ? AND author = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $sql_delete = "DELETE FROM Posts WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $post_id);

        if ($stmt_delete->execute()) {
            echo "<script>alert('Publicação excluída com sucesso!'); window.location.href='index.php';</script>";
            header("Location: index.php?message=Publicação excluída com sucesso");
            exit();
        } else {
            $error_message = "Erro ao tentar excluir a publicação.";
        }
    } else {
        echo "<script>alert('Você não tem permissão para excluir esta publicação.'); window.location.href='index.php';</script>";
    }
} else {
    $error_message = "Publicação inválida.";
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
    <title>Excluir Publicação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <h1 class="mt-5">Excluir Publicação</h1>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <a href="index.php" class="btn btn-secondary">Voltar para as Publicações</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/assets/JS/script.js"></script>
</body>

</html>