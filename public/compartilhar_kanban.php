<?php
// compartilhar_kanban.php

// Conectar ao banco de dados
$host = apache_getenv("DB_HOST");
$dbname = apache_getenv("DB_NAME");
$user = apache_getenv("DB_USER");
$password = apache_getenv("DB_PASS");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Verificar se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $user = trim($data['user']); // remove espaços em branco
    $shareLink = $data['link'];

    // Validação
    if (empty($user)) {
        echo json_encode(['message' => 'Nome de usuário não pode estar vazio']);
        exit;
    }

    // Verificar se o usuário já existe
    $stmtCheck = $pdo->prepare("SELECT * FROM usuarios WHERE nome = :user");
    $stmtCheck->bindParam(':user', $user);
    $stmtCheck->execute();

    // Se o usuário não existe, não o adiciona ao Kanban
    if ($stmtCheck->rowCount() === 0) {
        echo json_encode(['message' => 'Usuário não existe. Não é possível adicionar ao Kanban.']);
        exit;
    }

    if ($shareLink) {
        // Se a opção de compartilhar via link foi marcada
        echo json_encode(['message' => 'Kanban compartilhado com link']);
    } else {
        // Se foi inserido um usuário específico
        echo json_encode(['message' => 'Usuário ' . htmlspecialchars($user) . ' já existe e pode ser adicionado ao kanban']);
    }
}
?>
