<?php
require '../conexao.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$id = (int) $_GET['id'];

// Buscar livro atual
$sql = "SELECT * FROM livros WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$livro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livro) {
    echo "<script>alert('Livro não encontrado!'); window.location.href='listar.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $disponivel = isset($_POST['disponivel']) ? 1 : 0;
    $imagem = $livro['imagem'];

    // Se enviou nova imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $pasta = '../imagens/';

        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nomeArquivo = uniqid() . '.' . $extensao;
        $caminho = $pasta . $nomeArquivo;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
            // Apagar imagem antiga se existir
            if (!empty($livro['imagem']) && file_exists($pasta . $livro['imagem'])) {
                unlink($pasta . $livro['imagem']);
            }

            $imagem = $nomeArquivo;
        } else {
            echo "<script>alert('Erro ao salvar a nova imagem!');</script>";
        }
    }

    try {
        $sql = "UPDATE livros 
                SET titulo = :titulo, autor = :autor, disponivel = :disponivel, imagem = :imagem
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':titulo' => $titulo,
            ':autor' => $autor,
            ':disponivel' => $disponivel,
            ':imagem' => $imagem,
            ':id' => $id
        ]);

        echo "<script>
                alert('Livro atualizado com sucesso!');
                window.location.href = 'listar.php';
              </script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Erro: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Livro</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="container">
    <h1>Editar Livro</h1>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="titulo" placeholder="Título" value="<?= htmlspecialchars($livro['titulo']) ?>" required>
        <input type="text" name="autor" placeholder="Autor" value="<?= htmlspecialchars($livro['autor']) ?>" required>

        <label>
            <input type="checkbox" name="disponivel" <?= $livro['disponivel'] ? 'checked' : '' ?>>
            Disponível
        </label>

        <p>Imagem atual:</p>
        <?php if (!empty($livro['imagem'])): ?>
            <img src="../imagens/<?= htmlspecialchars($livro['imagem']) ?>" alt="Imagem atual" class="capa">
        <?php else: ?>
            <p>Sem imagem</p>
        <?php endif; ?>

        <input type="file" name="imagem" accept="image/*">

        <button type="submit">Salvar alterações</button>
    </form>
</div>

</body>
</html>