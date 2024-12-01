<?php // TODO Impedir usuário não autorizado de acessar o quadro via URL
namespace EngSoftKanban\GreenBoard;

require_once 'src/bdpdo.php';
require_once 'src/Model/Quadro.php';
require_once 'src/Controller/ListaController.php';
require_once 'src/Controller/CartaoController.php';
require_once 'src/Controller/MembroController.php';
require_once 'src/Controller/PermissaoController.php';
require_once 'src/Controller/UsuarioController.php';

use EngSoftKanban\GreenBoard\Controller\ListaController;
use EngSoftKanban\GreenBoard\Controller\CartaoController;
use EngSoftKanban\GreenBoard\Controller\MembroController;
use EngSoftKanban\GreenBoard\Controller\PermissaoController;
use EngSoftKanban\GreenBoard\Controller\UsuarioController;

session_start();

$permissaoController = new PermissaoController($pdo);

if (!isset($_SESSION['usuario_id']) || !$permissaoController->possuiPermissao($_SESSION['usuario_id'], $_SESSION['quadro_id'])) {
	header('Location: login.php');
}

if(isset($_GET['quadro_id'])) {
	$_SESSION['quadro_id'] = $_GET['quadro_id'];
}

$permissoes = $permissaoController->lerPorQuadro($_SESSION['quadro_id']);

$usuarioController = new UsuarioController($pdo);
$usuarios = $usuarioController->lerPorIds(array_column($permissoes, 'usuario_id'));

$listaController = new ListaController($pdo);

$cartaoController = new CartaoController($pdo);
$membroController = new MembroController($pdo);

$listaController->post();
$cartaoController->post();
$membroController->post();

$listas = $listaController->listar($_SESSION['quadro_id']);
$cartoes = $cartaoController->lerPorListas(array_column($listas, 'id'));

require_once 'src/View/quadro.php';