<?php

require_once __DIR__ . '/../src/Model/User.php';

use PHPUnit\Framework\TestCase;
use EngSoftKanban\GreenBoard\Model\User;

class UserTest extends TestCase
{
    private $pdo;
    private $userModel;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->exec("
            CREATE TABLE usuarios (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT,
                email TEXT UNIQUE,
                senha TEXT
            );
        ");
        
        $this->userModel = new User($this->pdo);
    }

    public function testRegistrarNovoUsuario()
    {
        $nome = 'John Doe';
        $email = 'johndoe@example.com';
        $senha = 'senha123';

        $resultado = $this->userModel->register($nome, $email, $senha);
        $this->assertTrue($resultado, "Falha ao registrar um novo usuário.");

        $stmt = $this->pdo->query("SELECT * FROM usuarios WHERE email = '$email'");
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($usuario, "Usuário não encontrado no banco de dados.");
        $this->assertEquals($nome, $usuario['nome'], "Nome do usuário não corresponde.");
        $this->assertEquals($email, $usuario['email'], "Email do usuário não corresponde.");
        $this->assertTrue(password_verify($senha, $usuario['senha']), "A senha do usuário não foi armazenada corretamente.");
    }

    public function testEncontrarUsuarioPorEmail()
    {
        $nome = 'Jane Doe';
        $email = 'janedoe@example.com';
        $senha = 'senha456';
        
        $this->userModel->register($nome, $email, $senha);
        
        $usuario = $this->userModel->findUser($email);

        $this->assertNotEmpty($usuario, "Usuário não encontrado pelo método findUser.");
        $this->assertEquals($nome, $usuario['nome'], "Nome do usuário não corresponde ao esperado.");
        $this->assertEquals($email, $usuario['email'], "Email do usuário não corresponde ao esperado.");
    }

    public function testRegistrarUsuarioEmailDuplicado()
    {
        $nome1 = 'User One';
        $email = 'duplicate@example.com';
        $senha1 = 'senha789';
        
        $this->userModel->register($nome1, $email, $senha1);
        
        $nome2 = 'User Two';
        $senha2 = 'senha101112';
        
        $this->expectException(PDOException::class);
        $this->userModel->register($nome2, $email, $senha2);
    }

    protected function tearDown(): void
    {
        $this->pdo = null;
    }
}
