<?php
require_once('../../backend/funcionalidades/config.php');
require_once('../../backend/funcionalidades/mailer.php');
require_once(__DIR__ . '/../componentes/layout.php');

if (isset($_SESSION['usuario']) || isset($_SESSION['ong'])) {
    header("Location: inicio.php");
    exit();
}

$mensagemRetorno = '';

if (!isset($_SESSION['csrf_recuperar_senha'])) {
    $_SESSION['csrf_recuperar_senha'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $csrf = $_POST['csrf_token'] ?? '';
    $csrfValido = is_string($csrf) && hash_equals($_SESSION['csrf_recuperar_senha'], $csrf);

    if (!$csrfValido || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
        $mensagemRetorno = 'Informe um e-mail válido e tente novamente.';
    } else {
        $mensagemRetorno = 'Se o e-mail estiver cadastrado, você receberá as instruções para redefinir sua senha.';
        $tokensCriados = [];

        try {
            $consulta = $mysqli->prepare(
                "SELECT id, nome, email, 'egresso' AS user_type FROM egressos WHERE email = ?
                 UNION ALL
                 SELECT id, nome, email, 'ong' AS user_type FROM ongs WHERE email = ?"
            );
            $consulta->bind_param('ss', $email, $email);
            $consulta->execute();
            $contas = $consulta->get_result()->fetch_all(MYSQLI_ASSOC);

            if ($contas) {
                $mysqli->query("DELETE FROM password_reset_tokens WHERE expires_at < NOW() OR used_at IS NOT NULL");
                $inserir = $mysqli->prepare(
                    "INSERT INTO password_reset_tokens (user_type, user_id, email, token_hash, expires_at)
                     VALUES (?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))"
                );
                $linksHtml = [];
                $linksTexto = [];

                foreach ($contas as $conta) {
                    $token = bin2hex(random_bytes(32));
                    $tokenHash = hash('sha256', $token);
                    $tipoUsuario = $conta['user_type'];
                    $idUsuario = (int) $conta['id'];
                    $emailConta = $conta['email'];
                    $inserir->bind_param('siss', $tipoUsuario, $idUsuario, $emailConta, $tokenHash);
                    $inserir->execute();
                    $tokensCriados[] = $tokenHash;

                    $rotulo = $tipoUsuario === 'ong' ? 'Instituição Social' : 'Jovem Egresso';
                    $link = urlBaseAplicacao() . '/redefinir_senha.php?token=' . rawurlencode($token);
                    $linkSeguro = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
                    $linksHtml[] = '<p><strong>' . $rotulo . ':</strong><br><a href="' . $linkSeguro . '">Redefinir minha senha</a></p>';
                    $linksTexto[] = $rotulo . ': ' . $link;
                }

                $nome = $contas[0]['nome'];
                $html = '<h2>Redefinição de senha do Apoio+</h2>'
                    . '<p>Olá, ' . htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') . '.</p>'
                    . '<p>Recebemos uma solicitação para redefinir sua senha. O link expira em uma hora e só pode ser usado uma vez.</p>'
                    . implode('', $linksHtml)
                    . '<p>Se você não solicitou a alteração, ignore este e-mail.</p>';
                $texto = "Redefinição de senha do Apoio+\n\n"
                    . "O link expira em uma hora e só pode ser usado uma vez.\n\n"
                    . implode("\n", $linksTexto)
                    . "\n\nSe você não solicitou a alteração, ignore este e-mail.";

                try {
                    enviarEmail($email, $nome, 'Redefinição de senha - Apoio+', $html, $texto);
                } catch (Throwable $erroEmail) {
                    if ($tokensCriados) {
                        $remover = $mysqli->prepare("DELETE FROM password_reset_tokens WHERE token_hash = ?");

                        foreach ($tokensCriados as $tokenCriado) {
                            $remover->bind_param('s', $tokenCriado);
                            $remover->execute();
                        }
                    }

                    throw $erroEmail;
                }
            }
        } catch (Throwable $erro) {
            error_log('Falha na recuperacao de senha: ' . $erro->getMessage());
        }

        $_SESSION['csrf_recuperar_senha'] = bin2hex(random_bytes(32));
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php renderizarFavicon(); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="<?= caminhoEstilo('estilos.css') ?>">
</head>
<body>
    <?php renderizarCabecalho('login'); ?>

    <main class="recuperar-senha-main">
        <section class="recuperar-senha-card" aria-labelledby="recuperar-senha-titulo">
            <h2 id="recuperar-senha-titulo" class="titulo-tela">Esqueceu a senha?</h2>
            <p>Redefina a senha em duas etapas</p>

            <?php if ($mensagemRetorno !== '') { ?>
                <div class="form-feedback" role="status"><?= htmlspecialchars($mensagemRetorno, ENT_QUOTES, 'UTF-8') ?></div>
            <?php } ?>

            <form action="recuperar_senha.php" method="post" class="recuperar-senha-form">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_recuperar_senha'], ENT_QUOTES, 'UTF-8') ?>">
                <input
                    type="email"
                    name="email"
                    id="email"
                    maxlength="100"
                    placeholder="E-mail"
                    required
                >

                <button type="submit">Enviar</button>
            </form>

            <a href="login.php" class="recuperar-senha-voltar">Voltar para o login</a>
        </section>
    </main>

    <?php renderizarRodape(); ?>
</body>
</html>
