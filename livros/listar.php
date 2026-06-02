<?php
require '../conexao.php';

$sql = "SELECT * FROM livros ORDER BY id DESC";
$stmt = $pdo->query($sql);
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Lista de Livros</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="lista-container">
    <h1>Lista de Livros</h1>

    <a class="btn-voltar" href="../painel.php">Voltar para o Painel</a>
    <a class="btn-editar" href="cadastrar.php" style="margin-left:10px; text-decoration:none;">Novo Livro</a>

    <table class="tabela-usuarios">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagem</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php if (count($livros) > 0): ?>
                <?php foreach ($livros as $l): ?>
                <tr>
                    <td><?= $l['id'] ?></td>

                    <td>
                        <?php if (!empty($l['imagem'])): ?>
                            <img src="../imagens/<?= htmlspecialchars($l['imagem']) ?>" class="capa" alt="Capa do livro">
                        <?php else: ?>
                            Sem imagem
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars($l['titulo']) ?></td>
                    <td><?= htmlspecialchars($l['autor']) ?></td>

                    <td>
                        <?= $l['disponivel'] ? 'Disponível' : 'Alugado' ?>
                    </td>

                    <td>
                        <a class="btn-editar" href="editar.php?id=<?= $l['id'] ?>">Editar</a>
                        <a class="btn-excluir" href="excluir.php?id=<?= $l['id'] ?>" onclick="return confirm('Deseja realmente excluir este livro?')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;">Nenhum livro cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>