<?php
namespace EngSoftKanban\GreenBoard\Controller;

require_once 'src/Model/Quadro.php';

use EngSoftKanban\GreenBoard\Model\Quadro;

class QuadroController {
    private $quadroModel;

    public function __construct($pdo) {
        $this->quadroModel = new Quadro($pdo);
    }

	public function listLists() {
		return $this->quadroModel->getRecent();
	}

    public function listCards() {
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
