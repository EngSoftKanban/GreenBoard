<?php
namespace EngSoftKanban\GreenBoard\Model;

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método para encontrar um usuário pelo e-mail
    public function findUser($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC); // Retorna os dados do usuário como um array associativo
    }

    // Método para registrar um novo usuário
    public function register($nome, $email, $senha) {
        $hashedPassword = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        return $stmt->execute([$nome, $email, $hashedPassword]);
    }

    // Outros métodos podem ser adicionados aqui conforme necessário
}
?>
