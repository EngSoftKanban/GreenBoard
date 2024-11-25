<?php
declare(strict_types=1);

namespace EngSoftKanban\GreenBoard;

require_once 'tests/Simular.php';

use \PDO;
use PHPUnit\Framework\TestCase;
use EngSoftKanban\GreenBoard\Teste\Simular;
use EngSoftKanban\GreenBoard\Controller\WebhookController;
use EngSoftKanban\GreenBoard\Controller\CartaoController;

final class WebhookTest extends TestCase {
	public PDO $pdo;
	public WebHookController $controller;

	public function testCriarWebhook(): void {
		$hook = ['id' => 2];
		$this->assertTrue($this->controller->criar($_SESSION['usuario_id'], 1, 1));

		$rs = $this->controller->ler(hook_id: $hook['id']);
		$this->assertEquals($hook['id'], $rs['id']);
	}
	
	public function testListarQuadrosEListas(): void {
		$ql = [ 0 => ['quadro_id' => 1, 'quadro_nome' => 'Kanban', 'lista_id' => 1, 'titulo' => 'A fazer'],
			1 => ['quadro_id' => 1, 'quadro_nome' => 'Kanban', 'lista_id' => 2, 'titulo' => 'Em andamento'],
			2  => ['quadro_id' => 1, 'quadro_nome' => 'Kanban', 'lista_id' => 3, 'titulo' => 'Concluido']
		];
		$this->assertEquals($ql, $this->controller->listarQuadrosEListas($_SESSION['usuario_id']));
	}

	public function testListarWebhooks(): void {
		$hooks = [0 => ['id' => 1, 'token' => '32a7942c40d5180e7f8624c5bb8f2ca0', 'usuario_id' => 1, 'quadro_id' => 1, 'lista_id' => 2]];
		$this->assertEquals($hooks, $this->controller->listarWebhooks($_SESSION['usuario_id']));
	}
	
	public function testLer(): void {
		$hook = ['id' => 1, 'token' => '32a7942c40d5180e7f8624c5bb8f2ca0', 'usuario_id' => 1, 'quadro_id' => 1, 'lista_id' => 2];
		$this->assertEquals($hook, $this->controller->ler(hook_id: $hook['id']));
	}

	public function testLerPorToken(): void {
		$hook = ['id' => 1, 'token' => '32a7942c40d5180e7f8624c5bb8f2ca0', 'usuario_id' => 1, 'quadro_id' => 1, 'lista_id' => 2];
		$this->assertEquals($hook, $this->controller->lerPorToken(token: $hook['token']));
	}

	public function testDestruir(): void {
		$hook = ['id' => 1];
		$this->assertTrue($this->controller->destruir($hook['id']));
		$this->assertEquals(null, $this->controller->ler(hook_id: $hook['id']));
	}

	// Testes sobre receber evento

	public function testAbrirIssue(): void {
		$cartaoController = new CartaoController($this->pdo);
		$cartao = ['id' => 4, 'corpo' => 'Título da issue', 'lista_id' => 2, 'posicao' => 1];
		
		$_GET['token'] = '32a7942c40d5180e7f8624c5bb8f2ca0';
		$_SERVER['CONTENT_TYPE'] = 'application/json';
		$_SERVER['HTTP_X_GITHUB_EVENT'] = 'issues';
		$payload = (object)['action' => 'opened', 'issue' => (object)['title' => 'Título da issue']];
		
		$this->expectOutputString('Sucesso\n' . print_r($payload, true));
		$this->controller->postHooks($this->controller, $cartaoController, $payload);

		$this->assertEquals($cartao, $cartaoController->acharPorCorpo($payload->issue->title));
	}
	
	public function testFecharIssue(): void {
		$cartaoController = new CartaoController($this->pdo);
		$cartao = ['id' => 4, 'corpo' => 'Título da issue', 'lista_id' => 2, 'posicao' => 1];
		$cartaoController->criar($cartao['corpo'], $cartao['lista_id']);
		
		$_GET['token'] = '32a7942c40d5180e7f8624c5bb8f2ca0';
		$_SERVER['CONTENT_TYPE'] = 'application/json';
		$_SERVER['HTTP_X_GITHUB_EVENT'] = 'issues';
		$payload = (object)['action' => 'closed', 'issue' => (object)['title' => 'Título da issue']];
		
		$this->expectOutputString('Sucesso\n' . print_r($payload, true));
		$this->controller->postHooks($this->controller, $cartaoController, $payload);

		$this->assertEquals(null, $cartaoController->acharPorCorpo($payload->issue->title));
	}

	public function testEditarIssue(): void {
		$cartaoController = new CartaoController($this->pdo);
		$cartao = ['id' => 4, 'corpo' => 'Título da issue', 'lista_id' => 2, 'posicao' => 1];
		$cartaoController->criar($cartao['corpo'], $cartao['lista_id']);

		$_GET['token'] = '32a7942c40d5180e7f8624c5bb8f2ca0';
		$_SERVER['CONTENT_TYPE'] = 'application/json';
		$_SERVER['HTTP_X_GITHUB_EVENT'] = 'issues';
		$payload = (object)['action' => 'edited', 'issue' => (object)['title' => 'Novo título da issue'],
			'changes' => (object)['title' => (object)[ 'from' => 'Título da issue']]
		];
		$cartao = ['id' => 4, 'corpo' => 'Novo título da issue', 'lista_id' => 2, 'posicao' => 1];

		$this->expectOutputString('Sucesso\n' . print_r($payload, true));
		$this->controller->postHooks($this->controller, $cartaoController, $payload);

		$this->assertEquals($cartao, $cartaoController->acharPorCorpo($payload->issue->title));
	}

	protected function setUp(): void {
		$this->pdo = Simular::criarBD();
		Simular::sessaoDono();
		$this->controller = new WebhookController($this->pdo);
	}

	protected function tearDown():void {
		Simular::destruirBD();
	}
}