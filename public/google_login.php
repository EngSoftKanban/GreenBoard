<?php
require_once 'db_connection.php';
require realpath(__DIR__ . '/../src/Controller/LoginController.php');
require_once __DIR__ . '/../vendor/autoload.php';


use EngSoftKanban\GreenBoard\Controller\LoginController;

$controller = new LoginController($pdo);
$controller->googleLogin();
