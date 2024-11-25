<?php
namespace EngSoftKanban\GreenBoard\Controller;

require_once 'src/Controller/CartaoController.php';
require_once 'src/Controller/ListaController.php';
require_once 'src/Controller/WebhookController.php';
require_once 'src/Controller/WebhookController.php';
require_once 'src/Model/Token.php';

use EngSoftKanban\GreenBoard\Controller\CartaoController;
use EngSoftKanban\GreenBoard\Controller\ListaController;
use EngSoftKanban\GreenBoard\Controller\WebhookController;
use EngSoftKanban\GreenBoard\Controller\MembroController;
use EngSoftKanban\GreenBoard\Model\Token;

use \PDO;

class TokenController {
	private PDO $pdo;
	private Token $token;

	public function __construct($pdo) {
		$this->token = new Token($pdo);
		$this->pdo = $pdo;
	}

	public function criar($quadro_id, $usuario_id) {
		return $this->token->criar($quadro_id, $usuario_id);
	}

	public function lerPorQuadro($quadro_id) {
		return $this->token->lerPorQuadro($quadro_id);
	}

	public function lerQuadrosPermitidos($quadro_id, $usuario_id) {
		return $this->token->lerQuadrosPermitidos($quadro_id, $usuario_id);
	}

	public function ehAutorizada($token, $quadro_id) {
		return $this->token->ehAutorizada($token, $quadro_id);
	}

	public function excluir($token_id) {
		return $this->token->excluir($token_id);
	}

	public function post() {
		if (isset($_POST['TOKEN'])) {
			return $this->criar(intval($_POST['quadro']), $_SESSION['usuario_id']);
		} else if (isset($_POST['token_id'])) {
			return $this->excluir($_POST['token_id']);
		}
	}

