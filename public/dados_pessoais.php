<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit(); 
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
                            <div class="profile-name" id="displayName"><?php echo $_SESSION['apelido'] ?? ''; ?></div>
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
    
    <form id="profileForm" method="post" enctype="multipart/form-data" action="update_profile.php">
        <div class="profile-image-container">
                    <img id="profileImage" src="getImage.php" alt="Sem foto" class="profile-image">
                    <p id="noImageText"></p>
                    <label id="chooseImageLabel" for="profileImageInput"></label>
                    <input type="file" id="profileImageInput" name="profileImage" style="display: none;" onchange="loadImage(event)">
        </div>
        <div class="profile-info">
            <div class="info-item">
                <label>Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo $_SESSION['nome'] ?? ''; ?>" disabled>
            </div>
            <div class="info-item">
                <label>Apelido:</label>
                <input type="text" id="apelido" name="apelido" value="<?php echo $_SESSION['apelido'] ?? ''; ?>" disabled>
            </div>
            <div class="info-item">
                <label>E-mail:</label>
                <input type="email" id="email" name="email" value="<?php echo $_SESSION['email'] ?? ''; ?>" disabled>
            </div>
            <div class="info-item">
                <label>Data de Nascimento:</label>
                <input type="text" id="dataNascimento" name="dataNascimento" value="<?php echo $_SESSION['dataNascimento'] ?? ''; ?>" disabled>
            </div>
        </div>
        <button id="saveButton" onclick="saveChanges()" style="display: none;" type="submit">Salvar</button>
    </form>
    <script>
    
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
            const apelido = localStorage.getItem('apelido') || '<?php echo $_SESSION["apelido"]; ?>'; // Carrega da sessão
            const email = localStorage.getItem('email');
            const dataNascimento = localStorage.getItem('dataNascimento') || '<?php echo $_SESSION["dataNascimento"]; ?>'; // Carrega da sessão
            const profileImage = localStorage.getItem('profileImage');

            document.getElementById('displayName').textContent = apelido; 

            if (nome) document.getElementById('nome').value = nome;
            if (apelido) document.getElementById('apelido').value = apelido; 
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


        document.getElementById('profileForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('update_profile.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro HTTP! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    alert('Perfil atualizado com sucesso!');
                    document.getElementById('nome').value = data.data.nome;
                    document.getElementById('email').value = data.data.email;
                    document.getElementById('apelido').value = data.data.apelido || '';
                    document.getElementById('dataNascimento').value = data.data.dataNascimento || '';
                    const profileImage = document.getElementById('profileImage');
                    if (data.data.icone) {
                        profileImage.src = 'getImage.php'; 
                    } else {
                        profileImage.src = ''; 
                    }

                    document.querySelectorAll('.profile-info input').forEach(function(input) {
                        input.disabled = true;
                    });
                
                    document.getElementById('saveButton').style.display = 'none';
                    document.getElementById('chooseImageLabel').style.display = 'none';
                    document.getElementById('profileImageInput').style.display = 'none';
                } else {
                    alert(data.message || 'Ocorreu um erro ao atualizar o perfil. Tente novamente. Detalhes: ${error.message}');
                }
            })
            .catch(error => {
                console.error('Erro completo:', error);
                alert('Erro ao enviar os dados. Por favor, tente novamente.');
            });
        });


        function loadImage(event) {
            const image = document.getElementById('profileImage');
            const reader = new FileReader();

            reader.onload = function() {
                image.src = reader.result;
                document.getElementById('chooseImageLabel').style.display = 'none';
                document.getElementById('noImageText').style.display = 'none';
            }

            reader.readAsDataURL(event.target.files[0]);
        }

        function saveChanges() {
            const nome = document.getElementById('nome').value;
            const apelido = document.getElementById('apelido').value;
            const email = document.getElementById('email').value;
            const dataNascimento = document.getElementById('dataNascimento').value;
            const profileImageInput = document.getElementById('profileImageInput'); 
            const profileImageFile = profileImageInput.files[0]; 
            const formData = new FormData(); 

            formData.append('nome', nome);
            formData.append('apelido', apelido);
            formData.append('email', email);
            formData.append('dataNascimento', dataNascimento);

            if (profileImageFile) {
                formData.append('profileImage', profileImageFile);
            }

            fetch('update_profile.php', {
                method: 'POST',
                body: formData 
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Alterações salvas com sucesso!');
                    
                    if (data.data.icone) {
                        document.getElementById('profileImage').src = data.data.icone; 
                    }
                } else {
                    alert('Erro ao salvar as alterações.');
                }

                document.querySelectorAll('.profile-info input').forEach(function(input) {
                    input.disabled = true;
                });
                document.getElementById('saveButton').style.display = 'none';
                document.getElementById('chooseImageLabel').style.display = 'none';
                document.getElementById('profileImageInput').style.display = 'none';
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        }
        function goToMainPage() {
            window.location.href = "index.php";
        }
        
</script>
</body>
</html>