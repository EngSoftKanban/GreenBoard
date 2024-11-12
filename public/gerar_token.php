<?php
require_once 'src/bdpdo.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario_id'])) {
    $usuario_id = (int)$_POST['usuario_id'];

    $token = bin2hex(random_bytes(16));

    $stmt = $pdo->prepare("UPDATE usuarios SET api_token = :token WHERE id = :id");
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':id', $usuario_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Token gerado com sucesso para o usuário ID $usuario_id: $token";
    } else {
        $_SESSION['message'] = "Erro ao gerar o token.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Token</title>
    <link rel="stylesheet" href="gerar_token.css"> 
</head>

<body>
<header>
        <div class="board-title">
            <img src="/resources/logo.png" alt="Logo GreenBoard" class="logo">
            <h1>GreenBoard</h1>
        </div>
        <div class="user-avatar">
            <img src="/resources/user.png" alt="Usuário" class="user-icon">
        </div>
    </header>
<h2>GERAR TOKEN</h2>



<form action="gerar_token.php" method="post">
    <label for="usuario_id">ID do Usuário:</label>
    <input type="number" id="usuario_id" name="usuario_id" required>
    <br><br>
    <button type="submit">Gerar Token</button>
</form>

<?php if (isset($_SESSION['message'])): ?>
    <p><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
<?php endif; ?>

</body>
</html>
