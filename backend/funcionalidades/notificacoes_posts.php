<?php

require_once __DIR__ . '/mailer.php';

function conteudoNotificacaoPost(string $instituicao, string $titulo, string $descricao, string $link): array
{
    $escapar = static fn(string $valor): string => htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    // Apenas links HTTP(S) podem aparecer como links clicáveis no e-mail.
    $linkValido = filter_var($link, FILTER_VALIDATE_URL)
        && in_array(strtolower((string) parse_url($link, PHP_URL_SCHEME)), ['http', 'https'], true);
    $html = '<h1>Nova publicação no Apoio+</h1>'
        . '<p><strong>' . $escapar($instituicao) . '</strong> publicou:</p>'
        . '<h2>' . $escapar($titulo) . '</h2>'
        . '<p>' . nl2br($escapar($descricao)) . '</p>';
    $texto = "Nova publicação no Apoio+\n\n{$instituicao} publicou:\n{$titulo}\n\n{$descricao}\n";
    if ($linkValido) {
        $html .= '<p><a href="' . $escapar($link) . '">Acessar oportunidade</a></p>';
        $texto .= "\nAcessar oportunidade: {$link}\n";
    }
    $aviso = 'Você recebeu este e-mail porque escolheu receber notificações de novas publicações no cadastro do Apoio+.';
    $html .= '<hr><p>' . $aviso . '</p>';
    $texto .= "\n" . $aviso;
    return [$html, $texto];
}

function notificarNovoPost(mysqli $banco, int $postId, int $ongId, string $titulo, string $descricao, string $link): void
{
    $consulta = $banco->prepare('SELECT nome FROM ongs WHERE id = ?');
    $consulta->bind_param('i', $ongId);
    $consulta->execute();
    $instituicao = $consulta->get_result()->fetch_assoc()['nome'] ?? 'Uma instituição';
    [$html, $texto] = conteudoNotificacaoPost($instituicao, $titulo, $descricao, $link);
    $destinatarios = $banco->query('SELECT id, nome, email FROM egressos WHERE receber_notificacoes = 1');
    while ($destinatario = $destinatarios->fetch_assoc()) {
        if (!filter_var($destinatario['email'], FILTER_VALIDATE_EMAIL)) {
            continue;
        }
        try {
            enviarEmail($destinatario['email'], $destinatario['nome'], 'Nova publicação no Apoio+', $html, $texto, null, 10);
        } catch (Throwable $erro) {
            // Não expor endereços ou credenciais nos logs e não interromper os demais envios.
            error_log('Falha na notificação do post ' . $postId . ' para egresso ' . (int) $destinatario['id']);
        }
    }
}
