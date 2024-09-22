<?php
// Usa as variáveis de ambiente
$host = getenv('DB_HOST');
$db = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

header('Content-Type: application/json'); 

try {
    // Conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['quadro_id'])) {
        $quadroId = $_GET['quadro_id'];

        $stmt = $pdo->prepare("
            SELECT l.titulo, c.corpo
            FROM listas l
            JOIN cartoes c ON l.id = c.lista_id
            WHERE l.quadro_id = :quadro_id
        ");
        $stmt->execute(['quadro_id' => $quadroId]);

        $results = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = [
                'lista' => htmlspecialchars($row['titulo']),
                'cartao' => htmlspecialchars($row['corpo']),
            ];
        }

        echo json_encode($results);
    } else {
        echo json_encode(['error' => 'Método não permitido ou quadro_id não fornecido.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro: ' . $e->getMessage()]);
}
?>
