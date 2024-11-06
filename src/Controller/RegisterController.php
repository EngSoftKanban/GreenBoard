<?php
namespace EngSoftKanban\GreenBoard\Controller;

use EngSoftKanban\GreenBoard\Model\User;

class RegisterController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

	public function register() {
		$erro = null;

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			// Verifique se os campos necessários estão preenchidos
			if (isset($_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['senhaconfirma'])) {
				$nome = $_POST['nome'];
				$email = $_POST['email'];
				$senha = $_POST['senha'];
				$senhaconfirma = $_POST['senhaconfirma'];

				// Verifique se as senhas são iguais
				if ($senha !== $senhaconfirma) {
					$erro = "As senhas não são iguais.";
				} else {
					// Chame o método register do modelo
					if ($this->userModel->register($nome, $email, $senha)) {
						session_start();
						session_regenerate_id();
						$_SESSION['usuario_id'] = $usuario['id'];
						$_SESSION['nome'] = $usuario['nome'];
						$_SESSION['email'] = $usuario['email'];
						$_SESSION['icone'] = $usuario['icone'];
						//Ssession_commit();
						// Redirecionar o usuário para a página principal ou anterior
						header("Location: " . ($_SESSION['redirect_url'] ?? 'painel.php'));
					} else {
						$erro = "Erro ao tentar cadastrar usuário.";
					}
				}
			}
		}
		return $erro; // Retorna erro, se houver
    }
}
?>
