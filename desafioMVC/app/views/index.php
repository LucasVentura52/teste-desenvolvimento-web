<?php
session_start();
include '../../public/db.php';
include '../models/Post.php';
include '../models/User.php';
include '../controllers/AuthController.php';
include '../controllers/PostController.php';
include '../controllers/LogoutController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!class_exists('User')) {
    die('A classe User não foi encontrada.');
}

$userModel = new User($conn);

$user = $userModel->getUserById(1);

if ($user) {

} else {
    echo "Usuário não encontrado.";
}

$postModel = new Post($conn);
$postController = new PostController($postModel);
$result = $postController->getAllPosts();
$posts = $postController->index();

$sql = "SELECT Posts.*, Users.name AS author_name FROM Posts JOIN Users ON Posts.author = Users.id ORDER BY created_at DESC";
$result = $conn->query($sql);

$loginMessage = isset($_SESSION['loginMessage']) ? $_SESSION['loginMessage'] : '';

$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

$user_id = $_SESSION['user_id'];
$user = $userModel->getUserById($user_id);

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'logout':
            $logoutController = new LogoutController();
            $logoutController->logout();
            break;
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
    <title>Publicações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/CSS/stylePu.css">
</head>

<body class="bg-dark text-white">

    <div class="container">

        <h1 class="mt-5">Publicações</h1>
        <div class="alert alert-success alert-link alert-dismissible fade show" role="alert">
            Você está logado!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <h5 class="mt-4">Bem-vindo, <?php echo $user['name']; ?>!</h5>

        <div class="d-flex justify-content-between mb-3 mt-4">
            <div class="d-flex justify-content-end mb-3">
                <a href="create_post.php" class="btn btn-info text-white me-2">Criar Nova Publicação</a>
            </div>

            <div class="d-flex justify-content-end mb-3">
                <a href="index.php?action=logout" class="btn btn-danger me-2">Logout</a>
                <a href="change_password.php" class="btn btn-warning">Alterar Senha</a>
            </div>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <div class="row">
                <?php while ($post = $result->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="card mb-4 bg-light text-dark">
                            <img src="<?php echo $post['img_url']; ?>" class="card-img-top" alt="Imagem da publicação">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $post['title']; ?></h5>
                                <p class="card-text"><?php echo $post['description']; ?></p>
                                <p class="text-muted">Autor: <?php echo $post['author_name']; ?></p>
                                <p class="text-muted">Data: <?php echo $post['created_at']; ?></p>

                                <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">Editar</a>
                                <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-danger"
                                    onclick="return confirm('Tem certeza que deseja excluir esta publicação?');">Excluir</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>Nenhuma publicação encontrada.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/assets/JS/script.js"></script>
</body>

</html>