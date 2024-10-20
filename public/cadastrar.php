<?php
namespace EngSoftKanban\GreenBoard;

require_once 'db_connection.php';  
require realpath(__DIR__ . '/../src/Model/User.php');         
require realpath(__DIR__ . '/../src/Controller/RegisterController.php'); 

use EngSoftKanban\GreenBoard\Controller\RegisterController;

$controller = new RegisterController($pdo);
$erro = $controller->register();

require realpath(__DIR__ . '/../src/View/register.php');
?>
