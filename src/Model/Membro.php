<?php
namespace EngSoftKanban\GreenBoard\Model;

use \PDO;

class Membro {
	private $pdo;

	public function __construct($pdo) {
		$this->pdo = $pdo;
	}

	public function listarMembros($cartao_id) {
		$sql = "SELECT * FROM adicionados WHERE cartao_id = :cartao_id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':cartao_id', $cartao_id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function encontrar($usuario_id, $cartao_id) {
		$sql = "SELECT * FROM adicionados WHERE cartao_id = :cartao_id and usuario_id = :usuario_id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
		$stmt->bindParam(':cartao_id', $cartao_id, PDO::PARAM_INT);
		$stmt->execute();
		return !empty($stmt->fetch(PDO::FETCH_ASSOC)); 
	}

	public function adicionarUsuario($usuario_id, $cartao_id) {
		$sql = "INSERT INTO adicionados (usuario_id, cartao_id) VALUES (:usuario_id, :cartao_id)";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':usuario_id', $usuario_id);
		$stmt->bindParam(':cartao_id', $cartao_id);
		return $stmt->execute();
	}

	public function removerUsuario($usuario_id, $cartao_id) {
		$sql = "DELETE FROM adicionados WHERE usuario_id = :usuario_id and cartao_id = :cartao_id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
		$stmt->bindParam(':cartao_id', $cartao_id, PDO::PARAM_INT);
		return $stmt->execute();
	}

	public function getIcone($usuario_id) {
		$sql = "SELECT icone FROM usuarios WHERE id = :usuario_id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':usuario_id', $usuario_id);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC)['icone'];
	}
}