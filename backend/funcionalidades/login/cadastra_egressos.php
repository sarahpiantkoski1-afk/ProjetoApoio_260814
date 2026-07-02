<?php
require_once('../config.php');

function voltarComAlerta($mensagem) {
    echo "<script>alert('" . addslashes($mensagem) . "'); window.history.back();</script>";
    exit();
}

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
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
    $consulta = $mysqli->prepare("INSERT INTO egressos (nome, email, senha) VALUES (?, ?, ?)");
    $consulta->bind_param("sss", $nome, $email, $hash);
    $consulta->execute();

    header("Location: ../../../frontend/telas/inicio.php");
    exit();
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        voltarComAlerta('Este E-mail ja esta cadastrado!');
    }

    voltarComAlerta('Nao foi possivel realizar o cadastro. Tente novamente.');
}

?>
