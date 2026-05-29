<?php
require_once 'functions.php';
validarSessao();
require_once 'classes/Usuario.php';

$usuario = new Usuario();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $usuarioSelecionado = $usuario->buscarUsuario($_GET['id']);
    if (!$usuarioSelecionado) {
        die("Usuário não encontrado.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario->atualizarUsuario($_POST['id'], $_POST['nome'], $_POST['email']);
    header('Location: usuarios.php');
    exit;
}

$pageTitle = 'Editar Usuário';
require_once 'layouts/header.php';
?>
        <form method="POST">
            <h1>Editar Usuário</h1>
            <input type="hidden" name="id" value="<?= htmlspecialchars($usuarioSelecionado['id']) ?>">
            <label>Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($usuarioSelecionado['nome']) ?>" required>
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($usuarioSelecionado['email']) ?>" required>
            <button type="submit">Salvar Alterações</button>
        </form>
<?php require_once 'layouts/footer.php'; ?>
