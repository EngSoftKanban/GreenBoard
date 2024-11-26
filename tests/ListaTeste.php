<?php
<<<<<<< HEAD
=======
namespace EngSoftKanban\GreenBoard\Teste;
>>>>>>> develop

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use EngSoftKanban\GreenBoard\Model\Lista;

class ListaTeste extends TestCase
{
    private $pdoMock;
    private $lista;

    protected function setUp(): void
    {
        
        $this->pdoMock = $this->createMock(PDO::class);
        
        
        $this->lista = new Lista($this->pdoMock);
    }

    public function testAdicionarLista()
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);

        $this->pdoMock->method('prepare')->willReturn($stmtMock);

        
        $resultado = $this->lista->criar('Nova Lista', 1);
        $this->assertTrue($resultado, 'A lista não foi adicionada com sucesso');
    }

    public function testEditarLista()
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);

        $this->pdoMock->method('prepare')->willReturn($stmtMock);

        
        $resultado = $this->lista->atualizar(1, 'Lista Editada');
        $this->assertTrue($resultado, 'A lista não foi editada com sucesso');
    }

    public function testRemoverLista()
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);

        $this->pdoMock->method('prepare')->willReturn($stmtMock);

        
        $resultado = $this->lista->remover(1);
        $this->assertTrue($resultado, 'A lista não foi removida com sucesso');
    }
}
