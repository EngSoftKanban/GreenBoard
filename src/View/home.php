<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenBoard - Sistema de Kanban</title>
    <style>
        /* Estilos globais */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #2b3d29;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Barra superior */
        .top-bar {
            background-color: white;
            height: 60px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            position: relative;
            width: 100%;
        }

        .logo {
            height: 40px;
            position: absolute;
            left: 20px;
        }

        .enter-button {
            padding: 10px 20px;
            background-color: #2e7d32;
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            position: absolute;
            right: 20px;
        }

        .enter-button:hover {
            background-color: #2D6A3F;
        }

        /* Container da imagem principal */
        .description-container {
            position: relative;
            width: 100%;
            height: 100vh; /* Ocupa toda a altura da tela */
            background-image: url('/resources/homepage21.png');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: flex-start; /* Alinha o conteúdo ao início da imagem */
            justify-content: flex-end; /* Alinha o conteúdo à direita */
            padding: 20px;
        }

        /* Estilos para o texto sobreposto */
        .text-overlay {
            background-color: rgba(43, 61, 41, 0.8); /* Fundo semi-transparente */
            color: white;
            padding: 20px;
            max-width: 40%; /* Limita a largura do texto */
            text-align: justify;
            font-size: 16px;
            border-radius: 8px;
            margin-top: 250px; /* Adiciona margem superior para levantar o texto */
        }

        .text-overlay h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .text-overlay p {
            font-size: 14px;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <!-- Barra superior -->
    <div class="top-bar">
        <img src="/resources/logo_1.png" alt="Logo GreenBoard" class="logo">
        <a href="/login.php" class="enter-button">Entre. É grátis</a>
    </div>

    <!-- Conteúdo principal com a imagem de fundo e texto sobreposto -->
    <div class="description-container">
        <div class="text-overlay">
            <h1>Sistema de Kanban</h1>
            <p>Aprimore a produtividade e aumente a efetividade da sua empresa usando o sistema de Kanban gratuito número 1 do mercado. Controle as tarefas e fluxos de trabalho do seu time ou de um projeto importante. Use o Greenboard e aproveite!</p>
        </div>
    </div>
</body>
</html>
