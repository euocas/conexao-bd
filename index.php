<?php
require 'conexao.php';
session_start();
$mensagem="";
if($_SERVER['REQUEST_METHOD']==='POST'){
    $email=trim($_POST['email']);
    $senha=trim($_POST['senha']);
 
$sql="SELECT * FROM usuarios WHERE email=:email";
$stmt=$pdo->prepare($sql);
$stmt->execute([':email'=>$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
if($usuario && password_verify($senha,$usuario['senha'])){
    $_SESSION['usuario'] = $usuario['nome'];
    $_SESSION['tipo'] = $usuario['tipo'];
    header("Location:painel.php");
    exit;
}else{
    $mensagem="<p class='erro'> Email ou senha inválidos!</p>";
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
        <button type="submit" class="btn-entrar">Entrar</button>
    </form>
</div>
</body>
</html>