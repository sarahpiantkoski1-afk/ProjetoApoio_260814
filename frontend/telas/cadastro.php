<?php
session_start();

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
    <link rel="stylesheet" href="../estilos/estilos.css?v=7">
    <link rel="stylesheet" href="../estilos/forms.css?v=4">
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
                <li><a href="contato.php">Contato</a></li>
                <?php
                    if (!isset($_SESSION['usuario']) && (!isset($_SESSION['ong']))) {
                        echo '<li><a href="cadastro.php" class="active">Cadastre-se</a></li>';
                        echo '<li><a href="login.php">Entrar</a></li>';
                    } else {
                        if (isset($_SESSION['ong']) && (!isset($_SESSION['usuario']))) {
                            echo '<li><a href="post_ongs.php">Criar Post</a></li>';
                        }
                        echo '<li><a href="logout.php" class="logout">LogOut</a></li>';
                    }
                ?>
            </ul>
        </nav>
    </header>
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
    <footer>
        <p>Apoio+ - O guia colaborativo para jovens egressos de abrigos institucionais
        </p>
    </footer>
    <script src="header-menu.js"></script>
</body>
</html>
