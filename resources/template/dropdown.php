<div class="background-panel">
	<div class="profile-dropinfo">
		<div class="profile-photoinfo">
			<div class="profile-picture-placeholder">
				<img id="profileImageMenu" src="<?php echo $_SESSION['icone'];?>" class="profile-image-menu">
			</div>
			<div class="profile-name" id="displayName">
				<?php echo $_SESSION['nome']; ?>
			</div>
		</div>
	</div>
	<div class="dropdown-content">
		<a href="/perfil.php">Dados Pessoais</a>
		<a href="#">Alterar Conta</a>
		<a href="#">Gerenciar Conta</a>
		<a href="#">Configurações</a>
		<a href="/logout.php">Logout</a>
	</div>
</div>