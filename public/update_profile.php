<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'src/bdpdo.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}

$usuario_id = $_SESSION['usuario_id'];
$nome = htmlspecialchars($_POST['nome'] ?? $_SESSION['nome'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? $_SESSION['email'] ?? '');
//$apelido = htmlspecialchars($_POST['apelido'] ?? $_SESSION['apelido'] ?? '');
//$dataNascimento = htmlspecialchars($_POST['dataNascimento'] ?? $_SESSION['dataNascimento'] ?? '');
$profileImage = $_SESSION['icone'] ?? "/resources/icone.svg";

if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = realpath(__DIR__ . '/resources/icones/');
    if (!$uploadDir) {
        mkdir(__DIR__ . '/resources/icones/', 0755, true);
        $uploadDir = realpath(__DIR__ . '/resources/icones/') . '/';
    }

    $fileName = uniqid() . '-' . basename($_FILES['profileImage']['name']); 
    $profileImage = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $profileImage)) {
        $_SESSION['icone'] = $profileImage; 
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao mover o arquivo.']);
        exit;
    }
} else {
    $profileImage = $_SESSION['icone'] ?? "/resources/icone.svg"; 
}


$sql = "UPDATE usuarios SET icone = ?, nome = ?, email = ?  WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $profileImage);;
$stmt->bindParam(2, $nome);
$stmt->bindParam(3, $email);
$stmt->bindParam(4, $usuario_id);


if ($stmt->execute()) {

    $_SESSION['icone'] = $profileImage; 
    $_SESSION['nome'] = $nome;
    $_SESSION['email'] = $email;
    //$_SESSION['apelido'] = $_POST['apelido'] ?? $_SESSION['apelido'] ?? '';
    //$_SESSION['dataNascimento'] = $_POST['dataNascimento'] ?? $_SESSION['dataNascimento'] ?? '';

    echo json_encode([
        'status' => 'success',
        'data' => [
            'icone' => $_SESSION['icone'],
            'nome' => $nome,
            'email' => $email,
            //'apelido' => $_SESSION['apelido'],
            //'dataNascimento' => $_SESSION['dataNascimento'], 
        ]
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar o perfil.']);
}

$pdo = null;
?>