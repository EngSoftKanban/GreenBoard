<?php
require_once __DIR__ . '/../src/Controller/HomeController.php';

use Src\Controller\HomeController;

$controller = new HomeController();
$controller->index();
