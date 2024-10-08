<?php
// Inicie a sessão
session_start();

// Inclua o arquivo de conexão ao banco de dados
require_once 'db_connection.php'; // Substitua pelo seu arquivo de conexão

// Verifique se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pegue o e-mail e a senha do formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Prepare a consulta para verificar o usuário no banco de dados
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    // Verifique se o usuário foi encontrado
    if ($stmt->rowCount() > 0) {
        // O usuário foi encontrado, agora verificamos a senha
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar a senha (supondo que a senha esteja armazenada como hash)
        if (password_verify($senha, $usuario['senha'])) {
            // Senha correta, iniciar a sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];

            // Redirecionar o usuário para a página principal ou anterior
            header("Location: " . ($_SESSION['redirect_url'] ?? 'dashboard.php')); // Se o usuário estava tentando acessar algo antes, redireciona para lá
            exit();
        } else {
            // Senha incorreta, exibir erro
            $erro = "Senha incorreta. Tente novamente.";
        }
    } else {
        // E-mail não encontrado, exibir erro
        $erro = "E-mail não encontrado. Tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GreenBoard</title>
    <style>
        /* Estilos que você já adicionou */
        body {
            background-color: #3C7A3F; /* Verde do fundo */
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 20px;
            width: 350px;
            text-align: center;
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: normal;
        }

        img {
            width: 100px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            font-weight: bold;
            margin-bottom: 10px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 30px; /* Borda arredondada */
            background-color: #B1E3A3;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #337E47;
            color: white;
            border: none;
            border-radius: 30px; /* Borda arredondada */
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #2D6A3F;
        }

        .forgot-password {
            text-align: right;
            margin-bottom: 20px;
        }

        .forgot-password a {
            color: #337E47;
            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .signup {
            margin-top: 20px;
        }

        .signup a {
            color: #337E47;
            text-decoration: none;
        }

        .signup a:hover {
            text-decoration: underline;
        }

        .erro {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo e título -->
        <img src="logo.png" alt="Logo GreenBoard"> <!-- Substitua com o caminho correto da imagem -->
        <h1>GreenBoard</h1>
        <p>Sua melhor opção!</p>

        <!-- Exibe mensagem de erro, se houver -->
        <?php if (isset($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>

        <!-- Formulário de login -->
        <form action="login.php" method="POST">
            <label for="email">E-mail</label>
            <input type="email" name="email" required>

            <label for="senha">Senha</label>
            <input type="password" name="senha" required>

            <div class="forgot-password">
                <a href="rec_senha.php">Esqueceu sua senha?</a>
            </div>

            <button type="submit">Entrar</button>
        </form>

        <!-- Link para cadastro -->
        <div class="signup">
            Ainda não tem login? <a href="registrar.php">Cadastre-se</a>
        </div>
    </div>
</body>
</html>
