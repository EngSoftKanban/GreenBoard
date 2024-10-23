<?php
namespace EngSoftKanban\GreenBoard;

session_start();

if (!isset($_SESSION['usuario_id'])) {
	header('Location: login.php');
}

require_once 'db_connection.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Model/Quadro.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Controller/PainelController.php';

use EngSoftKanban\GreenBoard\Controller\PainelController;

$controller = new PainelController($pdo);
$controller->post();

$quadros = $controller->getAll();
$recente = $controller->getRecent();

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/View/painel.php';