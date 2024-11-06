<?php
require_once 'db_connection.php'; 


if (isset($_POST['id'], $_POST['tipo'], $_POST['novo_valor'])) {
    $id = (int)$_POST['id'];
    $tipo = $_POST['tipo'];
    $novo_valor = $_POST['novo_valor'];

    
    if (!empty($_POST['token'])) {
        
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE api_token = :token");
        $stmt->bindParam(':token', $_POST['token']);
        $stmt->execute();

        
        if ($stmt->rowCount() === 0) {
            echo json_encode(['error' => 'Token inválido']);
            exit;
        }
    } 

    
    if ($tipo === 'lista') {

        $stmt = $pdo->prepare("UPDATE listas SET titulo = :novo_valor WHERE id = :id");
    } elseif ($tipo === 'cartao') {
    
        $stmt = $pdo->prepare("UPDATE cartoes SET corpo = :novo_valor WHERE id = :id");
    } else {
        echo json_encode(['error' => 'Tipo de item inválido']);
        exit;
    }


    $stmt->bindParam(':novo_valor', $novo_valor);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        echo json_encode('Item atualizado com sucesso');
    } else {
        echo json_encode('Falha ao atualizar item');
    }
} else {
    echo json_encode(['error' => 'Dados incompletos']);
}
?>
