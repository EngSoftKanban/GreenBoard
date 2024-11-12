<?php
require_once 'src/bdpdo.php';
require_once 'src/Controller/LoginController.php';
require_once 'vendor/autoload.php';


use EngSoftKanban\GreenBoard\Controller\LoginController;

$controller = new LoginController($pdo);
$controller->googleLogin();
