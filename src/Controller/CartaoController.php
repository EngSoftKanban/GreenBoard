<?php
namespace EngSoftKanban\GreenBoard\Controller;

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Model/Cartao.php';

use EngSoftKanban\GreenBoard\Model\Cartao;
use PDO;

class CartaoController {
    private $cartaoModel;

    public function __construct($pdo) {
        $this->cartaoModel = new Cartao($pdo);
    }

    public function listarCartoesPorLista($lista_id) {
        return $this->cartaoModel->listarPorLista($lista_id);
    }

    public function adicionar($corpo, $lista_id) {
        if (empty($corpo) || empty($lista_id)) {
            return json_encode(["success" => false, "message" => "Corpo ou ID da lista não podem estar vazios."]);
        }
        $resultado = $this->cartaoModel->adicionarCartao($corpo, $lista_id);
        return json_encode(["success" => $resultado, "message" => $resultado ? "Cartão adicionado com sucesso!" : "Erro ao adicionar o cartão."]);
    }

    public function atualizar($id, $corpo) {
        if (empty($id) || empty($corpo)) {
            return json_encode(["success" => false, "message" => "ID ou corpo do cartão não podem estar vazios."]);
        }
        $resultado = $this->cartaoModel->atualizarCartao($id, $corpo);
        return json_encode(["success" => $resultado, "message" => $resultado ? "Cartão atualizado com sucesso!" : "Erro ao atualizar o cartão."]);
    }

    public function deletar($id) {
        $cartao = $this->cartaoModel->getCartaoById($id);
        
        if (!$cartao) {
            return false; // Retorna false se o cartão não for encontrado
        }
    
        $lista_id = $cartao['lista_id'];
        $posicao_cartao = $cartao['posicao'];
    
        if ($this->cartaoModel->deletarCartao($id)) {
            $this->cartaoModel->atualizarPosicoes($lista_id, $posicao_cartao);
            return true; // Retorna true se a exclusão for bem-sucedida
        } else {
            return false; // Retorna false se a exclusão falhar
        }
    }

    public function atualizarPosicoes($cartoes) {
        // Chama o método do modelo para atualizar as posições
        if (empty($cartoes)) {
            return json_encode(["success" => false, "message" => "Nenhum cartão fornecido."]);
        }

        try {
            $resultado = $this->cartaoModel->atualizarPosicoes($cartoes);
            return json_encode(["success" => true, "message" => "Posições dos cartões atualizadas com sucesso."]);
        } catch (Exception $e) {
            return json_encode(["success" => false, "message" => "Erro: " . $e->getMessage()]);
        }
    }

    public function acharPorCorpo($corpo) {
		return $this->cartaoModel->acharPorCorpo($corpo);
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
    
    public function adicionarEtiqueta() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $nome = $input['nome'] ?? '';
            $cor = $input['cor'] ?? '';
            $cartao_id = $input['cartao_id'] ?? null;
    
            if (empty($nome) || empty($cor) || !$cartao_id) {
                echo json_encode(['success' => false, 'message' => 'Dados inválidos!']);
                return;
            }
    
            $result = $this->cartaoModel->addEtiqueta($nome, $cor, $cartao_id);
    
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Etiqueta adicionada com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao adicionar etiqueta.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método inválido']);
        }
    }

    public function getEtiquetas($cartao_id) {
        $etiquetas = $this->etiquetaModel->getEtiquetasByCartao($cartao_id);
        return json_encode(['status' => 'success', 'data' => $etiquetas]);
    }

    public function updateEtiqueta($id, $nome, $cor) {
        if ($this->cartaoModel->updateEtiqueta($id, $nome, $cor)) {
            return json_encode(['status' => 'success', 'message' => 'Etiqueta atualizada com sucesso.']);
        }
        return json_encode(['status' => 'error', 'message' => 'Erro ao atualizar a etiqueta.']);
    }

    public function deleteEtiqueta($id) {
        if ($this->cartaoModel->deleteEtiqueta($id)) {
            return json_encode(['status' => 'success', 'message' => 'Etiqueta excluída com sucesso.']);
        }
        return json_encode(['status' => 'error', 'message' => 'Erro ao excluir a etiqueta.']);
    }

    public function listarEtiquetasPorCartao($cartaoId)
    {
        return $this->cartaoModel->getEtiquetasByCartao($cartaoId);
    }

    public function apiHandler() {
        header('Content-Type: application/json');
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $id = $_GET['id'] ?? null;
        $data = json_decode(file_get_contents("php://input"), true);
    
        switch ($requestMethod) {
            case 'POST':
                $this->adicionarEtiqueta();
                break;
            case 'PUT':
                if ($id && isset($data['nome']) && isset($data['cor'])) {
                    $this->updateEtiqueta($id, $data['nome'], $data['cor']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Dados inválidos para atualização.']);
                }
                break;
            case 'DELETE':
                if ($id) {
                    $this->deleteEtiqueta($id);
                } else {
                    echo json_encode(['success' => false, 'message' => 'ID inválido para exclusão.']);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['message' => 'Método não permitido']);
                break;
        }
    }

}
