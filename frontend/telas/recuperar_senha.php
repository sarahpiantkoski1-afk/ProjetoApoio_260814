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
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="../estilos/estilos.css?v=10">
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
                        echo '<li><a href="cadastro.php">Cadastre-se</a></li>';
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

    <main class="recuperar-senha-main">
        <section class="recuperar-senha-card" aria-labelledby="recuperar-senha-titulo">
            <h2 id="recuperar-senha-titulo">Esqueceu a senha?</h2>
            <p>Redefina a senha em duas etapas</p>

            <form action="#" method="post" class="recuperar-senha-form">
                <input
                    type="email"
                    name="email"
                    id="email"
                    maxlength="100"
                    placeholder="E-mail"
                    required
                >

                <button type="submit">Enviar</button>
            </form>

            <a href="login.php" class="recuperar-senha-voltar">Voltar para o login</a>
        </section>
    </main>

    <footer>
        <p>Apoio+ O guia colaborativo para jovens egressos de abrigos institucionais</p>
    </footer>
    <script src="header-menu.js"></script>
</body>
</html>
