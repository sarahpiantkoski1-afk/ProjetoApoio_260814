<?php
session_start();
require_once(__DIR__ . '/../componentes/layout.php');

if (isset($_SESSION['usuario']) || isset($_SESSION['ong'])) {
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
    <title>Fazer Login</title>
    <link rel="stylesheet" href="<?= caminhoEstilo('estilos.css') ?>">
</head>
<body>
    <?php renderizarCabecalho('login'); ?>
    <main class="login-selecao-main">
        <h2 class="titulo-tela">Acesse sua conta</h2>

        <section class="login-opcoes" aria-label="Selecione o tipo de login">
            <article class="login-card">
                <div class="login-card-icone" aria-hidden="true">
                        <img src="../imagens/egresso.png" alt="jovem egresso" class="icone-login">
                </div>
                <h3>Jovem Egresso</h3>
                <p>Entre com sua conta para acessar conteúdos, oportunidades e muito mais.</p>
                <a class="login-card-botao" href="login/login_egressos.php">Entrar</a>
            </article>

            <article class="login-card">
                <div class="login-card-icone" aria-hidden="true">
                   <img src="../imagens/grupo.png" alt="ONG" class="icone-login">
                </div>
                <h3>Instituição Social</h3>
                <p>Entre com sua conta para gerenciar suas oportunidades e publicações.</p>
                <a class="login-card-botao" href="login/login_ongs.php">Entrar</a>
            </article>
        </section>

        <div class="login-links">
            <a href="cadastro.php">Ainda n&atilde;o tem conta? Cadastre-se</a>
        </div>
    </main>
    <?php renderizarRodape(); ?>
</body>
</html>
