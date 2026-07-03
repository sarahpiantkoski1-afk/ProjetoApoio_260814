<?php
session_start();
require_once('config.php'); 
header('Content-Type: application/json');


mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    if (!isset($_SESSION['ong_id'])) {
        echo json_encode([
            "status" => "erro",
            "message" => "Você precisa estar logado como ONG para criar posts."
        ]);
        exit();
    }

    $ong_id   = $_SESSION['ong_id'];
    $titulo   = $_POST['titulo'] ?? '';
    $link     = $_POST['link'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $categoriasSelecionadas = $_POST['categorias'] ?? [];
    $categoriasPermitidas = ['curso', 'moradia', 'apoio-psicologico', 'emprego', 'acolhimento'];

    
    if (!$titulo || !$link || !$descricao) {
        echo json_encode([
            "status" => "erro",
            "message" => "Todos os campos são obrigatórios."
        ]);
        exit();
    }

    if (!is_array($categoriasSelecionadas)) {
        $categoriasSelecionadas = [];
    }

    $categoriasSelecionadas = array_values(array_unique(array_intersect($categoriasSelecionadas, $categoriasPermitidas)));

    if (count($categoriasSelecionadas) === 0 || count($categoriasSelecionadas) > 2) {
        echo json_encode([
            "status" => "erro",
            "message" => "Selecione uma ou duas categorias para o post."
        ]);
        exit();
    }

    $colunaCategoria = $mysqli->query("SHOW COLUMNS FROM postagens LIKE 'categoria'");

    if ($colunaCategoria->num_rows === 0) {
        $mysqli->query("ALTER TABLE postagens ADD categoria VARCHAR(120) NOT NULL DEFAULT '' AFTER link");
    }

    $categoria = implode(',', $categoriasSelecionadas);

    $consulta = $mysqli->prepare("INSERT INTO postagens (ong_id, titulo, descricao, link, categoria) VALUES (?, ?, ?, ?, ?)");
    $consulta->bind_param("issss", $ong_id, $titulo, $descricao, $link, $categoria);

    if ($consulta->execute()) {
        echo json_encode([
            "status" => "ok",
            "message" => "Post criado com sucesso!"
        ]);
    } else {
        echo json_encode([
            "status" => "erro",
            "message" => "Erro ao criar post."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "erro",
        "message" => "Erro inesperado: " . $e->getMessage()
    ]);
}
exit();
