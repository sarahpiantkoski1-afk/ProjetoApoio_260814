<?php
session_start();
require_once(__DIR__ . '/../componentes/layout.php');

if (isset($_SESSION['usuario'] )||( isset($_SESSION['ong']))) {
    header("Location: inicio.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php renderizarFavicon(); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="<?= caminhoEstilo('estilos.css') ?>">
    <link rel="stylesheet" href="<?= caminhoEstilo('forms.css') ?>">
</head>
<body>
    <?php renderizarCabecalho('cadastro'); ?>
    <main class="login-selecao-main">
        <h2 class="titulo-tela">Como você deseja se cadastrar?</h2>

        <section class="login-opcoes" aria-label="Selecione o tipo de cadastro">
            <article class="login-card">
                <div class="login-card-icone" aria-hidden="true">
                    <img src="../imagens/egresso.png" alt="" class="icone-login">
                </div>
                <h3>Jovem Egresso</h3>
                <p>Crie sua conta para acessar conte&uacute;dos, oportunidades e muito mais.</p>
                <a class="login-card-botao" href="login/cadastro_egressos.php">Cadastrar</a>
            </article>

            <article class="login-card">
                <div class="login-card-icone" aria-hidden="true">
                    <img src="../imagens/grupo.png" alt="" class="icone-login">
                </div>
                <h3>Institui&ccedil;&atilde;o Social</h3>
                <p>Crie sua conta para divulgar oportunidades e gerenciar publica&ccedil;&otilde;es.</p>
                <a class="login-card-botao" href="login/cadastro_ongs.php">Cadastrar</a>
            </article>
        </section>
    </main>
    <?php renderizarRodape(); ?>
</body>
</html>
