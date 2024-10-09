<?php
namespace EngSoftKanban\GreenBoard;

use \PDO;

$host = apache_getenv("DB_HOST");
$dbname = apache_getenv("DB_NAME");
$user = apache_getenv("DB_USER");
$password = apache_getenv("DB_PASS");

try {
	$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if (isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['senhaconfirma'])) {
		if (strcmp($_POST['senha'], $_POST['senhaconfirma']) != 0) {
			$_REQUEST["erro"] = "As senhas não são iguais.";
		} else {
			$stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
			$stmt->bindParam(':nome', $nome);
			$stmt->bindParam(':email', $email);
			$stmt->bindParam(':senha', $senha_hash);
			$nome = $_POST['nome'];
			$email = $_POST['email'];
			$senha_hash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
			if ($stmt->execute()) {
				header("Location: /index.php", true); // TODO Mudar para o dashboard/início de sessão quando ele estiver pronto
				exit();
			} else {
				$_REQUEST["erro"] = "Erro"; // TODO Mudar para erro significativo
			}
		}
	}
} catch (PDOException $e) {
	die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>GreenBoard - Registrar</title>
		<link rel="stylesheet" href="styles.css">
	</head>
	<body style="justify-content: center; height:100vh">
		<div class="div-form-cadastrar">
			<div class="board-title" style="display: flex; justify-content: center; width:100%; margin-top: 5%">
				<img src="logo_clean.png" alt="Logo GreenBoard" class="logo" style="height:10vh; width:auto; margin-right: 2%">
				<h1>GreenBoard</h1>
			</div>
			<?php if (isset($_REQUEST['erro'])) {
				?>
			<div class="error-flash">
				<?php echo $_REQUEST['erro'];?>
			</div>
			<?php }?>
			<form method="post" action="<?php $_SERVER['PHP_SELF']?>" class="form-cadastrar">
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
				<b>Já tem cadastro? <a href="<?php $_SERVER['PHP_SELF']?>" class="a-cadastrar">Fazer login.</a></b>
			</div>
		</div>
	</body>
</html>