<?php
session_start();

if (!isset($_SESSION['icone'])) {
    die('Nenhuma imagem encontrada.');
}

$imagePath = $_SESSION['icone'];

if (!file_exists($imagePath)) {
    die('Imagem não encontrada.');
}

header('Content-Type: image/jpeg');
readfile($imagePath);
exit();
?>
