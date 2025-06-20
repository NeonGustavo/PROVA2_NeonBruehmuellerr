--Neon Gustavo Bruehmueller

<?php
session_start();
require 'conexao.php';

// Verifica se o usuário tem permissão de ADM
if (!isset($_SESSION['perfil']) || ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2)) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit;
}

// Inicializa variável
$produto = null;

// Se o formulário for enviado, busca o produto pelo ID ou nome
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['buscar_produto'])) {
        $busca = trim($_POST['buscar_produto']);

        // Verifica se a busca é um número (ID) ou um nome
        if (is_numeric($busca)) {
            $sql = "SELECT * FROM produto WHERE id_produto = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM produto WHERE nome_prod LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
        }

        $stmt->execute();
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se o produto não for encontrado, exibe um alerta
        if (!$produto) {
            echo "<script>alert('Produto não encontrado!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Produto</title>
    <script src="scripts.js"></script>
    <style>
        body {
    font-family: Arial, Helvetica, sans-serif;
    background-color: #011638;
    color:rgb(2, 19, 252);
    margin: 0;
    padding: 0;
}

h2 {
    text-align: center;
    color:rgb(25, 105, 151);
    margin-top: 30px;
}

form {
    background:rgb(31, 110, 134);
    max-width: 400px;
    margin: 40px auto 25px auto;
    padding: 28px 30px 18px 30px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(1, 22, 56, 0.14);
}

label {
    display: block;
    margin-bottom: 7px;
    color:rgb(16, 14, 124);
    font-weight: bold;
}

input[type="text"],
input[type="number"] {
    width: 100%;
    padding: 8px;
    margin-bottom: 18px;
    border: 1px solidrgb(49, 119, 9);
    border-radius: 8px;
    background:rgb(255, 255, 255);
    color:rgb(36, 117, 150);
    font-size: 1em;
    box-sizing: border-box;
    transition: border-color 0.2s;
}

input[type="text"]:focus,
input[type="number"]:focus {
    border-color:rgb(24, 175, 62);
    outline: none;
}

button[type="submit"],
button[type="reset"] {
    background:rgb(33, 87, 148);
    color: #fff;
    border: none;
    padding: 10px 22px;
    border-radius: 8px;
    font-size: 1em;
    margin-right: 8px;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
}

button[type="submit"]:hover,
button[type="reset"]:hover {
    background:rgb(12, 55, 121);
    color:rgb(55, 143, 150);
}

a {
    display: block;
    text-align: center;
    margin-top: 24px;
    color:rgb(27, 85, 139);
    text-decoration: none;
    font-weight: bold;
    transition: color 0.2s;
}
a:hover {
    color:rgb(25, 156, 124);
}

#sugestoes {
    margin-bottom: 10px;
}
        </style>
</head>
<body>
    <h2>Alterar Produto</h2>

    <!-- Formulário para buscar produto pelo ID ou Nome -->
    <form action="alterar_produto.php" method="POST">
        <label for="buscar_produto">Digite o ID ou Nome do produto:</label>
        <input type="text" id="buscar_produto" name="buscar_produto" required onkeyup="buscarSugestoes()">
        <div id="sugestoes"></div>
        <button type="submit">Buscar</button>
    </form>

    <?php if ($produto): ?>
        <!-- Formulário para alterar produto -->
        <form action="processa_alteracao_produto.php" method="POST">
            <input type="hidden" name="id_produto" value="<?= htmlspecialchars($produto['id_produto']) ?>">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome_prod']) ?>" required>

            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" value="<?= htmlspecialchars($produto['descricao']) ?>" required>

            <label for="preco">Preço unitário:</label>
            <input type="number" id="preco" name="preco" value="<?= htmlspecialchars($produto['valor_unit']) ?>" step="0.01" required>

            <button type="submit">Alterar</button>
            <button type="reset">Cancelar</button>
        </form>
    <?php endif; ?>

    <a href="principal.php">Voltar</a>
</body>
</html>