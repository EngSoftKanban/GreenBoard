<?php
namespace EngSoftKanban\GreenBoard\Controller;

use EngSoftKanban\GreenBoard\Model\Usuario;

class UsuarioController {
    private Usuario $usuario;

    public function __construct($pdo) {
        $this->usuario = new Usuario($pdo);
    }

	public function lerPorIds($usuarios_id) {
		return $this->usuario->lerPorIds($usuarios_id);
	}

	public function resposta() {
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
					if ($this->usuario->cadastrar($nome, $email, $senha)) {
						session_start();
						session_regenerate_id();
						$_SESSION['usuario_id'] = $usuario['id'];
						$_SESSION['nome'] = $usuario['nome'];
						$_SESSION['email'] = $usuario['email'];
						$_SESSION['icone'] = $usuario['icone'];
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
