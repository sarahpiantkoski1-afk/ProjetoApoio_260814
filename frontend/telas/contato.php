<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_contato'])) {
    header('Content-Type: application/json; charset=UTF-8');

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $ong = trim($_POST['ong'] ?? '');
    $assunto = trim($_POST['assunto'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');

    if ($nome && filter_var($email, FILTER_VALIDATE_EMAIL) && $assunto && $mensagem) {
        $destinatario = 'apoiomais.oficial@gmail.com';
        $assuntoEmail = 'Contato Apoio+ - ' . $assunto;
        $conteudoEmail = "Nome: {$nome}\n";
        $conteudoEmail .= "E-mail para resposta: {$email}\n";
        $conteudoEmail .= "ONG: " . ($ong ?: 'Nao informado') . "\n";
        $conteudoEmail .= "Assunto: {$assunto}\n\n";
        $conteudoEmail .= "Mensagem:\n{$mensagem}\n";
        $headers = "From: Apoio+ <no-reply@apoio.local>\r\n";
        $headers .= "Reply-To: {$email}\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Envio simulado para testes locais no XAMPP, onde mail() costuma falhar sem SMTP.
        // Para ativar o envio real depois, troque a linha abaixo por:
        // $envioOk = @mail($destinatario, $assuntoEmail, $conteudoEmail, $headers);
        $envioOk = true;

        echo json_encode([
            'status' => $envioOk ? 'ok' : 'erro',
            'message' => $envioOk
                ? 'Obrigado! Sua mensagem foi enviada com sucesso e logo entraremos em contato. 💜'
                : 'Nao foi possivel enviar a mensagem agora. Tente novamente mais tarde.'
        ]);
        exit();
    }

    echo json_encode([
        'status' => 'erro',
        'message' => 'Preencha os campos obrigatorios corretamente.'
    ]);
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - Apoio+</title>
    <link rel="stylesheet" href="../estilos/estilos.css?v=15">
    <link rel="stylesheet" href="../estilos/forms.css?v=5">
</head>
<body>
    <header>
        <picture id="logo-img">
            <img src="../imagens/apoiomais.png" alt="Logo" class="logo-header">
        </picture>
        <nav id="options-header">
            <ul>
                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="conteudo_educativo.php">Conteúdo Educativo</a></li>
                <li><a href="postagens.php">Postagens</a></li>
                <li><a href="ongs.php">ONGs</a></li>
                <li><a href="contato.php" class="active">Contato</a></li>
                <?php
                    if (!isset($_SESSION['usuario']) && (!isset($_SESSION['ong']))) {
                        echo '<li><a href="cadastro.php">Cadastre-se</a></li>';
                        echo '<li><a href="login.php">Entrar</a></li>';
                    } else {
                        if (isset($_SESSION['ong']) && (!isset($_SESSION['usuario']))) {
                            echo '<li><a href="post_ongs.php">Criar Post</a></li>';
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
                <h2>Nos envie uma mensagem!</h2>
            </article>
            <article class="form-down">
                <section class="contact">
                    <picture class="infos-contact">
                        <p class="frase-impacto-contato">Acolhimento &eacute; a nossa forma de mudar o mundo.</p>
                        <h4>Email</h4>
                        <div class="email-item">
                            <img src="../imagens/email.jpg" alt="Email" class="icone-contact">
                            <p class="info-name">apoiomais.oficial@gmail.com</p>
                        </div>

                        <a href="https://www.instagram.com/apoiomais.oficial/" target="_blank" rel="noopener noreferrer">
                            <img src="../imagens/logo-instagram.avif" alt="Instagram" class="icone-contact">
                            <p class="info-name">apoiomais.oficial</p>
                        </a>
                        <button class="abrir-modal-contato" type="button">Nos envie uma mensagem &#128070;</button>
                    </picture>
                </section>
            </article>
        </section>

        <div class="modal-contato" id="modal-contato" hidden>
            <div class="modal-contato-fundo" data-fechar-modal></div>
            <section class="modal-contato-caixa" role="dialog" aria-modal="true" aria-labelledby="modal-contato-titulo">
                <button class="fechar-modal-contato" type="button" aria-label="Fechar" data-fechar-modal>&times;</button>
                <h2 id="modal-contato-titulo">Fale conosco</h2>
                <p>Caso queira enviar alguma d&uacute;vida ou sugest&atilde;o, preencha os campos abaixo:</p>

                <form class="formulario-modal-contato" method="post" action="contato.php">
                    <input type="hidden" name="enviar_contato" value="1">

                    <label for="nome-contato">Nome</label>
                    <input type="text" name="nome" id="nome-contato" placeholder="Nome" required>

                    <label for="email-contato">E-mail para receber a resposta</label>
                    <input type="email" name="email" id="email-contato" placeholder="E-mail para receber a resposta" required>

                    <label for="ong-contato">ONGs (opcional)</label>
                    <input type="text" name="ong" id="ong-contato" placeholder="ONGs (opcional)">

                    <label for="assunto-contato">Assunto</label>
                    <input type="text" name="assunto" id="assunto-contato" placeholder="Assunto" required>

                    <label for="mensagem-contato">Mensagem</label>
                    <textarea name="mensagem" id="mensagem-contato" rows="5" placeholder="Mensagem" required></textarea>

                    <button class="botao-enviar-contato" type="submit">ENVIAR &rarr;</button>
                </form>
            </section>
        </div>

        <section id="sobre">
            <h2>Sobre o Apoio+</h2>
            <div class="card">
                <p>O Apoio + &eacute; uma plataforma digital criada para oferecer suporte pr&aacute;tico e emocional a jovens que deixam o acolhimento institucional e precisam iniciar sua vida adulta de forma aut&ocirc;noma. No Brasil, ao completar 18 anos, muitos jovens s&atilde;o obrigados a sair dos abrigos, mesmo sem contar com rede de apoio, emprego ou moradia. Essa transi&ccedil;&atilde;o, marcada por desafios como desemprego, risco de rua e vulnerabilidade social, exige orienta&ccedil;&atilde;o, acolhimento e oportunidades concretas para que possam construir um futuro digno e independente.</p>
                <p>Pensando nisso, o Apoio + re&uacute;ne em um s&oacute; espa&ccedil;o informa&ccedil;&otilde;es sobre direitos, mercado de trabalho, moradia e sa&uacute;de, al&eacute;m de conte&uacute;dos educativos e relatos de experi&ecirc;ncias que fortalecem o apoio emocional. A plataforma tamb&eacute;m conecta os jovens a profissionais da psicologia e assist&ecirc;ncia social, divulga cursos gratuitos e programas de inclus&atilde;o produtiva, e promove um ambiente interativo que incentiva a autonomia, o protagonismo juvenil e a constru&ccedil;&atilde;o de redes de apoio. Nosso objetivo &eacute; garantir que cada jovem tenha acesso &agrave;s ferramentas necess&aacute;rias para iniciar sua vida adulta com dignidade, confian&ccedil;a e esperan&ccedil;a.</p>
            </div>
        </section>
    </main>

    <footer>
        <p>Apoio+  O guia colaborativo para jovens egressos de abrigos institucionais</p>
    </footer>
    <script src="header-menu.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("modal-contato");
            const abrirModal = document.querySelector(".abrir-modal-contato");
            const fecharModal = document.querySelectorAll("[data-fechar-modal]");
            const formularioContato = document.querySelector(".formulario-modal-contato");

            if (!modal || !abrirModal) {
                return;
            }

            const abrir = () => {
                modal.hidden = false;
                document.body.classList.add("modal-contato-aberto");
            };

            const fechar = () => {
                modal.hidden = true;
                document.body.classList.remove("modal-contato-aberto");
            };

            abrirModal.addEventListener("click", abrir);
            fecharModal.forEach((elemento) => elemento.addEventListener("click", fechar));

            if (formularioContato) {
                formularioContato.addEventListener("submit", (event) => {
                    event.preventDefault();

                    const dados = new FormData(formularioContato);

                    fetch("contato.php", {
                        method: "POST",
                        body: dados
                    })
                    .then((resposta) => resposta.json())
                    .then((data) => {
                        if (data.status === "ok") {
                            formularioContato.reset();
                            fechar();
                            alert(data.message);
                            return;
                        }

                        alert(data.message || "Nao foi possivel enviar a mensagem agora.");
                    })
                    .catch(() => {
                        alert("Nao foi possivel enviar a mensagem agora. Tente novamente mais tarde.");
                    });
                });
            }

            document.addEventListener("keydown", (event) => {
                if (event.key === "Escape" && !modal.hidden) {
                    fechar();
                }
            });
        });
    </script>
</body>
</html>
