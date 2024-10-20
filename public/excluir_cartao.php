<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_connection.php';  
require_once '../controllers/CartoesController.php';

$cartaoController = new CartaoController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Chama a função de exclusão e verifica o resultado
    $resultado = $cartaoController->deletar($id);
    echo $resultado; // Deve retornar JSON
} else {
    echo json_encode(['success' => false, 'message' => 'ID do cartão não fornecido.']);
}
?>
