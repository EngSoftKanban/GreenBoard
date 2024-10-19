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
       

    <div class="profile-container">
    <div class="profile-header">
        <div class= "back-container">
            <img src="./arrow.svg" alt="Voltar" class="back-button" onclick="goToMainPage()">
        </div>   
        <h2 style="font-size: 20px;">Dados Pessoais</h2>
        <div class= "edit-button">
           <img src="./edit.svg" class="edit-icon" onclick="enableEdit()">
        </div>
    </div>

    <div class="profile-image-container">
        <img id="profileImage" src="" alt="Sem foto" class="profile-image">
        <p id="noImageText"></p>
        <label id="chooseImageLabel" style="display: none;" for="profileImageInput"></label>
        <input type="file" id="profileImageInput" style="display: none;" onchange="loadImage(event)">
    </div>

    <div class="profile-info">
        <div class="info-item">
            <label>Nome:</label>
            <input type="text" id="nome" value="Fulana da Silva" disabled>
        </div>
        <div class="info-item">
            <label>Apelido:</label>
            <input type="text" id="apelido" value="Fulana" disabled>
        </div>
        <div class="info-item">
            <label>E-mail:</label>
            <input type="email" id="email" value="fulana@hotmail.com" disabled>
        </div>
        <div class="info-item">
            <label>Data de Nascimento:</label>
            <input type="text" id="dataNascimento" value="01/05/1999" disabled>
        </div>
    </div>
    <button id="saveButton" style="display: none;"  onclick="saveChanges()">Salvar</button>
    </div>

    <script>
        window.onload = function() {
            initializeLocalStorage();
            loadData();
        };

        function initializeLocalStorage() {
            if (!localStorage.getItem('nome')) {
                localStorage.setItem('nome', 'Fulana da Silva');
            }
            if (!localStorage.getItem('apelido')) {
                localStorage.setItem('apelido', 'Fulana');
            }
            if (!localStorage.getItem('email')) {
                localStorage.setItem('email', 'fulana@hotmail.com');
            }
            if (!localStorage.getItem('dataNascimento')) {
                localStorage.setItem('dataNascimento', '01/05/1999');
            }
            if (!localStorage.getItem('profileImage')) {
                localStorage.setItem('profileImage', ''); // ou URL de uma imagem padrão
            }
        }

        function toggleMenu() {
            var menuContainer = document.querySelector('.menu-container');
            menuContainer.classList.toggle('active');
        }

        function enableEdit() {
            document.querySelectorAll('.profile-info input').forEach(function(input) {
                input.disabled = false;
            });

            document.getElementById('chooseImageLabel').style.display = 'block';
            document.getElementById('profileImageInput').style.display = 'block';
            document.getElementById('saveButton').style.display = 'block';
        }

        function loadData() {
            const nome = localStorage.getItem('nome');
            const apelido = localStorage.getItem('apelido');
            const email = localStorage.getItem('email');
            const dataNascimento = localStorage.getItem('dataNascimento');
            const profileImage = localStorage.getItem('profileImage');

            document.getElementById('displayName').textContent = apelido; // Exibe o apelido

            if (nome) document.getElementById('nome').value = nome;
            if (apelido) document.getElementById('apelido').value = apelido; // Define o apelido no campo
            if (email) document.getElementById('email').value = email;
            if (dataNascimento) document.getElementById('dataNascimento').value = dataNascimento;

            if (profileImage) {
                document.getElementById('profileImage').src = profileImage;
                document.getElementById('profileImageMenu').src = profileImage;
                document.getElementById('profileImageMenu').style.display = 'block';
                document.getElementById('profileImageTopBar').src = profileImage;
                document.getElementById('noImageText').style.display = 'none'; 
            } else {
                document.getElementById('noImageText').style.display = 'block';
            }
        }
    
            
        function loadImage(event) {
            const profileImage = document.getElementById('profileImage');
            const profileImageMenu = document.getElementById('profileImageMenu');
            const profileImageTopBar = document.getElementById('profileImageTopBar');
            const noImageText = document.getElementById('noImageText');
            
            const newImageUrl = URL.createObjectURL(event.target.files[0]);
            
            profileImage.src = newImageUrl;
            profileImageMenu.src = newImageUrl;
            profileImageTopBar.src = newImageUrl;
            
            noImageText.style.display = 'none';
            profileImage.style.display = 'block';
            profileImageMenu.style.display = 'block';
            profileImageTopBar.style.display = 'block';

            // Salvar a imagem no localStorage
            localStorage.setItem('profileImage', newImageUrl);
        }

        function saveChanges() {
            const nome = document.getElementById('nome').value;
            const apelido = document.getElementById('apelido').value; // Captura o apelido
            const email = document.getElementById('email').value;
            const dataNascimento = document.getElementById('dataNascimento').value; // Captura a data de nascimento

            const profileImage = document.getElementById('profileImage').src;

            console.log('Dados a serem enviados:', { nome, apelido, email, dataNascimento, profileImage });

            // Atualiza o localStorage com o novo apelido e data de nascimento
            localStorage.setItem('apelido', apelido);
            localStorage.setItem('dataNascimento', dataNascimento);

            // Atualiza o apelido na interface imediatamente
            document.getElementById('displayName').textContent = apelido; 

            fetch('update_profile.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `nome=${encodeURIComponent(nome)}&email=${encodeURIComponent(email)}&profileImage=${encodeURIComponent(profileImage)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Alterações salvas no servidor!');
                    // Você pode adicionar qualquer lógica adicional aqui, se necessário
                } else {
                    alert('Erro ao salvar as alterações.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });

            // Desativa os campos de entrada após salvar
            document.querySelectorAll('.profile-info input').forEach(function(input) {
                input.disabled = true;
            });

            document.getElementById('saveButton').style.display = 'none';
            document.getElementById('chooseImageLabel').style.display = 'none';
            document.getElementById('profileImageInput').style.display = 'none';
        }
        function goToMainPage() {
            window.location.href = "index.php";
        }
</script>
</body>
</html>