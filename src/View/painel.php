<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quadros - GreenBoard</title>
    <link rel="stylesheet" href="resources/css/quadros.css">
    <style>
        #create-modal {
            display: none; 
            position: absolute; 
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 10; 
            padding: 20px;
        }

        a {
            text-decoration: none; 
            color: black; 
        }

        a:hover {
            text-decoration: underline; 
        }

        .quadro {
            margin: 10px 0; 
        }
    </style>
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
    
    <div class="container">
        <div class="sidebar">
            <div class="menu">
                <div class="menu-item active">
                    <span class="icon">🏠</span>
                    <span class="text">Início</span>
                </div>
                <div class="menu-item">
                    <span class="icon">📋</span>
                    <span class="text">Quadros</span>
                </div>
            </div>
        </div>
        
        <div class="main">
            <div class="recently-viewed">
                <h2>Visualizados recentemente</h2>
                <div>
                    <?php if (empty($recentes)): ?>
                        <p>Nenhum quadro visualizado recentemente.</p>
                    <?php else: ?>
                        <?php foreach ($recentes as $quadro): ?>
                            <div class="quadro">
                                <a href="quadro.php?quadro_id=<?php echo $quadro['id']; ?>"><?php echo $quadro['nome']; ?></a> <!-- Caminho mantido -->
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="work-area">
                <h2>Suas áreas de trabalho</h2>
                <div id="quadro-list">
                    <?php if (empty($quadros)): ?>
                        <p>Nenhum quadro encontrado.</p>
                    <?php else: ?>
                        <?php foreach ($quadros as $quadro): ?>
                            <div>
                                <a href="quadro.php?quadro_id=<?php echo $quadro['id']; ?>"><?php echo $quadro['nome']; ?></a>
                                <form action="" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja remover esse quadro?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="quadro_id" value="<?php echo $quadro['id']; ?>">
                                    <button type="submit">X</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="create-quadro" onclick="showModal(event)">
                        <span>Criar novo quadro</span>
                    </div>
                </div>
                
                <div id="create-modal">
                    <h3>Criar novo quadro</h3>
                    <form id="create-board-form" action="" method="POST">
                        <input type="hidden" name="action" value="create">
                        <label for="nome_quadro">Nome:</label>
                        <input type="text" id="nome_quadro" name="nome_quadro" required>
                        <button type="submit" class="create-quadro">Criar quadro</button>
                        <button type="button" class="cancel-button" onclick="document.getElementById('create-modal').style.display='none';">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function showModal(event) {
            const modal = document.getElementById('create-modal');
            modal.style.display = 'block';
            const rect = event.currentTarget.getBoundingClientRect();
            modal.style.top = `${rect.bottom + window.scrollY}px`; 
            modal.style.left = `${rect.left}px`; 
        }

        window.onclick = function(event) {
            const modal = document.getElementById('create-modal');
            const createQuadro = document.querySelector('.create-quadro');
            if (event.target == modal) {
                modal.style.display = "none";
            } else if (!createQuadro.contains(event.target) && !modal.contains(event.target)) {
                modal.style.display = "none"; 
            }
        }
    </script>
</body>
</html>
