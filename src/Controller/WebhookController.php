<?php
namespace EngSoftKanban\GreenBoard\Controller;

require_once 'src/Model/Webhook.php';

use EngSoftKanban\GreenBoard\Model\Webhook;

class WebhookController {
	private Webhook $hookModel;

	public function __construct($pdo) {
		$this->hookModel = new Webhook($pdo);
	}

	public function listarQuadrosEListas($usuario_id) {
		return $this->hookModel->listarQuadrosEListas($usuario_id);
	}

	public function listarWebhooks($usuario_id) {
		return $this->hookModel->listarWebhooks($usuario_id);
	}

	public function lerWebhook($token) {
		return $this->hookModel->lerWebhook($token);
	}

	public function postWebhooks($usuario_id) {
		if (isset($_POST['WEBHOOK'])) {
			$post = $_POST;
			unset($post['WEBHOOK']);
			$option = implode($post);
			$commapos = strpos($option, ',');
			$this->hookModel->criarWebhook($usuario_id, substr($option, 0, $commapos), substr($option, $commapos + 1));
		} else if (isset($_POST['hookid'])) {
			$this->hookModel->deletarWebhook($_POST['hookid']);
		}
	}

	public function postHooks($webhookController, $cartaoController) {
		if (isset($_SERVER['CONTENT_TYPE'])) {
			$payload = json_decode(file_get_contents('php://input'));
			switch ($_SERVER['HTTP_X_GITHUB_EVENT']) {
				case 'issues':
					switch ($payload->action) {
						case 'closed':
							$cartao = $cartaoController->acharPorCorpo($payload->issue->title);
							if(!empty($cartao)) {
								$cartaoController->deletar($cartao['id']);
							}
							break;
						case 'reopened':
						case 'opened':
							$hook = $webhookController->lerWebhook($_GET['token']);
							$cartaoController->adicionar($payload->issue->title, $hook['lista_id']);
							break;
						case 'edited':
							if (empty($payload->changes->title)) {
								break;
							}
							$cartao = $cartaoController->acharPorCorpo($payload->changes->title->from);
							if(!empty($cartao)) {
								$cartaoController->atualizar($cartao['id'], $payload->issue->title);
							}
							break;
					}
					echo 'Sucesso\n';
					print_r($payload);
					break;
				default:
					header('HTTP/1.O 404 Not Found');
					die();
					break;
			}
		}
	}
}