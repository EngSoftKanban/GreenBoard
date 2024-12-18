<?php
namespace EngSoftKanban\GreenBoard\Controller;

require_once 'src/Model/Cartao.php';

use EngSoftKanban\GreenBoard\Model\Cartao;
use PDO;

class CartaoController {
    private $cartao;

    public function __construct($pdo) {
        $this->cartao = new Cartao($pdo);
    }

	public function ler($cartao_id) {
		return $this->cartao->ler($cartao_id);
	}

	public function lerPorLista($lista_id) {
		return $this->cartao->lerPorLista($lista_id);
	}

	public function lerPorListas($listas_id) {
		return $this->cartao->lerPorListas($listas_id);
	}

    public function criar($corpo, $lista_id) {
        if (empty($corpo) || empty($lista_id)) {
            return json_encode(["success" => false, "message" => "Corpo ou ID da lista não podem estar vazios."]);
        }
        $resultado = $this->cartao->adicionarCartao($corpo, $lista_id);
        return json_encode(["success" => $resultado, "message" => $resultado ? "Cartão adicionado com sucesso!" : "Erro ao adicionar o cartão."]);
    }

    public function editar($id, $corpo) {
        if (empty($id) || empty($corpo)) {
            return json_encode(["success" => false, "message" => "ID ou corpo do cartão não podem estar vazios."]);
        }
        $resultado = $this->cartao->atualizarCartao($id, $corpo);
        return json_encode(["success" => $resultado, "message" => $resultado ? "Cartão atualizado com sucesso!" : "Erro ao atualizar o cartão."]);
    }

	public function excluir($cartao_id) {
		$cartao = $this->cartao->getCartaoById($cartao_id);
		
		if (!$cartao) {
			return false; // Retorna false se o cartão não for encontrado
		}

		$lista_id = $cartao['lista_id'];
		$posicao_cartao = $cartao['posicao'];

		if ($this->cartao->removerCartao($cartao_id)) {
			$this->cartao->atualizarPosicoes($lista_id, $posicao_cartao);
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
            $resultado = $this->cartao->atualizarPosicoes($cartoes);
            return json_encode(["success" => true, "message" => "Posições dos cartões atualizadas com sucesso."]);
        } catch (Exception $e) {
            return json_encode(["success" => false, "message" => "Erro: " . $e->getMessage()]);
        }
    }

	public function editarPosCartoes($cartoes) {
		return $this->cartao->editarPosCartoes($cartoes);
	}

	public function acharPorCorpo($corpo) {
		return $this->cartao->acharPorCorpo($corpo);
	}

	public function post() {
		$payload = json_decode(file_get_contents('php://input'));
		if (isset($_POST['cartao_add'])) {
			$this->criar($_POST['cartao_corpo'], $_POST['lista_id']);
		}
		elseif (isset($_POST['cartao_rm'])) {
			$this->excluir($_POST['cartao_id']);
		}
		elseif ($payload != null && property_exists($payload, 'cartao_pos')) {
			echo json_encode(['resultado' => $this->editarPosCartoes($payload->cartao_pos)]);
			exit();
		}

		if ($payload != null) {
			if (property_exists($payload, 'etiqueta_editar')) {
				$dados = $payload->etiqueta_editar;
				$response = $this->updateEtiqueta($dados->id, $dados->nome, $dados->cor);
				echo json_encode(['success' => true, 'message' => 'Etiqueta atualizada com sucesso!']);
				exit();
			} elseif (property_exists($payload, 'etiqueta_criar')) {
				$dados = $payload->etiqueta_criar;
				$response = $this->adicionarEtiqueta($dados->nome, $dados->cor, $dados->cartao_id);
				echo json_encode(['success' => true, 'message' => 'Etiqueta adicionada com sucesso!']);
				exit();
			} elseif (property_exists($payload, 'etiqueta_excluir')) {
				$dados = $payload->etiqueta_excluir;
				$response = $this->deleteEtiqueta($dados->id);
				echo json_encode(['success' => true, 'message' => 'Etiqueta excluída com sucesso!']);
				exit();
			} else {
				echo json_encode(['success' => false, 'message' => 'Dados inválidos!']);
				exit();
			}
		}
	}

    public function adicionarEtiqueta($nome, $cor, $cartao_id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $nome ??= $input['nome'];
            $cor ??= $input['cor'];
            $cartao_id ??= $input['cartao_id'];
    
            if (empty($nome) || empty($cor) || !$cartao_id) {
                return json_encode(['success' => false, 'message' => 'Dados inválidos!']);
            }
    
            $result = $this->cartao->addEtiqueta($nome, $cor, $cartao_id);
    
            if ($result) {
                return json_encode(['success' => true, 'message' => 'Etiqueta adicionada com sucesso!']);
            } else {
                return json_encode(['success' => false, 'message' => 'Erro ao adicionar etiqueta.']);
            }
        } else {
            return json_encode(['success' => false, 'message' => 'Método inválido']);
        }
    }

    public function getEtiquetas($cartao_id) {
        $etiquetas = $this->etiquetaModel->getEtiquetasByCartao($cartao_id);
        return json_encode(['status' => 'success', 'data' => $etiquetas]);
    }

    public function updateEtiqueta($id, $nome, $cor) {
        if ($this->cartao->updateEtiqueta($id, $nome, $cor)) {
            return json_encode(['status' => 'success', 'message' => 'Etiqueta atualizada com sucesso.']);
        }
        return json_encode(['status' => 'error', 'message' => 'Erro ao atualizar a etiqueta.']);
    }

    public function deleteEtiqueta($id) {
        if ($this->cartao->deleteEtiqueta($id)) {
            return json_encode(['status' => 'success', 'message' => 'Etiqueta excluída com sucesso.']);
        }
        return json_encode(['status' => 'error', 'message' => 'Erro ao excluir a etiqueta.']);
    }

    public function listarEtiquetasPorCartao($cartaoId)
    {
        return $this->cartao->getEtiquetasByCartao($cartaoId);
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