<?php
session_start();

if (!isset($_SESSION['ong'])) {
    header("Location: inicio.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criação de Post - Apoio+</title>
    <link rel="stylesheet" href="../estilos/estilos.css?v=9">
    <link rel="stylesheet" href="../estilos/forms.css?v=5">
    <link rel="shortcut icon" href="../imagens/favicon.png" type="image/x-icon">
</head>
<body>
    <header>
        <picture id="logo-img">
            <img src="../imagens/apoiomais.png" alt="Logo" class="logo-header">
        </picture>
        <nav id="options-header">
            <ul>
                <li><a href="inicio.php" class='active'>Inicio</a></li>
                <li><a href="conteudo_educativo.php">Conteúdo Educativo</a></li>
                <li><a href="postagens.php">Postagens</a></li>
                <li><a href="ongs.php">ONGs</a></li>
                <li><a href="contato.php">Contato</a></li>
                <?php
                    if (!isset($_SESSION['usuario']) && (!isset($_SESSION['ong']))) {
                        echo '<li><a href="cadastro.php">Cadastre-se</a></li>';
                        echo '<li><a href="login.php">Entrar</a></li>';
                    } else {
                        if (isset($_SESSION['ong']) && (!isset($_SESSION['usuario']))) {
                            echo '<li><a href="post_ongs.php" class="active">Criar Post</a></li>';
                        }
                        echo '<li><a href="logout.php" class="logout">Sair &rarr;</a></li>';
                    }
                ?>
            </ul>
        </nav>
    </header>
    <main>
        <section class="form-container">
            <article class="form-up">
                <h2>Criando um novo post</h2>
            </article>
            <article class="form-down">

                
                <form id="form-post" >
                    
                
                    <label for="titulo">Título</label>
                    <input
                        type="text"
                        name="titulo"
                        id="titulo"
                        minlength="3"
                        maxlength="200"
                        placeholder="Vaga de emprego..."
                        required    
                    >

                    <label for="link">Link</label>
                    <input
                        type="text"
                        name="link"
                        id="link"
                        minlength="5"
                        maxlength="255"
                        placeholder="https://www.meublog.com"
                        required    
                    >

                    <label for="descricao">Descrição</label>
                    <textarea
                        name="descricao"
                        id="descricao"
                        cols="30"
                        rows="10"
                        placeholder="Escreva aqui..."
                        required
                    ></textarea>

                    <fieldset class="categoria-fieldset">
                        <legend>Categorias</legend>
                        <p class="categoria-ajuda">Selecione ate duas categorias.</p>
                        <div class="categoria-opcoes">
                            <label class="categoria-chip">
                                <input type="checkbox" name="categorias[]" value="curso">
                                <span>Curso</span>
                            </label>
                            <label class="categoria-chip">
                                <input type="checkbox" name="categorias[]" value="moradia">
                                <span>Moradia</span>
                            </label>
                            <label class="categoria-chip">
                                <input type="checkbox" name="categorias[]" value="apoio-psicologico">
                                <span>Apoio psicol&oacute;gico</span>
                            </label>
                            <label class="categoria-chip">
                                <input type="checkbox" name="categorias[]" value="emprego">
                                <span>Emprego</span>
                            </label>
                            <label class="categoria-chip">
                                <input type="checkbox" name="categorias[]" value="acolhimento">
                                <span>Acolhimento</span>
                            </label>
                        </div>
                    </fieldset>

                    <input type="submit" value="Postar" class="botao-executar">
                </form>
            </article>
        </section>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const limiteCategorias = 2;
    const categorias = document.querySelectorAll('input[name="categorias[]"]');

    categorias.forEach((categoria) => {
        categoria.addEventListener("change", () => {
            const selecionadas = document.querySelectorAll('input[name="categorias[]"]:checked');

            if (selecionadas.length > limiteCategorias) {
                categoria.checked = false;
                alert("Selecione no maximo duas categorias para a vaga.");
            }
        });
    });

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch("../../backend/funcionalidades/criar_post.php", { // ajuste caminho conforme seu projeto
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "ok") {
                alert(data.message);
                window.location.href = "inicio.php";
            } else {
                alert("Erro: " + data.message);
            }
        })
        .catch(err => {
            console.error("Erro ao criar post:", err);
            alert("Ocorreu um erro ao criar o post. Veja o console.");
        });
    });
});

</script>


    </main>
    <footer>
        <p>Apoio+  O guia colaborativo para jovens egressos de abrigos institucionais
        </p>
    </footer>
    <script src="header-menu.js"></script>
</body>
</html>
