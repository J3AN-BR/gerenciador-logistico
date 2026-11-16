<?php
require 'dados.php';
$dados = lerDados();
$editando = null;

if (isset($_GET['editar'])) {
    foreach ($dados['produtos'] as $p) {
        if ($p['id'] == $_GET['editar']) $editando = $p;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['id'])) {
        foreach ($dados['produtos'] as &$p) {
            if ($p['id'] == $_POST['id']) {
                $p['nome'] = $_POST['nome'];
                $p['peso'] = $_POST['peso'];
                $p['valor'] = $_POST['valor'];
            }
        }
    } else {
        $dados['produtos'][] = [
            'id' => uniqid(),
            'nome' => $_POST['nome'],
            'peso' => $_POST['peso'],
            'valor' => $_POST['valor']
        ];
    }
    salvarDados($dados);
    header('Location: index.php');
    exit;
}

include 'header.php';
?>
<h3><?= $editando ? 'Editar Produto' : 'Cadastrar Produto' ?></h3>
<form method="POST" class="card p-3 mb-4 shadow-sm">
    <input type="hidden" name="id" value="<?= $editando['id'] ?? '' ?>">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nome do Produto</label>
            <input type="text" name="nome" class="form-control" required value="<?= $editando['nome'] ?? '' ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Peso (kg)</label>
            <input type="number" step="0.01" name="peso" class="form-control" required value="<?= $editando['peso'] ?? '' ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Valor (R$)</label>
            <input type="number" step="0.01" name="valor" class="form-control" required value="<?= $editando['valor'] ?? '' ?>">
        </div>
    </div>
    <button type="submit" class="btn btn-success mt-3"><?= $editando ? 'Atualizar' : 'Salvar Produto' ?></button>
</form>

<h4>Produtos Cadastrados</h4>
<table class="table table-bordered bg-white shadow-sm">
    <thead><tr><th>ID</th><th>Nome</th><th>Peso</th><th>Valor</th><th>Ação</th></tr></thead>
    <tbody>
        <?php foreach ($dados['produtos'] as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['nome']) ?></td>
            <td><?= $p['peso'] ?> kg</td>
            <td>R$ <?= number_format($p['valor'], 2, ',', '.') ?></td>
            <td><a href="?editar=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Editar</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div></body></html>