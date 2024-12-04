<?php
namespace EngSoftKanban\GreenBoard\Controller;

require_once 'src/Model/Quadro.php';

use EngSoftKanban\GreenBoard\Model\Quadro;

class QuadroController {
	private Quadro $quadro;

	public function __construct($pdo) {
		$this->quadro = new Quadro($pdo);
	}

	public function criar($nome, $usuario_id) {
		return $this->quadro->criar($nome, $usuario_id);
	}

	public function ler($quadro_id) {
		return $this->quadro->ler($quadro_id);
	}

	public function lerTodos($usuario_id) {
		return $this->quadro->lerTodos($usuario_id);
	}
	
	public function lerRecente($usuario_id) {
		return $this->quadro->lerRecente($usuario_id);
	}
	
	public function editar($quadro_id, $novo_nome) {
		return $this->quadro->editar($quadro_id, $novo_nome);
	}

	public function excluir($quadro_id) {
		return $this->quadro->excluir($quadro_id); 
	}

	public function post() {
		if (isset($_POST['quadro_excluir'])) {
			try {
				$this->excluir($_POST['quadro_id']);
			} catch (Exception $e) {
				echo "Erro ao remover quadro: " . $e->getMessage();
			}
		} elseif (isset($_POST['quadro_criar'])) {
			$this->criar($_POST['nome_quadro'], $_SESSION['usuario_id']);
		} elseif (isset($_POST['quadro_editar'])) { 
			try {
				$this->editar($_POST['quadro_id'], $_POST['novo_nome']);
			} catch (Exception $e) {
				echo "Erro ao editar quadro: " . $e->getMessage();
			}
		}

		// $payload = json_decode(file_get_contents('php://input'), true);
		// error_log(print_r($payload, true));
		// if (property_exists($payload, 'etiqueta_editar')) {
		// 	$response = $cartaoController->updateEtiqueta($payload['id'], $payload['nome'], $payload['cor']);
		// 	echo json_encode(['success' => true, 'message' => 'Etiqueta atualizada com sucesso!']);
		// 	exit();
		// } elseif (property_exists($payload, 'etiqueta_criar')) {
		// 	$response = $cartaoController->adicionarEtiqueta($payload['nome'], $payload['cor'], $payload['cartao_id']);
		// 	echo json_encode(['success' => true, 'message' => 'Etiqueta adicionada com sucesso!']);
		// 	exit();
		// } elseif (property_exists($payload, 'etiqueta_excluir')) {
		// 	$response = $cartaoController->deleteEtiqueta($payload['id']);
		// 	echo json_encode(['success' => true, 'message' => 'Etiqueta excluída com sucesso!']);
		// 	exit();
		// } elseif (isset($payload)) {
		// 	echo json_encode(['success' => false, 'message' => 'Dados inválidos!']);
		// 	exit();
		// }
	}
}
