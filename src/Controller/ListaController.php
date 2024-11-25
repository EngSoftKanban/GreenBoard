<?php
namespace EngSoftKanban\GreenBoard\Controller;

require_once 'src/Model/Lista.php';
require_once 'src/Model/User.php';
require_once 'src/Model/Cartao.php';

use EngSoftKanban\GreenBoard\Model\Lista;
use EngSoftKanban\GreenBoard\Model\User;
use EngSoftKanban\GreenBoard\Model\Cartao;

use \PDO;

class ListaController {
	private Lista $listaModel;
	private Cartao $cartaoModel;  
	private PDO $pdo;

	public function __construct($pdo) {
		$this->listaModel = new Lista($pdo);
		$this->cartaoModel = new Cartao($pdo);  
		$this->pdo = $pdo;
	}

	public function listar($quadro_id) {
		return $this->listaModel->listar($quadro_id);
	}

	public function criar($lista_titulo, $quadro_id) {
		return $this->listaModel->criar($lista_titulo, $quadro_id);
	}

	public function ler($lista_id) {
		return $this->listaModel->ler($lista_id);
	}

	public function atualizarLista($lista_id, $titulo) {
		return $this->listaModel->atualizar($lista_id, $titulo);
	}

	public function editarPosListas($listas) {
		return $this->listaModel->editarPosListas($listas);
	}

    public function remover($lista_id) {
		if ($this->listaModel->possuiCartao($lista_id)) {
			return false;
		}
		$lista = $this->listaModel->lerListaPorId($lista_id);
		
		if (!$lista) {
			return false; // Retorna false se o cartão não for encontrado
		}

		$quadro_id = $lista['quadro_id'];
		$lista_pos = $lista['posicao'];

		if ($this->listaModel->remover($lista_id)) {
			$this->listaModel->atualizarPosicoes($quadro_id, $lista_pos);
			return true; // Retorna true se a exclusão for bem-sucedida
		} else {
			return false; // Retorna false se a exclusão falhar
		}
    }

	public function post() {
		$payload = json_decode(file_get_contents('php://input'));
		if (isset($_POST['lista_add'])) {
			$this->criar($_POST['lista_titulo'], $_POST['quadro_id']);
		}
		elseif (isset($_POST['lista_rm'])) {
			$this->remover($_POST['lista_id']);
		}
		elseif (isset($_POST['editar_item_id'])) {
			$item_id = $_POST['editar_item_id'];
			$item_tipo = $_POST['editar_item_tipo'];
			$item_texto = $_POST['editar_item_texto'];
			
			return strcmp($item_tipo, "lista") == 0 ? $this->atualizarLista($item_id, $item_texto) : $this->cartaoModel->atualizarCartao($item_id, $item_texto);
		}
		elseif ($payload != null && property_exists($payload, 'lista_pos')) {
			echo json_encode(['resultado' => $this->editarPosListas($payload->lista_pos)]);
			exit();
		}
	}
}
