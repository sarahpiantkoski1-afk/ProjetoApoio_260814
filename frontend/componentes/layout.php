<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const APOIO_ASSET_VERSION = '20';

function caminhoEstilo(string $arquivo, int $nivel = 0): string
{
    return str_repeat('../', $nivel + 1) . 'estilos/' . $arquivo . '?v=' . APOIO_ASSET_VERSION;
}

function renderizarCabecalho(string $paginaAtiva = '', int $nivel = 0): void
{
    $prefixoTelas = str_repeat('../', $nivel);
    $prefixoFrontend = str_repeat('../', $nivel + 1);
    $linksPrincipais = [
        'inicio' => ['Inicio', 'inicio.php'],
        'conteudo' => ['Conteúdo Educativo', 'conteudo_educativo.php'],
        'postagens' => ['Postagens', 'postagens.php'],
        'ongs' => ['ONGs', 'ongs.php'],
        'contato' => ['Contato', 'contato.php'],
    ];

    $classeLink = static fn (string $pagina): string => $paginaAtiva === $pagina ? ' class="active" aria-current="page"' : '';
    ?>
    <header>
        <picture id="logo-img">
            <img src="<?= htmlspecialchars($prefixoFrontend . 'imagens/apoiomais.png', ENT_QUOTES, 'UTF-8') ?>" alt="Apoio+" class="logo-header">
        </picture>
        <nav id="options-header" aria-label="Navegação principal">
            <ul>
                <?php foreach ($linksPrincipais as $pagina => [$rotulo, $destino]) { ?>
                    <li><a href="<?= htmlspecialchars($prefixoTelas . $destino, ENT_QUOTES, 'UTF-8') ?>"<?= $classeLink($pagina) ?>><?= htmlspecialchars($rotulo, ENT_QUOTES, 'UTF-8') ?></a></li>
                <?php } ?>

                <?php if (!isset($_SESSION['usuario']) && !isset($_SESSION['ong'])) { ?>
                    <li><a href="<?= $prefixoTelas ?>cadastro.php"<?= $classeLink('cadastro') ?>>Cadastre-se</a></li>
                    <li><a href="<?= $prefixoTelas ?>login.php"<?= $classeLink('login') ?>>Entrar</a></li>
                <?php } else { ?>
                    <?php if (isset($_SESSION['ong']) && !isset($_SESSION['usuario'])) { ?>
                        <li><a href="<?= $prefixoTelas ?>post_ongs.php"<?= $classeLink('post_ongs') ?>>Criar Post</a></li>
                    <?php } ?>
                    <li><a href="<?= $prefixoTelas ?>logout.php" class="logout">Sair &rarr;</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>
    <?php
}

function renderizarRodape(int $nivel = 0): void
{
    $prefixoTelas = str_repeat('../', $nivel);
    ?>
    <footer>
        <p>Apoio+ O guia colaborativo para jovens egressos de abrigos institucionais</p>
    </footer>
    <script src="<?= htmlspecialchars($prefixoTelas . 'header-menu.js', ENT_QUOTES, 'UTF-8') ?>"></script>
    <?php
}
