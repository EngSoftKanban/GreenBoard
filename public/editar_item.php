<?php
$host = apache_getenv("DB_HOST");
$dbname = apache_getenv("DB_NAME");
$host = apache_getenv("DB_USER");
$dbname = apache_getenv("DB_PASS");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_item_id'])) {
    $item_id = $_POST['editar_item_id'];
    $item_tipo = $_POST['editar_item_tipo'];
    $item_texto = $_POST['editar_item_texto'];
    
	$sqlCartao = strcmp($item_tipo, "lista") == 0 ? "UPDATE listas SET titulo = :texto WHERE id = :id" : "UPDATE cartoes SET corpo = :texto WHERE id = :id";
    $stmtCartao = $pdo->prepare($sqlCartao);
    $stmtCartao->bindParam(':id', $item_id);
    $stmtCartao->bindParam(':id', $item_texto);
    $stmtCartao->execute();
    $cartao = $stmtCartao->fetch(PDO::FETCH_ASSOC);

    if ($cartao) {
        
    } else {
        echo "Cartão não encontrado.";
    }
} else {
    echo "Método de requisição inválido.";
}
?>