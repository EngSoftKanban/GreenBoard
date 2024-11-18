<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'src/bdpdo.php'; 


if (isset($_POST['token'], $_POST['tipo'], $_POST['valor'])) {
    $token = $_POST['token'];
    $tipo = $_POST['tipo'];
    $valor = $_POST['valor'];

    
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE api_token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    
    if ($stmt->rowCount() === 0) {
        echo json_encode(['error' => 'Token inválido']);
        exit;
    }

    
    if ($tipo === 'lista') {
        
        if (!isset($_POST['quadro_id'])) {
            echo json_encode(['error' => 'ID do quadro não fornecido']);
            exit;
        }
        $quadro_id = (int)$_POST['quadro_id'];
        $stmt = $pdo->prepare("INSERT INTO listas (titulo, quadro_id, posicao) VALUES (:valor, :quadro_id, :posicao)");
        $posicao = 0; 
        $stmt->bindParam(':quadro_id', $quadro_id);
        $stmt->bindParam(':posicao', $posicao);
    } elseif ($tipo === 'cartao') {
        
        if (!isset($_POST['lista_id'])) {
            echo json_encode(['error' => 'ID da lista não fornecido']);
            exit;
        }
        $lista_id = (int)$_POST['lista_id'];
        $stmt = $pdo->prepare("INSERT INTO cartoes (corpo, lista_id, posicao) VALUES (:valor, :lista_id, :posicao)");
        $posicao = 0; 
        $stmt->bindParam(':lista_id', $lista_id);
        $stmt->bindParam(':posicao', $posicao);
    } else {
        echo json_encode(['error' => 'Tipo de item inválido']);
        exit;
    }

    // Executa a inserção
    $stmt->bindParam(':valor', $valor);
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Item adicionado com sucesso']);
    } else {
        echo json_encode(['error' => 'Falha ao adicionar item']);
    }
} else {
    echo json_encode(['error' => 'Dados incompletos']);
}
?>
