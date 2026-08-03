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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fazer Login</title>
    <link rel="stylesheet" href="<?= caminhoEstilo('estilos.css') ?>">
</head>
<body>
    <?php renderizarCabecalho('login'); ?>
    <main class="login-selecao-main">
        <h2>Quem voc&ecirc; &eacute;?</h2>

        <section class="login-opcoes" aria-label="Selecione o tipo de login">
            <article class="login-card">
                <div class="login-card-icone" aria-hidden="true">
                    <div class="login-card-icone" aria-hidden="true">
                       👤
                </div>
                </div>
                <h3>Jovem Egresso</h3>
                <p>Entre com sua conta para acessar conte&uacute;dos, oportunidades e muito mais.</p>
                <a class="login-card-botao" href="login/login_egressos.php">Entrar</a>
            </article>

            <article class="login-card">
                <div class="login-card-icone" aria-hidden="true">
                   <div class="login-card-icone" aria-hidden="true">
    🏛️
</div>
                </div>
                <h3>Institui&ccedil;&atilde;o Social</h3>
                <p>Entre com sua conta para gerenciar suas oportunidades e publica&ccedil;&otilde;es.</p>
                <a class="login-card-botao" href="login/login_ongs.php">Entrar</a>
            </article>
        </section>
    </main>
    <?php renderizarRodape(); ?>
</body>
</html>
