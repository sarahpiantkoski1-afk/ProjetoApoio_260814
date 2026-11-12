<?php
if (session_status() == PHP_SESSION_NONE) {
session_start();}

// ========== Config ============
$hostInformado = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
$hostAtual = strtolower((string) (parse_url('http://' . $hostInformado, PHP_URL_HOST) ?: $hostInformado));
$ambienteLocal = in_array($hostAtual, ['localhost', '127.0.0.1', '::1'], true);

if ($ambienteLocal) {
    $_DB['server'] = "localhost";
    $_DB['user'] = "root";
    $_DB['password'] = "";
    $_DB['database'] = "apoio";
} else {
    $_DB['server'] = "sql207.infinityfree.com";
    $_DB['user'] = "if0_42222268";
    $_DB['password'] = "I5vBp7y5KJubBPu";
    $_DB['database'] = "if0_42222268_apoiomais";
}
// ==============================
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Desativa relatórios de erros

try {
    $mysqli = new mysqli($_DB['server'], $_DB['user'], $_DB['password'], $_DB['database']);
    $mysqli->set_charset("utf8mb4");
} catch (Exception $e) {
    error_log($e->getMessage());
    exit('Erro ao conectar ao banco de dados: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
}

?>
