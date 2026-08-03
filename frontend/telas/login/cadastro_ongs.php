<?php
session_start();
require_once(__DIR__ . '/../../componentes/layout.php');

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
    <link rel="stylesheet" href="<?= caminhoEstilo('estilos.css', 1) ?>">
    <link rel="stylesheet" href="<?= caminhoEstilo('forms.css', 1) ?>">
</head>
<body>
    <?php renderizarCabecalho('cadastro', 1); ?>
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
    <?php renderizarRodape(1); ?>
</body>
</html>
