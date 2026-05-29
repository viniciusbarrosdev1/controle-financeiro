<?php
require_once 'functions.php';
validarSessao();
require_once 'classes/Ativo.php';
require_once 'classes/Dividendo.php';

$ativo = new Ativo();
$dividendo = new Dividendo();

$investimentos = $ativo->calcularPrecoMedio();
$dividendos = $dividendo->calcularDividendosPorAtivo();

$dadosGrafico = [];
foreach ($investimentos as $item) {
    $dadosGrafico[$item['ativo']] = [
        'investido' => (float) $item['total_valor'],
        'dividendos' => 0
    ];
}
foreach ($dividendos as $item) {
    if (!isset($dadosGrafico[$item['ativo']])) {
        $dadosGrafico[$item['ativo']] = ['investido' => 0, 'dividendos' => 0];
    }
    $dadosGrafico[$item['ativo']]['dividendos'] = (float) $item['total_dividendos'];
}

$totalInvestido = array_sum(array_column($dadosGrafico, 'investido'));
$totalDividendos = array_sum(array_column($dadosGrafico, 'dividendos'));

$pageTitle = 'Relatório de Investimentos x Dividendos';
$pageScripts = ['https://cdn.jsdelivr.net/npm/chart.js'];
require_once 'layouts/header.php';
?>
        <h1>Relatório de Investimentos x Dividendos</h1>

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

        <div class="chart-container">
            <canvas id="graficoInvestimentosDividendos"></canvas>
        </div>

        <script>
            const dados = <?php echo json_encode($dadosGrafico); ?>;

            const labels = Object.keys(dados);
            const dadosInvestidos = Object.values(dados).map(item => item.investido);
            const dadosDividendos = Object.values(dados).map(item => item.dividendos);

            const formatBRL = (v) => 'R$ ' + Number(v).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            const ctx = document.getElementById('graficoInvestimentosDividendos').getContext('2d');

            const gradInvest = ctx.createLinearGradient(0, 0, 0, 400);
            gradInvest.addColorStop(0, 'rgba(0, 77, 153, 0.95)');
            gradInvest.addColorStop(1, 'rgba(0, 77, 153, 0.55)');

            const gradDiv = ctx.createLinearGradient(0, 0, 0, 400);
            gradDiv.addColorStop(0, 'rgba(40, 167, 69, 0.95)');
            gradDiv.addColorStop(1, 'rgba(40, 167, 69, 0.55)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Investido',
                            data: dadosInvestidos,
                            backgroundColor: gradInvest,
                            borderColor: 'rgba(0, 77, 153, 1)',
                            borderWidth: 1,
                            borderRadius: 6,
                            borderSkipped: false,
                            maxBarThickness: 60
                        },
                        {
                            label: 'Dividendos Recebidos',
                            data: dadosDividendos,
                            backgroundColor: gradDiv,
                            borderColor: 'rgba(40, 167, 69, 1)',
                            borderWidth: 1,
                            borderRadius: 6,
                            borderSkipped: false,
                            maxBarThickness: 60
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    animation: { duration: 800, easing: 'easeOutQuart' },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { usePointStyle: true, padding: 16, font: { size: 13 } }
                        },
                        title: {
                            display: true,
                            text: 'Investido x Dividendos por Ativo',
                            font: { size: 16, weight: 'bold' },
                            color: '#004d99',
                            padding: { top: 6, bottom: 18 }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.85)',
                            padding: 12,
                            cornerRadius: 6,
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 13 },
                            callbacks: {
                                label: (ctx) => `${ctx.dataset.label}: ${formatBRL(ctx.parsed.y)}`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 13, weight: 'bold' }, color: '#333' }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.06)' },
                            ticks: { font: { size: 12 }, color: '#666', callback: (v) => formatBRL(v) }
                        }
                    }
                }
            });
        </script>
<?php require_once 'layouts/footer.php'; ?>
