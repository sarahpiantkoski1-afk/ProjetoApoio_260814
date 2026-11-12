<?php
require_once __DIR__ . '/config.php';
header('Content-Type: application/json; charset=utf-8');

function responderConteudo(string $status, string $mensagem, int $codigo = 200): void
{
    http_response_code($codigo);
    echo json_encode(['status' => $status, 'message' => $mensagem], JSON_UNESCAPED_UNICODE);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['ong_id']) || isset($_SESSION['usuario'])) {
    responderConteudo('erro', 'Entre com uma conta de instituição para adicionar conteúdo.', 401);
}

$titulo = trim($_POST['titulo'] ?? '');
$url = trim($_POST['youtube_url'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$nichosEnviados = $_POST['nichos'] ?? [];
$nichosPermitidos = ['direitos', 'autoestima', 'acolhimento', 'autocuidado', 'dicas'];

if (mb_strlen($titulo) < 3 || mb_strlen($titulo) > 200 || $descricao === '' || mb_strlen($descricao) > 1200 || strlen($url) > 500) {
    responderConteudo('erro', 'Confira o título, a descrição e o link do vídeo.');
}

if (!is_array($nichosEnviados)) $nichosEnviados = [];
$nichosEnviados = array_values(array_filter($nichosEnviados, 'is_string'));
$nichos = array_values(array_unique(array_intersect($nichosEnviados, $nichosPermitidos)));
if (count($nichos) < 1 || count($nichos) > 2) {
    responderConteudo('erro', 'Selecione um ou dois nichos.');
}

$partesUrl = filter_var($url, FILTER_VALIDATE_URL) ? parse_url($url) : false;
$host = strtolower($partesUrl['host'] ?? '');
$videoId = '';
if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
    $videoId = explode('/', trim($partesUrl['path'] ?? '', '/'))[0] ?? '';
} elseif (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
    $videoId = $partesUrl['query'] ?? '';
    parse_str($videoId, $parametros);
    $videoId = $parametros['v'] ?? '';
    if ($videoId === '' && preg_match('~^/(?:live|embed|shorts)/([A-Za-z0-9_-]{11})(?:/|$)~', $partesUrl['path'] ?? '', $correspondencia)) {
        $videoId = $correspondencia[1];
    }
}
if (!preg_match('/^[A-Za-z0-9_-]{11}$/', $videoId)) {
    responderConteudo('erro', 'Informe um link válido de vídeo do YouTube.');
}

try {
    $ongId = (int) $_SESSION['ong_id'];
    $categorias = implode(',', $nichos);
    $consulta = $mysqli->prepare('INSERT INTO conteudos_educativos (ong_id, titulo, descricao, youtube_url, youtube_id, nichos) VALUES (?, ?, ?, ?, ?, ?)');
    $consulta->bind_param('isssss', $ongId, $titulo, $descricao, $url, $videoId, $categorias);
    $consulta->execute();
    responderConteudo('ok', 'Conteúdo educativo adicionado com sucesso!');
} catch (Throwable $erro) {
    error_log('[conteudo_educativo] Falha ao salvar. codigo=' . $erro->getCode() . ' mensagem=' . $erro->getMessage());
    responderConteudo('erro', 'Não foi possível salvar. Verifique se a migration 006_conteudos_educativos_ongs.sql foi aplicada.', 500);
}
