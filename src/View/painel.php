<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Painel - GreenBoard</title>
	<style>
		<?php require_once "resources/css/painel.css";?>
		#create-modal, #edit-modal {
			display: none;
			position: absolute;
			background-color: white;
			border-radius: 5px;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
			z-index: 10;
			padding: 20px;
		}

		a {
			text-decoration: none;
			color: black;
		}

		a:hover {
			text-decoration: underline;
		}

		.quadro {
			margin: 10px 0;
		}
	</style>
</head>
<body>
	<header>
		<div class="board-title">
			<a href="index.php">
				<img src="/resources/logo.png" alt="Logo GreenBoard" class="logo">
			</a>
			<h1>GreenBoard</h1>
		</div>
		<div class="user-avatar">
			<img src="<?php echo $_SESSION['icone'];?>" alt="Usuário" class="user-icon">
		</div>
	</header>

    <div class="container">
		<div class="main">
			<div class="recently-viewed">
				<h2>Visualizados recentemente</h2>
				<div>
					<?php if (empty($recentes)): ?>
						<p>Nenhum quadro visualizado recentemente.</p>
					<?php else: ?>
						<?php foreach ($recentes as $quadro): ?>
							<a href="quadro.php?quadro_id=<?php echo $quadro['id']; ?>">
								<div class="quadro">
									<?php echo $quadro['nome']; ?>
								</div>
							</a>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>

			<div class="work-area">
				<h2>Suas áreas de trabalho</h2>
				<div id="quadro-list">
					<?php if (!empty($quadros)): ?>
						<?php foreach ($quadros as $quadro): ?>
							<div>
								<a href="quadro.php?quadro_id=<?php echo $quadro['id']; ?>">
									<?php echo $quadro['nome']; ?>
								</a>
								<form action="" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja remover esse quadro?');">
									<input type="hidden" name="quadro_excluir">
									<input type="hidden" name="quadro_id" value="<?php echo $quadro['id']; ?>">
									<button type="submit">
										<?php require 'resources/trash.svg'?>
									</button>
								</form>
								<!-- Botão para abrir o modal de edição -->
								<button onclick="showEditModal(<?php echo $quadro['id']; ?>, '<?php echo $quadro['nome']; ?>')">
									<?php require 'resources/pencil_square.svg'?>
								</button>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
					<div class="create-quadro" onclick="showModal(event)">
						<span>Criar novo quadro</span>
					</div>
				</div>

				<!-- Modal para criar quadro -->
				<div id="create-modal">
					<h3>Criar novo quadro</h3>
					<form id="create-board-form" action="" method="POST">
						<input type="hidden" name="quadro_criar">
						<label for="quadro_nome">Nome:</label>
						<input type="text" id="quadro_nome" name="quadro_nome" required>
						<button type="submit" class="create-quadro">Criar quadro</button>
						<button type="button" class="cancel-button" onclick="document.getElementById('create-modal').style.display='none';">
							Cancelar
						</button>
					</form>
				</div>

				<!-- Modal para editar quadro -->
				<div id="edit-modal">
					<h3>Editar quadro</h3>
					<form id="edit-board-form" action="" method="POST">
						<input type="hidden" name="quadro_editar">
						<input type="hidden" name="quadro_id" id="edit-quadro-id">
						<label for="edit-nome_quadro">Nome:</label>
						<input type="text" id="edit-nome_quadro" name="novo_nome" required>
						<button type="submit" class="create-quadro">Salvar alterações</button>
						<button type="button" class="cancel-button" onclick="document.getElementById('edit-modal').style.display='none';">
							Cancelar
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script>
		function showModal(event) {
			const modal = document.getElementById('create-modal');
			modal.style.display = 'block';
			const rect = event.currentTarget.getBoundingClientRect();
			modal.style.top = `${rect.bottom + window.scrollY}px`; 
			modal.style.left = `${rect.left}px`; 
		}

		function showEditModal(quadro_id, nome) {
			const modal = document.getElementById('edit-modal');
			const nomeInput = document.getElementById('edit-nome_quadro');
			const quadroIdInput = document.getElementById('edit-quadro-id');

			nomeInput.value = nome;
			quadroIdInput.value = quadro_id;

			modal.style.display = 'block';
		}

		window.onclick = function(event) {
			const createModal = document.getElementById('create-modal');
			const editModal = document.getElementById('edit-modal');
			if (event.target == createModal || event.target == editModal) {
				createModal.style.display = 'none';
				editModal.style.display = 'none';
			}
		}
	</script>
</body>
</html>
