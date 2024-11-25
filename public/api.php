<?php
namespace EngSoftKanban\GreenBoard;

require_once 'src/bdpdo.php';
require_once 'src/Controller/TokenController.php';

use EngSoftKanban\GreenBoard\Controller\TokenController;

$tokenController = new TokenController($pdo);

$tokenController->postAPI();