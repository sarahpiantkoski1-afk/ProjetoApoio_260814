<?php
session_start();

if (isset($_SESSION['usuario'] )||( isset($_SESSION['ong']))) {
    header("Location: ../inicio.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ongs</title>
    <link rel="stylesheet" href="../../estilos/estilos.css?v=9">
    <link rel="stylesheet" href="../../estilos/forms.css?v=4">
</head>
<body>
    <header>
        <picture id="logo-img">
            <img src="../../imagens/apoiomais.png" alt="Logo" class="logo-header">
        </picture>

        <nav id="options-header">
            <ul>
                <li><a href="../inicio.php">Inicio</a></li>
                <li><a href="../conteudo_educativo.php">Conteúdo Educativo</a></li>
                <li><a href="../postagens.php">Postagens</a></li>
                <li><a href="../contato.php">Contato</a></li>
                <?php
                    if (!isset($_SESSION['usuario']) && (!isset($_SESSION['ong']))) {
                        echo '<li><a href="../cadastro.php">Cadastre-se</a></li>';
                        echo '<li><a href="../login.php" class="active">Entrar</a></li>';
                    } else {
                        if (isset($_SESSION['ong']) && (!isset($_SESSION['usuario']))) {
                            echo '<li><a href="../post_ongs.php">Criar Post</a></li>';
                        }
                        echo '<li><a href="../logout.php" class="logout">Sair &rarr;</a></li>';
                    }
                ?>
            </ul>
        </nav>
    </header>

    <main>
        <section class="form-container">

            <article class="form-up">        
                <h2>Ongs</h2>
            </article>

            <article class="form-down">
                <form action="../../../backend/funcionalidades/login/verifica_login_ongs.php" method="post">
                    <input type="hidden" name="tipo_usuario" value="ong">

                    <label for="cnpj">CNPJ</label>
                    <input 
                        type="text" 
                        name="cnpj" 
                        id="cnpj" 
                        minlength="14" 
                        maxlength="18" 
                        placeholder="00.000.000/0000-00"
                        required
                    >

                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        maxlength="100"
                        placeholder="seu@email.com" 
                        required
                    >

                    <label for="senha">Senha</label>
                    <input 
                        type="password" 
                        name="senha" 
                        id="senha" 
                        minlength="8"
                        maxlength="255"
                        placeholder="******" 
                        required
                    >

                    <input type="submit" value="Entrar" class="botao-executar">
                </form>
            </article>
        </section>
    </main>

    <footer>
        <p>Apoio+  O guia colaborativo para jovens egressos de abrigos institucionais
        </p>
    </footer>
    <script src="../header-menu.js"></script>
</body>
</html>
