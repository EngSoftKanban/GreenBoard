<?php
namespace EngSoftKanban\GreenBoard;

use \PDO;

$host = apache_getenv("DB_HOST");
$dbname = apache_getenv("DB_NAME");
$user = apache_getenv("DB_USER");
$password = apache_getenv("DB_PASS");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Model/Quadro.php';

if( isset($_GET['quadro_id'])) {
	$_SESSION['quadro_id'] = $_GET['quadro_id'];
}
$quadro_id = $_SESSION['quadro_id'];

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/View/quadro.php';