<?php
require_once 'controllers/QuadrosController.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = apache_getenv("DB_HOST");
$dbname = apache_getenv("DB_NAME");
$user = apache_getenv("DB_USER");
$password = apache_getenv("DB_PASS");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $controller = new QuadrosController($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            if ($_POST['action'] === 'delete') {
                
                try {
                    $controller->delete($_POST['quadro_id']);
                } catch (Exception $e) {
                    echo "Erro ao remover quadro: " . $e->getMessage();
                }
            } elseif ($_POST['action'] === 'create') {
                $controller->create($_POST['nome_quadro']);
            }
        }
    }
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
