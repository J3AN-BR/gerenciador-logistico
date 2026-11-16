<?php
require 'dados.php';
$dados = lerDados();
$editando = null;

if (isset($_GET['editar'])) {
    foreach ($dados['transportadores'] as $t) {
        if ($t['id'] == $_GET['editar']) $editando = $t;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['id'])) {
        foreach ($dados['transportadores'] as &$t) {
            if ($t['id'] == $_POST['id']) {
                $t['nome'] = $_POST['nome'];
                $t['cnpj'] = $_POST['cnpj'];
                $t['veiculo'] = $_POST['veiculo'];
            }
        }
    } else {
        $dados['transportadores'][] = [
            'id' => uniqid(),
            'nome' => $_POST['nome'],
            'cnpj' => $_POST['cnpj'],
            'veiculo' => $_POST['veiculo']
        ];
    }
    salvarDados($dados);
    header('Location: transportadores.php');
    exit;
}

include 'header.php';
?>
<h3><?= $editando ? 'Editar Transportador' : 'Cadastrar Transportador' ?></h3>
<form method="POST" class="card p-3 mb-4 shadow-sm">
    <input type="hidden" name="id" value="<?= $editando['id'] ?? '' ?>">
    <div class="row g-3">
        <div class="col-md-5">
            <label class="form-label">Nome / Razão Social</label>
            <input type="text" name="nome" class="form-control" required value="<?= $editando['nome'] ?? '' ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">CPF / CNPJ</label>
            <input type="text" name="cnpj" class="form-control" required value="<?= $editando['cnpj'] ?? '' ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Veículo / Placa</label>
            <input type="text" name="veiculo" class="form-control" required value="<?= $editando['veiculo'] ?? '' ?>">
        </div>
    </div>
    <button type="submit" class="btn btn-success mt-3"><?= $editando ? 'Atualizar' : 'Salvar Transportador' ?></button>
</form>

<h4>Transportadores Cadastrados</h4>
<table class="table table-bordered bg-white shadow-sm">
    <thead><tr><th>Nome</th><th>CPF/CNPJ</th><th>Veículo</th><th>Ação</th></tr></thead>
    <tbody>
        <?php foreach ($dados['transportadores'] as $t): ?>
        <tr>
            <td><?= htmlspecialchars($t['nome']) ?></td>
            <td><?= htmlspecialchars($t['cnpj']) ?></td>
            <td><?= htmlspecialchars($t['veiculo']) ?></td>
            <td><a href="?editar=<?= $t['id'] ?>" class="btn btn-sm btn-warning">Editar</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div></body></html>