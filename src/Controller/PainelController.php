<?php
namespace EngSoftKanban\GreenBoard\Controller;

require_once "src/Model/Quadro.php";

use EngSoftKanban\GreenBoard\Model\Quadro;

class PainelController {
    private $quadroModel;

    public function __construct($pdo) {
        $this->quadroModel = new Quadro($pdo);
    }

	public function getRecent($usuario_id) {
		return $this->quadroModel->lerRecente($usuario_id);
	}

    public function getAll($usuario_id) {
		return $this->quadroModel->lerTodos($usuario_id);
    }

    public function remover($id) {
        
        $this->quadroModel->remover($id); 
    }

    public function criar($nome, $usuario_id) {
        $this->quadroModel->criar($nome, $usuario_id);
    }

	public function post() {
		if (isset($_POST['action'])) {
			if ($_POST['action'] === 'delete') {
				try {
					$this->remover($_POST['quadro_id']);
				} catch (Exception $e) {
					echo "Erro ao remover quadro: " . $e->getMessage();
				}
			} elseif ($_POST['action'] === 'create') {
				$this->criar($_POST['nome_quadro'], $_SESSION['usuario_id']);
			}
		}
	}
}
