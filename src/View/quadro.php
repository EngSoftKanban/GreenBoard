<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenBoard - Kanban</title>
    <link rel="stylesheet" href="/resources/css/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
</head>
<body>
    <div class="board-header">
        <div class="board-title">
            <a href="painel.php">
            <img src="/resources/logo.png" alt="Logo GreenBoard" class="logo">
            </a>
            <h1>GreenBoard</h1>
        </div>
        <div class="users">
        <img src="/resources/olivia.jpeg" alt="Usuário 1" class="user-icon">
        <img src="/resources/taylor.jpg" alt="Usuário 2" class="user-icon">
        <img src="/resources/lalisa.jpg" alt="Usuário 3" class="user-icon">

        <button class="share-button" onclick="openShareModal()">Compartilhar</button>
        
        <div class="menu-container">
            <div class="profile-icon" onclick="toggleMenu()">
                <img id="profileImageTopBar" 
                    src="<?php echo $_SESSION['icone']; ?>" class="profile-image-menu">
            </div>
            <?php include realpath($_SERVER['DOCUMENT_ROOT']) . '/../resources/template/dropdown.php';?>
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
                    <img src="/resources/share.png" alt="Compartilhar" class="share-icon">
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
                        <?php $cartoes = $cartaoController->listarCartoesPorLista($lista['id']);
							  foreach ($cartoes as $cartao): ?>
                            <div class="card" id="card_<?php echo $cartao['id']; ?>">
                                <div class="tags-container" style="display: flex; gap: 5px; margin-bottom: 5px;">
                                        <?php 
                                        $etiquetas = $cartaoController->listarEtiquetasPorCartao($cartao['id']);
                                        foreach ($etiquetas as $etiqueta): 
                                            $cor = htmlspecialchars($etiqueta['cor']); // Garantir que a cor está escapada
                                        ?>
                                            <div class="tag" style="width: 20px; height: 8px; background-color: <?php echo $cor; ?>; border-radius: 2px;" title="<?php echo $etiqueta['nome']; ?>"
                                                onclick="openEditForm('<?php echo $etiqueta['id']; ?>', '<?php echo $etiqueta['nome']; ?>', '<?php echo $cor; ?>')">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <div class="card-header">
									<div>
										<p><?php echo $cartao['corpo']; ?></p>
										<div style="display:flex">
											<form action="" method="post">
												<input type="hidden" name="cartao_id" value="<?php echo $cartao['id'];?>">
												<?php if ($membroController->encontrar($_SESSION['usuario_id'], $cartao['id'])) { ?>
													<input type="hidden" name="membro_rm">
													<input class="membro-btn" type="image" src="/resources/minus.svg"/>
												<?php } else { ?>
													<input type="hidden" name="membro_add">
													<input class="membro-btn" type="image" src="/resources/plus.svg"/>
												<?php } ?>
											</form>
											<?php 
												$membros = $membroController->listar($cartao['id']);
												foreach ($membros as $membro):
													$usuarioo = $membroController->getUsuario($membro['usuario_id'])?>
												<img class="membro-btn" src="<?php echo $usuarioo['icone'];?>" title="<?php echo $usuarioo['nome'];?>">
											<?php endforeach;?>
										</div>
									</div>
									<div class="card-options">
										<span class="options-icon" onclick="toggleOptions(<?php echo $cartao['id']; ?>)" style="color: black;">&#9998;</span>
										<div class="card-options-menu" id="card_options_menu_<?php echo $cartao['id']; ?>">
											<button class="edit-btn" onclick="editItem('cartao', <?php echo $cartao['id']; ?>, '<?php echo $cartao['corpo']; ?>')">Editar</button>
											<button class="edit-btn" onclick="deleteCard(<?php echo $cartao['id']; ?>)">Excluir</button>
                                            <button class="edit-btn" onclick="addTag(<?php echo $cartao['id']; ?>)">Adicionar Etiqueta</button>
										</div>
									</div>
                                </div>
                                
                            </div>
                        <?php endforeach; ?>
                        <div class="add-card-container">
                            <button id="addCardButton_<?php echo $lista['id']; ?>" class="add-card-btn" onclick="showAddCardForm(<?php echo $lista['id']; ?>)">
								Adicionar Cartão
                                <img src="/resources/plus.svg" alt="Adicionar" class="icon" style="width: 20px; height: 20x; margin-left: 5px;float: right">
                            </button>
                            <form id="addCardForm_<?php echo $lista['id']; ?>" class="add-card-form" style="display:none;" onsubmit="addCard(event, <?php echo $lista['id']; ?>)">
                                <input type="text" name="corpo_cartao" placeholder="Insira um nome para o cartão..." required>
                                <button type="submit" style="font-size: 15px;">Adicionar Cartão</button>
                                <button type="button" style="background-color: transparent;" onclick="hideAddCardForm(<?php echo $lista['id']; ?>)">
                                    <img src="/resources/close_icon.png" alt="Fechar" style="width: 20px; height: 20px;">
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Adicionar nova lista -->
            <div class="add-list-container">
                <button id="addListButton" class="add-card-btn" style="width: 250px; border-radius: 15px; height: 55px; background-color: #91d991; margin-right: 10px; margin-top: -10px;" onclick="showAddListForm()">Adicionar Lista
                    <img src="/resources/plus.svg" alt="Adicionar" class="icon" style="width: 20px; height: 20px; margin-left: 45px; float:right">
                </button>

                <form id="addListForm" class="add-list-form" style="display:none;" onsubmit="addList(event)">
                    <input type="text" name="titulo_lista" placeholder="Insira um título para a lista..." required>
                    <button type="submit" style="font-size: 15px;">Adicionar Lista</button>
                    <button type="button" style="background-color: transparent;" onclick="hideAddListForm()">
                        <img src="/resources/close_icon.png" alt="Fechar" style="width: 20px; height: 20px;">
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div id="addTagModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 style="color: black; margin-bottom: 10px">Adicionar Etiqueta</h2>
            <label style="color: black;">Título:</label>
                <input type="text" id="tagInput" style="background-color: #bee9be; padding: 10px; margin-bottom: 10px; border-radius:15px; border: 2px solid #ccc;" placeholder="Nome da etiqueta" required>
            <label style="color: black;">Cor:</label>
                <input type="color" id="tagColorInput" style="background-color: #bee9be; border-radius:10px; border: 2px solid #ccc; margin-bottom: 20px;" value="#ffffff" required> 
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button type="button" style="border-radius: 15px;" onclick="saveTag()">Salvar Etiqueta</button>
            </div>    
        </div>
    </div>
    <div id="editTagForm" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeEditForm()">&times;</span>
            <h3 style="color: black; margin-bottom: 10px">Editar Etiqueta</h3>
            <form id="tagForm" method="POST">
                <input type="hidden" id="editTagId" name="id">
                
                <label for="tagTitle" style="color: black; margin-bottom: 10px" >Título:</label>
                <input style="background-color: #bee9be; padding: 10px; margin-bottom: 10px; border-radius:15px; border: 2px solid #ccc;" type="text" id="tagTitle" name="nome" required>

                <label for="tagColor" style="color: black; margin-bottom: 10px">Cor:</label>
                <input  style="background-color: #bee9be; border-radius:10px; border: 2px solid #ccc; margin-bottom: 20px;" type="color" id="tagColor" name="cor" required>
                
                <div style="display: flex; gap: 10px; justify-content: space-between;">
                    <button type="submit" style="border-radius: 15px;">Salvar Alterações</button>
                    <button type="button" onclick="deleteTag()" style="border-radius: 15px;">Excluir Etiqueta</button>
                </div>
            </form>
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
        
        function toggleOptions() {
            const options = document.getElementById('options');
            // Verifica o estado atual de exibição e alterna
            if (options.style.display === 'none' || options.style.display === '') {
                options.style.display = 'block'; // Mostra as opções
            } else {
                options.style.display = 'none'; // Esconde as opções
            }
        }

        let currentCardId; 

        function addTag(cardId) {
            currentCardId = cardId;
            document.getElementById('addTagModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('addTagModal').style.display = 'none';
            document.getElementById('tagInput').value = ''; 
            document.getElementById('tagColorInput').value = '#ffffff'; 
        }

        function saveTag(event) {
            const tagName = document.getElementById('tagInput').value;
            const tagColor = document.getElementById('tagColorInput').value;

            if (tagName.trim() === '') {
                alert('Por favor, insira o nome da etiqueta.');
                return;
            }

            fetch('apicard.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    nome: tagName,
                    cor: tagColor,
                    cartao_id: currentCardId,
                }),
            })
            .then(response => {
                console.log('Status da resposta:', response.status);
                return response.json(); 
            })
            .then(data => {
                console.log('Resposta do servidor:', data); 
                if (data.success) {
                    alert('Etiqueta adicionada com sucesso!');
                    location.reload();
                } else {
                    alert('Erro ao adicionar a etiqueta: ' + (data.message || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                alert('Etiqueta adicionada com sucesso!');
                location.reload();
            });
        }

        function openEditForm(id, nome, cor) {
            document.getElementById('editTagForm').style.display = 'block';
            document.getElementById('editTagId').value = id;
            document.getElementById('tagTitle').value = nome;
            document.getElementById('tagColor').value = cor;
        }

        function closeEditForm() {
            document.getElementById('editTagForm').style.display = 'none';
        }

        function deleteTag() {
            const tagId = document.getElementById('editTagId').value;
            const confirmation = confirm('Tem certeza de que deseja excluir esta etiqueta?');

            if (confirmation) {
                fetch(`apicard.php`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: tagId }),
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) location.reload();
                })
                .catch(error => console.error('Erro:', error));
            } else {
                console.log('Exclusão cancelada.');
            }
        }

        document.getElementById('tagForm').addEventListener('submit', updateTag);

        function updateTag(event) {
        event.preventDefault();

        const id = document.getElementById('editTagId').value;
        const nome = document.getElementById('tagTitle').value;
        const cor = document.getElementById('tagColor').value;

        fetch('apicard.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id, nome: nome, cor: cor }),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erro HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            alert(data.message);
            if (data.success) location.reload();
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao atualizar a etiqueta: verifique o console para mais detalhes.');
        });
    }   

    </script>
</body>
</html>
