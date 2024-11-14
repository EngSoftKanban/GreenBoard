<?php
namespace EngSoftKanban\GreenBoard\Teste;

use \PDO;

class Simular {
	public static $bD = array(
		'host' => '127.0.0.1',
		'db' => 'GreenTest',
		'user' => 'root',
		'pass' => '',
		'charset' => 'utf8mb4'
	);

	public static $queries = null;

	public static function criarBD(): PDO {
		$globalPDO = new PDO('mysql:host=' . Simular::$bD['host'] . ';', Simular::$bD['user'], Simular::$bD['pass']);
		$globalPDO->exec('CREATE DATABASE ' . Simular::$bD['db'] . '; GRANT ALL ON ' . Simular::$bD['db'] . '.* TO \''
			. Simular::$bD['user'] . '\'@\'localhost\'; FLUSH PRIVILEGES;');
		
		$pdo = new PDO('mysql:host=' . Simular::$bD['host'] . ';dbname=' . Simular::$bD['db'] . ';charset=' . Simular::$bD['charset'],
			Simular::$bD['user'], Simular::$bD['pass'], [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => false,
		]);
		Simular::$queries ??= file_get_contents('tests/GreenTest.sql', true);
		$pdo->exec(Simular::$queries);
		return $pdo;
	}

	public static function destruirBD(): void {
		$globalPDO = new PDO('mysql:host=' . Simular::$bD['host'] . ';', Simular::$bD['user'], Simular::$bD['pass']);
		$globalPDO->exec('DROP DATABASE '. Simular::$bD['db']);
	}

	public static function sessaoDono(): void {
		$_SESSION['usuario_id'] = 1;
		$_SESSION['nome'] = 'dono';
		$_SESSION['email'] = 'dono@mail';
		$_SESSION['icone'] = '/resources/icone.svg';
	}
}
