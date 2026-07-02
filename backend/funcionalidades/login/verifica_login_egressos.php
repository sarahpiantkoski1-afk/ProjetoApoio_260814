<?php
require_once('../config.php');

$email = $_POST['email'];
$senha = $_POST['senha'];

$consulta = $mysqli->prepare("SELECT email, senha FROM egressos WHERE email = ?");
$consulta->bind_param("s", $email);
$consulta->execute();

$resultado = $consulta->get_result();
$dados = $resultado->fetch_assoc();

$hash = $dados['senha'];

if ($dados) {
    if (password_verify($senha, $hash)) {      
        $_SESSION['usuario'] = $email;
        header("Location: ../../../frontend/telas/inicio.php");
        exit();
    } else {
        header("Location: ../../../frontend/telas/login/login_egressos.php?erro=senha");
        exit();
    }
} else {
    header("Location: ../../../frontend/telas/login/login_egressos.php?erro=email");
    exit();
}

?>