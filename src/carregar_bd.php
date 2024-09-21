<?php
// credenciais do banco de dados
$host = 'localhost';
$db = 'GreenBoard'; 
$user = 'root'; 
$pass = ''; 

header('Content-Type: application/json'); 

try {
    // Conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // T0 - detectar o pedido de envio de dados
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['quadro_id'])) {
        $quadroId = $_GET['quadro_id'];

        // T1 - ler os dados do quadro no banco de dados
        $stmt = $pdo->prepare("
            SELECT l.titulo, c.corpo
            FROM listas l
            JOIN cartoes c ON l.id = c.lista_id
            WHERE l.quadro_id = :quadro_id
        ");
        $stmt->execute(['quadro_id' => $quadroId]); // Executa a consulta

        $results = [];

        // T2 - para enviar dados para o cliente
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = [
                'lista' => htmlspecialchars($row['titulo']),
                'cartao' => htmlspecialchars($row['corpo']),
            ];
        }

        // para enviar os dados como JSON
        echo json_encode($results);
    } else {
        echo json_encode(['error' => 'Método não permitido ou quadro_id não fornecido.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro: ' . $e->getMessage()]);
}
?>
