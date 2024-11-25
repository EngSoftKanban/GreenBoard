<?php
declare(strict_types=1);

namespace EngSoftKanban\GreenBoard\Teste;

require_once 'tests/Simular.php';

use \PDO;
use PHPUnit\Framework\TestCase;
use EngSoftKanban\GreenBoard\Teste\Simular;
use EngSoftKanban\GreenBoard\Controller\TokenController;

final class TokenTest extends TestCase {
	public PDO $pdo;
	public TokenController $controller;

	public function testCriarToken(): void {
		$token = ['id' => 2, 'quadro_id' => 1];
		$this->assertTrue($this->controller->criar($token['quadro_id']));

		$rs = $this->controller->ler($token['quadro_id']);
		$this->assertEquals($token['id'], $rs['id']);
		$this->assertEquals($token['quadro_id'], $rs['quadro_id']);
	}

	public function testLerPorQuadro(): void {
		$token = ['id' => 1, 'quadro_id' => 1];
		$rs = $this->controller->lerPorQuadro($token['id']);
		$this->assertEquals($token['id'], $rs['id']);
		$this->assertEquals($token['quadro_id'], $rs['quadro_id']);
	}
	
	public function testExcluirToken(): void {
		$token = ['id' => 1, 'quadro_id' => 1];
		$this->assertTrue($this->controller->excluir($token['id']));
		$this->assertEquals(null, $this->controller->ler($token['id']));
	}

	protected function setUp(): void {
		$this->pdo = Simular::criarBD();
		$this->controller = new TokenController($this->pdo);
	}

	protected function tearDown(): void {
		Simular::destruirBD();
	}
}
