<?php
namespace EngSoftKanban\GreenBoard\Model;

use \PDO;

class Lista {
	private PDO $pdo;

	public function __construct($pdo) {
		$this->pdo = $pdo;
	}

	public function listar($quadro_id) {
		$sql = "SELECT * FROM listas WHERE quadro_id = :quadro_id ORDER BY posicao";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':quadro_id', $quadro_id, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function criar($titulo, $quadro_id) {
		$stmt = $this->pdo->prepare('SELECT * FROM listas WHERE quadro_id = :quadro_id');
		$stmt->bindParam(':quadro_id', $quadro_id);
		$stmt->execute();
		$listas = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$num = count($listas);
		$stmt2 = $this->pdo->prepare('INSERT INTO listas (titulo, posicao, quadro_id) VALUES (:titulo, :pos, :quadro_id)');
		$stmt2->bindParam(':titulo', $titulo);
		$stmt2->bindParam(':pos', $num);
		$stmt2->bindParam(':quadro_id', $quadro_id);
		return $stmt2->execute();  // Retorna true se bem sucedido
	}

	public function atualizar($lista_id, $titulo) {
		$stmt = $this->pdo->prepare('UPDATE listas SET titulo = :titulo WHERE id = :id');
		$stmt->bindParam(':id', $lista_id);
		$stmt->bindParam(':titulo', $titulo);
		return $stmt->execute();
	}

	public function remover($lista_id) {
		$stmt = $this->pdo->prepare('DELETE FROM listas WHERE id = :lista_id');
		$stmt->bindParam(':lista_id', $lista_id);
		return $stmt->execute();  // Retorna true se bem sucedido
	}

	public function lerListaPorId($lista_id) {
		$stmt = $this->pdo->prepare('SELECT * FROM listas WHERE id = :id');
		$stmt->bindParam(':id', $lista_id);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function lerListasEmQuadro($quadro_id) {
		$stmt = $this->pdo->prepare('SELECT * FROM listas WHERE quadro_id = :quadro_id');
		$stmt->bindParam(':quadro_id', $quadro_id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function atualizarPosicoes($quadro_id, $lista_pos) {
		$listas = $this->lerListasEmQuadro($quadro_id);
		foreach ($listas as $lista) {
			if ($lista['posicao'] > $lista_pos) {
				$stmt = $this->pdo->prepare('UPDATE listas SET posicao = :posicao WHERE id = :id');
				$nova_pos = $lista['posicao'] - 1;
				$stmt->bindParam(':posicao', $nova_pos, PDO::PARAM_INT);
				$stmt->bindParam(':id', $lista['id'], PDO::PARAM_INT);
				$stmt->execute();
			}
		}
		return true;
	}

	public function editarPosListas($listas) {
		foreach ($listas as $lista) {
			$stmt = $this->pdo->prepare("UPDATE listas SET posicao = $lista->posicao WHERE id = :id");
			$stmt->bindParam(':id', $lista->id, PDO::PARAM_INT);
			if (!$stmt->execute()) {
				return false;
			}
		}
		return true;
	}

	public function possuiCartao($lista_id) {
		$stmt = $this->pdo->prepare('SELECT * FROM cartoes WHERE lista_id = :lista_id');
		$stmt->bindParam(':lista_id', $lista_id);
		$stmt->execute();
		return count($stmt->fetchAll(PDO::FETCH_ASSOC)) > 0;
	}
}
