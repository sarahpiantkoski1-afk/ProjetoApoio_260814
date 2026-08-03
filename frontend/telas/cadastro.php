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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="<?= caminhoEstilo('estilos.css') ?>">
    <link rel="stylesheet" href="<?= caminhoEstilo('forms.css') ?>">
</head>
<body>
    <?php renderizarCabecalho('cadastro'); ?>
    <main>
        <h2>Quem você é?</h2>
        <section class="escolha">
            <article class="option">
                <p><a href="login/cadastro_egressos.php">Jovem Egresso</a></p>
            </article>
            <article class="option">
                <p><a href="login/cadastro_ongs.php">Instituição Social</a></p>
            </article>
        </section>
    </main>
    <?php renderizarRodape(); ?>
</body>
</html>
