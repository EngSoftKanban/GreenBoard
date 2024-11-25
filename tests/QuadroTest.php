<?php
declare(strict_types=1);

namespace EngSoftKanban\GreenBoard\Teste;

require_once 'tests/Simular.php';

use \PDO;
use PHPUnit\Framework\TestCase;
use EngSoftKanban\GreenBoard\Teste\Simular;
use EngSoftKanban\GreenBoard\Controller\QuadroController;

final class QuadroTest extends TestCase {
	public PDO $pdo;
	public QuadroController $controller;

	public function testCriarQuadro(): void {
		$quadro = ['id' => 2, 'nome' => 'Quadro'];
		$this->assertTrue($this->controller->criar($quadro['nome'], $_SESSION['usuario_id']));

		$rs = $this->controller->ler(quadro_id: $quadro['id']);
		$this->assertEquals($quadro['id'], $rs['id']);
		$this->assertEquals($quadro['nome'], $rs['nome']);
	}

	public function testRemoverQuadro(): void {
		$quadro = ['id' => 2, 'nome' => 'Quadro'];
		$this->assertTrue($this->controller->remover($quadro['id']));
		$this->assertEquals(null, $this->controller->ler(quadro_id: $quadro['id']));
	}

	protected function setUp(): void {
		$this->pdo = Simular::criarBD();
		Simular::sessaoDono();
		$this->controller = new QuadroController($this->pdo);
	}

	protected function tearDown(): void {
		Simular::destruirBD();
	}
}