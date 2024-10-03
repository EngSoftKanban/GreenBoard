<?php
require_once 'db_connection.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    if (isUserExists($email, $pdo)) {
        $token = generateToken($email, $pdo);
        sendResetEmail($email, $token);
        
        header("Location: nova_senha.php?email=" . urlencode($email) . "&token=" . urlencode($token));
        exit;
    } else {
        header("Location: erro.php");
        exit;
    }
}
?>