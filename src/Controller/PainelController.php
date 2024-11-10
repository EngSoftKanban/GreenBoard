<?php
namespace EngSoftKanban\GreenBoard\Controller;

require_once __DIR__ . "/../Model/Quadro.php";

use EngSoftKanban\GreenBoard\Model\Quadro;

class PainelController {
    private $quadroModel;

    public function __construct($pdo) {
        $this->quadroModel = new Quadro($pdo);
    }

	public function getRecent($usuario_id) {
		return $this->quadroModel->getRecent($usuario_id);
	}

    public function getAll($usuario_id) {
		return $this->quadroModel->getAll($usuario_id);
    }

    public function deleteQuadro($id) {
        
        $this->quadroModel->deleteQuadro($id); 
    }

    public function create($nome, $usuario_id) {
        $this->quadroModel->create($nome, $usuario_id);
    }

	public function post() {
		if (isset($_POST['action'])) {
			if ($_POST['action'] === 'delete') {
				try {
					$this->deleteQuadro($_POST['quadro_id']);
				} catch (Exception $e) {
					echo "Erro ao remover quadro: " . $e->getMessage();
				}
			} elseif ($_POST['action'] === 'create') {
				$this->create($_POST['nome_quadro'], $_SESSION['usuario_id']);
			}
		}
	}
}
