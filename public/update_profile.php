<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

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

$usuario_id = $_SESSION['usuario_id'];
$nome = htmlspecialchars($_POST['nome'] ?? $_SESSION['nome'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? $_SESSION['email'] ?? '');
$apelido = htmlspecialchars($_POST['apelido'] ?? $_SESSION['apelido'] ?? '');
$dataNascimento = htmlspecialchars($_POST['dataNascimento'] ?? $_SESSION['dataNascimento'] ?? '');
$profileImage = $_SESSION['icone'] ?? null;

if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = './resources/icones/';
    $fileName = basename($_FILES['profileImage']['name']); 
    $profileImage = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $profileImage)) {
        $_SESSION['icone'] = $profileImage; 
        echo "Imagem enviada com sucesso: " . $profileImage; 
    } else {
        echo "Erro ao mover o arquivo.";
        exit;
    }
} else {
    echo "Erro no upload da imagem: " . $_FILES['profileImage']['error']; 
} 

$sql = "UPDATE usuarios SET nome = ?, email = ?, icone = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $nome);
$stmt->bindParam(2, $email);
$stmt->bindParam(3, $profileImage);
$stmt->bindParam(4, $usuario_id);


if ($stmt->execute()) {

    $_SESSION['nome'] = $nome;
    $_SESSION['email'] = $email;
    $_SESSION['apelido'] = $_POST['apelido'] ?? $_SESSION['apelido'] ?? '';
    $_SESSION['dataNascimento'] = $_POST['dataNascimento'] ?? $_SESSION['dataNascimento'] ?? '';
    $_SESSION['icone'] = $profileImage; 

    echo json_encode([
        'status' => 'success',
        'data' => [
            'nome' => $nome,
            'email' => $email,
            'apelido' => $_SESSION['apelido'],
            'dataNascimento' => $_SESSION['dataNascimento'],
            'icone' => $_SESSION['icone'] 
        ]
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar o perfil.']);
}

$pdo = null;
?>