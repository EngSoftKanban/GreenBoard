<?php
namespace EngSoftKanban\GreenBoard;

session_start();

if (!isset($_SESSION['usuario_id'])) {
	header('Location: login.php');
}

if( isset($_GET['quadro_id'])) {
	$_SESSION['quadro_id'] = $_GET['quadro_id'];
}

$quadro_id = $_SESSION['quadro_id'];

require_once 'db_connection.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Model/Quadro.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Controller/ListaController.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Controller/CartaoController.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Controller/MembroController.php';

use EngSoftKanban\GreenBoard\Controller\ListaController;
use EngSoftKanban\GreenBoard\Controller\CartaoController;
use EngSoftKanban\GreenBoard\Controller\MembroController;

$listaController = new ListaController($pdo);
$listas = $listaController->listar($quadro_id);

$cartaoController = new CartaoController($pdo);
$membroController = new MembroController($pdo);

$membroController->post();

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/View/quadro.php';