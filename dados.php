<?php
session_start();
$arquivo = 'banco_dados.json';

if (!file_exists($arquivo)) {
    $estruturaInicial = [
        'produtos' => [],
        'enderecos' => [],
        'transportadores' => [],
        'fretes' => []
    ];
    file_put_contents($arquivo, json_encode($estruturaInicial, JSON_PRETTY_PRINT));
}

function lerDados() {
    global $arquivo;
    return json_decode(file_get_contents($arquivo), true);
}

function salvarDados($dados) {
    global $arquivo;
    file_put_contents($arquivo, json_encode($dados, JSON_PRETTY_PRINT));
}
?>