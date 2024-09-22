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

    
    $sqlCartoes = "DELETE FROM cartoes WHERE lista_id = :lista_id";
    $stmtCartoes = $pdo->prepare($sqlCartoes);
    $stmtCartoes->bindParam(':lista_id', $lista_id);
    
    if ($stmtCartoes->execute()) {
    
        $sqlLista = "DELETE FROM listas WHERE id = :id";
        $stmtLista = $pdo->prepare($sqlLista);
        $stmtLista->bindParam(':id', $lista_id);

        if ($stmtLista->execute()) {
            echo "Lista e cartões excluídos com sucesso!";

            
            $sqlAtualizarPosicoes = "UPDATE listas SET posicao = posicao - 1 WHERE posicao > (SELECT posicao FROM listas WHERE id = :id)";
            $stmtAtualizarPosicoes = $pdo->prepare($sqlAtualizarPosicoes);
            $stmtAtualizarPosicoes->bindParam(':id', $lista_id);
            $stmtAtualizarPosicoes->execute();
        } else {
            echo "Erro ao excluir a lista.";
        }
    } else {
        echo "Erro ao excluir os cartões da lista.";
    }
} else {
    echo "Método de requisição inválido.";
}
?>
