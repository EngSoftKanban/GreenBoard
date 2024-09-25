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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['titulo_lista']) && isset($_POST['quadro_id'])) {
    $titulo_lista = $_POST['titulo_lista'];
    $quadro_id = $_POST['quadro_id'];

    // Verificar se o quadro_id existe na tabela quadros
    $sqlCheckQuadro = "SELECT id FROM quadros WHERE id = :quadro_id";
    $stmtCheck = $pdo->prepare($sqlCheckQuadro);
    $stmtCheck->bindParam(':quadro_id', $quadro_id);
    $stmtCheck->execute();

    if ($stmtCheck->rowCount() > 0) {
        // Inserir a nova lista com quadro_id
        $sql = "INSERT INTO listas (titulo, posicao, quadro_id) VALUES (:titulo, 0, :quadro_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':titulo', $titulo_lista);
        $stmt->bindParam(':quadro_id', $quadro_id);

        if ($stmt->execute()) {
            echo "Lista adicionada com sucesso!";
        } else {
            echo "Erro ao adicionar a lista.";
        }
    } else {
        echo "O quadro especificado não existe.";
    }
} else {
    echo "Método de requisição inválido ou dados incompletos.";
}
?>