<?php
namespace EngSoftKanban\GreenBoard\Controller;

use EngSoftKanban\GreenBoard\Model\Usuario;
use Google_Client;

class LoginController {
    private $userModel;
    private $pdo;  // Defina a propriedade $pdo

    public function __construct($pdo) {
        $this->pdo = $pdo;  // Inicialize $pdo
        $this->userModel = new User($pdo);
    }

    public function login() {
        $erro = null;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['email'], $_POST['senha'])) {
                $email = $_POST['email'];
                $senha = $_POST['senha'];
                $usuario = $this->userModel->findUser($email);

                if ($usuario) {
                    if (password_verify($senha, $usuario['senha'])) {
                        session_start();
                        session_regenerate_id();
                        $_SESSION['usuario_id'] = $usuario['id'];
                        $_SESSION['nome'] = $usuario['nome'];
                        $_SESSION['email'] = $usuario['email'];
                        $_SESSION['icone'] = $usuario['icone'];
                        header("Location: " . ($_SESSION['redirect_url'] ?? 'painel.php'));
                        exit();
                    } else {
                        $erro = "E-mail ou senha incorreto(s). Tente novamente.";
                    }
                } else {
                    $erro = "E-mail ou senha incorreto(s). Tente novamente.";
                }
            }
        }

        return $erro;
    }

    public function googleLogin() {
        $client = new Google_Client();
        $client->setClientId('839888767080-06qndqiukr23tt6tcddg5mak4h4dghq3.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->setRedirectUri('http://localhost/google_callback.php');
        $client->addScope('email');
        $client->addScope('profile');

        $authUrl = $client->createAuthUrl();

        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
        exit();
    }

    public function findUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createUser($data) {
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nome, email, senha, icone) VALUES (:nome, :email, :senha, :icone)");
        $stmt->bindParam(':nome', $data['nome']);
        $stmt->bindParam(':email', $data['email']);
        
        // Define uma senha padrão ou nula para usuários OAuth
        $senha = $data['senha'] ?? '';  // Use uma string vazia ou NULL, se permitido no banco de dados
        $stmt->bindParam(':senha', $senha);
        
        $stmt->bindParam(':icone', $data['icone']);
        return $stmt->execute();
    }
    
}

