<!DOCTYPE html> 
<html lang="pt-BR">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>GreenBoard - Kanban</title>
		<style>
			<?php require_once "resources/css/styles.css"; ?>
		</style>
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
						<img id="profileImageTopBar" 
                             src="<?php echo $_SESSION['icone'] ?? '/resources/default_icon.png'; ?>" 
                             class="profile-image-menu">
					</div>
					<?php include 'resources/template/dropdown.php'; ?>
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
				<a href="/tokens.php">
					<div class="tab-common-shadow">Tokens da API</div>
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
					<div class="edit-button">
						<img src="/resources/edit.svg" class="edit-icon" onclick="enableEdit()">
					</div>
				</div>
				<form id="profileForm" method="post" enctype="multipart/form-data" action="update_profile.php">
					<div class="profile-image-container">
						<img id="profileImage" 
                             src="<?php echo $_SESSION['icone'] ?? '/resources/default_icon.png'; ?>" 
                             alt="Sem foto" class="profile-image">
						<p id="noImageText" style="display: none;">Nenhuma imagem selecionada</p>
						<label id="chooseImageLabel" for="profileImageInput" style="display: none;">Escolher imagem</label>
						<input type="file" id="profileImageInput" name="profileImage" style="display: none;" 
                               onchange="loadImage(event)">
					</div>
					<div class="profile-info">
						<label>Nome:</label>
						<input type="text" id="nome" name="nome" 
                               value="<?php echo $_SESSION['nome'] ?? ''; ?>" disabled>
						<label>E-mail:</label>
						<input type="email" id="email" name="email" 
                               value="<?php echo $_SESSION['email'] ?? ''; ?>" disabled>
					</div>
					<button id="saveButton" style="display: none;" type="submit">Salvar</button>
				</form>
			</div>
		</div>
		<script>
			function toggleMenu() {
				document.querySelector('.menu-container').classList.toggle('active');
			}

			function enableEdit() {
				document.querySelectorAll('.profile-info input').forEach(function(input) {
					input.disabled = false;
				});
				document.getElementById('chooseImageLabel').style.display = 'block';
				document.getElementById('profileImageInput').style.display = 'block';
				document.getElementById('saveButton').style.display = 'block';
			}

			function loadImage(event) {
				const image = document.getElementById('profileImage');
				const reader = new FileReader();

				reader.onload = function() {
					image.src = reader.result;
					document.getElementById('chooseImageLabel').style.display = 'none';
					document.getElementById('noImageText').style.display = 'none';
				};

				reader.readAsDataURL(event.target.files[0]);
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
						alert('Alterações salvas com sucesso!');
						location.reload();
					} else {
						alert(data.message || 'Erro ao atualizar o perfil.');
					}
				})
				.catch(error => {
					console.error('Erro:', error);
					alert('Ocorreu um erro inesperado.');
				});
			});
		</script>
	</body>
</html>
