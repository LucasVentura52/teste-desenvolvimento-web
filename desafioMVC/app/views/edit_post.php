<?php
session_start();
include '../../public/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $sql = "SELECT * FROM Posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();

        if ($post['author'] != $user_id) {
            echo "<script>alert('Você não tem permissão para editar esta publicação.'); window.location.href='index.php';</script>";
            exit();
        }

    } else {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

$message = "";
$message2 = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $img_url = $_POST['img_url'];

    $sql_update = "UPDATE Posts SET title = ?, description = ?, img_url = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssi", $title, $description, $img_url, $post_id);

    if ($stmt_update->execute()) {
        $message = "Publicação atualizada com sucesso.";
    } else {
        $message2 = "Erro ao atualizar a publicação. Tente novamente.";
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
    <title>Editar Publicação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/CSS/stylePost.css">
</head>

<body>

    <div class="container">
        <h1 class="mt-5">Editar Publicação</h1>
        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if (!empty($message2)): ?>
            <div class="alert alert-danger"><?php echo $message2; ?></div>
        <?php endif; ?>

        <form action="edit_post.php?id=<?php echo $post['id']; ?>" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Título</label>
                <input type="text" name="title" class="form-control"
                    value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <input type="text" name="description" class="form-control"
                    value="<?php echo htmlspecialchars($post['description']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="img_url" class="form-label">URL da Imagem</label>
                <input type="text" name="img_url" class="form-control"
                    value="<?php echo htmlspecialchars($post['img_url']); ?>">
            </div>
            <div class="mt-5">
                <button type="submit" class="btn btn-success me-2">Atualizar Publicação</button>
                <a href="index.php" class="btn btn-primary">Voltar para Publicações</a>
            </div>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/assets/JS/script.js"></script>
</body>

</html>