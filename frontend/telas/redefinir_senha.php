<?php
require_once('../../backend/funcionalidades/config.php');

if (isset($_SESSION['usuario']) || isset($_SESSION['ong'])) {
    header('Location: inicio.php');
    exit();
}

if (!isset($_SESSION['csrf_redefinir_senha'])) {
    $_SESSION['csrf_redefinir_senha'] = bin2hex(random_bytes(32));
}

$token = trim($_POST['token'] ?? $_GET['token'] ?? '');
$tokenValido = preg_match('/^[a-f0-9]{64}$/', $token) === 1;
$registroToken = null;
$mensagemErro = '';
$senhaAlterada = false;

if ($tokenValido) {
    $tokenHash = hash('sha256', $token);
    $consultaToken = $mysqli->prepare(
        "SELECT id, user_type, user_id, email
         FROM password_reset_tokens
         WHERE token_hash = ? AND used_at IS NULL AND expires_at >= NOW()
         LIMIT 1"
    );
    $consultaToken->bind_param('s', $tokenHash);
    $consultaToken->execute();
    $registroToken = $consultaToken->get_result()->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';
    $csrf = $_POST['csrf_token'] ?? '';
    $csrfValido = is_string($csrf) && hash_equals($_SESSION['csrf_redefinir_senha'], $csrf);

    if (!$csrfValido) {
        $mensagemErro = 'A sessão do formulário expirou. Abra novamente o link recebido por e-mail.';
    } elseif (!$registroToken) {
        $mensagemErro = 'Este link é inválido, expirou ou já foi utilizado.';
    } elseif (mb_strlen($senha) < 8 || mb_strlen($senha) > 255) {
        $mensagemErro = 'A nova senha deve ter entre 8 e 255 caracteres.';
    } elseif ($senha !== $confirmarSenha) {
        $mensagemErro = 'As senhas não conferem.';
    } else {
        try {
            $mysqli->begin_transaction();
            $bloquearToken = $mysqli->prepare(
                "SELECT id, user_type, user_id, email
                 FROM password_reset_tokens
                 WHERE token_hash = ? AND used_at IS NULL AND expires_at >= NOW()
                 LIMIT 1 FOR UPDATE"
            );
            $bloquearToken->bind_param('s', $tokenHash);
            $bloquearToken->execute();
            $tokenBloqueado = $bloquearToken->get_result()->fetch_assoc();

            if (!$tokenBloqueado) {
                throw new RuntimeException('Token indisponivel.');
            }

            $tabelasPermitidas = ['egresso' => 'egressos', 'ong' => 'ongs'];
            $tabela = $tabelasPermitidas[$tokenBloqueado['user_type']] ?? null;

            if ($tabela === null) {
                throw new RuntimeException('Tipo de conta invalido.');
            }

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $idUsuario = (int) $tokenBloqueado['user_id'];
            $emailUsuario = $tokenBloqueado['email'];
            $atualizarSenha = $mysqli->prepare("UPDATE {$tabela} SET senha = ? WHERE id = ? AND email = ?");
            $atualizarSenha->bind_param('sis', $senhaHash, $idUsuario, $emailUsuario);
            $atualizarSenha->execute();

            if ($atualizarSenha->affected_rows !== 1) {
                throw new RuntimeException('Conta nao encontrada.');
            }

            $invalidarTokens = $mysqli->prepare(
                "UPDATE password_reset_tokens SET used_at = NOW()
                 WHERE user_type = ? AND user_id = ? AND used_at IS NULL"
            );
            $tipoUsuario = $tokenBloqueado['user_type'];
            $invalidarTokens->bind_param('si', $tipoUsuario, $idUsuario);
            $invalidarTokens->execute();
            $mysqli->commit();

            $senhaAlterada = true;
            $registroToken = null;
            $_SESSION['csrf_redefinir_senha'] = bin2hex(random_bytes(32));
        } catch (Throwable $erro) {
            $mysqli->rollback();
            error_log('Falha ao redefinir senha: ' . $erro->getMessage());
            $mensagemErro = $erro->getMessage() === 'Token indisponivel.'
                ? 'Este link é inválido, expirou ou já foi utilizado.'
                : 'Não foi possível alterar a senha agora. Tente solicitar um novo link.';
        }
    }
}

if (!$tokenValido || (!$registroToken && !$senhaAlterada && $mensagemErro === '')) {
    $mensagemErro = 'Este link é inválido, expirou ou já foi utilizado.';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - Apoio+</title>
    <link rel="stylesheet" href="../estilos/estilos.css?v=17">
</head>
<body>
    <header>
        <picture id="logo-img">
            <img src="../imagens/apoiomais.png" alt="Apoio+" class="logo-header">
        </picture>
        <nav id="options-header" aria-label="Navegação principal">
            <ul>
                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="conteudo_educativo.php">Conteúdo Educativo</a></li>
                <li><a href="postagens.php">Postagens</a></li>
                <li><a href="ongs.php">ONGs</a></li>
                <li><a href="contato.php">Contato</a></li>
                <li><a href="cadastro.php">Cadastre-se</a></li>
                <li><a href="login.php" class="active">Entrar</a></li>
            </ul>
        </nav>
    </header>

    <main class="recuperar-senha-main">
        <section class="recuperar-senha-card" aria-labelledby="redefinir-senha-titulo">
            <h2 id="redefinir-senha-titulo">Crie uma nova senha</h2>

            <?php if ($senhaAlterada) { ?>
                <div class="form-feedback form-feedback-sucesso" role="status">
                    Sua senha foi alterada com sucesso.
                </div>
                <a href="login.php" class="recuperar-senha-voltar">Ir para o login</a>
            <?php } elseif ($registroToken) { ?>
                <?php if ($mensagemErro !== '') { ?>
                    <div class="form-feedback form-feedback-erro" role="alert"><?= htmlspecialchars($mensagemErro, ENT_QUOTES, 'UTF-8') ?></div>
                <?php } ?>

                <form action="redefinir_senha.php" method="post" class="recuperar-senha-form">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_redefinir_senha'], ENT_QUOTES, 'UTF-8') ?>">

                    <label for="senha">Nova senha</label>
                    <input type="password" name="senha" id="senha" minlength="8" maxlength="255" autocomplete="new-password" required>

                    <label for="confirmar-senha">Confirme a nova senha</label>
                    <input type="password" name="confirmar_senha" id="confirmar-senha" minlength="8" maxlength="255" autocomplete="new-password" required>

                    <button type="submit">Alterar senha</button>
                </form>
            <?php } else { ?>
                <div class="form-feedback form-feedback-erro" role="alert"><?= htmlspecialchars($mensagemErro, ENT_QUOTES, 'UTF-8') ?></div>
                <a href="recuperar_senha.php" class="recuperar-senha-voltar">Solicitar um novo link</a>
            <?php } ?>
        </section>
    </main>

    <footer><p>Apoio+ O guia colaborativo para jovens egressos de abrigos institucionais</p></footer>
    <script src="header-menu.js"></script>
</body>
</html>
