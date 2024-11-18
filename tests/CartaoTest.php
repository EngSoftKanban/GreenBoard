<?php

require_once __DIR__ . '/../src/Model/Cartao.php';
require_once __DIR__ . '/../src/Controller/CartaoController.php';

use PHPUnit\Framework\TestCase;
use EngSoftKanban\GreenBoard\Model\Cartao;
use EngSoftKanban\GreenBoard\Controller\CartaoController;


class CartaoTest extends TestCase
{
    private $pdo;
    private $cartaoModel;
    private $cartaoController;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->exec("
            CREATE TABLE cartoes (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                corpo TEXT,
                lista_id INTEGER,
                posicao INTEGER
            );
        ");
        
        $this->cartaoModel = new Cartao($this->pdo);
        $this->cartaoController = new CartaoController($this->pdo);
    }

    public function testAdicionarCartaoNoController()
    {
        $corpo = 'Novo Cartão';
        $lista_id = 1;
        $posicao = 1;

        $response = $this->cartaoController->adicionar($corpo, $lista_id);

        $result = json_decode($response, true);

        $this->assertTrue($result['success'], "Falha ao adicionar cartão através do controller.");

        $stmt = $this->pdo->query("SELECT * FROM cartoes WHERE corpo = '$corpo'");
        $cartao = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($cartao, "Cartão não encontrado no banco de dados.");
        $this->assertEquals($corpo, $cartao['corpo'], "Corpo do cartão não corresponde.");
    }

    public function testAtualizarCartaoNoController()
    {
        $this->pdo->exec("INSERT INTO cartoes (corpo, lista_id, posicao) VALUES ('Cartão para Atualizar', 1, 1)");
        $cartao_id = $this->pdo->lastInsertId();

        $novoCorpo = 'Cartão Atualizado';

        $response = $this->cartaoController->atualizar($cartao_id, $novoCorpo);
        $result = json_decode($response, true);

        $this->assertTrue($result['success'], "Falha ao atualizar o cartão através do controller.");

        $stmt = $this->pdo->query("SELECT * FROM cartoes WHERE id = $cartao_id");
        $cartao = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals($novoCorpo, $cartao['corpo'], "Corpo do cartão não foi atualizado.");
    }

    public function testDeletarCartaoNoController()
    {
        $this->pdo->exec("INSERT INTO cartoes (corpo, lista_id, posicao) VALUES ('Cartão para Deletar', 1, 1)");
        $cartao_id = $this->pdo->lastInsertId();

        $response = $this->cartaoController->remover($cartao_id);
        $this->assertTrue($response, "Falha ao deletar o cartão através do controller.");

        $stmt = $this->pdo->query("SELECT * FROM cartoes WHERE id = $cartao_id");
        $cartao = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertFalse($cartao, "Cartão não foi removido do banco de dados.");
    }

    public function testListarCartoesPorLista()
    {
        $this->pdo->exec("INSERT INTO cartoes (corpo, lista_id, posicao) VALUES ('Cartão 1', 1, 1)");
        $this->pdo->exec("INSERT INTO cartoes (corpo, lista_id, posicao) VALUES ('Cartão 2', 1, 2)");

        $cartoes = $this->cartaoController->listarCartoesPorLista(1);

        $this->assertEquals('Cartão 1', $cartoes[0]['corpo'], "Corpo do primeiro cartão não corresponde.");
        $this->assertEquals('Cartão 2', $cartoes[1]['corpo'], "Corpo do segundo cartão não corresponde.");
    }

    protected function tearDown(): void
    {
        $this->pdo = null; 
    }
}