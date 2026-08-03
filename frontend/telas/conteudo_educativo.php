<?php
session_start();
require_once(__DIR__ . '/../componentes/layout.php');

$conteudos = [
    [
        'titulo' => 'Instru&ccedil;&otilde;es de um psic&oacute;logo sobre autocuidado',
        'link' => 'https://youtu.be/dCwOLOwmtkE?si=2xFWJX1Cj80DZiEi',
        'thumb' => 'https://img.youtube.com/vi/dCwOLOwmtkE/hqdefault.jpg',
        'categorias' => 'autoestima,autocuidado,dicas',
    ],
    [
        'titulo' => 'Relato e orienta&ccedil;&atilde;o a jovens que rec&eacute;m completaram 18 anos',
        'link' => 'https://youtu.be/DE8U1LpMffY?si=Lvbu42YTjlMm-jNa',
        'thumb' => 'https://img.youtube.com/vi/DE8U1LpMffY/hqdefault.jpg',
        'categorias' => 'acolhimento,dicas',
    ],
    [
        'titulo' => 'Desafios, direitos e apoio social',
        'link' => 'https://youtu.be/YzDqQwZws-w?si=G8066V5qiW-ZbDE3',
        'thumb' => 'https://img.youtube.com/vi/YzDqQwZws-w/hqdefault.jpg',
        'categorias' => 'direitos,acolhimento',
    ],
    [
        'titulo' => 'Autonomia e juventude',
        'link' => 'https://www.youtube.com/live/yVH7k70RElY?si=TgDn7bX0-zXADOL2',
        'thumb' => 'https://img.youtube.com/vi/yVH7k70RElY/hqdefault.jpg',
        'categorias' => 'autoestima,dicas',
    ],
    [
        'titulo' => 'Quando o abrigo acaba',
        'link' => 'https://youtu.be/TIDPCiWevRw?si=A90mYZsu7g8mIgWc',
        'thumb' => 'https://img.youtube.com/vi/TIDPCiWevRw/hqdefault.jpg',
        'categorias' => 'acolhimento,direitos',
    ],
    [
        'titulo' => 'Hist&oacute;rias reais, jovens egressos compartilham suas experi&ecirc;ncias',
        'link' => 'https://www.youtube.com/live/hIEd8nR8dPE?si=zkkkb16bJYkiIWn2',
        'thumb' => 'https://img.youtube.com/vi/hIEd8nR8dPE/hqdefault.jpg',
        'categorias' => 'acolhimento,autoestima',
    ],
    [
        'titulo' => 'Passo a passo: como fazer a barba',
        'link' => 'https://youtu.be/fdjIvwSb_AY?si=D8Xzxcnv8pHAeajo',
        'thumb' => 'https://img.youtube.com/vi/fdjIvwSb_AY/hqdefault.jpg',
        'categorias' => 'autocuidado,dicas',
    ],
    [
        'titulo' => 'Direitos de crian&ccedil;as e adolescentes',
        'link' => 'https://youtu.be/bRe4HI4fwJ0?si=Dtv-LKVb2xyo6LbK',
        'thumb' => 'https://img.youtube.com/vi/bRe4HI4fwJ0/hqdefault.jpg',
        'categorias' => 'direitos',
    ],
    [
        'titulo' => 'A vida depois do acolhimento',
        'link' => 'https://youtu.be/IYvd_gy5FjM?si=NJ2TcvPmP0TfD9Fh',
        'thumb' => 'https://img.youtube.com/vi/IYvd_gy5FjM/hqdefault.jpg',
        'categorias' => 'acolhimento,dicas',
    ],
    [
        'titulo' => 'Como praticar o autocuidado no dia a dia',
        'link' => 'https://youtu.be/tYiD3FJRXgM?si=r4a3cBUEiJsRMLIS',
        'thumb' => 'https://img.youtube.com/vi/tYiD3FJRXgM/hqdefault.jpg',
        'categorias' => 'autocuidado,dicas',
    ],
    [
        'titulo' => 'Direitos humanos e prote&ccedil;&atilde;o a jovens em acolhimento',
        'link' => 'https://youtu.be/YBD9lxmE30g?si=SEvK0Hqz5LuGpcnq',
        'thumb' => 'https://img.youtube.com/vi/YBD9lxmE30g/hqdefault.jpg',
        'categorias' => 'direitos,acolhimento',
    ],
    [
        'titulo' => 'Transi&ccedil;&atilde;o da vida institucional para a vida adulta',
        'link' => 'https://youtu.be/94QP7YsHwto?si=15raaUf9nosRumZ3/',
        'thumb' => 'https://img.youtube.com/vi/94QP7YsHwto/hqdefault.jpg',
        'categorias' => 'acolhimento,dicas',
    ],
    [
        'titulo' => 'Dicas financeiras',
        'link' => 'https://youtu.be/3nSfnNmYJaY?si=PNmF1mStYVpvATma',
        'thumb' => 'https://img.youtube.com/vi/3nSfnNmYJaY/hqdefault.jpg',
        'categorias' => 'dicas',
    ],
    [
        'titulo' => 'Hist&oacute;rias reais',
        'link' => 'https://youtu.be/TDqhTRIhieM?si=IfY4f9aqzl2pgoEx',
        'thumb' => 'https://img.youtube.com/vi/TDqhTRIhieM/hqdefault.jpg',
        'categorias' => 'acolhimento,autoestima',
    ],
    [
        'titulo' => 'Dicas para jovens adultos',
        'link' => 'https://youtu.be/Vwj9o_Wpqp8?si=wNoqgeqqT169ebm6',
        'thumb' => 'https://img.youtube.com/vi/Vwj9o_Wpqp8/hqdefault.jpg',
        'categorias' => 'dicas,autoestima',
    ],
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conteúdo Educativo - Apoio+</title>
    <link rel="stylesheet" href="<?= caminhoEstilo('estilos.css') ?>">
</head>
<body>
    <?php renderizarCabecalho('conteudo'); ?>
    <main class="educativo-main">
        <section class="educativo-container">
            <h1>Conte&uacute;dos Educativos</h1>

            <div class="filtro-postagens educativo-ferramentas">
                <button class="botao-filtro" type="button" aria-label="Abrir filtros de categorias" aria-expanded="false">
                    &#128269;
                </button>
                <div class="painel-filtro" hidden>
                    <button type="button" class="filtro-chip" data-categoria="direitos">Direitos</button>
                    <button type="button" class="filtro-chip" data-categoria="autoestima">Autoestima</button>
                    <button type="button" class="filtro-chip" data-categoria="acolhimento">Acolhimento</button>
                    <button type="button" class="filtro-chip" data-categoria="autocuidado">Autocuidado</button>
                    <button type="button" class="filtro-chip" data-categoria="dicas">Dicas</button>
                </div>
            </div>

            <div class="educativo-grid">
                <?php foreach ($conteudos as $conteudo) { ?>
                    <a class="educativo-card" href="<?php echo htmlspecialchars($conteudo['link']); ?>" target="_blank" rel="noopener noreferrer" data-categorias="<?php echo htmlspecialchars($conteudo['categorias']); ?>">
                        <img src="<?php echo htmlspecialchars($conteudo['thumb']); ?>" alt="Capa do video">
                        <div class="educativo-card-texto">
                            <h3><?php echo $conteudo['titulo']; ?></h3>
                            <p>Postado em: 03 Mar 2026</p>
                        </div>
                    </a>
                <?php } ?>
            </div>
            <p class="sem-resultados" hidden>Nenhum conte&uacute;do encontrado para esta categoria.</p>
        </section>
    </main>
    <?php renderizarRodape(); ?>
    <script src="post-filter.js"></script>
</body>
</html>
