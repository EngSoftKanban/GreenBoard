<?php
$host = apache_getenv("DB_HOST");
$dbname = apache_getenv("DB_NAME");
$user = apache_getenv("DB_USER");
$password = apache_getenv("DB_PASS");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}

// Verifica se há uma ação de editar item (lista ou cartão)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['action'] === 'editarItem') {
    $item_id = $_POST['editar_item_id'];
    $item_tipo = $_POST['editar_item_tipo'];
    $item_texto = $_POST['editar_item_texto'];

    // Prepara a consulta SQL para atualizar o item
    $sqlItem = strcmp($item_tipo, "lista") == 0 
        ? "UPDATE listas SET titulo = :texto WHERE id = :id" 
        : "UPDATE cartoes SET corpo = :texto WHERE id = :id";
    
    $stmtItem = $pdo->prepare($sqlItem);
    $stmtItem->bindParam(':id', $item_id);
    $stmtItem->bindParam(':texto', $item_texto);

    // Executa a consulta e retorna resposta JSON
    if ($stmtItem->execute()) {
        echo json_encode(['success' => true, 'message' => strcmp($item_tipo, "lista") == 0 
            ? "Título da lista atualizado" 
            : "Corpo do cartão atualizado"]);
    } else {
        echo json_encode(['success' => false, 'message' => strcmp($item_tipo, "lista") == 0 
            ? "Lista não encontrada." 
            : "Cartão não encontrado."]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "Método de requisição inválido."]);
}
?>

