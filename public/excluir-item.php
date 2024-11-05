<?php
require_once 'db_connection.php'; 


if (isset($_POST['token'], $_POST['tipo'], $_POST['id'])) {
    $token = $_POST['token'];
    $tipo = $_POST['tipo'];
    $id = (int)$_POST['id'];

    
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE api_token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    
    if ($stmt->rowCount() === 0) {
        echo json_encode(['error' => 'Token inválido']);
        exit;
    }

    
    if ($tipo === 'lista') {
        $stmt = $pdo->prepare("DELETE FROM listas WHERE id = :id");
    } elseif ($tipo === 'cartao') {
        $stmt = $pdo->prepare("DELETE FROM cartoes WHERE id = :id");
    } else {
        echo json_encode(['error' => 'Tipo de item inválido']);
        exit;
    }

    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Item excluído com sucesso']);
    } else {
        echo json_encode(['error' => 'Falha ao excluir item']);
    }
} else {
    echo json_encode(['error' => 'Dados incompletos']);
}
?>
