<?php
namespace EngSoftKanban\GreenBoard\Model;

use \PDO;

class Usuario {
	private PDO $pdo;

	public function __construct($pdo) {
		$this->pdo = $pdo;
	}

	// Método para encontrar um usuário pelo e-mail
	public function findUser($email) {
		$stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
		$stmt->execute([$email]);
		return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna os dados do usuário como um array associativo
	}

	// Método para registrar um novo usuário
	public function cadastrar($nome, $email, $senha) {
		$hashedPassword = password_hash($senha, PASSWORD_DEFAULT);
		$stmt = $this->pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
		return $stmt->execute([$nome, $email, $hashedPassword]);
	}

	public function lerPorIds($usuarios_id) {
		$stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE id IN (' . implode(',', $usuarios_id) . ')');
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
