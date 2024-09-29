<?php
session_start();
include '../../public/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $img_url = $_POST['img_url'];
    $author_id = $_SESSION['user_id'];

    if (empty($title) || empty($description)) {
        $error_message = "O título e a descrição são obrigatórios!";
    } else {
        $sql = "INSERT INTO Posts (title, description, img_url, author) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $description, $img_url, $author_id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Erro ao criar a publicação!";
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
    <title>Criar Nova Publicação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/CSS/stylePost.css">
</head>

<body>

    <div class="container">
        <h1 class="d-flex justify-content-center mt-5">Criar Nova Publicação</h1>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="create_post.php" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label mt-3">Título</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea name="description" class="form-control" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <label for="img_url" class="form-label">URL da Imagem (opcional)</label>
                <input type="url" name="img_url" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Criar Publicação</button>
            <a href="index.php" class="btn btn-primary">Voltar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/assets/JS/script.js"></script>
</body>

</html>