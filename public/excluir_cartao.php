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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['excluir_cartao_id'])) {
    $cartao_id = $_POST['excluir_cartao_id'];

    
    $sql = "DELETE FROM cartoes WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $cartao_id);

    if ($stmt->execute()) {
        echo "Cartão excluído com sucesso!";
    } else {
        echo "Erro ao excluir cartão.";
    }
} else {
    echo "Método de requisição inválido.";
}

