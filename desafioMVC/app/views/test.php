<?php
session_start();

echo "Testando include do db.php<br>";
include '../../public/db.php';

echo "Testando include do User.php<br>";
include '../models/User.php';

echo "Testando include do AuthController.php<br>";
include '../controllers/AuthController.php';

echo "Todos os arquivos foram incluídos com sucesso.";
?>
