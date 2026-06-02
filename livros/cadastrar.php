<?php
require '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $disponivel = isset($_POST['disponivel']) ? 1 : 0;
    $imagem = null;

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $pasta = '../imagens/';

        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nomeArquivo = uniqid() . '.' . $extensao;
        $caminho = $pasta . $nomeArquivo;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
            $imagem = $nomeArquivo;
        } else {
            echo "<script>alert('Erro ao salvar a imagem!');</script>";
        }
    }

    try {
        $sql = "INSERT INTO livros (titulo, autor, disponivel, imagem)
                VALUES (:titulo, :autor, :disponivel, :imagem)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':titulo' => $titulo,
            ':autor' => $autor,
            ':disponivel' => $disponivel,
            ':imagem' => $imagem
        ]);

        echo "<script>
                alert('Livro cadastrado com sucesso!');
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
<title>Cadastrar Livro</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="container">
    <h1>Cadastrar Livro</h1>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="titulo" placeholder="Título" required>
        <input type="text" name="autor" placeholder="Autor" required>

        <label>
            <input type="checkbox" name="disponivel" checked>
            Disponível
        </label>

        <input type="file" name="imagem" accept="image/*">

        <button type="submit">Cadastrar</button>
    </form>
</div>

</body>
</html>