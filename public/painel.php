<?php
namespace EngSoftKanban\GreenBoard;

session_start();

if (!isset($_SESSION['usuario_id'])) {
	header('Location: login.php');
}

require_once 'db_connection.php';
require_once __DIR__ . '/../src/Model/Quadro.php';
require_once __DIR__ . '/../src/Controller/PainelController.php';

use EngSoftKanban\GreenBoard\Controller\PainelController;

$controller = new PainelController($pdo);
$controller->post();

$quadros = $controller->getAll($_SESSION['usuario_id']);
$recente = $controller->getRecent($_SESSION['usuario_id']);

require_once __DIR__ . '/../src/View/painel.php';