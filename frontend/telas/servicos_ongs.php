<?php
require_once('../../backend/funcionalidades/config.php');

$municipios = [
    'curitiba' => ['nome' => 'Curitiba', 'itens' => ['Fundação de Ação Social (FAS)', 'Centro de Referência da Assistência Social (CRAS)', 'Casa da Acolhida e do Regresso']],
    'pinhais' => ['nome' => 'Pinhais', 'itens' => ['Secretaria Municipal de Assistência Social', 'CRAS Norte', 'Centro da Juventude de Pinhais']],
    'colombo' => ['nome' => 'Colombo', 'itens' => ['CRAS Maracanã', 'Centro da Juventude Colombo', 'Secretaria de Assistência Social']],
    'piraquara' => ['nome' => 'Piraquara', 'itens' => ['CRAS São Cristóvão', 'Centro da Juventude de Piraquara', 'Proteção Social Especial']],
    'sao-jose' => ['nome' => 'São José dos Pinhais', 'itens' => ['CRAS Central', 'Centro da Juventude', 'Secretaria de Assistência Social']]
];
$chave = $_GET['cidade'] ?? 'curitiba';
$cidade = $municipios[$chave] ?? $municipios['curitiba'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços em <?= htmlspecialchars($cidade['nome']) ?> - Apoio+</title>
    <link rel="stylesheet" href="../estilos/estilos.css?v=10">
</head>
<body>
    <header>
        <picture id="logo-img"><img src="../imagens/apoiomais.png" alt="Apoio+" class="logo-header"></picture>
        <nav id="options-header"><ul>
            <li><a href="inicio.php">Inicio</a></li><li><a href="conteudo_educativo.php">Conteúdo Educativo</a></li>
            <li><a href="postagens.php">Postagens</a></li><li><a href="ongs.php" class="active">ONGs</a></li><li><a href="contato.php">Contato</a></li>
        </ul></nav>
    </header>
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
    <footer><p>Apoio+ O guia colaborativo para jovens egressos de abrigos institucionais</p></footer>
    <script src="header-menu.js"></script>
</body>
</html>
