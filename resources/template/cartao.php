<div class="card" id="card_<?php echo $cartao['id']; ?>">
	<div class="tags-container" style="display: flex; gap: 5px; margin-bottom: 5px;">
			<?php 
			$etiquetas = $cartaoController->listarEtiquetasPorCartao($cartao['id']);
			foreach ($etiquetas as $etiqueta): 
				$cor = htmlspecialchars($etiqueta['cor']); // Garantir que a cor está escapada
			?>
				<div class="tag" style="width: 20px; height: 8px; background-color: <?php echo $cor; ?>; border-radius: 2px;" title="<?php echo $etiqueta['nome']; ?>"
					onclick="openEditForm('<?php echo $etiqueta['id']; ?>', '<?php echo $etiqueta['nome']; ?>', '<?php echo $cor; ?>')">
				</div>
			<?php endforeach; ?>
		</div>
	<div class="card-header">
		<div>
			<p><?php echo $cartao['corpo']; ?></p>
			<div style="display:flex">
				<form action="" method="post">
					<input type="hidden" name="cartao_id" value="<?php echo $cartao['id'];?>">
					<?php if ($membroController->encontrar($_SESSION['usuario_id'], $cartao['id'])) { ?>
						<input type="hidden" name="membro_rm">
						<input class="membro-btn" type="image" src="/resources/minus.svg"/>
					<?php } else { ?>
						<input type="hidden" name="membro_add">
						<input class="membro-btn" type="image" src="/resources/plus.svg"/>
					<?php } ?>
				</form>
				<?php 
					$membros = $membroController->listar($cartao['id']);
					foreach ($membros as $membro):
						$membroUsuario = $membroController->getUsuario($membro['usuario_id'])?>
					<img class="membro-btn" src="<?php echo $membroUsuario['icone'];?>" title="<?php echo $membroUsuario['nome'];?>">
				<?php endforeach;?>
			</div>
		</div>
		<div class="card-options">
			<span class="options-icon" onclick="toggleOptions(<?php echo $cartao['id']; ?>)" style="color: black;">&#9998;</span>
			<div class="card-options-menu" id="card_options_menu_<?php echo $cartao['id']; ?>">
				<button class="edit-btn" onclick="editItem('cartao', <?php echo $cartao['id']; ?>, '<?php echo $cartao['corpo']; ?>')">Editar</button>
				<form action="" method="post" onsubmit="return confirm('Tem certeza que deseja excluir este cartão?')">
					<input type="hidden" name="cartao_rm">
					<input type="hidden" name="cartao_id" value="<?php echo $cartao['id']; ?>">
					<button class="edit-btn">Excluir</button>
				</form>
				<button class="edit-btn" onclick="addTag(<?php echo $cartao['id']; ?>)">Adicionar Etiqueta</button>
			</div>
		</div>
	</div>
</div>