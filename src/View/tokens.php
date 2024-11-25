<!DOCTYPE html> 
<html lang="pt-BR">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Tokens - GreenBoard</title>
		<style>
			<?php require_once "resources/css/styles.css";?>
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
						<img id="profileImageTopBar" src="<?php echo $_SESSION['icone'];?>" class="profile-image-menu">
					</div>
					<?php include 'resources/template/dropdown.php';?>
				</div>
			</div>
		</div>
		<div style="display: flex; flex-direction: row">
			<div class="tab-header">
				<a href="/perfil.php">
					<div class="tab-common">Dados pessoais</div>
				</a>
				<a href="/webhooks.php">
					<div class="tab-common">Webhooks</div>
				</a>
				<a href="/tokens.php">
					<div class="tab-selected-shadow">Tokens da API</div>
				</a>
				<a href="quadro.php?quadro_id=<?php echo $_SESSION['quadro_id'];?>" style="position: fixed; left: 8px; bottom: 8px;">
					<div class= "back-container">
						<img src="/resources/arrow.svg" class="back-button">
					</div>
				</a>
			</div>
			<div class="profile-container">
				<div style="margin-bottom: 20px">
					<h2 style="font-size: 20px">Tokens</h2>
				</div>
				<div style="display: flex;flex-direction: column;align-items: center;margin-bottom: 32px">
					<?php foreach ($tokens as $token) {?>
						<div class="hooks-item">
							<?php 
							foreach ($opcoes as $opcao) {
								if ($token['quadro_id'] == $opcao['id']) {;?>
								<div style="text-align: left">
									<?php echo 'Quadro: ' . $opcao['nome'];?><br>
								</div>
							<?php }
							} ?>
							Token: <?php echo $token['token'];?>
							<form action="" method="post" onsubmit="return confirm('Tem certeza que deseja excluir esta Token?')">
								<input type="hidden" name="token_id" value="<?php echo $token['id'];?>">
								<button>
									<img src="/resources/trash.svg" style="width: 16px;color:white">
								</button>
							</form>
						</div>
					<?php } ?>
				</div>
				<div>
					<form action="" method="post">
						<input type="hidden" name="TOKEN" value="">
						<select name="quadro" id="">
							<?php foreach ($opcoes as $opcao) {?>
								<option value="<?php echo $opcao['id'];?>">
									<?php echo $opcao['nome'];?>
								</option>
							<?php } ?>
						</select>
						<button>Criar Token</button>
					</form>
				</div>
			</div>
		</div>
		<script>
			function toggleMenu() {
				var menuContainer = document.querySelector('.menu-container');
				menuContainer.classList.toggle('active');
			}
		</script>
	</body>
</html>