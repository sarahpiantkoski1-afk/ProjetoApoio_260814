<?php
session_start();
require_once('../../backend/funcionalidades/mailer.php');
require_once(__DIR__ . '/../componentes/layout.php');

if (!isset($_SESSION['csrf_contato'])) {
    $_SESSION['csrf_contato'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_contato'])) {
    header('Content-Type: application/json; charset=UTF-8');

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $ong = trim($_POST['ong'] ?? '');
    $assunto = trim($_POST['assunto'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');
    $csrf = $_POST['csrf_token'] ?? '';

    $csrfValido = is_string($csrf) && hash_equals($_SESSION['csrf_contato'], $csrf);
    $camposValidos = $nome !== ''
        && mb_strlen($nome) <= 100
        && filter_var($email, FILTER_VALIDATE_EMAIL)
        && mb_strlen($email) <= 150
        && mb_strlen($ong) <= 150
        && $assunto !== ''
        && mb_strlen($assunto) <= 150
        && $mensagem !== ''
        && mb_strlen($mensagem) <= 5000;

    if (!$csrfValido || !$camposValidos) {
        http_response_code(422);
        echo json_encode([
            'status' => 'erro',
            'message' => 'Preencha os campos obrigatorios corretamente e tente novamente.'
        ]);
        exit();
    }

    $escapar = static fn (string $valor): string => htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
    $ongExibida = $ong !== '' ? $ong : 'Não informado';
    $html = '<h2>Nova mensagem recebida pelo Apoio+</h2>'
        . '<p><strong>Nome:</strong> ' . $escapar($nome) . '</p>'
        . '<p><strong>E-mail para resposta:</strong> ' . $escapar($email) . '</p>'
        . '<p><strong>ONG:</strong> ' . $escapar($ongExibida) . '</p>'
        . '<p><strong>Assunto:</strong> ' . $escapar($assunto) . '</p>'
        . '<p><strong>Mensagem:</strong><br>' . nl2br($escapar($mensagem)) . '</p>';
    $texto = "Nova mensagem recebida pelo Apoio+\n\n"
        . "Nome: {$nome}\n"
        . "E-mail para resposta: {$email}\n"
        . "ONG: {$ongExibida}\n"
        . "Assunto: {$assunto}\n\n"
        . "Mensagem:\n{$mensagem}\n";

    try {
        enviarEmail(
            'apoiomais.oficial@gmail.com',
            'Apoio+',
            'Contato Apoio+ - ' . $assunto,
            $html,
            $texto,
            ['email' => $email, 'nome' => $nome]
        );

        $_SESSION['csrf_contato'] = bin2hex(random_bytes(32));
        echo json_encode([
            'status' => 'ok',
            'message' => 'Obrigado! Sua mensagem foi enviada com sucesso e logo entraremos em contato. 💜',
            'csrf_token' => $_SESSION['csrf_contato']
        ]);
    } catch (Throwable $erro) {
        error_log('Falha no formulario de contato: ' . $erro->getMessage());
        http_response_code(500);
        echo json_encode([
            'status' => 'erro',
            'message' => 'Nao foi possivel enviar a mensagem agora. Tente novamente mais tarde.'
        ]);
    }
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - Apoio+</title>
    <link rel="stylesheet" href="<?= caminhoEstilo('estilos.css') ?>">
    <link rel="stylesheet" href="<?= caminhoEstilo('forms.css') ?>">
</head>
<body>
    <?php renderizarCabecalho('contato'); ?>

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
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_contato'], ENT_QUOTES, 'UTF-8') ?>">

                    <label for="nome-contato">Nome</label>
                    <input type="text" name="nome" id="nome-contato" maxlength="100" placeholder="Nome" required>

                    <label for="email-contato">E-mail para receber a resposta</label>
                    <input type="email" name="email" id="email-contato" maxlength="150" placeholder="E-mail para receber a resposta" required>

                    <label for="ong-contato">ONGs (opcional)</label>
                    <input type="text" name="ong" id="ong-contato" maxlength="150" placeholder="ONGs (opcional)">

                    <label for="assunto-contato">Assunto</label>
                    <input type="text" name="assunto" id="assunto-contato" maxlength="150" placeholder="Assunto" required>

                    <label for="mensagem-contato">Mensagem</label>
                    <textarea name="mensagem" id="mensagem-contato" rows="5" maxlength="5000" placeholder="Mensagem" required></textarea>

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

    <?php renderizarRodape(); ?>
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
                    const botaoEnviar = formularioContato.querySelector('[type="submit"]');
                    botaoEnviar.disabled = true;

                    fetch("contato.php", {
                        method: "POST",
                        body: dados
                    })
                    .then((resposta) => resposta.json())
                    .then((data) => {
                        if (data.status === "ok") {
                            formularioContato.reset();
                            formularioContato.elements.csrf_token.value = data.csrf_token;
                            fechar();
                            alert(data.message);
                            return;
                        }

                        alert(data.message || "Nao foi possivel enviar a mensagem agora.");
                    })
                    .catch(() => {
                        alert("Nao foi possivel enviar a mensagem agora. Tente novamente mais tarde.");
                    })
                    .finally(() => {
                        botaoEnviar.disabled = false;
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
