<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenBoard - Kanban</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="board-header">
        <div class="board-title">
            <img src="logo.png" alt="Logo GreenBoard" class="logo">
            <h1>GreenBoard</h1>
        </div>
        <div class="users">
            <img src="olivia.jpeg" alt="Usuário 1" class="user-icon">
            <img src="taylor.jpg" alt="Usuário 2" class="user-icon">
            <img src="lalisa.jpg" alt="Usuário 3" class="user-icon">
            <span class="extra-users">+1</span>
        </div>
    </div>

    <div class="kanban-board">
        <?php
        
        $columns = [
            "A fazer" => [
                ["title" => "Uma feature qualquer no Kanban", "statuses" => ["blue", "yellow", "cyan"]],
                ["title" => "Outra feature no Kanban como exemplo", "statuses" => []]
            ],
            "Em andamento" => [
                ["title" => "Uma feature qualquer no Kanban", "statuses" => ["cyan"]],
            ],
            "Concluído" => []
        ];

        foreach ($columns as $columnName => $cards) {
            $columnId = uniqid('col_'); 
            echo '<div class="column" id="' . $columnId . '">';
            echo '<div class="column-header">';
            echo '<h2>' . $columnName . '</h2>';
            echo '<div class="column-options">';
            echo '<span class="options-icon">&#8226;&#8226;&#8226;</span>';
            echo '<div class="options-menu">';
            echo '<button class="edit-list-btn">Editar</button>';
            echo '<button class="delete-list-btn" onclick="deleteColumn(\'' . $columnId . '\')">Excluir</button>'; 
            echo '</div>';
            echo '</div>';
            echo '</div>';

            foreach ($cards as $index => $card) {
                $cardId = uniqid('card_'); 
                echo '<div class="card" id="' . $cardId . '">';
                if (!empty($card['statuses'])) {
                    echo '<div class="card-header">';
                    foreach ($card['statuses'] as $status) {
                        echo '<span class="status ' . $status . '"></span>';
                    }
                    echo '</div>';
                }
                echo '<p>' . $card['title'] . '</p>';
                echo '<div class="card-options">';
                echo '<button class="edit-btn">&#9998;</button>';
                echo '<div class="card-options-menu">';
                echo '<button class="edit-card-btn">Editar</button>';
                echo '<button class="delete-card-btn" onclick="deleteCard(\'' . $cardId . '\')">Excluir</button>'; 
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }

            echo '<button class="add-card-btn">Adicionar cartão +</button>';
            echo '</div>';
        }
        ?>
    </div>

    <script>
        
        function deleteColumn(columnId) {
            const column = document.getElementById(columnId);
            if (column) {
                column.remove(); 
            }
        }

        
        function deleteCard(cardId) {
            const card = document.getElementById(cardId);
            if (card) {
                card.remove(); 
            }
        }
    </script>
</body>
</html>
