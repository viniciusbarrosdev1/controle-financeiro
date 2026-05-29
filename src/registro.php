<?php
    require_once "classes/Usuario.php";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $usuario = new Usuario();
        $usuario->criarUsuario($_POST['nome'], $_POST['email'], $_POST['senha']);
        header('Location: login.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuário</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form method="POST">
        <h1>Registrar</h1>
        <label>Nome:</label>
        <input type="text" name="nome" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Senha:</label>
        <input type="password" name="senha" required>
        <button type="submit">Registrar</button>
        <p style="color:#333;">Já tem uma conta? <a href="login.php">Entrar</a></p>
    </form>
</body>
</html>
