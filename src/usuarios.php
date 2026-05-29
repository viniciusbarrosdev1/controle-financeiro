<?php
require_once 'functions.php';
validarSessao();
require_once 'classes/Usuario.php';

$usuario = new Usuario();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['excluir'])) {
        $usuario->excluirUsuario($_POST['id']);
    }
}

$usuarios = $usuario->listarUsuarios();

$pageTitle = 'Gerenciar Usuários';
require_once 'layouts/header.php';
?>
        <h1>Gerenciar Usuários</h1>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['id'] ?></td>
                    <td><?= $usuario['nome'] ?></td>
                    <td><?= $usuario['email'] ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                            <button type="submit" name="excluir">Excluir</button>
                        </form>
                        <form method="GET" action="editar_usuario.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                            <button type="submit">Editar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
<?php require_once 'layouts/footer.php'; ?>
