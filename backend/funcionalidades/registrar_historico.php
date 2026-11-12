<?php
require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['ok' => false]);
    exit;
}

$tipo = $_POST['tipo'] ?? '';
$itemId = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
if (!in_array($tipo, ['conteudo', 'vaga', 'video'], true) || !$itemId || $itemId < 1) {
    http_response_code(400);
    echo json_encode(['ok' => false]);
    exit;
}

$usuario = $mysqli->prepare('SELECT id FROM egressos WHERE email = ? AND historico_navegacao_ativo = 1');
$usuario->bind_param('s', $_SESSION['usuario']);
$usuario->execute();
$egresso = $usuario->get_result()->fetch_assoc();
if (!$egresso) {
    http_response_code(401);
    echo json_encode(['ok' => false]);
    exit;
}

$registro = $mysqli->prepare('INSERT INTO historico_navegacao (egresso_id, tipo, item_id) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE visitado_em = CURRENT_TIMESTAMP');
$registro->bind_param('isi', $egresso['id'], $tipo, $itemId);
$registro->execute();
echo json_encode(['ok' => true]);
