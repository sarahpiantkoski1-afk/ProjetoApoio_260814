<?php

$configuracaoLocal = __DIR__ . '/mail.local.php';
$valoresLocais = is_file($configuracaoLocal) ? require $configuracaoLocal : [];

if (!is_array($valoresLocais)) {
    throw new RuntimeException('A configuracao local de e-mail precisa retornar um array.');
}

$valorConfiguracao = static function (string $chave, mixed $padrao = null) use ($valoresLocais): mixed {
    $valorAmbiente = getenv($chave);

    if ($valorAmbiente !== false && $valorAmbiente !== '') {
        return $valorAmbiente;
    }

    return $valoresLocais[$chave] ?? $padrao;
};

return [
    'host' => (string) $valorConfiguracao('MAIL_HOST', 'smtp.gmail.com'),
    'port' => (int) $valorConfiguracao('MAIL_PORT', 587),
    'username' => (string) $valorConfiguracao('MAIL_USERNAME', ''),
    'password' => (string) $valorConfiguracao('MAIL_PASSWORD', ''),
    'encryption' => strtolower((string) $valorConfiguracao('MAIL_ENCRYPTION', 'tls')),
    'from_address' => (string) $valorConfiguracao('MAIL_FROM_ADDRESS', $valorConfiguracao('MAIL_USERNAME', '')),
    'from_name' => (string) $valorConfiguracao('MAIL_FROM_NAME', 'Apoio+'),
    'app_url' => rtrim((string) $valorConfiguracao('APP_URL', ''), '/'),
];
