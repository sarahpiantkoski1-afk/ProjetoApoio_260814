<?php
session_start();
require_once(__DIR__ . '/../componentes/layout.php');

if (!isset($_SESSION['ong']) || !isset($_SESSION['ong_id']) || isset($_SESSION['usuario'])) {
    header('Location: inicio.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php renderizarFavicon(); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar conteúdo - Apoio+</title>
    <link rel="stylesheet" href="<?= caminhoEstilo('estilos.css') ?>">
    <link rel="stylesheet" href="<?= caminhoEstilo('forms.css') ?>">
</head>
<body>
    <?php renderizarCabecalho('adicionar_conteudo'); ?>
    <main>
        <section class="form-container">
            <article class="form-up">
                <h2 class="titulo-tela">Adicionando conteúdo educativo</h2>
            </article>
            <article class="form-down">
                <form id="form-conteudo">
                    <label for="titulo">Título do vídeo</label>
                    <input type="text" name="titulo" id="titulo" minlength="3" maxlength="200" placeholder="Ex.: Dicas de autocuidado" required>

                    <label for="youtube_url">Link do YouTube</label>
                    <input type="url" name="youtube_url" id="youtube_url" maxlength="500" placeholder="https://www.youtube.com/watch?v=..." required>

                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" id="descricao" maxlength="1200" rows="6" placeholder="Conte brevemente o que o jovem vai encontrar neste vídeo..." required></textarea>

                    <fieldset class="categoria-fieldset">
                        <legend>Nichos</legend>
                        <p class="categoria-ajuda">Selecione até duas categorias.</p>
                        <div class="categoria-opcoes">
                            <label class="categoria-chip"><input type="checkbox" name="nichos[]" value="direitos"><span>Direitos</span></label>
                            <label class="categoria-chip"><input type="checkbox" name="nichos[]" value="autoestima"><span>Autoestima</span></label>
                            <label class="categoria-chip"><input type="checkbox" name="nichos[]" value="acolhimento"><span>Acolhimento</span></label>
                            <label class="categoria-chip"><input type="checkbox" name="nichos[]" value="autocuidado"><span>Autocuidado</span></label>
                            <label class="categoria-chip"><input type="checkbox" name="nichos[]" value="dicas"><span>Dicas</span></label>
                        </div>
                    </fieldset>
                    <input type="submit" value="Adicionar conteúdo" class="botao-executar">
                </form>
            </article>
        </section>
    </main>
    <?php renderizarRodape(); ?>
    <script src="adicionar-conteudo.js?v=1"></script>
</body>
</html>
