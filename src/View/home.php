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
        }

        /* Barra superior */
        .top-bar {
            background-color: white; /* Cor da barra */
            height: 60px; /* Altura da barra */
            display: flex; /* Para alinhar itens horizontalmente */
            align-items: center; /* Alinha itens verticalmente ao centro */
            padding: 0 20px; /* Espaçamento nas laterais */
            position: relative; /* Para que os filhos possam usar posicionamento absoluto */
            width: 100%;
        }

        .logo {
            height: 40px; /* Altura do logo */
            position: absolute; /* Permite que o logo fique sobreposto */
            left: 20px; /* Distância da esquerda */
        }

        .enter-button {
            padding: 10px 20px; /* Espaçamento interno do botão */
            background-color: #2e7d32; /* Cor de fundo do botão para combinar com o login */
            color: white; /* Cor do texto do botão */
            border: none; /* Remove borda padrão */
            border-radius: 30px; /* Bordas arredondadas */
            cursor: pointer; /* Cursor como mão ao passar por cima */
            text-decoration: none; /* Remove sublinhado do link */
            font-size: 16px; /* Tamanho da fonte do botão */
            font-weight: bold;
            position: absolute; /* Posicionamento absoluto para ficar à direita */
            right: 20px; /* Distância da direita */
        }

        .enter-button:hover {
            background-color: #2D6A3F; /* Cor de fundo ao passar o mouse */
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
    </style>
</head>
<body>
    <div class="top-bar">
        <img src="/resources/logo_1.png" alt="Logo GreenBoard" class="logo">
        <a href="/login.php" class="enter-button">Entre. É grátis</a>
    </div>
    <header class="header">
    </header>
    <main>
        <img src="/resources/homepage.png" alt="Homepage" class="home">
    </main>
</body>
</html>
