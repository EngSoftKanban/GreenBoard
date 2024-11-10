<?php
namespace EngSoftKanban\GreenBoard\Model;

use \PDO;

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
    
    public function atualizarPosicoes($lista_id, $posicao_cartao) {
		$cartoes = $this->getCartoesByListaId($lista_id);
        foreach ($cartoes as $cartao) {
			if ($cartao['posicao'] > $posicao_cartao) {
				$sql = "UPDATE cartoes SET posicao = :posicao WHERE id = :id";
				$stmt = $this->pdo->prepare($sql);
				$nova_pos = $cartao['posicao'] - 1;
				$stmt->bindParam(':posicao', $nova_pos, PDO::PARAM_INT);
				$stmt->bindParam(':id', $cartao['id'], PDO::PARAM_INT);
				$stmt->execute();
			}
		}
		return true;
	}

    public function addEtiqueta($nome, $cor, $cartao_id) {
        $sql = "INSERT INTO etiquetas (nome, cor, cartao_id) VALUES (:nome, :cor, :cartao_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cor', $cor);
        $stmt->bindParam(':cartao_id', $cartao_id);
        return $stmt->execute();
    }

    // Método para obter etiquetas por cartão
    public function getEtiquetasByCartao($cartao_id) {
        $sql = "SELECT * FROM etiquetas WHERE cartao_id = :cartao_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':cartao_id', $cartao_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para editar uma etiqueta
    public function updateEtiqueta($id, $nome, $cor) {
        $sql = "UPDATE etiquetas SET nome = :nome, cor = :cor WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cor', $cor);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Método para excluir uma etiqueta
    public function deleteEtiqueta($id) {
        $sql = "DELETE FROM etiquetas WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
