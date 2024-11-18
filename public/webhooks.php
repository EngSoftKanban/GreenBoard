<?php
namespace EngSoftKanban\GreenBoard;

session_start();

if (!isset($_SESSION['usuario_id'])) {
	header('Location: login.php');
}

require_once 'src/bdpdo.php';
require_once 'src/Model/Webhook.php';
require_once 'src/Controller/WebhookController.php';

use EngSoftKanban\GreenBoard\Controller\WebhookController;

$usuario_id = $_SESSION['usuario_id'];
$hookController = new WebhookController($pdo);

$hookController->postWebhooks($usuario_id);

$hooks = $hookController->listarWebhooks($usuario_id);
$opcoes = $hookController->listarQuadrosEListas($usuario_id);

require_once 'src/View/webhooks.php';