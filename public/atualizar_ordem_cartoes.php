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

// Recebe os dados JSON do JavaScript
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    foreach ($data as $cartao) {
        $sql = "UPDATE cartoes SET posicao = :posicao, lista_id = :lista_id WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':posicao', $cartao['position'], PDO::PARAM_INT);
        $stmt->bindParam(':lista_id', $cartao['lista_id'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $cartao['id'], PDO::PARAM_INT);
        $stmt->execute();
    }
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
