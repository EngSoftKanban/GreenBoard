<?php

require 'load_env.php';

$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['corpo_cartao']) && isset($_POST['lista_id'])) {
    $corpo_cartao = $_POST['corpo_cartao'];
    $lista_id = $_POST['lista_id'];

    // Prepara o SQL para inserir o novo cartão
    $sql = "INSERT INTO cartoes (corpo, posicao, lista_id) VALUES (:corpo, 0, :lista_id)"; // Usando 0 como posição inicial
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':corpo', $corpo_cartao);
    $stmt->bindParam(':lista_id', $lista_id);

    if ($stmt->execute()) {
        echo "Cartão adicionado com sucesso!";
    } else {
        echo "Erro ao adicionar o cartão.";
    }
} else {
    echo "Método de requisição inválido ou dados incompletos.";
}
?>