<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado']);
    exit();
}

$targetDir = "uploads/"; // Diretório onde as imagens serão armazenadas
$targetFile = $targetDir . basename($_FILES["profileImage"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Verificar se a imagem é um arquivo de imagem real
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["profileImage"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'O arquivo não é uma imagem.']);
        $uploadOk = 0;
    }
}

// Verificar se o arquivo já existe
if (file_exists($targetFile)) {
    echo json_encode(['status' => 'error', 'message' => 'Desculpe, o arquivo já existe.']);
    $uploadOk = 0;
}

// Verificar tamanho do arquivo
if ($_FILES["profileImage"]["size"] > 500000) { // Limite de 500KB
    echo json_encode(['status' => 'error', 'message' => 'Desculpe, seu arquivo é muito grande.']);
    $uploadOk = 0;
}

// Permitir certos formatos de arquivo
if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
    echo json_encode(['status' => 'error', 'message' => 'Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.']);
    $uploadOk = 0;
}

// Verificar se $uploadOk é 0 por erro
if ($uploadOk == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Desculpe, seu arquivo não foi enviado.']);
} else {
    if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $targetFile)) {
        // Salve o caminho da imagem no banco de dados
        $user_id = $_SESSION['user_id'];
        $host = apache_getenv("DB_HOST");
        $dbname = apache_getenv("DB_NAME");
        $user = apache_getenv("DB_USER");
        $password = apache_getenv("DB_PASS");

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE usuarios SET profile_image = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$targetFile, $user_id]);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao conectar ao banco de dados.']);
            exit();
        }

        echo json_encode(['status' => 'success', 'imageUrl' => $targetFile]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Desculpe, ocorreu um erro ao enviar seu arquivo.']);
    }
}
?>