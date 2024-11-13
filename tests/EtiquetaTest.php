<?php

require_once __DIR__ . '/../src/Model/Cartao.php';
require_once __DIR__ . '/../src/Controller/CartaoController.php';

use PHPUnit\Framework\TestCase;
use EngSoftKanban\GreenBoard\Model\Cartao;
use EngSoftKanban\GreenBoard\Controller\CartaoController;

class EtiquetaTest extends TestCase
{
    private $pdo;
    private $cartaoModel;
    private $cartaoController;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->exec("
            CREATE TABLE etiquetas (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT,
                cor TEXT,
                cartao_id INTEGER
            );
        ");

        $this->cartaoModel = new Cartao($this->pdo);
        $this->cartaoController = new CartaoController($this->pdo);
    }

    public function testAdicionarEtiquetaNoController()
    {
        $nome = 'Urgente';
        $cor = '#FF0000';
        $cartao_id = 1;

        $response = $this->cartaoController->adicionarEtiqueta($nome, $cor, $cartao_id);
        $result = json_decode($response, true);

        $this->assertTrue($result['success'], "Falha ao adicionar etiqueta através do controller.");

        $stmt = $this->pdo->query("SELECT * FROM etiquetas WHERE nome = '$nome'");
        $etiqueta = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($etiqueta, "Etiqueta não encontrada no banco de dados.");
        $this->assertEquals($nome, $etiqueta['nome'], "Nome da etiqueta não corresponde.");
    }

    public function testAtualizarEtiquetaNoController()
    {
        $this->pdo->exec("INSERT INTO etiquetas (nome, cor, cartao_id) VALUES ('Atualizar', '#00FF00', 1)");
        $etiqueta_id = $this->pdo->lastInsertId();

        $novoNome = 'Atualizado';
        $novaCor = '#0000FF';

        $response = $this->cartaoController->updateEtiqueta($etiqueta_id, $novoNome, $novaCor);
        $result = json_decode($response, true);

        $this->assertTrue($result['status'] === 'success', "Falha ao atualizar a etiqueta.");

        $stmt = $this->pdo->query("SELECT * FROM etiquetas WHERE id = $etiqueta_id");
        $etiqueta = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals($novoNome, $etiqueta['nome'], "Nome da etiqueta não foi atualizado.");
        $this->assertEquals($novaCor, $etiqueta['cor'], "Cor da etiqueta não foi atualizada.");
    }

    public function testDeletarEtiquetaNoController()
    {
        $this->pdo->exec("INSERT INTO etiquetas (nome, cor, cartao_id) VALUES ('Excluir', '#FFFF00', 1)");
        $etiqueta_id = $this->pdo->lastInsertId();

        $response = $this->cartaoController->deleteEtiqueta($etiqueta_id);
        $result = json_decode($response, true);

        $this->assertTrue($result['status'] === 'success', "Falha ao excluir a etiqueta.");

        $stmt = $this->pdo->query("SELECT * FROM etiquetas WHERE id = $etiqueta_id");
        $etiqueta = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertFalse($etiqueta, "Etiqueta ainda existe no banco de dados.");
    }

    public function testListarEtiquetasPorCartao()
    {
        $this->pdo->exec("INSERT INTO etiquetas (nome, cor, cartao_id) VALUES ('Etiqueta 1', '#FF0000', 1)");
        $this->pdo->exec("INSERT INTO etiquetas (nome, cor, cartao_id) VALUES ('Etiqueta 2', '#00FF00', 1)");

        $response = $this->cartaoController->listarEtiquetasPorCartao(1);
        $result = json_decode($response, true);

        $this->assertEquals('success', $result['status'], "Falha ao listar as etiquetas.");
        $this->assertCount(2, $result['data'], "Número de etiquetas não corresponde.");
        $this->assertEquals('Etiqueta 1', $result['data'][0]['nome'], "Nome da primeira etiqueta não corresponde.");
        $this->assertEquals('Etiqueta 2', $result['data'][1]['nome'], "Nome da segunda etiqueta não corresponde.");
    }

    protected function tearDown(): void
    {
        $this->pdo = null;
    }
}