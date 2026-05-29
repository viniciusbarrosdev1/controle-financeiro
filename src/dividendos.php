<?php
require_once 'functions.php';
validarSessao();
require_once 'classes/Dividendo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dividendo = new Dividendo();
    $dividendo->adicionarDividendo($_POST['ativo'], $_POST['valor'], $_POST['data_recebimento']);
    $mensagem = "Dividendo registrado com sucesso!";
}

$pageTitle = 'Cadastrar Dividendos';
require_once 'layouts/header.php';
?>
        <form method="POST">
            <h1>Cadastrar Dividendos</h1>
            <?php if (!empty($mensagem)): ?>
                <p style="color:green;"><?= $mensagem ?></p>
            <?php endif; ?>
            <label>Ativo:</label>
            <input type="text" name="ativo" required>
            <label>Valor Recebido:</label>
            <input type="number" step="0.01" name="valor" required>
            <label>Data de Recebimento:</label>
            <input type="date" name="data_recebimento" required>
            <button type="submit">Cadastrar</button>
        </form>
<?php require_once 'layouts/footer.php'; ?>
