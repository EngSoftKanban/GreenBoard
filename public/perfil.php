<?php
namespace EngSoftKanban\GreenBoard;

session_start();

if (!isset($_SESSION['usuario_id'])) {
	header('Location: login.php');
}

require_once 'db_connection.php';

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/../src/View/perfil.php';