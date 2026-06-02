<?php
require '../conexao.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$id = (int) $_GET['id'];

// Buscar o livro para apagar a imagem também
$sql = "SELECT imagem FROM livros WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$livro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livro) {
    echo "<script>alert('Livro não encontrado!'); window.location.href='listar.php';</script>";
    exit;
}

try {
    // Apagar imagem do servidor, se existir
    if (!empty($livro['imagem'])) {
        $caminhoImagem = '../imagens/' . $livro['imagem'];
        if (file_exists($caminhoImagem)) {
            unlink($caminhoImagem);
        }
    }

    // Excluir do banco
    $sql = "DELETE FROM livros WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    echo "<script>
            alert('Livro excluído com sucesso!');
            window.location.href = 'listar.php';
          </script>";
    exit;
} catch (PDOException $e) {
    echo "<script>alert('Erro ao excluir: " . $e->getMessage() . "');</script>";
}
?>