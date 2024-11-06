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
		$stmt = $this->pdo->prepare('SELECT * FROM cartoes WHERE lista_id = :lista_id');
		$stmt->bindParam(':lista_id', $lista_id);
		$stmt->execute();
		$cartoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$num = count($cartoes);
		$stmt2 = $this->pdo->prepare('INSERT INTO cartoes (corpo, posicao, lista_id) VALUES (:corpo, :pos, :lista_id)');
		$stmt2->bindParam(':corpo', $corpo);
		$stmt2->bindParam(':pos', $num);
		$stmt2->bindParam(':lista_id', $lista_id);
		return $stmt2->execute();
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

	public function acharPorCorpo($corpo) {
        $stmt = $this->pdo->prepare('SELECT * FROM cartoes WHERE corpo = :corpo');
        $stmt->bindParam(':corpo', $corpo);
        $stmt->execute();
        return $stmt->fetch();
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
}
