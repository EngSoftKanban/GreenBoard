<?php
// atualizar_etiqueta.php
header('Content-Type: application/json');

// Verifica se o método da requisição é PUT
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Lê o conteúdo da requisição
    $input = json_decode(file_get_contents('php://input'), true);

    // Obtém os dados
    $id = $input['id'] ?? null;
    $nome = $input['nome'] ?? '';
    $cor = $input['cor'] ?? '';

    // Validação básica
    if (empty($id) || empty($nome) || empty($cor)) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos!']);
        exit;
    }

    // Conexão com o banco de dados
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=seu_banco', 'usuario', 'senha');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Prepara e executa a atualização
        $stmt = $pdo->prepare("UPDATE etiquetas SET nome = ?, cor = ? WHERE id = ?");
        $result = $stmt->execute([$nome, $cor, $id]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Etiqueta atualizada com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar a etiqueta.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro de conexão: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
}
?>