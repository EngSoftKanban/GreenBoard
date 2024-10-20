<?php
namespace EngSoftKanban\GreenBoard\Model;

use PDO;

class PasswordModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        session_start();
    }

    public function isUserExists($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->rowCount() === 1;
    }

    public function generateTokenSession() {
        $token = bin2hex(random_bytes(50));
        $_SESSION['reset_token'] = $token; 
        $_SESSION['token_expiration'] = time() + 3600;
        return $token;
    }

    public function sendResetEmail($email, $token) {
        $link = "http://localhost:8080/public/nova_senha.php?token=" . urlencode($token);
        $message = "Clique no link para redefinir sua senha: " . $link;
        mail($email, "Recuperação de senha", $message, "From: green4board@hotmail.com");
    }

    public function validateTokenSession($token) {
        if (isset($_SESSION['reset_token']) && $_SESSION['reset_token'] === $token) {
            if (time() < $_SESSION['token_expiration']) {
                return true;
            }
        }
        return false; 
    }

    public function updatePassword($email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
        $stmt->execute([$hashedPassword, $email]);

        unset($_SESSION['reset_token']);
        unset($_SESSION['token_expiration']);
    }
}
?>
