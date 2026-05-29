<?php
    require_once 'functions.php';
    validarSessao();
    require_once 'classes/Compra.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $compra = new Compra();
        $compra->adicionarCompra($_POST['ativo'], $_POST['quantidade'], $_POST['valor_unitario'], $_POST['data_compra']);
        $mensagem = "Compra adicionada com sucesso!";
    }

    $pageTitle = 'Cadastrar Compra';
    require_once 'layouts/header.php';
?>
        <form method="POST">
            <h1>Cadastrar Compra</h1>
            <?php if (!empty($mensagem)): ?>
                <p style="color:green;"><?= $mensagem ?></p>
            <?php endif; ?>
            <label>Ativo:</label>
            <input type="text" name="ativo" required>
            <label>Quantidade:</label>
            <input type="number" name="quantidade" required>
            <label>Valor Unitário:</label>
            <input type="number" step="0.01" name="valor_unitario" required>
            <label>Data da Compra:</label>
            <input type="date" name="data_compra" required>
            <button type="submit">Cadastrar</button>
        </form>
<?php require_once 'layouts/footer.php'; ?>
