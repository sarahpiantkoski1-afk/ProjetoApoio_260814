<?php
session_start();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - Apoio+</title>
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
                <li><a href="conteudo_educativo.php">Conteudo Educativo</a></li>
                <li><a href="postagens.php">Postagens</a></li>
                <li><a href="contato.php" class="active">Contato</a></li>
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
            <article class="form-up">
                <h2>Nos envie uma mensagem!</h2>
            </article>
            <article class="form-down">
                <section class="contact">
                    <picture class="infos-contact">
                        <h4>Email</h4>
                        <div class="email-item">
                            <img src="../imagens/email.jpg" alt="Email" class="icone-contact">
                            <p class="info-name">l4is00aguirre@gmail.com</p>
                        </div>

                        <div class="email-item">
                            <img src="../imagens/email.jpg" alt="Email" class="icone-contact">
                            <p class="info-name">sarahpiantkoski1@gmail.com</p>
                        </div>

                        <a href="https://www.instagram.com/melaisss_/" target="_blank" rel="noopener noreferrer">
                            <img src="../imagens/logo-instagram.avif" alt="Icone" class="icone-contact">
                            <p class="info-name">La&iacute;s Aguirre</p>
                        </a>
                        <a href="https://www.instagram.com/sah_lorenaa/" target="_blank" rel="noopener noreferrer">
                            <img src="../imagens/logo-instagram.avif" alt="Icone" class="icone-contact">
                            <p class="info-name">Sarah Lorena</p>
                        </a>
                    </picture>
                </section>
            </article>
        </section>

        <section id="sobre">
            <h2>Sobre o Apoio+</h2>
            <div class="card">
                <p>O Apoio + &eacute; uma plataforma digital criada para oferecer suporte pr&aacute;tico e emocional a jovens que deixam o acolhimento institucional e precisam iniciar sua vida adulta de forma aut&ocirc;noma. No Brasil, ao completar 18 anos, muitos jovens s&atilde;o obrigados a sair dos abrigos, mesmo sem contar com rede de apoio, emprego ou moradia. Essa transi&ccedil;&atilde;o, marcada por desafios como desemprego, risco de rua e vulnerabilidade social, exige orienta&ccedil;&atilde;o, acolhimento e oportunidades concretas para que possam construir um futuro digno e independente.</p>
                <p>Pensando nisso, o Apoio + re&uacute;ne em um s&oacute; espa&ccedil;o informa&ccedil;&otilde;es sobre direitos, mercado de trabalho, moradia e sa&uacute;de, al&eacute;m de conte&uacute;dos educativos e relatos de experi&ecirc;ncias que fortalecem o apoio emocional. A plataforma tamb&eacute;m conecta os jovens a profissionais da psicologia e assist&ecirc;ncia social, divulga cursos gratuitos e programas de inclus&atilde;o produtiva, e promove um ambiente interativo que incentiva a autonomia, o protagonismo juvenil e a constru&ccedil;&atilde;o de redes de apoio. Nosso objetivo &eacute; garantir que cada jovem tenha acesso &agrave;s ferramentas necess&aacute;rias para iniciar sua vida adulta com dignidade, confian&ccedil;a e esperan&ccedil;a.</p>
            </div>
        </section>
    </main>

    <footer>
        <p>Apoio+ - O guia colaborativo para jovens egressos de abrigos institucionais</p>
    </footer>
    <script src="header-menu.js"></script>
</body>
</html>
