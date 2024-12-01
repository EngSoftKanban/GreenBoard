<?php
namespace EngSoftKanban\GreenBoard\Model;

use \PDO;

class Permissao {
	private PDO $pdo;

	public function __construct($pdo) {
		$this->pdo = $pdo;
	}

	public function lerPorQuadro($quadro_id) {
		$stmt = $this->pdo->prepare("SELECT * FROM permissoes WHERE quadro_id = $quadro_id");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function possuiPermissao($usuario_id, $quadro_id) {
		$stmt = $this->pdo->prepare("SELECT * FROM permissoes WHERE usuario_id = $usuario_id AND quadro_id = $quadro_id");
		$stmt->execute();
		return !empty($stmt->fetch()); 
	}
}
