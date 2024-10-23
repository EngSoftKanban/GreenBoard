<?php
namespace EngSoftKanban\GreenBoard\Controller;

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Model/Lista.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Model/Usuario.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Model/Cartao.php';

use EngSoftKanban\GreenBoard\Model\Lista;
use EngSoftKanban\GreenBoard\Model\Usuario;
use EngSoftKanban\GreenBoard\Model\Cartao;

class ListaController {
    private $listaModel;
    private $cartaoModel;  
    private $pdo;

    public function __construct($pdo) {
        $this->listaModel = new Lista();
        $this->cartaoModel = new Cartao($pdo);  
        $this->pdo = $pdo;
    }

    public function listar() {
        return $this->listaModel->listar();
    }

    public function buscarUsuarios() {
        return Usuario::buscarTodos();
    }

    public function adicionarLista($titulo, $quadro_id) {
        try {
            if (empty($titulo) || empty($quadro_id)) {
                return json_encode(["success" => false, "message" => "Título ou ID do quadro não podem estar vazios."]);
            }
    
            $resultado = $this->listaModel->adicionarLista($titulo, $quadro_id);
            return json_encode(["success" => $resultado, "message" => $resultado ? "Lista adicionada com sucesso!" : "Erro ao adicionar a lista."]);
        } catch (Exception $e) {
            return json_encode(["success" => false, "message" => "Erro: " . $e->getMessage()]);
        }
    }

    public function atualizarLista($id, $titulo) {
        if (empty($id) || empty($titulo)) {
            return json_encode(["success" => false, "message" => "ID ou título não podem estar vazios."]);
        }

        $sql = "UPDATE listas SET titulo = :titulo WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':titulo', $titulo);
        return json_encode(["success" => $stmt->execute(), "message" => $stmt->execute() ? "Lista atualizada com sucesso!" : "Erro ao atualizar a lista."]);
    }

    public function deletarLista($id) {
        try {
            $this->pdo->beginTransaction();
    
            $cartoes = $this->cartaoModel->getCartoesByListaId($id);
            foreach ($cartoes as $cartao) {
                if (!$this->cartaoModel->deletarCartao($cartao['id'])) {
                    throw new Exception("Erro ao excluir o cartão ID {$cartao['id']}");
                }
            }
            
            $stmt = $this->pdo->prepare("DELETE FROM listas WHERE id = :id");
            $stmt->bindParam(':id', $id);
            
            if (!$stmt->execute()) {
                throw new Exception("Erro ao excluir a lista ID $id");
            }
    
            $this->pdo->commit();
            return json_encode(["success" => true, "message" => "Lista excluída com sucesso."]);
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return json_encode(["success" => false, "message" => $e->getMessage()]);
        }
    }

    public function atualizarPosicoes($data) {
        if ($data) {
            return $this->listaModel->atualizarPosicoes($data);
        }
        return false;
    }

    
    
}
