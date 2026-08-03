<?php
session_start();
require_once(__DIR__ . '/../../componentes/layout.php');

if (isset($_SESSION['usuario'] )||( isset($_SESSION['ong']))) {
    header("Location: ../inicio.php");
    exit();
}

$erroLogin = isset($_GET['erro'])
    ? 'CNPJ, e-mail ou senha incorretos. Verifique os dados e tente novamente.'
    : '';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ongs</title>
    <link rel="stylesheet" href="<?= caminhoEstilo('estilos.css', 1) ?>">
    <link rel="stylesheet" href="<?= caminhoEstilo('forms.css', 1) ?>">
</head>
<body>
    <?php renderizarCabecalho('login', 1); ?>

    <main>
        <section class="form-container">

            <article class="form-up">        
                <h2>Ongs</h2>
            </article>

            <article class="form-down">
                <form action="../../../backend/funcionalidades/login/verifica_login_ongs.php" method="post">
                    <input type="hidden" name="tipo_usuario" value="ong">

                    <?php if ($erroLogin !== '') { ?>
                        <div class="form-feedback form-feedback-erro" role="alert"><?= htmlspecialchars($erroLogin, ENT_QUOTES, 'UTF-8') ?></div>
                    <?php } ?>

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

                    <div class="login-links">
                        <a href="../recuperar_senha.php">Esqueceu sua senha?</a>
                        <a href="cadastro_ongs.php">Ainda n&atilde;o tem conta? Cadastre-se</a>
                    </div>
                </form>
            </article>
        </section>
    </main>

    <?php renderizarRodape(1); ?>
</body>
</html>
