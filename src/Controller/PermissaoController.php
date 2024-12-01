<?php
namespace EngSoftKanban\GreenBoard\Controller;

require_once 'src/Model/Permissao.php';

use EngSoftKanban\GreenBoard\Model\Permissao;

class PermissaoController {
	private Permissao $perm;

	public function __construct($pdo) {
		$this->perm = new Permissao($pdo);
	}

	public function lerPorQuadro($quadro_id) {
		return $this->perm->lerPorQuadro($quadro_id);
	}

	public function possuiPermissao($usuario_id, $quadro_id) {
		return $this->perm->possuiPermissao($usuario_id, $quadro_id);
	}
}
