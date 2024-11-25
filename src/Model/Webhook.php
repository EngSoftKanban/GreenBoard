<?php
namespace EngSoftKanban\GreenBoard\Model;

use \PDO;

class Webhook {
	private PDO $pdo;

	public function __construct($pdo) {
		$this->pdo = $pdo;
	}

	public function listarQuadrosEListas($usuario_id) {
		$stmt = $this->pdo->prepare('SELECT q.quadro_id, q.quadro_nome, listas.id as lista_id, listas.titulo FROM listas
			INNER JOIN (SELECT quadros.id as quadro_id, quadros.nome as quadro_nome
						FROM quadros INNER JOIN (SELECT quadro_id FROM permissoes WHERE eh_admin = true AND usuario_id = :usuario_id) p
						ON quadros.id = p.quadro_id) q
			ON q.quadro_id = listas.quadro_id');
		$stmt->bindParam(':usuario_id', $usuario_id);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function criar($usuario_id, $quadro_id, $lista_id) {
		$token = md5(strval(time()));
		$stmt = $this->pdo->prepare('INSERT INTO webhooks (token, usuario_id, quadro_id, lista_id) VALUES (:token, :usuario_id, :quadro_id, :lista_id)');
		$stmt->bindParam(':token', $token);
		$stmt->bindParam(':usuario_id', $usuario_id);
		$stmt->bindParam(':quadro_id', $quadro_id);
		$stmt->bindParam(':lista_id', $lista_id);
		return $stmt->execute(); 
	}

	public function listarWebhooks($usuario_id) {
		$stmt = $this->pdo->prepare('SELECT * from webhooks WHERE usuario_id = :usuario_id');
		$stmt->bindParam(':usuario_id', $usuario_id);
		$stmt->execute(); 
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function ler($hook_id) {
		$stmt = $this->pdo->prepare('SELECT * from webhooks WHERE id = :hook_id');
		$stmt->bindParam(':hook_id', $hook_id);
		$stmt->execute(); 
		return $stmt->fetch();
	}

	public function lerPorToken($token) {
		$stmt = $this->pdo->prepare('SELECT * from webhooks WHERE token = :token');
		$stmt->bindParam(':token', $token);
		$stmt->execute(); 
		return $stmt->fetch();
	}

	public function destruir($hook_id) {
		$stmt = $this->pdo->prepare('DELETE FROM webhooks WHERE id = :hook_id');
		$stmt->bindParam(':hook_id', $hook_id);
		return $stmt->execute();
	}
}
