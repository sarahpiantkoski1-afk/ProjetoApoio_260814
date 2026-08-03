<?php
require_once('../config.php');

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

$consulta = $mysqli->prepare("SELECT email, senha FROM egressos WHERE email = ?");
$consulta->bind_param("s", $email);
$consulta->execute();

$resultado = $consulta->get_result();
$dados = $resultado->fetch_assoc();

if ($dados && password_verify($senha, $dados['senha'])) {
    session_regenerate_id(true);
    $_SESSION['usuario'] = $email;
    unset($_SESSION['ong'], $_SESSION['ong_id']);
    header("Location: ../../../frontend/telas/inicio.php");
    exit();
}

header("Location: ../../../frontend/telas/login/login_egressos.php?erro=credenciais");
exit();

?>
