<?php
namespace EngSoftKanban\GreenBoard;

if (!isset($_GET['token'])) {
	header('HTTP/1.O 404 Not Found');
	die();
}

require_once 'src/bdpdo.php';
require_once 'src/Model/Quadro.php';
require_once 'src/Controller/ListaController.php';
require_once 'src/Controller/CartaoController.php';
require_once 'src/Controller/WebhookController.php';

use EngSoftKanban\GreenBoard\Controller\ListaController;
use EngSoftKanban\GreenBoard\Controller\CartaoController;
use EngSoftKanban\GreenBoard\Controller\WebhookController;

$cartaoController = new CartaoController($pdo);
$webhookController = new WebhookController($pdo);

$webhookController->postHooks($webhookController, $cartaoController);