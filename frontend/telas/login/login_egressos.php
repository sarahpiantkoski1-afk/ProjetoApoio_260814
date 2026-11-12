<?php
session_start();
require_once(__DIR__ . '/../../componentes/layout.php');

if (isset($_SESSION['usuario'] )||( isset($_SESSION['ong']))) {
    header("Location: ../inicio.php");
    exit();
}

$erroLogin = isset($_GET['erro'])
    ? 'E-mail ou senha incorretos. Verifique os dados e tente novamente.'
    : '';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php renderizarFavicon(1); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jovem Egresso</title>
    <link rel="stylesheet" href="<?= caminhoEstilo('estilos.css', 1) ?>">
    <link rel="stylesheet" href="<?= caminhoEstilo('forms.css', 1) ?>">
</head>
<body>
    <?php renderizarCabecalho('login', 1); ?>
    <main>
        <section class="form-container">
            <article class="form-up">        
                <h2 class="titulo-tela">Jovem Egresso</h2>
            </article>
            <article class="form-down">
                <form action="../../../backend/funcionalidades/login/verifica_login_egressos.php" method="post" autocomplete="off">

                    <?php if ($erroLogin !== '') { ?>
                        <div class="form-feedback form-feedback-erro" role="alert"><?= htmlspecialchars($erroLogin, ENT_QUOTES, 'UTF-8') ?></div>
                    <?php } ?>

                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        maxlength="100"
                        placeholder="seu@email.com" 
                        autocomplete="off"
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
                            autocomplete="off"
                            required
                        >
                        <button class="alternar-senha" type="button" data-password-target="senha" aria-label="Mostrar senha" aria-pressed="false" title="Mostrar senha">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path><circle cx="12" cy="12" r="2.5"></circle></svg>
                        </button>
                    </div>

                    <input type="submit" value="Entrar" class="botao-executar">

                    <div class="login-links">
                        <a href="../recuperar_senha.php">Esqueceu sua senha?</a>
                        <a href="cadastro_egressos.php">Ainda n&atilde;o tem conta? Cadastre-se</a>
                    </div>
                </form>
            </article>
        </section>
    </main>
    <?php renderizarRodape(1); ?>
    <script src="../password-toggle.js?v=<?= APOIO_ASSET_VERSION ?>"></script>
</body>
</html>
