<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GreenBoard</title>
    <style>
        body {
            background-color: #2e7d32; 
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
            border-radius: 30px; 
            background-color: #81c784;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #2e7d32;
            color: white;
            border: none;
            border-radius: 30px; 
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

        .erro {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="logo_login.png" alt="Logo GreenBoard" style="width: 150px; height: auto;">

        <!-- Exibe mensagem de erro, se houver -->
        <?php if (isset($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>

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

        <div class="signup">
            Ainda não tem login? <a href="cadastrar.php">Cadastre-se</a>
        </div>
    </div>
</body>
</html>
