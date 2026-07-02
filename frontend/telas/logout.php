<?php
session_start();

function realizarLogout() {
    $_SESSION = [];
    session_destroy();
    header("Location: inicio.php");
    exit();
}

realizarLogout();
?>
