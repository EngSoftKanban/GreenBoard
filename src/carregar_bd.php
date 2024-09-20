<?php
// Definindo as credenciais do banco de dados
$host = 'localhost';
$db = 'GreenBoard'; // Nome do banco de dados
$user = 'root'; // Nome do usuário
$pass = ''; // Senha, se houver. Deixe vazio se não houver.

try {
    // Conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para carregar cartões e listas
    $stmt = $pdo->prepare("
        SELECT l.titulo, c.corpo
        FROM listas l
        JOIN cartoes c ON l.id = c.lista_id
        WHERE l.quadro_id = :quadro_id
    ");
    $stmt->execute(['quadro_id' => 1]); // ou o ID do quadro desejado

    // Exibindo os resultados
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Lista: " . htmlspecialchars($row['titulo']) . " - Cartão: " . htmlspecialchars($row['corpo']) . "<br>";
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
