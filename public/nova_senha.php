<?php
session_start();
require_once 'db_connection.php'; 
require_once 'functions.php';      

if (isset($_GET['token']) && validateTokenSession($_GET['token'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newPassword = $_POST['new-password'];
        $confirmPassword = $_POST['confirm-password'];

        if ($newPassword === $confirmPassword) {
            $email = $_SESSION['email']; 
            updatePassword($email, $newPassword, $pdo);

            header("Location: sucesso.php");
            exit;
        } else {
            echo "As senhas não coincidem!";
        }
    }
} else {
    header("Location: token_invalido.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova senha</title>
    <link rel="stylesheet" href="style_rec.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
</head>
<body>
    <div style="height:420px;" class="container">
        <img src="logo_rec_senha.png" alt="GreenBoard Logo" class="logo">
        <form action="" method="POST">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <label for="new-password">Nova senha:</label>
            <input type="password" class="input_btn1" id="new-password" name="new-password" required>
            <label for="confirm-password">Confirmar nova senha:</label>
            <input type="password" class="input_btn2" id="confirm-password" name="confirm-password" required>
            <button type="submit" class="btn">Confirmar</button>
        </form>
    </div>



</body>
</html>