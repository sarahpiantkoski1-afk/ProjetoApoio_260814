<?php
require_once('../config.php');

function voltarComAlerta($mensagem) {
    echo "<script>alert('" . addslashes($mensagem) . "'); window.history.back();</script>";
    exit();
}

function voltarComErroCnpj($erro) {
    header('Location: ../../../frontend/telas/login/cadastro_ongs.php?erro_cnpj=' . rawurlencode($erro));
    exit();
}

function consultarCnpjBrasilApi($cnpj) {
    $curl = curl_init('https://brasilapi.com.br/api/cnpj/v1/' . rawurlencode($cnpj));

    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => ['Accept: application/json'],
        CURLOPT_USERAGENT => 'ApoioPlus/1.0',
    ]);

    $resposta = curl_exec($curl);
    $codigoHttp = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $erroCurl = curl_errno($curl);
    curl_close($curl);

    return [
        'codigo_http' => $codigoHttp,
        'erro_curl' => $erroCurl,
        'dados' => $resposta === false ? null : json_decode($resposta, true),
    ];
}

$nome = $_POST['nome'] ?? '';
$cnpj = trim($_POST['cnpj'] ?? '');
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';

if ($senha !== $confirmar_senha) {
    voltarComAlerta('As senhas nao conferem!');
}

$cnpjNumeros = preg_replace('/\D/', '', $cnpj);

if (strlen($cnpjNumeros) !== 14) {
    voltarComErroCnpj('invalido');
}

$cnpj = preg_replace(
    '/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/',
    '$1.$2.$3/$4-$5',
    $cnpjNumeros
);

$consultaDuplicidade = $mysqli->prepare("SELECT id FROM ongs WHERE cnpj = ? OR email = ? LIMIT 1");
$consultaDuplicidade->bind_param("ss", $cnpj, $email);
$consultaDuplicidade->execute();
$resultadoDuplicidade = $consultaDuplicidade->get_result();

if ($resultadoDuplicidade->num_rows > 0) {
    voltarComAlerta('Este CNPJ ou E-mail ja esta cadastrado!');
}

$consultaCnpj = consultarCnpjBrasilApi($cnpjNumeros);

if (in_array($consultaCnpj['codigo_http'], [400, 404], true)) {
    voltarComErroCnpj('invalido');
}

$dadosCnpj = $consultaCnpj['dados'];

if ($consultaCnpj['erro_curl'] !== 0 || $consultaCnpj['codigo_http'] !== 200 || !is_array($dadosCnpj)) {
    voltarComErroCnpj('indisponivel');
}

$situacaoCadastral = strtoupper(trim($dadosCnpj['descricao_situacao_cadastral'] ?? ''));

if ($situacaoCadastral !== 'ATIVA') {
    voltarComErroCnpj('inativo');
}

try {
    $hash = password_hash($senha, PASSWORD_BCRYPT);
    $consulta = $mysqli->prepare("INSERT INTO ongs (nome, cnpj, email, senha) VALUES (?, ?, ?, ?)");
    $consulta->bind_param("ssss", $nome, $cnpj, $email, $hash);
    $consulta->execute();

    header("Location: ../../../frontend/telas/inicio.php");
    exit();
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        voltarComAlerta('Este CNPJ ou E-mail ja esta cadastrado!');
    }

    voltarComAlerta('Nao foi possivel realizar o cadastro. Tente novamente.');
}

?>
