<?php
namespace EngSoftKanban\GreenBoard;

if (!isset($_GET['token'])) {
	header('HTTP/1.O 404 Not Found');
	die();
}

require_once 'db_connection.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Model/Quadro.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Controller/ListaController.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Controller/CartaoController.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Controller/WebhookController.php';

use EngSoftKanban\GreenBoard\Controller\ListaController;
use EngSoftKanban\GreenBoard\Controller\CartaoController;
use EngSoftKanban\GreenBoard\Controller\WebhookController;

$cartaoController = new CartaoController($pdo);
$webhookController = new WebhookController($pdo);

$webhookController->postHooks($webhookController, $cartaoController);