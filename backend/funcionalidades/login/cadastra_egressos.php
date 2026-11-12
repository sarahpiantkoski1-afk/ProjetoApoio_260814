<?php
require_once('../config.php');

function voltarComAlerta($mensagem) {
    echo "<script>alert('" . addslashes($mensagem) . "'); window.history.back();</script>";
    exit();
}

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$receberNotificacoes = ($_POST['receber_notificacoes'] ?? '') === '1' ? 1 : 0;
$senha = $_POST['senha'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';

if ($senha !== $confirmar_senha) {
    voltarComAlerta('As senhas nao conferem!');
}

$consultaDuplicidade = $mysqli->prepare("SELECT id FROM egressos WHERE email = ? LIMIT 1");
$consultaDuplicidade->bind_param("s", $email);
$consultaDuplicidade->execute();
$resultadoDuplicidade = $consultaDuplicidade->get_result();

if ($resultadoDuplicidade->num_rows > 0) {
    voltarComAlerta('Este E-mail ja esta cadastrado!');
}

try {
    $hash = password_hash($senha, PASSWORD_BCRYPT);
    $historicoAtivo = 1;
    $consulta = $mysqli->prepare("INSERT INTO egressos (nome, email, senha, receber_notificacoes, historico_navegacao_ativo) VALUES (?, ?, ?, ?, ?)");
    $consulta->bind_param("sssii", $nome, $email, $hash, $receberNotificacoes, $historicoAtivo);
    $consulta->execute();

    header("Location: ../../../frontend/telas/inicio.php");
    exit();
} catch (mysqli_sql_exception $e) {
    error_log(sprintf(
        '[cadastro_egressos] Falha no cadastro. codigo=%d sqlstate=%s mensagem=%s',
        $e->getCode(),
        $e->getSqlState(),
        $e->getMessage()
    ));

    if ($e->getCode() == 1062) {
        voltarComAlerta('Este E-mail ja esta cadastrado!');
    }

    if ($e->getCode() == 1054 && strpos($e->getMessage(), 'historico_navegacao_ativo') !== false) {
        voltarComAlerta('O banco de dados precisa ser atualizado. Execute a migration 004_historico_apenas_novos_egressos.sql e tente novamente.');
    }

    voltarComAlerta('Nao foi possivel realizar o cadastro. Tente novamente.');
}

?>
