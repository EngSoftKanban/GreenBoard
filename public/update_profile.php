<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado.']);
    exit();
}

$host = 'localhost';
$dbname = 'GreenBoard';
$user = 'root';
$password = '';
$charset = 'utf8mb4';

header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao conectar com o banco de dados: ' . $e->getMessage()]);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$nome = htmlspecialchars($_POST['nome'] ?? $_SESSION['nome'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? $_SESSION['email'] ?? '');
$profileImage = $_SESSION['icone'] ?? '/resources/icone.svg';

if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = mime_content_type($_FILES['profileImage']['tmp_name']);

    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(['status' => 'error', 'message' => 'Tipo de arquivo não permitido.']);
        exit();
    }

    if ($_FILES['profileImage']['size'] > 2 * 1024 * 1024) {
        echo json_encode(['status' => 'error', 'message' => 'O arquivo excede o tamanho máximo de 2MB.']);
        exit();
    }

    $uploadDir = __DIR__ . '/resources/icones/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao criar o diretório de upload.']);
        exit();
    }

    $fileName = uniqid() . '-' . basename($_FILES['profileImage']['name']);
    $profileImage = '/resources/icones/' . $fileName;

    if (!move_uploaded_file($_FILES['profileImage']['tmp_name'], $uploadDir . $fileName)) {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao mover o arquivo.']);
        exit();
    }
}

try {
    $sql = "UPDATE usuarios SET icone = ?, nome = ?, email = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $profileImage);
    $stmt->bindParam(2, $nome);
    $stmt->bindParam(3, $email);
    $stmt->bindParam(4, $usuario_id);

    if ($stmt->execute()) {
        $_SESSION['icone'] = $profileImage;
        $_SESSION['nome'] = $nome;
        $_SESSION['email'] = $email;

        echo json_encode([
            'status' => 'success',
            'data' => [
                'icone' => $_SESSION['icone'],
                'nome' => $nome,
                'email' => $email,
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar o perfil.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
}

$pdo = null;
