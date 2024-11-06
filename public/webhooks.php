<?php
namespace EngSoftKanban\GreenBoard;

session_start();

if (!isset($_SESSION['usuario_id'])) {
	header('Location: login.php');
}

require_once 'db_connection.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Model/Webhook.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/Controller/WebhookController.php';

use EngSoftKanban\GreenBoard\Controller\WebhookController;

$usuario_id = $_SESSION['usuario_id'];
$hookController = new WebhookController($pdo);
$hooks = $hookController->listarWebhooks($usuario_id);
$opcoes = $hookController->listarQuadrosEListas($usuario_id);

$hookController->postWebhooks($usuario_id);

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/View/webhooks.php';