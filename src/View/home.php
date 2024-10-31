<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenBoard - Sistema de Kanban</title>
    <!-- Estilos CSS internos -->
    <style>
        /* Estilos globais */
        .container-principal {
            max-width: 1200px;
            margin: 0 auto;
        }
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

        /* Restante do conteúdo */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 20px;
            background-color: #2b3d29;
        }

        .kanban-board {
            display: flex;
            gap: 20px;
            padding: 20px;
        }

        .kanban-column {
            background-color: #3a5240;
            padding: 15px;
            border-radius: 8px;
            width: 200px;
        }

        .kanban-column h3 {
            margin-bottom: 15px;
            font-size: 18px;
            color: white;
        }

        .kanban-card {
            background-color: #d4d8d2;
            color: black;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
            display: flex;
            flex-direction: column;
        }

        .card-labels {
            display: flex;
            gap: 4px;
            margin-bottom: 8px;
        }

        .label-blue {
            width: 30px;
            height: 6px;
            background-color: #4f93c7;
            border-radius: 3px;
        }

        .label-yellow {
            width: 30px;
            height: 6px;
            background-color: #e7c63c;
            border-radius: 3px;
        }

        .add-card {
            background-color: transparent;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }

        .description {
            padding: 30px;
            text-align: center;
        }

        .description h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .description p {
            font-size: 16px;
            max-width: 600px;
            color: #c2c2c2;
        }

        .home {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <!-- Barra superior -->
    <div class="top-bar">
        <img src="/resources/logo_1.png" alt="Logo GreenBoard" class="logo">
        <a href="/login.php" class="enter-button">Entre. É grátis</a>
    </div>

    <!-- Conteúdo principal centralizado -->
    <div class="container-principal">
        <header class="header">
            <!-- Espaço para o conteúdo do cabeçalho, se necessário -->
        </header>
        <main>
            <!-- Imagem principal ou conteúdo da página inicial -->
            <img src="/resources/homepage.png" alt="Homepage" class="home">
            </div>
        </main>
    </div>
</body>
</html>
