<?php
namespace EngSoftKanban\GreenBoard;

require_once 'db_connection.php';  
require realpath(__DIR__ . '/../src/Model/User.php');         
require realpath(__DIR__ . '/../src/Controller/LoginController.php'); 

use EngSoftKanban\GreenBoard\Controller\LoginController;

$controller = new LoginController($pdo);
$erro = $controller->login();

require realpath(__DIR__ . '/../src/View/login.php');
?>
