<?php
<<<<<<< HEAD
=======
namespace EngSoftKanban\GreenBoard\Teste;

>>>>>>> develop
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Model/Lista.php';
require_once __DIR__ . '/../src/Controller/ListaController.php';

use EngSoftKanban\GreenBoard\Model\Lista;
use EngSoftKanban\GreenBoard\Controller\ListaController;
use \PDO;

class ListaTest extends TestCase {

    private PDO $pdo;
    private ListaController $listaController;

    protected function setUp(): void {
        // Configura a conexão com o banco de dados em memória para testes
        $this->pdo = new PDO('sqlite::memory:'); // Usando SQLite para testar em memória
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Criação de tabelas temporárias para testes
        $this->pdo->exec("CREATE TABLE listas (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            titulo TEXT,
            posicao INTEGER,
            quadro_id INTEGER
        )");

        // Criação da tabela 'cartoes', já que está sendo acessada
        $this->pdo->exec("CREATE TABLE cartoes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            titulo TEXT,
            descricao TEXT,
            lista_id INTEGER,
            FOREIGN KEY(lista_id) REFERENCES listas(id)
        )");

        // Instancia o controller
        $this->listaController = new ListaController($this->pdo);
    }

    public function testListarListas() {
        // Insere algumas listas para testar
        $this->listaController->criar('Lista 1', 1);
        $this->listaController->criar('Lista 2', 1);

        $listas = $this->listaController->listar(1);
        $this->assertCount(2, $listas);
        $this->assertEquals('Lista 1', $listas[0]['titulo']);
        $this->assertEquals('Lista 2', $listas[1]['titulo']);
    }

    public function testCriarLista() {
        $resultado = $this->listaController->criar('Nova Lista', 1);
        $this->assertTrue($resultado);

        // Verifica se a lista foi realmente criada
        $listas = $this->listaController->listar(1);
        $this->assertCount(1, $listas);
        $this->assertEquals('Nova Lista', $listas[0]['titulo']);
    }

    public function testRemoverLista() {
        // Cria uma lista para remover
        $this->listaController->criar('Lista para Remover', 1);
        $listas = $this->listaController->listar(1);
        $lista_id = $listas[0]['id'];

        // Remover a lista
        $resultado = $this->listaController->remover($lista_id);
        $this->assertTrue($resultado);

        // Verifica se a lista foi removida
        $listas = $this->listaController->listar(1);
        $this->assertCount(0, $listas);
    }

    public function testAtualizarLista() {
        // Cria uma lista
        $this->listaController->criar('Lista Original', 1);
        $listas = $this->listaController->listar(1);
        $lista_id = $listas[0]['id'];

        // Atualiza a lista
        $resultado = $this->listaController->atualizarLista($lista_id, 'Lista Atualizada');
        $this->assertTrue($resultado);

        // Verifica se o título foi atualizado
        $listas = $this->listaController->listar(1);
        $this->assertEquals('Lista Atualizada', $listas[0]['titulo']);
    }

    protected function tearDown(): void {
        // Limpeza do banco de dados após os testes
        $this->pdo->exec("DROP TABLE IF EXISTS listas");
        $this->pdo->exec("DROP TABLE IF EXISTS cartoes");
    }
}
