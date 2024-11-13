<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use EngSoftKanban\GreenBoard\Model\Cartao;

class CartaoTeste extends TestCase
{
    private $pdoMock;
    private $cartao;

    protected function setUp(): void
    {
        
        $this->pdoMock = $this->createMock(PDO::class);
        
        
        $this->cartao = new Cartao($this->pdoMock);
    }

    public function testAdicionarCartao()
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);

        $this->pdoMock->method('prepare')->willReturn($stmtMock);

        $resultado = $this->cartao->adicionarCartao('Novo Cartão', 1);
        $this->assertTrue($resultado, 'O cartão não foi adicionado com sucesso');
    }

    public function testEditarCartao()
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);

        $this->pdoMock->method('prepare')->willReturn($stmtMock);

        $resultado = $this->cartao->atualizarCartao(1, 'Cartão Atualizado');
        $this->assertTrue($resultado, 'O cartão não foi editado com sucesso');
    }

    public function testRemoverCartao()
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);

        $this->pdoMock->method('prepare')->willReturn($stmtMock);

        $resultado = $this->cartao->removerCartao(1);
        $this->assertTrue($resultado, 'O cartão não foi removido com sucesso');
    }
}
