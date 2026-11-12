<?php

use PHPMailer\PHPMailer\Exception as MailerException;
use PHPMailer\PHPMailer\PHPMailer;

function configuracaoEmail(): array
{
    static $configuracao;

    if ($configuracao === null) {
        $configuracao = require __DIR__ . '/../config/mail.php';
    }

    return $configuracao;
}

function urlBaseAplicacao(): string
{
    $configuracao = configuracaoEmail();

    if ($configuracao['app_url'] !== '') {
        return $configuracao['app_url'];
    }

    $host = $_SERVER['HTTP_HOST'] ?? '';
    $hostsLocais = ['localhost', '127.0.0.1', '::1'];
    $hostSemPorta = strtolower(preg_replace('/:\\d+$/', '', trim($host, '[]')));

    if (!in_array($hostSemPorta, $hostsLocais, true)) {
        throw new RuntimeException('Defina APP_URL na configuracao do ambiente de producao.');
    }

    $https = ($_SERVER['HTTPS'] ?? '') === 'on';
    $diretorio = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));

    return ($https ? 'https' : 'http') . '://' . $host . rtrim($diretorio, '/');
}

function enviarEmail(
    string $destinatario,
    string $nomeDestinatario,
    string $assunto,
    string $html,
    string $texto,
    ?array $responderPara = null,
    int $timeout = 30
): void {
    $diretorioPHPMailer = __DIR__ . '/../vendor/phpmailer/src';
    $classesPHPMailer = [
        $diretorioPHPMailer . '/Exception.php',
        $diretorioPHPMailer . '/PHPMailer.php',
        $diretorioPHPMailer . '/SMTP.php',
    ];

    foreach ($classesPHPMailer as $classePHPMailer) {
        if (!is_file($classePHPMailer)) {
            throw new RuntimeException('PHPMailer local nao encontrado em backend/vendor/phpmailer/src.');
        }

        require_once $classePHPMailer;
    }
    $configuracao = configuracaoEmail();

    $senhaNaoConfigurada = $configuracao['password'] === ''
        || str_starts_with($configuracao['password'], 'COLOQUE_AQUI_');

    if ($configuracao['username'] === '' || $senhaNaoConfigurada || $configuracao['from_address'] === '') {
        throw new RuntimeException('Credenciais SMTP nao configuradas.');
    }

    $mailer = new PHPMailer(true);

    try {
        $mailer->isSMTP();
        $mailer->Timeout = $timeout;
        $mailer->Timelimit = $timeout;
        $mailer->Host = $configuracao['host'];
        $mailer->Port = $configuracao['port'];
        $mailer->SMTPAuth = true;
        $mailer->Username = $configuracao['username'];
        $mailer->Password = $configuracao['password'];
        $mailer->CharSet = PHPMailer::CHARSET_UTF8;

        if ($configuracao['encryption'] === 'ssl' || $configuracao['encryption'] === 'smtps') {
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($configuracao['encryption'] === 'tls' || $configuracao['encryption'] === 'starttls') {
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } elseif ($configuracao['encryption'] !== '' && $configuracao['encryption'] !== 'none') {
            throw new RuntimeException('Criptografia SMTP invalida. Use tls, ssl ou none.');
        }

        $mailer->setFrom($configuracao['from_address'], $configuracao['from_name']);
        $mailer->addAddress($destinatario, $nomeDestinatario);

        if ($responderPara !== null) {
            $mailer->addReplyTo($responderPara['email'], $responderPara['nome'] ?? '');
        }

        $mailer->isHTML(true);
        $mailer->Subject = $assunto;
        $mailer->Body = $html;
        $mailer->AltBody = $texto;
        $mailer->send();
    } catch (MailerException $e) {
        throw new RuntimeException('Falha no envio SMTP.', 0, $e);
    }
}
