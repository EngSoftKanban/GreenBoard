<?php
namespace EngSoftKanban\GreenBoard\Controller;

use EngSoftKanban\GreenBoard\Model\User;

class LoginController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    public function login() {
        $erro = null;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Pegue o e-mail e a senha do formulário
            if (isset($_POST['email'], $_POST['senha'])) {
                $email = $_POST['email'];
                $senha = $_POST['senha'];
                // Verifique se o usuário existe
                $usuario = $this->userModel->findUser($email);

                if ($usuario) {
                    // Verificar a senha
                    if (password_verify($senha, $usuario['senha'])) {
                        // Senha correta, iniciar a sessão
                        session_start();
						session_regenerate_id();
                        $_SESSION['usuario_id'] = $usuario['id'];
						$_SESSION['nome'] = $usuario['nome'];
						$_SESSION['email'] = $usuario['email'];
						$_SESSION['icone'] = $usuario['icone'];
						//Ssession_commit();
                        // Redirecionar o usuário para a página principal ou anterior
                        header("Location: " . ($_SESSION['redirect_url'] ?? 'painel.php'));
                        exit();
                    } else {
                        // Senha incorreta, exibir erro
                        $erro = "E-mail ou senha incorreto(s). Tente novamente.";
                    }
                } else {
                    // E-mail não encontrado, exibir erro
                    $erro = "E-mail ou senha incorreto(s). Tente novamente.";
                }
            }
        }

        return $erro; // Retorna erro, se houver
    }
}
?>
