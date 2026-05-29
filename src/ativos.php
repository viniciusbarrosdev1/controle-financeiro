<?php
require_once 'functions.php';
validarSessao();
require_once 'classes/Ativo.php';

$ativo = new Ativo();
$relatorio = $ativo->calcularPrecoMedio();

$pageTitle = 'Relatório de Ativos';
require_once 'layouts/header.php';
?>
        <h1>Relatório de Ativos</h1>
        <table border="1">
            <tr>
                <th>Ativo</th>
                <th>Total Comprado</th>
                <th>Total Investido</th>
                <th>Preço Médio</th>
            </tr>
            <?php foreach ($relatorio as $linha): ?>
                <tr>
                    <td><?= $linha['ativo'] ?></td>
                    <td><?= number_format($linha['total_quantidade'], 0, ',', '.') ?></td>
                    <td>R$ <?= number_format($linha['total_valor'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($linha['preco_medio'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
<?php require_once 'layouts/footer.php'; ?>
