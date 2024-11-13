<?php
namespace EngSoftKanban\GreenBoard;

require_once 'src/bdpdo.php';
require 'src/Model/User.php';
require 'src/Controller/RegisterController.php';

use EngSoftKanban\GreenBoard\Controller\RegisterController;

$controller = new RegisterController($pdo);
$erro = $controller->register();

require 'src/View/cadastrar.php';
