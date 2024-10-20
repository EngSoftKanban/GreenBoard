<?php
class Cartao {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarPorLista($listaId) {
        $sql = "SELECT * FROM cartoes WHERE lista_id = :lista_id ORDER BY posicao";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':lista_id', $listaId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionarCartao($corpo, $lista_id) {
        $sql = "INSERT INTO cartoes (corpo, posicao, lista_id) VALUES (:corpo, 0, :lista_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':corpo', $corpo);
        $stmt->bindParam(':lista_id', $lista_id);
        return $stmt->execute();
    }

    public function atualizarCartao($id, $corpo) {
        $sql = "UPDATE cartoes SET corpo = :corpo WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':corpo', $corpo);
        return $stmt->execute();
    }

    public function deletarCartao($id) {
        $sql = "DELETE FROM cartoes WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getCartoesByListaId($lista_id) {
        $sql = "SELECT * FROM cartoes WHERE lista_id = :lista_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':lista_id', $lista_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getCartaoById($id) {
        $sql = "SELECT * FROM cartoes WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarPosicoes($cartoes) {
        foreach ($cartoes as $cartao) {
            $sql = "UPDATE cartoes SET posicao = :posicao, lista_id = :lista_id WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':posicao', $cartao['position'], PDO::PARAM_INT);
            $stmt->bindParam(':lista_id', $cartao['lista_id'], PDO::PARAM_INT);
            $stmt->bindParam(':id', $cartao['id'], PDO::PARAM_INT);
            $stmt->execute();
        }
        return true;
    }

}
