<?php
namespace EngSoftKanban\GreenBoard;

require_once 'vendor/autoload.php'; // Autoloader do Composer
require_once 'src/bdpdo.php';

use EngSoftKanban\GreenBoard\Controller\PasswordController;

// Instanciar o controlador de recuperação de senha
$controller = new PasswordController($pdo);

$message = null;
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newPassword = $_POST['new-password'];
        $confirmPassword = $_POST['confirm-password'];

        $message = $controller->resetPassword($token, $newPassword, $confirmPassword);
        if ($message === true) {
            header("Location: sucesso.php");
            exit;
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
    <link rel="stylesheet" href="/resources/css/style_rec.css">
</head>
<body>
    <div class="container">
        <img src="/resources/logo_rec_senha.png" alt="GreenBoard Logo" class="logo">
        <?php if ($message): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="new-password">Nova senha:</label>
            <input type="password" id="new-password" name="new-password" required>
            <label for="confirm-password">Confirmar nova senha:</label>
            <input type="password" id="confirm-password" name="confirm-password" required>
            <button type="submit" class="btn">Confirmar</button>
        </form>
    </div>
</body>
</html>
