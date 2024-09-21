<?php

$host = 'localhost';
$dbname = 'GreenBoard';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}


$sql = "SELECT * FROM listas";
$listas = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

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
        <?php foreach ($listas as $lista): ?>
            <div class="column" id="lista_<?php echo $lista['id']; ?>">
                <div class="column-header">
                    <h2><?php echo $lista['titulo']; ?></h2>
                    <div class="column-options">
                    <span class="options-icon" onclick="toggleOptions(<?php echo $lista['id']; ?>)" style="color: black;">&#9998;</span>
                        <div class="options-menu" id="options_menu_<?php echo $lista['id']; ?>">
                            <button class="edit-list-btn">Editar</button>
                            <button class="delete-list-btn" onclick="deleteColumn(<?php echo $lista['id']; ?>)">Excluir</button>
                        </div>
                    </div>
                </div>

                <?php
                
                $sqlCards = "SELECT * FROM cartoes WHERE lista_id = :lista_id";
                $stmt = $pdo->prepare($sqlCards);
                $stmt->bindParam(':lista_id', $lista['id']);
                $stmt->execute();
                $cartoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <?php foreach ($cartoes as $cartao): ?>
                    <div class="card" id="card_<?php echo $cartao['id']; ?>">
                        <div class="card-header">
                            <p><?php echo $cartao['corpo']; ?></p>
                            <div class="card-options">
<span class="options-icon" onclick="toggleOptions(<?php echo $lista['id']; ?>)" style="color: black;">&#9998;</span>
                                <div class="card-options-menu" id="card_options_menu_<?php echo $cartao['id']; ?>">
                                    <button class="edit-card-btn">Editar</button>
                                    <button class="delete-card-btn" onclick="deleteCard(<?php echo $cartao['id']; ?>)">Excluir</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <button class="add-card-btn">Adicionar cartão +</button>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function deleteColumn(lista_id) {
            if (confirm("Tem certeza que deseja excluir esta lista?")) {
                const formData = new FormData();
                formData.append('excluir_lista_id', lista_id);
                
                fetch('excluir_lista.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(result => {
                    alert(result);
                    document.getElementById(`lista_${lista_id}`).remove();
                })
                .catch(error => console.error('Erro:', error));
            }
        }

        function deleteCard(cartao_id) {
            if (confirm("Tem certeza que deseja excluir este cartão?")) {
                const formData = new FormData();
                formData.append('excluir_cartao_id', cartao_id);
                
                fetch('excluir_cartao.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(result => {
                    alert(result);
                    document.getElementById(`card_${cartao_id}`).remove();
                })
                .catch(error => console.error('Erro:', error));
            }
        }

        function toggleOptions(lista_id) {
            const menu = document.getElementById(`options_menu_${lista_id}`);
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        }

        function toggleCardOptions(cartao_id) {
            const menu = document.getElementById(`card_options_menu_${cartao_id}`);
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        }
    </script>
</body>
</html>
