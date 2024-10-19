<?php
require 'db_connection.php'; // Inclua seu arquivo de conexão com o banco de dados

// ID do usuário de teste
$userId = 1; // O mesmo ID que você usou para atualizar

$stmt = $conn->prepare("SELECT nome, email, profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

echo json_encode($user);

$stmt->close();
$conn->close();
?>