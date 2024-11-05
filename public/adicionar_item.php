<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Itens</title>
    <link rel="stylesheet" href="adicionar_item.css"> 
</head>

<body>
<header>
    <div class="board-title">
        <img src="/resources/logo.png" alt="Logo GreenBoard" class="logo">
        <h1>GreenBoard</h1>
    </div>
    <div class="user-avatar">
        <img src="/resources/user.png" alt="Usuário" class="user-icon">
    </div>
</header>

<h2>Adicionar Item</h2>
<form action="adicionar-item.php" method="post">
    <label for="token">Token:</label>
    <input type="text" id="token" name="token" required>

    <label for="tipo">Tipo de Item:</label>
    <select id="tipo" name="tipo" required onchange="toggleListaIdInput()">
        <option value="lista">Lista</option>
        <option value="cartao">Cartão</option>
    </select>

    <label for="valor">Valor:</label>
    <input type="text" id="valor" name="valor" required>

    
    <div id="quadro_id_container" style="display: none;">
        <label for="quadro_id">ID do Quadro:</label>
        <input type="number" id="quadro_id" name="quadro_id" required>
    </div>

    <div id="lista_id_container" style="display: none;">
        <label for="lista_id">ID da Lista:</label>
        <input type="number" id="lista_id" name="lista_id" required>
    </div>

    <button type="submit">Adicionar Item</button>
</form>

<h3>Excluir Item</h3>
<form action="excluir-item.php" method="post">
    <label for="token_excluir">Token:</label>
    <input type="text" id="token_excluir" name="token" required>

    <label for="tipo_excluir">Tipo de Item:</label>
    <select id="tipo_excluir" name="tipo" required>
        <option value="lista">Lista</option>
        <option value="cartao">Cartão</option>
    </select>

    <label for="id_excluir">ID do Item:</label>
    <input type="number" id="id_excluir" name="id" required>

    <button type="submit">Excluir Item</button>
</form>

<script>
function toggleListaIdInput() {
    const tipo = document.getElementById('tipo').value;
    const quadroIdContainer = document.getElementById('quadro_id_container');
    const listaIdContainer = document.getElementById('lista_id_container');
    const quadroIdInput = document.getElementById('quadro_id');
    const listaIdInput = document.getElementById('lista_id');

    if (tipo === 'lista') {
        quadroIdContainer.style.display = 'block';
        quadroIdInput.required = true;
        listaIdContainer.style.display = 'none';
        listaIdInput.required = false;
    } else {
        quadroIdContainer.style.display = 'none';
        quadroIdInput.required = false;
        listaIdContainer.style.display = 'block';
        listaIdInput.required = true;
    }
}
</script>


</body>
</html>
