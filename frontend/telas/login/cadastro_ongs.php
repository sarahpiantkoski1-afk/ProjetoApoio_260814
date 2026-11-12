<?php
session_start();
require_once(__DIR__ . '/../../componentes/layout.php');

if (isset($_SESSION['usuario'] )||( isset($_SESSION['ong']))) {
    header("Location: ../inicio.php");
    exit();
}

$mensagensCnpj = [
    'invalido' => 'Adicione um CNPJ válido.',
    'inativo' => 'Adicione um CNPJ com situação cadastral ativa.',
    'indisponivel' => 'Não foi possível validar o CNPJ agora. Tente novamente mais tarde.',
];
$erroCnpj = $mensagensCnpj[$_GET['erro_cnpj'] ?? ''] ?? '';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php renderizarFavicon(1); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Ongs</title>
    <link rel="stylesheet" href="<?= caminhoEstilo('estilos.css', 1) ?>">
    <link rel="stylesheet" href="<?= caminhoEstilo('forms.css', 1) ?>">
</head>
<body>
    <?php renderizarCabecalho('cadastro', 1); ?>
    <main>
        <section class="form-container">
            <article class="form-up">

                <h2 class="titulo-tela">Instituição Social</h2>
            </article>
            <article class="form-down">

                    
                <form action="../../../backend/funcionalidades/login/cadastra_ongs.php" method="post" autocomplete="off">
                    
                    <label for="nome">Nome</label>
                    <input
                        type="text"
                        name="nome"
                        id="nome"
                        minlength="3"
                        maxlength="100"
                        placeholder="Nome"
                        autocomplete="organization"
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
                        autocomplete="off"
                        aria-describedby="erro-cnpj"
                        required
                    >
                    <?php if ($erroCnpj !== '') { ?>
                        <div id="erro-cnpj" class="form-feedback form-feedback-erro" role="alert"><?= htmlspecialchars($erroCnpj, ENT_QUOTES, 'UTF-8') ?></div>
                    <?php } ?>

                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        maxlength="100"
                        placeholder="seu@email.com" 
                        autocomplete="email"
                        required
                    >

                    <label for="senha">Senha</label>
                    <div class="campo-senha">
                        <input
                            type="password"
                            name="senha"
                            id="senha"
                            minlength="8"
                            maxlength="255"
                            placeholder="******"
                            autocomplete="new-password"
                            required
                        >
                        <button class="alternar-senha" type="button" data-password-target="senha" aria-label="Mostrar senha" aria-pressed="false" title="Mostrar senha">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path><circle cx="12" cy="12" r="2.5"></circle></svg>
                        </button>
                    </div>

                    <label for="confirmar_senha">Confirme a senha</label>
                    <div class="campo-senha">
                        <input
                            type="password"
                            name="confirmar_senha"
                            id="confirmar_senha"
                            minlength="8"
                            maxlength="255"
                            placeholder="******"
                            autocomplete="new-password"
                            required
                        >
                        <button class="alternar-senha" type="button" data-password-target="confirmar_senha" aria-label="Mostrar senha" aria-pressed="false" title="Mostrar senha">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path><circle cx="12" cy="12" r="2.5"></circle></svg>
                        </button>
                    </div>

                    <input type="submit" value="Cadastrar" class="botao-executar">
                </form>
            </article>
        </section>
    </main>
    <?php renderizarRodape(1); ?>
    <script src="../password-toggle.js?v=<?= APOIO_ASSET_VERSION ?>"></script>
</body>
</html>