	public function postAPI() {
		if (isset($_SERVER['HTTP_X_GREENBOARD_EVENT'])) {
			$payload = json_decode(file_get_contents('php://input'));
			if($payload == null) {
				header('HTTP/1.O 400 Bad Request');
				exit();
			}
			if (!property_exists($payload, 'item') || !property_exists($payload, 'quadro_id') || !property_exists($payload, 'token')) {
				header('HTTP/1.O 400 Bad Request');
				exit();
			} elseif (!$this->ehAutorizada($payload->token, $payload->quadro_id)) {
				header('HTTP/1.0 403 Forbidden');
				exit();
			}
			switch($payload->item) {
				case 'cartao':
					$cartaoController = new CartaoController($this->pdo);
					switch ($_SERVER['HTTP_X_GREENBOARD_EVENT']) {
						case 'criar':
							if (!property_exists($payload, 'cartao_corpo') || !property_exists($payload, 'lista_id')) {
								header('HTTP/1.O 400 Bad Request');
								exit();
							}
							echo $cartaoController->criar($payload->cartao_corpo, $payload->lista_id);
							break;
						case 'ler':
							if (property_exists($payload, 'cartao_id')) {
								echo json_encode($cartaoController->ler($payload->cartao_id));
							} elseif (property_exists($payload, 'lista_id')) {
								echo json_encode($cartaoController->lerPorLista($payload->lista_id));
							} else {
								header('HTTP/1.O 400 Bad Request');
								exit();
							}
							break;
						case 'editar':
							if (property_exists($payload, 'cartao_id') && property_exists($payload, 'cartao_corpo')) {
								echo $cartaoController->editar($payload->cartao_id, $payload->cartao_corpo);
							} else {
								header('HTTP/1.O 400 Bad Request');
								exit();
							}
							break;
						case 'excluir':
							if (property_exists($payload, 'cartao_id')) {
								echo json_encode(['resultado' => $cartaoController->excluir($payload->cartao_id) ? 'Sucesso' : 'Erro']);
							} else {
								header('HTTP/1.O 400 Bad Request');
								exit();
							}
							break;
						default:
							header('HTTP/1.O 400 Bad Request');
							exit();
					}
				case 'lista':
					$listaController = new ListaController($this->pdo);
					switch ($_SERVER['HTTP_X_GREENBOARD_EVENT']) {
						case 'criar':
							if (!property_exists($payload, 'lista_titulo')) {
								header('HTTP/1.O 400 Bad Request');
								exit();
							}
							echo json_encode(['resultado' => $listaController->criar($payload->lista_titulo, $payload->quadro_id)
								? 'Sucesso' : 'Erro']);
							break;
						case 'ler':
							if (property_exists($payload, 'lista_id')) {
								echo json_encode($listaController->ler($payload->lista_id));
							} else {
								echo json_encode($listaController->listar($payload->quadro_id));
							}
							break;
						case 'editar':
							if (property_exists($payload, 'lista_id') && property_exists($payload, 'lista_titulo')) {
								echo json_encode($listaController->atualizarLista($payload->lista_id, $payload->lista_titulo));
							} else {
								header('HTTP/1.O 400 Bad Request');
								exit();
							}
							break;
						case 'excluir':
							if (property_exists($payload, 'lista_id')) {
								echo json_encode(['resultado' => $listaController->remover($payload->lista_id) ? 'Sucesso' : 'Erro']);
							} else {
								header('HTTP/1.O 400 Bad Request');
								exit();
							}
							break;
						default:
							header('HTTP/1.O 400 Bad Request');
							exit();
					}
				case 'etiqueta':
					$cartaoController = new CartaoController($this->pdo);
					switch ($_SERVER['HTTP_X_GREENBOARD_EVENT']) {
						case 'criar':
							if (!property_exists($payload, 'cartao_id') || !property_exists($payload, 'etiqueta_nome')
									|| !property_exists($payload, 'etiqueta_cor')) {
								header('HTTP/1.O 400 Bad Request');
								exit();
							}
							echo $cartaoController->adicionarEtiqueta($payload->etiqueta_nome, $payload->etiqueta_cor,
								$payload->cartao_id);
							break;
						case 'ler':
							if (property_exists($payload, 'etiqueta_id')) {
								echo json_encode($cartaoController->lerEtiqueta($payload->etiqueta_id));
							} elseif (property_exists($payload, 'cartao_id')) {
								echo json_encode($cartaoController->listarEtiquetasPorCartao($payload->cartao_id));
							} else {
								header('HTTP/1.O 400 Bad Request');
								exit();
							}
							break;
						case 'editar':
							if (property_exists($payload, 'etiqueta_id') && property_exists($payload, 'etiqueta_nome')
									&& property_exists($payload, 'etiqueta_cor')) {
								echo $cartaoController->updateEtiqueta($payload->etiqueta_id, $payload->etiqueta_nome,
									$payload->etiqueta_cor);
							} else {
								header('HTTP/1.O 400 Bad Request');
								exit();
							}
							break;
						case 'excluir':
							if (property_exists($payload, 'etiqueta_id')) {
								echo $cartaoController->deleteEtiqueta($payload->etiqueta_id);
							} else {
								header('HTTP/1.O 400 Bad Request');
								exit();
							}
							break;
						default:
							header('HTTP/1.O 400 Bad Request');
							exit();
					}
				case 'webhook':
					$hookController = new WebhookController($this->pdo);
					switch ($_SERVER['HTTP_X_GREENBOARD_EVENT']) {
						case 'criar':
							if (!property_exists($payload, 'usuario_id') || !property_exists($payload, 'quadro_id')
									|| !property_exists($payload, 'lista_id')) {
								header('HTTP/1.O 400 Bad Request');
								exit();
							}
							echo json_encode(['resultado' => $hookController->criar($payload->usuario_id, $payload->quadro_id,
								$payload->lista_id) ? 'Sucesso' : 'Erro']);
							break;
						case 'ler':
							if (property_exists($payload, 'hook_id')) {
								echo json_encode($hookController->ler($payload->hook_id));
							} elseif (property_exists($payload, 'hook_token')) {
								echo json_encode($hookController->lerPorToken($payload->hook_token));
							}
							break;
						case 'editar':
							header('HTTP/1.O 400 Bad Request');
							exit();
						case 'excluir':
							if (property_exists($payload, 'hook_id')) {
								echo json_encode(['resultado' => $hookController->destruir($payload->hook_id) ? 'Sucesso' : 'Erro']);
							} else {
								header('HTTP/1.O 400 Bad Request');
								exit();
							}
							break;
						default:
							header('HTTP/1.O 400 Bad Request');
							exit();
					}
				case 'adicionado':
					$membroController = new MembroController($this->pdo);
					switch ($_SERVER['HTTP_X_GREENBOARD_EVENT']) {
						case 'criar':
							if (!property_exists($payload, 'usuario_id') || !property_exists($payload, 'quadro_id')
									|| !property_exists($payload, 'lista_id')) {
								header('HTTP/1.O 400 Bad Request');
								exit();
							}
							echo json_encode(['resultado' => $hookController->criar($payload->usuario_id, $payload->quadro_id, 
								$payload->lista_id) ? 'Sucesso' : 'Erro']);
							break;
						case 'ler':
							if (property_exists($payload, 'hook_id')) {
								echo json_encode($hookController->ler($payload->hook_id));
							} elseif (property_exists($payload, 'hook_token')) {
								echo json_encode($hookController->lerPorToken($payload->hook_token));
							}
							break;
						case 'editar':
							header('HTTP/1.O 400 Bad Request');
							exit();
						case 'excluir':
							if (property_exists($payload, 'hook_id')) {
								echo json_encode(['resultado' => $hookController->destruir($payload->hook_id) ? 'Sucesso' : 'Erro']);
							} else {
								header('HTTP/1.O 400 Bad Request');
								exit();
							}
							break;
						default:
							header('HTTP/1.O 400 Bad Request');
							exit();
					}
				case 'permissoes':
					$membroController = new MembroController($this->pdo);
					switch ($_SERVER['HTTP_X_GREENBOARD_EVENT']) {
						case 'criar':
							header('HTTP/1.O 400 Bad Request');
							exit();
						case 'ler':
							header('HTTP/1.O 400 Bad Request');
							exit();
						case 'editar':
							header('HTTP/1.O 400 Bad Request');
							exit();
						case 'excluir':
							header('HTTP/1.O 400 Bad Request');
							exit();
						default:
							header('HTTP/1.O 400 Bad Request');
							exit();
					}
				default:
					header('HTTP/1.O 400 Bad Request');
					exit();			
			}
		}
	}
}
