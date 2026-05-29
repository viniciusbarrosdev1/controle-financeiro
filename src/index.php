<?php
require_once 'functions.php';
validarSessao();
require_once 'classes/Ativo.php';
require_once 'classes/Dividendo.php';

$totalInvestido = array_sum(array_column((new Ativo())->calcularPrecoMedio(), 'total_valor'));
$totalDividendos = array_sum(array_column((new Dividendo())->calcularDividendosPorAtivo(), 'total_dividendos'));

$pageTitle = 'Dashboard - Gestão de Ativos';
require_once 'layouts/header.php';
?>
        <section class="dashboard">
            <h1>Bem-vindo à Gestão de Ativos</h1>
            <p>Este sistema ajuda você a gerenciar seus investimentos em ativos e os dividendos recebidos. Use o menu acima para navegar pelas opções.</p>
            <div class="cards">
                <div class="card">
                    <h2>Total Investido</h2>
                    <p>R$ <?= number_format($totalInvestido, 2, ',', '.') ?></p>
                </div>
                <div class="card">
                    <h2>Total de Dividendos</h2>
                    <p>R$ <?= number_format($totalDividendos, 2, ',', '.') ?></p>
                </div>
            </div>
        </section>
<?php require_once 'layouts/footer.php'; ?>
