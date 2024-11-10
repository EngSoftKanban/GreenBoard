<?php
namespace EngSoftKanban\GreenBoard;

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'db_connection.php';

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Controller/CartaoController.php';

use EngSoftKanban\GreenBoard\Controller\CartaoController;

$cartaoController = new CartaoController($pdo);

header('Content-Type: application/json; charset=UTF-8');
ob_clean();

$input = json_decode(file_get_contents('php://input'), true);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($input['id'], $input['nome'], $input['cor'])) {
        $response = $cartaoController->updateEtiqueta($input['id'], $input['nome'], $input['cor']);
        echo json_encode(['success' => true, 'message' => 'Etiqueta atualizada com sucesso!']);
        exit();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($input['nome'], $input['cor'], $input['cartao_id'])) {
        $response = $cartaoController->adicionarEtiqueta($input['nome'], $input['cor'], $input['cartao_id']);
        echo json_encode(['success' => true, 'message' => 'Etiqueta adicionada com sucesso!']);
        exit();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($input['id'])) {
        $response = $cartaoController->deleteEtiqueta($input['id']);
        echo json_encode(['success' => true, 'message' => 'Etiqueta excluída com sucesso!']);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos!']);
        exit();
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
    exit();
}
?>