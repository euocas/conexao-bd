
<?php
// Inclui o arquivo de conexão com o banco de dados
require 'conexao.php';

// Inicia a sessão para permitir salvar dados do usuário logado
session_start();

// Variável para guardar mensagens de erro ou aviso
$mensagem = "";

// Verifica se o formulário foi enviado usando o método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Pega o email digitado no formulário e remove espaços extras
    $email = trim($_POST['email']);

    // Pega a senha digitada e remove espaços extras
    $senha = trim($_POST['senha']);

    // Comando SQL para buscar um usuário pelo email
    $sql = "SELECT * FROM usuarios WHERE email = :email";

    // Prepara o comando SQL para evitar SQL Injection (ataques)
    $stmt = $pdo->prepare($sql);

    // Executa o comando SQL, substituindo :email pelo valor digitado
    $stmt->execute([':email' => $email]);

    // Pega os dados do usuário encontrado (se existir)
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o usuário existe e se a senha digitada confere com a senha salva no banco
    if ($usuario && password_verify($senha, $usuario['senha'])) {

        // Salva o nome do usuário na sessão (para mostrar no painel)
        $_SESSION['usuario'] = $usuario['nome'];

        // Salva o tipo do usuário (admin, aluno, etc.)
        $_SESSION['tipo'] = $usuario['tipo'];

        // Redireciona o usuário para a página do painel
        header("Location: painel.php");
        exit; // Encerra o script para garantir o redirecionamento
    } else {

        // Caso o email ou a senha estejam incorretos, mostra mensagem de erro
        $mensagem = "<p class='erro'>Email ou senha inválidos!</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Login - Biblioteca</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Login</h1>
    <?php echo $mensagem; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
</div>
</body>
</html>
