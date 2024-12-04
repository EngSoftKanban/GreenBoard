<?php
namespace EngSoftKanban\GreenBoard;

require_once 'src/bdpdo.php';
require 'src/Model/User.php';
require 'src/Controller/UsuarioController.php';

use EngSoftKanban\GreenBoard\Controller\UsuarioController;

$controller = new UsuarioController($pdo);
$erro = $controller->resposta();

require 'src/View/cadastrar.php';
