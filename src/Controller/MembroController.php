<?php
namespace EngSoftKanban\GreenBoard\Controller;

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Model/Membro.php';

use EngSoftKanban\GreenBoard\Model\Membro;

class MembroController {
	private $membroModel;

	public function __construct($pdo) {
		$this->membroModel = new Membro($pdo);
	}

	public function listar($cartao_id) {
		return $this->membroModel->listarMembros($cartao_id);
	}

	public function encontrar($usuario_id, $cartao_id) {
		return $this->membroModel->encontrar($usuario_id, $cartao_id);
	}

	public function adicionar($usuario_id, $cartao_id) {
		return $this->membroModel->adicionarUsuario($usuario_id, $cartao_id);
	}

	public function remover($usuario_id, $cartao_id) {
		return $this->membroModel->removerUsuario($usuario_id, $cartao_id);
	}

	public function getIcone($usuario_id) {
		return $this->membroModel->getIcone($usuario_id);
	}

	public function post() {
		if (isset($_POST['membro_add'])) {
			error_log($_POST['cartao_id']);
			$this->adicionar($_SESSION['usuario_id'], $_POST['cartao_id']);
		}
		else if (isset($_POST['membro_rm'])) {
			$this->remover($_SESSION['usuario_id'], $_POST['cartao_id']);
		}
	}
}