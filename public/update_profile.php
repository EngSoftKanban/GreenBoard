<?php
session_start();
$_SESSION['user_id'] = 1;

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado']);
    exit;
}

// Conexão com o banco de dados
include 'db_connection.php';

$user_id = $_SESSION['user_id'];
$nome = $_POST['nome'];
$email = $_POST['email'];

// Verifica se uma imagem foi enviada
if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['profileImage']['tmp_name'];
    $file_name = $_FILES['profileImage']['name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $dest_path = "uploads/" . uniqid() . '.' . $file_ext; // Gera um nome único para o arquivo

    // Move o arquivo para o diretório de uploads
    if (move_uploaded_file($file_tmp, $dest_path)) {
        $icone = $dest_path; // Armazena o caminho da imagem

        // Atualiza nome, email e icone no banco de dados
        $sql = "UPDATE usuarios SET nome=?, email=?, icone=? WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nome, $email, $icone, $user_id);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao mover o arquivo para o diretório de uploads']);
        exit;
    }
} else {
    // Caso não haja upload de imagem, atualize apenas nome e email
    $sql = "UPDATE usuarios SET nome=?, email=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nome, $email, $user_id);
}

// Executa a query
if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar os dados no banco de dados']);
}

// Fecha a conexão
$stmt->close();
$conn->close();
?>