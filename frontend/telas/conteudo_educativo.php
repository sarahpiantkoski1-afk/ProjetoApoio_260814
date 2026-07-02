<?php
session_start();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conteudo Educativo - Apoio+</title>
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
                <li><a href="conteudo_educativo.php" class="active">Conteudo Educativo</a></li>
                <li><a href="postagens.php">Postagens</a></li>
                <li><a href="contato.php">Contato</a></li>
                <?php
                    if (!isset($_SESSION['usuario']) && (!isset($_SESSION['ong']))) {
                        echo '<li><a href="cadastro.php">Cadastre-se</a></li>';
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
        <section class="form-container">
            <article class="educativo">
                <h2 class="educativo-titulo">Conteudos educativos</h2>
                <h4 class="educativo-subtitulo">Encontre aqui videos já disponiveis na internet de cuidado e orientações úteis!</h4>
            </article>
            <article>
                
               
                <ul class="educativo-lista">
                    <li><a href="https://youtu.be/dCwOLOwmtkE?si=2xFWJX1Cj80DZiEi" target="_blank" rel="noopener noreferrer">Instruções de um psicólogo sobre autocuidado</a></li>
                    <li><a href="https://youtu.be/DE8U1LpMffY?si=Lvbu42YTjlMm-jNa" target="_blank" rel="noopener noreferrer">Relato e orientação a jovens que recém completaram 18 anos</a></li>
                    <li><a href="https://youtu.be/YzDqQwZws-w?si=G8066V5qiW-ZbDE3" target="_blank" rel="noopener noreferrer">Desafios, direitos e apoio social</a></li>
                    <li><a href="https://www.youtube.com/live/yVH7k70RElY?si=TgDn7bX0-zXADOL2" target="_blank" rel="noopener noreferrer">Autonomia e juventude</a></li>
                    <li><a href="https://youtu.be/TIDPCiWevRw?si=A90mYZsu7g8mIgWc" target="_blank" rel="noopener noreferrer">Quando o abrigo acaba</a></li>
                    <li><a href="https://www.youtube.com/live/hIEd8nR8dPE?si=zkkkb16bJYkiIWn2" target="_blank" rel="noopener noreferrer">Histórias reais, jovens egressos compartilham suas experiências</a></li>
                    <li><a href="https://youtu.be/fdjIvwSb_AY?si=D8Xzxcnv8pHAeajo" target="_blank" rel="noopener noreferrer">Passo a passo: como fazer a barba</a></li>
                    <li><a href="https://youtu.be/bRe4HI4fwJ0?si=Dtv-LKVb2xyo6LbK" target="_blank" rel="noopener noreferrer">direitos de crianças e adolescentes</a></li>
                    <li><a href="https://youtu.be/IYvd_gy5FjM?si=NJ2TcvPmP0TfD9Fh" target="_blank" rel="noopener noreferrer">A vida depois do acolhimentoA vida depois do acolhimento</a></li>
                    <li><a href="https://youtu.be/tYiD3FJRXgM?si=r4a3cBUEiJsRMLIS" target="_blank" rel="noopener noreferrer">Como praticar o autocuidado no dia a dia</a></li>
                    <li><a href="https://youtu.be/YBD9lxmE30g?si=SEvK0Hqz5LuGpcnq" target="_blank" rel="noopener noreferrer">Direitos humanos e proteção a jovens em acolhimento</a></li>
                    <li><a href="https://youtu.be/94QP7YsHwto?si=15raaUf9nosRumZ3/" target="_blank" rel="noopener noreferrer">Transição da vida institucional para a vida adulta </a></li>
                    <li><a href="https://youtu.be/3nSfnNmYJaY?si=PNmF1mStYVpvATma" target="_blank" rel="noopener noreferrer">Dicas financeiras</a></li>
                    <li><a href="https://youtu.be/TDqhTRIhieM?si=IfY4f9aqzl2pgoEx" target="_blank" rel="noopener noreferrer">Historias reais</a></li>
                    <li><a href="https://youtu.be/Vwj9o_Wpqp8?si=wNoqgeqqT169ebm6" target="_blank" rel="noopener noreferrer">Dicas para jovens adultos</a></li>
                </ul>
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
