<?php
namespace EngSoftKanban\GreenBoard;

require_once 'src/bdpdo.php';
require_once 'src/Model/Usuario.php';
require_once 'src/Controller/LoginController.php';

use EngSoftKanban\GreenBoard\Controller\LoginController;

$controller = new LoginController($pdo);
$erro = $controller->login();

require_once 'src/View/login.php';