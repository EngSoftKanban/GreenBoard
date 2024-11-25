<?php
namespace EngSoftKanban\GreenBoard;

session_start();

if (!isset($_SESSION['usuario_id'])) {
	header('Location: login.php');
}

require_once 'src/bdpdo.php';
require_once 'src/Controller/TokenController.php';
require_once 'src/Controller/QuadroController.php';

use EngSoftKanban\GreenBoard\Controller\TokenController;
use EngSoftKanban\GreenBoard\Controller\QuadroController;

$tokenController = new TokenController($pdo);
$quadroController = new QuadroController($pdo);

$tokenController->post();

$tokens = $tokenController->lerPorQuadro($_SESSION['quadro_id']);
$quadro = $quadroController->ler($_SESSION['quadro_id']);
$opcoes = $tokenController->lerQuadrosPermitidos($_SESSION['quadro_id'], $_SESSION['usuario_id']);

require_once 'src/View/tokens.php';