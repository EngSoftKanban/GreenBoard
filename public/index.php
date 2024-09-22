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

// Seleciona as listas
$sql = "SELECT * FROM listas";
$listas = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenBoard - Kanban</title>
    <link rel="stylesheet" href="style.css">
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
    
    <div class="scroll-container">
    <div class="kanban-board">
        <?php foreach ($listas as $lista): ?>
            <div class="column" id="lista_<?php echo $lista['id']; ?>">
                <div class="column-header">
                    <div class="title-container">
                        <h2><?php echo $lista['titulo']; ?></h2>
                    </div>
                </div>

                <?php
                // Seleciona os cartões pertencentes à lista
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
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="add-card-container">    <!-- Botão para adicionar cartão -->
                <button class="add-card-btn" onclick="showAddCardForm(<?php echo $lista['id']; ?>)">Adicionar Cartão
                <img src="plus.png" alt="Adicionar" class="icon" style="width: 20px; height: 20px; margin-left: 45px;">
                </button>

                <!-- Formulário para adicionar cartão (oculto inicialmente) -->
                <form id="addCardForm_<?php echo $lista['id']; ?>"  style="display:none;" class="add-card-form" onsubmit="addCard(event, <?php echo $lista['id']; ?>)">
                    <input type="text" name="corpo_cartao" placeholder="Insira um nome para o cartão..." required>
                    <button type="submit" >Adicionar</button>
                </form>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="add-list-container">
            <button class="add-card-btn" style="width: 230px; border-radius: 15px; height: 55px; background-color: #91d991; margin-right: 10px; margin-top: -6px;" onclick="showAddListForm()">Adicionar Lista
                <img src="plus.png" alt="Adicionar" class="icon" style="width: 20px; height: 20px; margin-left: 45px; ">
            </button>

            <form id="addListForm" class= "add-card-form" style="display:none;" onsubmit="addList(event)">
                <input type="text" name="titulo_lista" placeholder="Insira um título" required>
                <button type="submit">Adicionar</button>
            </form>
        </div>
    </div>        
    </div>

    <script>
        // Função para exibir o formulário de adicionar cartão
        function showAddCardForm(lista_id) {
            const form = document.getElementById(`addCardForm_${lista_id}`);
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        }

        // Função para adicionar uma lista
        function addList(event) {
            event.preventDefault();
            const form = document.getElementById('addListForm');
            const formData = new FormData(form);

            fetch('adicionar_lista.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                alert(result);
                location.reload();  // Recarrega a página para atualizar as listas
            })
            .catch(error => console.error('Erro:', error));
        }
        function showAddListForm() {
            const form = document.getElementById('addListForm');
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        }
        // Função para adicionar um cartão
        function addCard(event, lista_id) {
            event.preventDefault();
            const form = document.getElementById(`addCardForm_${lista_id}`);
            const formData = new FormData(form);
            formData.append('lista_id', lista_id);

            fetch('adicionar_cartao.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                alert(result);
                location.reload();  // Recarrega a página para atualizar os cartões
            })
            .catch(error => console.error('Erro:', error));
        }
    </script>
</body>
</html>