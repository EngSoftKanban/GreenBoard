<?php
namespace EngSoftKanban\GreenBoard\Model;

use \PDO;

class Token {
	private PDO $pdo;

	public function __construct($pdo) {
		$this->pdo = $pdo;
	}

	public function criar($quadro_id, $usuario_id) {
		$token = md5(strval(time()));
		$stmt = $this->pdo->prepare("INSERT INTO tokens (token, quadro_id, usuario_id) VALUES ('$token', $quadro_id, $usuario_id)");
		return $stmt->execute();
	}

	public function ler($token_id) {
		$stmt = $this->pdo->prepare("SELECT * FROM tokens WHERE id = $token_id");
		$stmt->execute();
		return $stmt->fetch();
	}

	public function lerPorQuadro($quadro_id) {
		$stmt = $this->pdo->prepare("SELECT * FROM tokens WHERE quadro_id = $quadro_id");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function lerQuadrosPermitidos($quadro_id, $usuario_id) {
		$stmt = $this->pdo->prepare("SELECT *
						FROM quadros INNER JOIN (SELECT quadro_id FROM permissoes WHERE eh_admin = true AND usuario_id = $usuario_id) p
						ON quadros.id = p.quadro_id");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function ehAutorizada($token, $quadro_id) {
		$stmt = $this->pdo->query("SELECT * FROM tokens WHERE token = '$token'");
		$stmt->execute();
		$rs = $stmt->fetch();
		return $rs ? $rs['quadro_id'] == $quadro_id : $rs;
	}

	public function excluir($token_id) {
		$stmt = $this->pdo->query("DELETE FROM tokens WHERE id = $token_id");
		return $stmt->execute();
	}
}
