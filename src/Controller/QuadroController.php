<?php
namespace EngSoftKanban\GreenBoard\Controller;

require_once 'src/Model/Quadro.php';

use EngSoftKanban\GreenBoard\Model\Quadro;

class QuadroController {
    private $quadroModel;

    public function __construct($pdo) {
        $this->quadroModel = new Quadro($pdo);
    }

    public function ler(int $quadro_id) {
        return $this->quadroModel->ler($quadro_id);
    }

    public function lerTodos($usuario_id) {
        return $this->quadroModel->lerTodos($usuario_id);
    }

    public function lerRecente($usuario_id) {
        return $this->quadroModel->lerRecente($usuario_id);
    }

    public function remover($quadro_id) {
        return $this->quadroModel->remover($quadro_id); 
    }

    public function criar($nome, $usuario_id) {
        return $this->quadroModel->criar($nome, $usuario_id);
    }

    public function editar($quadro_id, $novo_nome) {
        return $this->quadroModel->editar($quadro_id, $novo_nome);
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
            } elseif ($_POST['action'] === 'edit') { 
                try {
                    $this->editar($_POST['quadro_id'], $_POST['novo_nome']);
                } catch (Exception $e) {
                    echo "Erro ao editar quadro: " . $e->getMessage();
                }
            }
        }
    }
}
