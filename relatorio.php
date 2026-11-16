<?php
require 'dados.php';
$dados = lerDados();
$dataFiltro = $_GET['data'] ?? date('Y-m-d');

$fretesFiltrados = array_filter($dados['fretes'], function($f) use ($dataFiltro) {
    return $f['data'] == $dataFiltro;
});

// Download em formato CSV (abre direto no Excel)
if (isset($_GET['baixar'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=relatorio_fretes_' . $dataFiltro . '.csv');
    $saida = fopen('php://output', 'w');
    fputcsv($saida, ['ID', 'Data', 'Transportador', 'Origem', 'Destino', 'Produtos'], ';');
    foreach ($fretesFiltrados as $f) {
        fputcsv($saida, [$f['id'], $f['data'], $f['transportador'], $f['origem'], $f['destino'], implode(' | ', $f['produtos'])], ';');
    }
    fclose($saida);
    exit;
}

include 'header.php';
?>
<h3>Relatório de Fretes por Dia</h3>
<form method="GET" class="card p-3 mb-4 shadow-sm">
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Selecione a Data</label>
            <input type="date" name="data" class="form-control" value="<?= $dataFiltro ?>">
        </div>
        <div class="col-md-8">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="?data=<?= $dataFiltro ?>&baixar=1" class="btn btn-success">Baixar Relatório (.CSV / Excel)</a>
        </div>
    </div>
</form>

<table class="table table-bordered bg-white shadow-sm">
    <thead><tr><th>Data</th><th>Transportador</th><th>Origem</th><th>Destino</th><th>Produtos</th></tr></thead>
    <tbody>
        <?php foreach ($fretesFiltrados as $f): ?>
        <tr>
            <td><?= date('d/m/Y', strtotime($f['data'])) ?></td>
            <td><?= htmlspecialchars($f['transportador']) ?></td>
            <td><?= htmlspecialchars($f['origem']) ?></td>
            <td><?= htmlspecialchars($f['destino']) ?></td>
            <td><?= htmlspecialchars(implode(', ', $f['produtos'])) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div></body></html>