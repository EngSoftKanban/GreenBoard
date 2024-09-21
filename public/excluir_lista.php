<?php

$host = 'localhost';
$dbname = 'GreenBoard';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['excluir_lista_id'])) {
    $lista_id = $_POST['excluir_lista_id'];

    
    $sql = "DELETE FROM listas WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $lista_id);

    if ($stmt->execute()) {
        echo "Lista excluída com sucesso!";
    } else {
        echo "Erro ao excluir lista.";
    }
} else {
    echo "Método de requisição inválido.";
}
?>
