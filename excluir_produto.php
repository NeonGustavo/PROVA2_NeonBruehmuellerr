--Neon Gustavo Bruehmueller--
<?php
session_start();
require 'conexao.php';

// Verifica se o usuário tem permissão de ADM
if (!isset($_SESSION['perfil']) || ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2)) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit;
}

// Busca todos os produtos cadastrados em ordem alfabética
$sql = "SELECT * FROM produto ORDER BY nome_prod ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Se um ID for passado via GET, exclui o produto
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_produto = $_GET['id'];

    // Exclui o produto do banco de dados
    $sql = "DELETE FROM produto WHERE id_produto = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_produto, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Produto excluído com sucesso!'); window.location.href='excluir_produto.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir produto!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Produto</title>
    <style>
        body {
    font-family: Arial, Helvetica, sans-serif;
    background-color: #011638;
    color:rgb(22, 102, 148);
    margin: 0;
    padding: 0;
}

h2 {
    text-align: center;
    color:rgb(26, 69, 148);
    margin-top: 30px;
}

table {
    width: 90%;
    margin: 30px auto 0 auto;
    border-collapse: collapse;
    background:rgb(46, 133, 145);
    color:rgb(48, 97, 143);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(26, 151, 114, 0.13);
}

th, td {
    padding: 12px 10px;
    text-align: center;
    border: 3px solid rgb(26, 138, 101);
}

th {
    background:rgb(31, 110, 134);
    color: #fff;
}

tr:nth-child(even) {
    background:rgb(25, 105, 151);
    color: #2E294E;
}

tr:nth-child(odd) {
    background:rgb(39, 197, 110);
}

a {
    color:rgb(33, 87, 148);
    text-decoration: none;
    font-weight: bold;
    margin: 0 5px;
    transition: color 0.2s;
}

a:hover {
    color:rgb(39, 71, 139);
}

a {
    display: block;
    text-align: center;
    margin: 30px auto 0 auto;
    color:rgb(43, 177, 103);
    text-decoration: none;
    font-weight: bold;
    font-size: 1.1em;
    width: fit-content;
    transition: color 0.2s;
}

a:hover {
    color:rgb(46, 185, 120);
}

p {
    text-align: center;
    color:rgb(52, 172, 146);
    margin-top: 30px;
}
        </style>
</head>
<body>
    <h2>Excluir Produto</h2>

    <?php if (!empty($produtos)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço unitário</th>
                <th>Quantidade</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?= htmlspecialchars($produto['id_produto']) ?></td>
                    <td><?= htmlspecialchars($produto['nome_prod']) ?></td>
                    <td><?= htmlspecialchars($produto['descricao']) ?></td>
                    <td><?= htmlspecialchars($produto['valor_unit']) ?></td>
                    <td><?= htmlspecialchars($produto['qtde']) ?></td>
                    <td>
                        <a href="excluir_produto.php?id=<?= htmlspecialchars($produto['id_produto']) ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum produto encontrado.</p>
    <?php endif; ?>

    <a href="principal.php">Voltar</a>
</body>
</html>