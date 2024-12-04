<?php
namespace EngSoftKanban\GreenBoard;

session_start();

if (!isset($_SESSION['usuario_id'])) {
	header('Location: login.php');
}

require_once 'src/bdpdo.php';
require_once 'src/Model/Quadro.php';
require_once 'src/Controller/QuadroController.php';

use EngSoftKanban\GreenBoard\Controller\QuadroController;

$controller = new QuadroController($pdo);
$controller->post();

$quadros = $controller->lerTodos($_SESSION['usuario_id']);
$recente = $controller->lerRecente($_SESSION['usuario_id']);

require_once 'src/View/painel.php';