<?php
require_once('../config.php');

$cnpj = $_POST['cnpj'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$tipo_usuario = $_POST['tipo_usuario'] ?? '';

if ($tipo_usuario !== 'ong') {
    header("Location: ../../../frontend/telas/login/login_ongs.php?erro=tipo");
    exit();
}

$consulta = $mysqli->prepare("SELECT id, email, senha FROM ongs WHERE email = ? and cnpj = ?");
$consulta->bind_param("ss", $email, $cnpj);
$consulta->execute();

$resultado = $consulta->get_result();
$dados = $resultado->fetch_assoc();

if ($dados && password_verify($senha, $dados['senha'])) {
    $_SESSION['ong'] = $email;
    $_SESSION['ong_id'] = $dados['id'];
    unset($_SESSION['usuario']);

    header("Location: ../../../frontend/telas/postagens.php");
    exit();
}

header("Location: ../../../frontend/telas/login/login_ongs.php?erro=informacoesInvalidas");
exit();

?>
