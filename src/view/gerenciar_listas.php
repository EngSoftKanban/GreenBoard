<?php
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

$quadro_id = isset($_GET['quadro_id']) ? intval($_GET['quadro_id']) : 0;

try {
    $sqlUpdateAcesso = "UPDATE quadros SET ultimo_acesso = NOW() WHERE id = :quadro_id";
    $stmtUpdate = $pdo->prepare($sqlUpdateAcesso);
    $stmtUpdate->bindParam(':quadro_id', $quadro_id);
    $stmtUpdate->execute();
} catch (PDOException $e) {
    die("Erro ao atualizar último acesso: " . $e->getMessage());
}

$sql = "SELECT * FROM listas WHERE quadro_id = :quadro_id ORDER BY posicao";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':quadro_id', $quadro_id);
$stmt->execute();
$listas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Listas - GreenBoard</title>
    <link rel="stylesheet" href="../Css/gerenciar_listas.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
</head>
<body>
    <div class="board-header">
        <div class="board-title">
        <img src="../resources/logo.png" alt="Logo GreenBoard" class="logo">
        <h1>GreenBoard</h1>
        </div>
        <div class="users">
            <img src="../resources/olivia.jpeg" alt="Usuário 1" class="user-icon">
            <img src="../resources/taylor.jpg" alt="Usuário 2" class="user-icon">
            <img src="../resources/lalisa.jpg" alt="Usuário 3" class="user-icon">
            <span class="extra-users">+1</span>
        </div>
    </div>

    <a href="quadros.php" class="back-button">Inicio</a>

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
                                <button class="edit-list-btn" onclick="editItem('lista', <?php echo $lista['id']; ?>, '<?php echo $lista['titulo']; ?>')">Editar</button>
                                <button class="delete-list-btn" onclick="deleteColumn(<?php echo $lista['id']; ?>)">Excluir</button>
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
                                            <button class="edit-card-btn" onclick="editItem('cartao', <?php echo $cartao['id']; ?>, '<?php echo $cartao['corpo']; ?>')">Editar</button>
                                            <button class="delete-card-btn" onclick="deleteCard(<?php echo $cartao['id']; ?>)">Excluir</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="add-card-container">
                            <button id="addCardButton_<?php echo $lista['id']; ?>" class="add-card-btn" onclick="showAddCardForm(<?php echo $lista['id']; ?>)">Adicionar Cartão
                                <img src="../resources/Plus.png" alt="Adicionar" class="icon" style="width: 20px; height: 20px; margin-left: 5px;">
                            </button>
                            <form id="addCardForm_<?php echo $lista['id']; ?>" class="add-card-form" style="display:none;" onsubmit="addCard(event, <?php echo $lista['id']; ?>)">
                                <input type="text" name="corpo_cartao" placeholder="Insira um nome para o cartão..." required>
                                <button type="submit" style="font-size: 15px;">Adicionar Cartão</button>
                                <button type="button" style="background-color: transparent;" onclick="hideAddCardForm(<?php echo $lista['id']; ?>)">
                                    <img src="resource/close_icon.png" alt="Fechar" style="width: 20px; height: 20px;">
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="add-list-container">
                <button id="addListButton" class="add-card-btn" style="width: 250px; border-radius: 15px; height: 55px; background-color: #91d991; margin-right: 10px; margin-top: -10px;" onclick="showAddListForm()">Adicionar Lista
                    <img src="Plus.png" alt="Adicionar" class="icon" style="width: 20px; height: 20px; margin-left: 45px;">
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
            formData.append('quadro_id', <?php echo $quadro_id; ?>);
            fetch('../public/adicionar_lista.php', {
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
            const button = document.getElementById('addListButton');
            if (form && button) {
                button.style.display = 'none';
                form.style.display = 'block'; 
            }
        }

        function hideAddListForm() {
            const form = document.getElementById('addListForm');
            const button = document.getElementById('addListButton');
            if (form && button) {
                form.style.display = 'none'; 
                button.style.display = 'block'; 
            }
        }
       
        function deleteColumn(lista_id) {
            if (confirm("Tem certeza que deseja excluir esta lista?")) {
                const formData = new FormData();
                formData.append('excluir_lista_id', lista_id);
                fetch('../excluir_lista.php', {
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
                fetch('../excluir_cartao.php', {
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
    </script>
</body>
</html>
