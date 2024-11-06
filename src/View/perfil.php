<!DOCTYPE html> 
<html lang="pt-BR">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>GreenBoard - Kanban</title>
		<link rel="stylesheet" href="/resources/css/styles.css">
	</head>
	<body class="profile-body">
		<div class="profile-header2">
			<div class="board-title">
				<img src="/resources/logo.png" alt="Logo GreenBoard" class="logo">
				<h1>GreenBoard</h1>
			</div>
			<div class="users">
				<div class="menu-container">
					<div class="profile-icon" onclick="toggleMenu()">
						<img id="profileImageTopBar" src="<?php echo $_SESSION['icone'];?>" class="profile-image-menu">
					</div>
					<?php include realpath($_SERVER['DOCUMENT_ROOT']) . '/../resources/template/dropdown.php';?>
				</div>
			</div>
		</div>
		<div style="display: flex; flex-direction: row">
			<div class="tab-header">
				<a href="/perfil.php">
					<div class="tab-selected">Dados pessoais</div>
				</a>
				<a href="/webhooks.php">
					<div class="tab-common">Webhooks</div>
				</a>
				<a href="">
					<div class="tab-common-shadow">Tokens de acesso pessoal</div>
				</a>
				<a href="quadro.php?quadro_id=<?php echo $_SESSION['quadro_id'];?>" style="position: fixed; left: 8px; bottom: 8px;">
					<div class= "back-container">
						<img src="/resources/arrow.svg" class="back-button">
					</div>
				</a>
			</div>
			<div class="profile-container">
				<div class="profile-header">
					<h2 style="font-size: 20px;">Dados Pessoais</h2>
					<div class= "edit-button">
						<img src="/resources/edit.svg" class="edit-icon" onclick="enableEdit()">
					</div>
				</div>
				<form id="profileForm" method="post" enctype="multipart/form-data" action="update_profile.php">
					<div class="profile-image-container">
						<img id="profileImage" src="<?php echo $_SESSION['icone']; ?>" alt="Sem foto" class="profile-image">
						<p id="noImageText"></p>
						<label id="chooseImageLabel" for="profileImageInput"></label>
						<input type="file" id="profileImageInput" name="profileImage" style="display: none;" onchange="loadImage(event)">
					</div>
					<div class="profile-info">
						<label>Nome:</label>
						<input type="text" id="nome" name="nome" value="<?php echo $_SESSION['nome']; ?>" disabled>
						<label>E-mail:</label>
						<input type="email" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" disabled>
					</div>
					<button id="saveButton" onclick="saveChanges()" style="display: none;" type="submit">Salvar</button>
				</form>
			</div>
		</div>
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
				const apelido = localStorage.getItem('apelido'); 
				const email = localStorage.getItem('email');
				const dataNascimento = localStorage.getItem('dataNascimento'); // Carrega da sessão
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

				let formData = new FormData(this); 

				fetch('update_profile.php', {
					method: 'POST',
					body: formData,
				})
				.then(response => response.json())
				.then(data => {
					if (data.status === 'success') {
					location.reload();
					} else {
						alert('Erro ao atualizar o perfil');
					}
				})
				.catch(error => {
					console.error('Erro:', error);
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
				window.location.href = "quadro.php?quadro_id=<?php echo $_SESSION['quadro_id'];?>";
			}
		</script>
	</body>
</html>