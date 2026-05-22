<?php

require '../conexao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null) {
    header('Location: listar.php');
    exit;
}

$mensagem = "";

$sql = 'SELECT * FROM usuarios WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header('Location: listar.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $tipo = $_POST['tipo'];
    $senha = trim($_POST['senha'] ?? '');

    try {
        if ($senha !== '') {
            $sql = 'UPDATE usuarios SET nome = :nome, email = :email, senha = :senha, tipo = :tipo WHERE id = :id';
            $params = [
                'nome' => $nome,
                'email' => $email,
                'senha' => password_hash($senha, PASSWORD_DEFAULT),
                'tipo' => $tipo,
                'id' => $id,
            ];
        } else {
            $sql = 'UPDATE usuarios SET nome = :nome, email = :email, tipo = :tipo WHERE id = :id';
            $params = [
                'nome' => $nome,
                'email' => $email,
                'tipo' => $tipo,
                'id' => $id,
            ];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        header('Location: listar.php');
        exit;
    } catch (PDOException $e) {
        $mensagem = "<p class='error'>Erro ao atualizar: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Usuário</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Editar Usuário</h1>
    <?php echo $mensagem; ?>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
        <input type="password" name="senha" placeholder="Nova senha (deixe em branco para manter)">
        <select name="tipo" required>
            <option value="admin" <?= $usuario['tipo'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="aluno" <?= $usuario['tipo'] === 'aluno' ? 'selected' : '' ?>>Aluno</option>
        </select>
        <button type="submit" class="btn-entrar">Salvar</button>
        <a class="btn-voltar" href="listar.php">Cancelar</a>
    </form>
</div>
</body>
</html>
