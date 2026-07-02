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

    
    if (!$titulo || !$link || !$descricao) {
        echo json_encode([
            "status" => "erro",
            "message" => "Todos os campos são obrigatórios."
        ]);
        exit();
    }


    $consulta = $mysqli->prepare("INSERT INTO postagens (ong_id, titulo, descricao, link) VALUES (?, ?, ?, ?)");
    $consulta->bind_param("isss", $ong_id, $titulo, $descricao, $link);

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
