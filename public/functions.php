<?php

session_start(); 

function isUserExists($email, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->rowCount() === 1;
}

function generateTokenSession() {
    $token = bin2hex(random_bytes(50));
    $_SESSION['reset_token'] = $token; 
    $_SESSION['token_expiration'] = time() + 3600;
    return $token;
}

function sendResetEmail($email, $token) {
    $link = "http://localhost:8080/public/nova_senha?token=.php" . $token;
    $message = "Clique no link para redefinir sua senha: " . $link;
    mail($email, "Recuperação de senha", $message, "From: green4board@hotmail.com");
}

function validateTokenSession($token) {
    if (isset($_SESSION['reset_token']) && $_SESSION['reset_token'] === $token) {
        if (time() < $_SESSION['token_expiration']) {
            return true;
        }
    }
    return false; 
}

function updatePassword($email, $password, $pdo) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
    $stmt->execute([$hashedPassword, $email]);

    unset($_SESSION['reset_token']);
    unset($_SESSION['token_expiration']);
}

?>