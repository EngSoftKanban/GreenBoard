<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Edição de Listas e Cartões</title>
    <link rel="stylesheet" href="testar_edicao.css"> 
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

    <h2>EDIÇÃO VIA TOKEN</h2>
    <form action="editar-item.php" method="post">
        <label for="token">Token:</label>
        <input type="text" id="token" name="token"> 
        
        <label for="id">ID do Item:</label>
        <input type="number" id="id" name="id" required>
        
        <label for="tipo">Tipo de Item:</label>
        <select id="tipo" name="tipo" required>
            <option value="lista">Lista</option>
            <option value="cartao">Cartão</option>
        </select>

        <label for="novo_valor">Novo Valor:</label>
        <input type="text" id="novo_valor" name="novo_valor" required>
        
        <button type="submit">Atualizar Item</button>
    </form>

    <h3>Resposta do Servidor:</h3>
    <div id="resposta"></div>

    <script>
        document.querySelector('form').onsubmit = async function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            const response = await fetch('editar-item.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            document.getElementById('resposta').innerText = JSON.stringify(result);
            
            if (result.token) {
                document.getElementById('tokenGerado').innerText = result.token; 
            }
        };
    </script>
</body>
</html>
