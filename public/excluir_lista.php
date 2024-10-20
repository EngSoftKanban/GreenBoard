<?php
// Habilita a exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexão com o banco de dados
$host = apache_getenv("DB_HOST");
$dbname = apache_getenv("DB_NAME");
$user = apache_getenv("DB_USER");
$password = apache_getenv("DB_PASS");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}

require_once '../controllers/ListasController.php';

$controller = new ListaController($pdo);

// Verifica se há uma ação de excluir lista
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_lista_id'])) {
    $lista_id = $_POST['excluir_lista_id'];

    // Verifica se o ID da lista foi fornecido
    if ($lista_id) {
        if ($controller->deletarLista($lista_id)) {
            echo json_encode(['success' => true, 'message' => 'Lista e cartões excluídos com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao excluir a lista.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID da lista não fornecido.']);
    }
    exit; // Garante que o script não continue após a resposta JSON
}

// Se não for um POST, retorna um erro
echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
exit; // Garante que o script não continue após a resposta JSON
?>
