<?php
namespace EngSoftKanban\GreenBoard\Controller;

require_once __DIR__ . "/../Model/Quadro.php";

use EngSoftKanban\GreenBoard\Model\Quadro;

class PainelController {
    private $quadroModel;

    public function __construct($pdo) {
        $this->quadroModel = new Quadro($pdo);
    }

	public function getRecent() {
		return $this->quadroModel->getRecent();
	}

    public function getAll() {
		return $this->quadroModel->getAll();
    }

    public function deleteQuadro($id) {
        
        $this->quadroModel->deleteQuadro($id); 
    }

    public function create($nome) {
        $this->quadroModel->create($nome);
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
				$this->create($_POST['nome_quadro']);
			}
		}
	}
}
