<?php
require 'dados.php';
$dados = lerDados();
$editando = null;

if (isset($_GET['editar'])) {
    foreach ($dados['enderecos'] as $e) {
        if ($e['id'] == $_GET['editar']) $editando = $e;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['id'])) {
        foreach ($dados['enderecos'] as &$e) {
            if ($e['id'] == $_POST['id']) {
                $e['tipo'] = $_POST['tipo'];
                $e['cep'] = $_POST['cep'];
                $e['logradouro'] = $_POST['logradouro'];
                $e['cidade'] = $_POST['cidade'];
            }
        }
    } else {
        $dados['enderecos'][] = [
            'id' => uniqid(),
            'tipo' => $_POST['tipo'],
            'cep' => $_POST['cep'],
            'logradouro' => $_POST['logradouro'],
            'cidade' => $_POST['cidade']
        ];
    }
    salvarDados($dados);
    header('Location: enderecos.php');
    exit;
}

include 'header.php';
?>
<h3><?= $editando ? 'Editar Endereço' : 'Cadastrar Endereço' ?></h3>
<form method="POST" class="card p-3 mb-4 shadow-sm">
    <input type="hidden" name="id" value="<?= $editando['id'] ?? '' ?>">
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select">
                <option value="Origem" <?= ($editando['tipo'] ?? '') == 'Origem' ? 'selected' : '' ?>>Origem</option>
                <option value="Destino" <?= ($editando['tipo'] ?? '') == 'Destino' ? 'selected' : '' ?>>Destino</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">CEP</label>
            <input type="text" name="cep" class="form-control" required value="<?= $editando['cep'] ?? '' ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Rua / Número</label>
            <input type="text" name="logradouro" class="form-control" required value="<?= $editando['logradouro'] ?? '' ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Cidade / UF</label>
            <input type="text" name="cidade" class="form-control" required value="<?= $editando['cidade'] ?? '' ?>">
        </div>
    </div>
    <button type="submit" class="btn btn-success mt-3"><?= $editando ? 'Atualizar' : 'Salvar Endereço' ?></button>
</form>

<h4>Endereços Cadastrados</h4>
<table class="table table-bordered bg-white shadow-sm">
    <thead><tr><th>Tipo</th><th>CEP</th><th>Endereço</th><th>Cidade</th><th>Ação</th></tr></thead>
    <tbody>
        <?php foreach ($dados['enderecos'] as $e): ?>
        <tr>
            <td><span class="badge bg-<?= $e['tipo'] == 'Origem' ? 'info' : 'secondary' ?>"><?= $e['tipo'] ?></span></td>
            <td><?= htmlspecialchars($e['cep']) ?></td>
            <td><?= htmlspecialchars($e['logradouro']) ?></td>
            <td><?= htmlspecialchars($e['cidade']) ?></td>
            <td><a href="?editar=<?= $e['id'] ?>" class="btn btn-sm btn-warning">Editar</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div></body></html>