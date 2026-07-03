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
    <title>Cadastro - Ongs</title>
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
                <li><a href="../conteudo_educativo.php">Conteudo Educativo</a></li>
                <li><a href="../postagens.php">Postagens</a></li>
                <li><a href="../contato.php">Contato</a></li>
                <?php
                    if (!isset($_SESSION['usuario']) && (!isset($_SESSION['ong']))) {
                        echo '<li><a href="../cadastro.php" class="active">Cadastre-se</a></li>';
                        echo '<li><a href="../login.php">Entrar</a></li>';
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

                <h2>Instituição Social</h2>
            </article>
            <article class="form-down">

                    
                <form action="../../../backend/funcionalidades/login/cadastra_ongs.php" method="post">
                    
                    <label for="nome">Nome</label>
                    <input
                        type="text"
                        name="nome"
                        id="nome"
                        minlength="3"
                        maxlength="100"
                        placeholder="Nome"
                        required
                    >
                    
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

                    <label for="confirmar_senha">Confirme a senha</label>
                    <input
                        type="password"
                        name="confirmar_senha"
                        id="confirmar_senha"
                        minlength="8"
                        maxlength="255"
                        placeholder="******"
                        required
                    >

                    <input type="submit" value="Cadastrar" class="botao-executar">
                </form>
            </article>
        </section>
    </main>
    <footer>
        <p>Apoio+ - O guia colaborativo para jovens egressos de abrigos institucionais
        </p>
    </footer>
    <script src="../header-menu.js"></script>
</body>
</html>
