<!DOCTYPE html>
<html lang="pt-BR">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>GreenBoard - Registrar</title>
		<style>
			<?php require_once "resources/css/styles.css";?>
		</style>
	</head>
	<body style="justify-content: center; height:100vh">
		<div class="div-form-cadastrar">
			<div class="board-title" style="display: flex; justify-content: center; width:100%; margin-top: 5%">
				<a href="index.php">
					<img src="/resources/logo_clean.png" alt="Logo GreenBoard" class="logo" style="height:10vh; width:auto; margin-right: 2%">
				</a>
				<h1>GreenBoard</h1>
			</div>
			
			<?php if (isset($erro)) { ?>
				<div class="error-flash">
					<?php echo $erro; ?>
				</div>
			<?php } ?>

			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-cadastrar">
				<label for="nome" class="label-cadastrar">Nome</label>
				<input type="text" name="nome" class="input-cadastrar" required>
				
				<label for="email" class="label-cadastrar">E-mail</label>
				<input type="email" name="email" id="email" class="input-cadastrar" required>
				
				<label for="senha" class="label-cadastrar">Senha</label>
				<input type="password" name="senha" id="senha" class="input-cadastrar" required>
				
				<label for="senhaconfirma" class="label-cadastrar">Confirmar senha</label>
				<input type="password" name="senhaconfirma" id="senhaconfirma" class="input-cadastrar" required>
				
				<input type="submit" value="Cadastrar" class="submit-cadastrar">
			</form>
			
			<div style="display: flex; justify-content: center; width:100%; margin-bottom: 5%">
				<b>Já tem cadastro? <a href="login.php" class="a-cadastrar">Fazer login.</a></b>
			</div>
		</div>
	</body>
</html>
