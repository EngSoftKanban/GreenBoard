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
    foreach ($data as $lista) {
        $sql = "UPDATE listas SET posicao = :posicao WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':posicao', $lista['position'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $lista['id'], PDO::PARAM_INT);
        $stmt->execute();
    }
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
