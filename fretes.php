<?php
require 'dados.php';
$dados = lerDados();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dados['fretes'][] = [
        'id' => uniqid(),
        'data' => $_POST['data'],
        'transportador' => $_POST['transportador'],
        'origem' => $_POST['origem'],
        'destino' => $_POST['destino'],
        'produtos' => $_POST['produtos'] // Array com vários produtos
    ];
    salvarDados($dados);
    header('Location: fretes.php');
    exit;
}

include 'header.php';
?>
<h3>Gerar Novo Frete</h3>
<form method="POST" class="card p-3 mb-4 shadow-sm">
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <label class="form-label">Data do Frete</label>
            <input type="date" name="data" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Transportador</label>
            <select name="transportador" class="form-select" required>
                <?php foreach ($dados['transportadores'] as $t): ?>
                    <option value="<?= htmlspecialchars($t['nome']) ?>"><?= htmlspecialchars($t['nome']) ?> (<?= $t['veiculo'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Origem</label>
            <select name="origem" class="form-select" required>
                <?php foreach ($dados['enderecos'] as $e): if ($e['tipo'] == 'Origem'): ?>
                    <option value="<?= htmlspecialchars($e['cidade'] . ' - ' . $e['logradouro']) ?>"><?= htmlspecialchars($e['cidade']) ?></option>
                <?php endif; endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Destino</label>
            <select name="destino" class="form-select" required>
                <?php foreach ($dados['enderecos'] as $e): if ($e['tipo'] == 'Destino'): ?>
                    <option value="<?= htmlspecialchars($e['cidade'] . ' - ' . $e['logradouro']) ?>"><?= htmlspecialchars($e['cidade']) ?></option>
                <?php endif; endforeach; ?>
            </select>
        </div>
    </div>

    <h5>Produtos na Carga (1 Transportador para Vários Produtos)</h5>
    <div id="lista-produtos">
        <div class="input-group mb-2">
            <select name="produtos[]" class="form-select" required>
                <?php foreach ($dados['produtos'] as $p): ?>
                    <option value="<?= htmlspecialchars($p['nome']) ?>"><?= htmlspecialchars($p['nome']) ?> (<?= $p['peso'] ?>kg)</option>
                <?php endforeach; ?>
            </select>
            <button type="button" class="btn btn-outline-primary" onclick="adicionarProduto()">+ Adicionar Outro Produto</button>
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Registrar Frete</button>
</form>

<h4>Fretes Registrados</h4>
<table class="table table-bordered bg-white shadow-sm">
    <thead><tr><th>Data</th><th>Transportador</th><th>Origem</th><th>Destino</th><th>Produtos</th></tr></thead>
    <tbody>
        <?php foreach ($dados['fretes'] as $f): ?>
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

<script>
// JavaScript para incluir campos dinamicamente conforme pedido no roteiro
function adicionarProduto() {
    const container = document.getElementById('lista-produtos');
    const primeiraLinha = container.firstElementChild;
    const novaLinha = primeiraLinha.cloneNode(true);
    const botao = novaLinha.querySelector('button');
    botao.textContent = 'Remover';
    botao.className = 'btn btn-outline-danger';
    botao.onclick = function() { novaLinha.remove(); };
    container.appendChild(novaLinha);
}
</script>
</div></body></html>