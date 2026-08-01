<?php
session_start();

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
    <title>Fazer Login</title>s
    <link rel="stylesheet" href="../estilos/estilos.css?v=9">
</head>
<body>
    <header>
        <picture id="logo-img">
            <img src="../imagens/apoiomais.png" alt="Logo" class="logo-header">
        </picture>
        <nav id="options-header">
            <ul>
                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="conteudo_educativo.php">Conteudo Educativo</a></li>
                <li><a href="postagens.php">Postagens</a></li>
                <li><a href="ongs.php">ONGs</a></li>
                <li><a href="contato.php">Contato</a></li>
                <?php
                    if (!isset($_SESSION['usuario']) && (!isset($_SESSION['ong']))) {
                        echo '<li><a href="inicio.php">Cadastre-se</a></li>';
                        echo '<li><a href="login.php" class="active">Entrar</a></li>';
                    } else {
                        if (isset($_SESSION['ong']) && (!isset($_SESSION['usuario']))) {
                            echo '<li><a href="post_ongs.php">Criar Post</a></li>';
                        }
                        echo '<li><a href="logout.php" class="logout">Sair &rarr;</a></li>';
                    }
                ?>
            </ul>
        </nav>
    </header>
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
    <footer>
        <p>Apoio+ O guia colaborativo para jovens egressos de abrigos institucionais</p>
    </footer>
    <script src="header-menu.js"></script>
</body>
</html>
