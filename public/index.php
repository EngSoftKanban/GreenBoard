<?php
session_start();
if (session_status() != PHP_SESSION_ACTIVE) {
	header('Location: login.php');
}
$host = apache_getenv("DB_HOST");
$dbname = apache_getenv("DB_NAME");
$user = apache_getenv("DB_USER");
$password = apache_getenv("DB_PASS");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}

$sql = "SELECT * FROM listas ORDER BY posicao";
$listas = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenBoard - Kanban</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
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

        <button class="share-button" onclick="openShareModal()">Compartilhar</button>
        
        <div class="menu-container">
            <div class="profile-icon" onclick="toggleMenu()">
            <img id="profileImageTopBar" src="" alt="Sem foto" class="profile-image-menu">
                <div class="background-panel">
                    <div class="profile-dropinfo">
                        <div class="profile-photoinfo">
                            <div class="profile-picture-placeholder">
                                <img id="profileImageMenu" alt="Sem foto" class="profile-image-menu" style="display: none;">
                            </div>
                            <div class="profile-name" id="displayName"></div>
                        </div>    
        <div class="dropdown-content">
            <a href="dados_pessoais.php">Dados Pessoais</a>
            <a href="#">Alterar Conta</a>
            <a href="#">Gerenciar Conta</a>
            <a href="#">Configurações</a>
            <a href="#">Logout</a>
        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Modal de compartilhamento -->
    <div id="shareModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeShareModal()">&times;</span>
            <h2 style="background-color: #91d991; width: 270px; border-radius: 15px; color: black; padding-left: 10px; padding-top: 5px; padding-bottom: 5px;">Compartilhar Kanban</h2>
            <form onsubmit="shareKanban(event)">
                <input class="share-form" type="text" id="shareInput" placeholder="Endereço de e-mail ou nome" required>
                <button type="submit" class="share-button">Enviar</button> 
            </form>
            <div class="share-container">
                <div class= "icon-container">
                    <img src="share.png" alt="Compartilhar" class="share-icon">
                </div>    
                <div class= "text-container">   
                    <p style="color: black; font-size: 16px; padding-left: 5px; padding-top: 5px;">Compartilhar KanBan com um link</p>
                    <button onclick="copyLink()" style="color: white; background-color: transparent; padding: 5px; width: 90px;">Copiar link</button> 
                </div>
            </div>     
            <h4 style="color: black; font-size: 15px; margin-top: 20px;">Membros do Kanban</h4>
            <ul id="memberList">
                <li style="color: black; margin-top: 15px; margin-left: 15px;">Fulana (você) - Administrador do Kanban</li>
                <?php

                $stmt = $pdo->query("SELECT nome FROM usuarios");
                $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($usuarios as $usuario) {
                    echo "<li>" . htmlspecialchars($usuario['nome']) . "</li>";
                }
                ?>
            </ul>
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
                        <div class="column-options">
                            <span class="options-icon" onclick="toggleOptions(<?php echo $lista['id']; ?>)" style="color: black;">&#9998;</span>
                            <div class="options-menu" id="options_menu_<?php echo $lista['id']; ?>">
                                <button class="edit-btn" onclick="editItem('lista', <?php echo $lista['id']; ?>, '<?php echo $lista['titulo']; ?>')">Editar</button>
                                <button class="edit-btn" onclick="deleteColumn(<?php echo $lista['id']; ?>)">Excluir</button>
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
                                        <span class="options-icon" onclick="toggleOptions(<?php echo $cartao['id']; ?>)" style="color: black;">&#9998;</span>
                                        <div class="card-options-menu" id="card_options_menu_<?php echo $cartao['id']; ?>">
                                            <button class="edit-btn" onclick="editItem('cartao', <?php echo $cartao['id']; ?>, '<?php echo $cartao['corpo']; ?>')">Editar</button>
                                            <button class="edit-btn" onclick="deleteCard(<?php echo $cartao['id']; ?>)">Excluir</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="add-card-container">
                            <button id="addCardButton_<?php echo $lista['id']; ?>" class="add-card-btn" onclick="showAddCardForm(<?php echo $lista['id']; ?>)">
								Adicionar Cartão
                                <img src="plus.svg" alt="Adicionar" class="icon" style="width: 20px; height: 20x; margin-left: 5px;float: right">
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
                </div>
            <?php endforeach; ?>

            <!-- Adicionar nova lista -->
            <div class="add-list-container">
                <button id="addListButton" class="add-card-btn" style="width: 250px; border-radius: 15px; height: 55px; background-color: #91d991; margin-right: 10px; margin-top: -10px;" onclick="showAddListForm()">Adicionar Lista
                    <img src="plus.svg" alt="Adicionar" class="icon" style="width: 20px; height: 20px; margin-left: 45px; float:right">
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
    <script src="./profileImage.js"></script>
    <script>

        window.onload = function() {
            loadProfileData(); 
        };

        function loadProfileData() {
            const apelido = localStorage.getItem('apelido');
            const profileImage = localStorage.getItem('profileImage');

            if (apelido) {
                document.getElementById('displayName').textContent = apelido;
            }

            if (profileImage) {
                document.getElementById('profileImageTopBar').src = profileImage; 
                document.getElementById('profileImageMenu').src = profileImage; 
            }
        }

        function toggleMenu() {
            var menuContainer = document.querySelector('.menu-container');
            menuContainer.classList.toggle('active');
        }
   
        function showAddCardForm(lista_id) {
            const form = document.getElementById(`addCardForm_${lista_id}`);
            const button = document.querySelector(`#addCardButton_${lista_id}`);
            if (form && button) {
                button.style.display = 'none';
                form.style.display = 'block'; 
            }
        }

        function hideAddCardForm(lista_id) {
            const form = document.getElementById(`addCardForm_${lista_id}`);
            const button = document.querySelector(`#addCardButton_${lista_id}`);
            if (form && button) {
                form.style.display = 'none'; 
                button.style.display = 'block'; 
            }
        }

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
                location.reload(); 
            })
            .catch(error => console.error('Erro:', error));
        }

        function addList(event) {
            event.preventDefault();
            const form = document.getElementById('addListForm');
            const formData = new FormData(form);
            formData.append('quadro_id', 1);
            fetch('adicionar_lista.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                alert(result);
                location.reload();  
            })
            .catch(error => console.error('Erro:', error));
        }

        function showAddListForm() {
            const form = document.getElementById('addListForm');
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
            document.getElementById('addListButton').style.display = 'none'; 
        }

        function hideAddListForm() {
            const form = document.getElementById('addListForm');
            form.style.display = 'none';
            document.getElementById('addListButton').style.display = 'block'; 
        }


        function editItem(tipo, item_id, texto) {
            let novo_texto = prompt(tipo == 'lista' ? "Entre o novo título da lista" : "Entre o novo corpo do cartão", texto);
            if (novo_texto) {
                const formData = new FormData();
                formData.append('editar_item_id', item_id);
                formData.append('editar_item_tipo', tipo);
                formData.append('editar_item_texto', novo_texto);
                
                fetch('editar_item.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(result => {
                    alert(result);
                    window.location.reload();
                })
                .catch(error => console.error('Erro:', error));
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            new Sortable(document.querySelector('.kanban-board'), {
                group: 'listas',
                animation: 150,
                handle: '.column-header',
                onEnd: function (evt) {
                    let listas = document.querySelectorAll('.column');
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
		});

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
        
        document.querySelectorAll('.cards-container').forEach(function (container) {
            new Sortable(container, {
                group: 'cartoes',
                animation: 150,
                handle: '.card',
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

        // Funções para atualizar a ordem das listas e cartões no banco de dados
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

        function openShareModal() {
            document.getElementById('shareModal').style.display = 'block';
        }

        function closeShareModal() {
            document.getElementById('shareModal').style.display = 'none';
        }

        function shareKanban(event) {
            event.preventDefault();
            const input = document.getElementById('shareInput').value;
            const shareLink = document.getElementById('shareLink').checked;
            
            // Aqui você faria uma requisição para o backend para compartilhar
            console.log('Compartilhando com:', input, 'Link ativado:', shareLink);
            
            // Exemplo de requisição AJAX
            fetch('compartilhar_kanban.php', {
                method: 'POST',
                body: JSON.stringify({ user: input, link: shareLink }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                // Atualize a lista de membros aqui
                closeShareModal();
            })
            .catch(error => console.error('Erro:', error));
        }

        function copyLink() {
            const dummy = document.createElement('textarea');
            dummy.value = "http://kanban.example.com/share-link";
            document.body.appendChild(dummy);
            dummy.select();
            document.execCommand('copy');
            document.body.removeChild(dummy);
            alert('Link copiado!');
        }

        function shareKanban(event) {
    event.preventDefault();
    const input = document.getElementById('shareInput').value;
    const shareLink = document.getElementById('shareLink').checked;

    fetch('compartilhar_kanban.php', {
        method: 'POST',
        body: JSON.stringify({ user: input, link: shareLink }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        // Atualizar a lista de membros dinamicamente
        if (!shareLink) {
            const memberList = document.getElementById('memberList');
            const newUser = document.createElement('li');
            newUser.textContent = input;
            memberList.appendChild(newUser);
        }
        closeShareModal();
    })
    .catch(error => console.error('Erro:', error));
}


    </script>
</body>
</html>
