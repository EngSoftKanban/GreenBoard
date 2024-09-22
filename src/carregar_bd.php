<?php
require 'vendor/autoload.php'; // Inclui o autoloader do Composer

// Carrega as variáveis de ambiente do arquivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Usa as variáveis de ambiente
$host = $_ENV['DB_HOST'];
$db = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];

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
