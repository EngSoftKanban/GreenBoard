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

    
    $sqlCartao = "SELECT lista_id, posicao FROM cartoes WHERE id = :id";
    $stmtCartao = $pdo->prepare($sqlCartao);
    $stmtCartao->bindParam(':id', $cartao_id);
    $stmtCartao->execute();
    $cartao = $stmtCartao->fetch(PDO::FETCH_ASSOC);

    if ($cartao) {
        $lista_id = $cartao['lista_id'];
        $posicao_cartao = $cartao['posicao'];

        
        $sqlExcluir = "DELETE FROM cartoes WHERE id = :id";
        $stmtExcluir = $pdo->prepare($sqlExcluir);
        $stmtExcluir->bindParam(':id', $cartao_id);

        if ($stmtExcluir->execute()) {
            
            $sqlAtualizar = "UPDATE cartoes SET posicao = posicao - 1 WHERE lista_id = :lista_id AND posicao > :posicao";
            $stmtAtualizar = $pdo->prepare($sqlAtualizar);
            $stmtAtualizar->bindParam(':lista_id', $lista_id);
            $stmtAtualizar->bindParam(':posicao', $posicao_cartao);
            $stmtAtualizar->execute();

            echo "Cartão excluído e posições atualizadas com sucesso!";
        } else {
            echo "Erro ao excluir o cartão.";
        }
    } else {
        echo "Cartão não encontrado.";
    }
} else {
    echo "Método de requisição inválido.";
}
?>
