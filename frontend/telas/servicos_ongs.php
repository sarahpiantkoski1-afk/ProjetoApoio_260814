<?php
require_once('../../backend/funcionalidades/config.php');
require_once(__DIR__ . '/../componentes/layout.php');

$municipios = require(__DIR__ . '/../dados/municipios.php');
$chave = $_GET['cidade'] ?? 'curitiba';
$cidade = $municipios[$chave] ?? $municipios['curitiba'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços em <?= htmlspecialchars($cidade['nome']) ?> - Apoio+</title>
    <link rel="stylesheet" href="<?= caminhoEstilo('estilos.css') ?>">
</head>
<body>
    <?php renderizarCabecalho('ongs'); ?>
    <main class="ongs-main">
        <section class="ongs-apresentacao">
            <span class="ongs-selo">Rede de Apoio Regional</span>
            <h1>Serviços e instituições em <?= htmlspecialchars($cidade['nome']) ?></h1>
            <p>Consulte abaixo os serviços cadastrados para este município.</p>
        </section>
        <section class="servicos-lista">
            <?php foreach ($cidade['itens'] as $item): ?>
                <article class="card"><h2><?= htmlspecialchars($item) ?></h2><p>Atendimento, orientação e encaminhamento para a rede de proteção social do município.</p></article>
            <?php endforeach; ?>
            <a class="botao-servicos servicos-voltar" href="ongs.php">&larr; Voltar ao mapa</a>
        </section>
    </main>
    <?php renderizarRodape(); ?>
</body>
</html>
