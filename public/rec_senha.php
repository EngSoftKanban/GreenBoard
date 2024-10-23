<?php
namespace EngSoftKanban\GreenBoard;

require_once 'db_connection.php'; // O caminho deve ser correto se o arquivo está em public
require realpath(__DIR__ . '/../src/Model/PasswordModel.php'); // Ajuste o caminho para o PasswordModel
require realpath(__DIR__ . '/../src/Controller/PasswordController.php'); // Ajuste o caminho para o PasswordController

use EngSoftKanban\GreenBoard\Controller\PasswordController;

// Instanciar o controlador de recuperação de senha
$controller = new PasswordController($pdo);

$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $message = $controller->requestReset($email);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de senha</title>
    <link rel="stylesheet" href="resources/css/style_rec.css">
</head>
<body>
    <div class="container">
        <img src="/resources/logo_rec_senha.png" alt="GreenBoard Logo" class="logo">
        <?php if ($message): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required>
            <button type="submit" class="btn">Redefinir senha</button>
        </form>
    </div>
</body>
</html>
