<?php
require 'db_connection.php'; 

$userId = 1; 

$stmt = $conn->prepare("SELECT nome, email, profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

echo json_encode($user);

$stmt->close();
$conn->close();
?>