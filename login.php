<?php
session_start();
require_once 'classes/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Usuario();
    $login = $usuario->validarLogin($_POST['email'], $_POST['senha']);

    if ($login) {
        $_SESSION['usuario'] = $login;
        header('Location: index.php');
        exit;
    } else {
        echo "<h1>Credenciais Inválidas</h1>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Login</h1>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Senha:</label>
        <input type="password" name="senha" required><br>
        <button type="submit">Entrar</button>
    </form>
    <?php if (isset($erro)): ?>
        <p><?= $erro ?></p>
    <?php endif; ?>
</body>
</html>