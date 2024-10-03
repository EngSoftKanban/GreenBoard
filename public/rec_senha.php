<?php
require_once 'db_connection.php'; 
require_once 'functions.php';  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    if (isUserExists($email, $pdo)) {
        $token = generateTokenSession(); 
        sendResetEmail($email, $token);
        echo "E-mail de recuperação enviado!";
    } else {
        echo "E-mail não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de senha</title>
    <link rel="stylesheet" href="style_rec.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
</head>
<body>
    <div class="container">
        <img src="logo_rec_senha.png" alt="GreenBoard Logo" class="logo">
        <form action="send_email.php" method="POST">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required>
            <button type="submit" class="btn">Redefinir senha</button>
        </form>
    </div>

</body>
</html>