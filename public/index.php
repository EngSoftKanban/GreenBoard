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

// Seleciona as listas, mantendo a ordenação
$sql = "SELECT * FROM listas ORDER BY posicao";
$listas = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenBoard - Kanban</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <style>
        .kanban-board,
        .column,
        .card {
            user-select: none; /* Impede a seleção de texto enquanto arrasta */
        }
    </style>
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
                            <div class="column-options">
                                <span class="options-icon" onclick="toggleOptions(<?php echo $lista['id']; ?>)" style="color: black;">&#9998;</span>
                                <div class="options-menu" id="options_menu_<?php echo $lista['id']; ?>">
                                    <button class="edit-list-btn" onclick="showEditListForm(<?php echo $lista['id']; ?>)">Editar</button>
                                    <button class="delete-list-btn" onclick="deleteColumn(<?php echo $lista['id']; ?>)">Excluir</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cards-container">
                        <?php
                        $sqlCards = "SELECT * FROM cartoes WHERE lista_id = :lista_id ORDER BY posicao";
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
                                        <span class="options-icon" onclick="toggleCardOptions(<?php echo $cartao['id']; ?>)" style="color: black;">&#9998;</span>
                                        <div class="card-options-menu" id="card_options_menu_<?php echo $cartao['id']; ?>">
                                            <button class="edit-card-btn" onclick="showEditCardForm(<?php echo $cartao['id']; ?>)">Editar</button>
                                            <button class="delete-card-btn" onclick="deleteCard(<?php echo $cartao['id']; ?>)">Excluir</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="add-card-container">
                        <button id="addCardButton_<?php echo $lista['id']; ?>" class="add-card-btn" onclick="showAddCardForm(<?php echo $lista['id']; ?>)">Adicionar Cartão
                            <img src="plus.png" alt="Adicionar" class="icon" style="width: 20px; height: 20px; margin-left: 5px;">
                        </button>

                        <form id="addCardForm_<?php echo $lista['id']; ?>" class="add-card-form" style="display:none;" onsubmit="addCard(event, <?php echo $lista['id']; ?>)">
                            <input type="text" name="corpo_cartao" placeholder="Insira um nome para o cartão..." required>
                            <button type="submit" style="font-size: 15px;">Adicionar Cartão</button>
                            <button type="button" style="background-color: transparent;" onclick="hideAddCardForm(<?php echo $lista['id']; ?>)">
                                <img src="close_icon.png" alt="Fechar" style="width: 20px; height: 20px;">
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="add-list-container">
                <button id="addListButton" class="add-card-btn" style="width: 250px; border-radius: 15px; height: 55px; background-color: #91d991; margin-right: 10px; margin-top: -10px;" onclick="showAddListForm()">Adicionar Lista
                    <img src="plus.png" alt="Adicionar" class="icon" style="width: 20px; height: 20px; margin-left: 45px;">
                </button>

                <form id="addListForm" class="add-list-form" style="display:none;" onsubmit="addList(event)">
                    <input type="text" name="titulo_lista" placeholder="Insira um título para a lista..." required>
                    <button type="submit" style="font-size: 15px;">Adicionar Lista</button>
                    <button type="button" style="background-color: transparent;" onclick="hideAddListForm()">
                        <img src="close_icon.png" alt="Fechar" style="width: 20px; height: 20px;">
                    </button>
                </form>
            </div>
        </div>        
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicialização para colunas
            const kanbanBoard = document.querySelector('.kanban-board');

            new Sortable(kanbanBoard, {
                group: 'listas',
                animation: 150,
                handle: '.column-header', // Manuseia a arrastabilidade pelos cabeçalhos
                onEnd: function (evt) {
                    let listas = kanbanBoard.querySelectorAll('.column');
                    let order = [];
                    listas.forEach((lista, index) => {
                        order.push({
                            id: lista.id.replace('lista_', ''),
                            position: index + 1
                        });
                    });
                    atualizarOrdemListas(order);
                }
            });

            // Inicialização para cartões
            document.querySelectorAll('.cards-container').forEach(function (container) {
                new Sortable(container, {
                    group: 'cartoes',
                    animation: 150,
                    handle: '.card-header',
                    onEnd: function (evt) {
                        let cartoes = evt.to.querySelectorAll('.card');
                        let order = [];
                        cartoes.forEach((cartao, index) => {
                            order.push({
                                id: cartao.id.replace('card_', ''),
                                lista_id: evt.to.closest('.column').id.replace('lista_', ''),
                                position: index + 1
                            });
                        });
                        atualizarOrdemCartoes(order);
                    }
                });
            });
        });

        // Função para atualizar a ordem das listas no banco de dados
        function atualizarOrdemListas(order) {
            fetch('atualizar_ordem_listas.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(order)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    console.log('Ordem das listas atualizada com sucesso');
                } else {
                    console.error('Erro ao atualizar a ordem das listas');
                }
            })
            .catch(error => console.error('Erro:', error));
        }

        // Função para atualizar a ordem dos cartões no banco de dados
        function atualizarOrdemCartoes(order) {
            fetch('atualizar_ordem_cartoes.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(order)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    console.log('Ordem dos cartões atualizada com sucesso');
                } else {
                    console.error('Erro ao atualizar a ordem dos cartões');
                }
            })
            .catch(error => console.error('Erro:', error));
        }

        function showAddListForm() {
            document.getElementById('addListForm').style.display = 'block';
            document.getElementById('addListButton').style.display = 'none';
        }

        function hideAddListForm() {
            document.getElementById('addListForm').style.display = 'none';
            document.getElementById('addListButton').style.display = 'block';
        }

        function addList(event) {
            event.preventDefault();
            let formData = new FormData(event.target);
            fetch('adicionar_lista.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    location.reload(); // Recarrega a página para ver a nova lista
                } else {
                    console.error('Erro ao adicionar a lista');
                }
            })
            .catch(error => console.error('Erro:', error));
        }

        function showAddCardForm(listaId) {
            document.getElementById('addCardForm_' + listaId).style.display = 'block';
            document.getElementById('addCardButton_' + listaId).style.display = 'none';
        }

        function hideAddCardForm(listaId) {
            document.getElementById('addCardForm_' + listaId).style.display = 'none';
            document.getElementById('addCardButton_' + listaId).style.display = 'block';
        }

        function addCard(event, listaId) {
            event.preventDefault();
            let formData = new FormData(event.target);
            formData.append('lista_id', listaId);
            fetch('adicionar_cartao.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    location.reload(); // Recarrega a página para ver o novo cartão
                } else {
                    console.error('Erro ao adicionar o cartão');
                }
            })
            .catch(error => console.error('Erro:', error));
        }

        function deleteColumn(listaId) {
            if (confirm('Tem certeza que deseja excluir esta lista?')) {
                fetch('deletar_lista.php?id=' + listaId, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        location.reload(); // Recarrega a página para ver a lista removida
                    } else {
                        console.error('Erro ao deletar a lista');
                    }
                })
                .catch(error => console.error('Erro:', error));
            }
        }

        function deleteCard(cartaoId) {
            if (confirm('Tem certeza que deseja excluir este cartão?')) {
                fetch('deletar_cartao.php?id=' + cartaoId, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        location.reload(); // Recarrega a página para ver o cartão removido
                    } else {
                        console.error('Erro ao deletar o cartão');
                    }
                })
                .catch(error => console.error('Erro:', error));
            }
        }

        function toggleOptions(listaId) {
            const menu = document.getElementById('options_menu_' + listaId);
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        }

        function toggleCardOptions(cartaoId) {
            const menu = document.getElementById('card_options_menu_' + cartaoId);
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        }

        function showEditListForm(listaId) {
            // Implementação da lógica para editar uma lista
        }

        function showEditCardForm(cartaoId) {
            // Implementação da lógica para editar um cartão
        }
    </script>
</body>
</html>
