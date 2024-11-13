<?php // TODO Impedir usuário não autorizado de acessar o quadro via URL
namespace EngSoftKanban\GreenBoard;
// $startt = microtime(true);

session_start();

if (!isset($_SESSION['usuario_id'])) {
	header('Location: login.php');
}

if(isset($_GET['quadro_id'])) {
	$_SESSION['quadro_id'] = $_GET['quadro_id'];
}

$quadro_id = $_SESSION['quadro_id'];

require_once 'src/bdpdo.php';
require_once 'src/Model/Quadro.php';
require_once 'src/Controller/ListaController.php';
require_once 'src/Controller/CartaoController.php';
require_once 'src/Controller/MembroController.php';

use EngSoftKanban\GreenBoard\Controller\ListaController;
use EngSoftKanban\GreenBoard\Controller\CartaoController;
use EngSoftKanban\GreenBoard\Controller\MembroController;

$listaController = new ListaController($pdo);

$cartaoController = new CartaoController($pdo);
$membroController = new MembroController($pdo);

$listaController->post();
$cartaoController->post();
$membroController->post();

$listas = $listaController->listar($quadro_id);

require_once 'src/View/quadro.php';

// echo (microtime(true) - $startt) * 1000;