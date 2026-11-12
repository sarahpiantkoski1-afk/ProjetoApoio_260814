<?php
require_once('../../backend/funcionalidades/config.php');
require_once(__DIR__ . '/../componentes/layout.php');

$vagasVisitadas = [];
$historicoAtivo = false;
if (!empty($_SESSION['usuario'])) {
    $statusHistorico = $mysqli->prepare('SELECT historico_navegacao_ativo FROM egressos WHERE email = ?');
    $statusHistorico->bind_param('s', $_SESSION['usuario']);
    $statusHistorico->execute();
    $historicoAtivo = (bool) ($statusHistorico->get_result()->fetch_assoc()['historico_navegacao_ativo'] ?? false);
}
if ($historicoAtivo) {
    $consultaHistorico = $mysqli->prepare("SELECT item_id FROM historico_navegacao WHERE egresso_id = (SELECT id FROM egressos WHERE email = ?) AND tipo = 'vaga'");
    $consultaHistorico->bind_param('s', $_SESSION['usuario']);
    $consultaHistorico->execute();
    foreach ($consultaHistorico->get_result() as $registro) {
        $vagasVisitadas[(int) $registro['item_id']] = true;
    }
}

function categoriasPostagem($post) {
    if (!empty($post['categoria'])) {
        return $post['categoria'];
    }

    $texto = strtolower(($post['titulo'] ?? '') . ' ' . ($post['descricao'] ?? ''));
    $categorias = [];

    if (strpos($texto, 'curso') !== false || strpos($texto, 'capacit') !== false || strpos($texto, 'oficina') !== false) {
        $categorias[] = 'curso';
    }

    if (strpos($texto, 'moradia') !== false || strpos($texto, 'quarto') !== false) {
        $categorias[] = 'moradia';
    }

    if (strpos($texto, 'psic') !== false || strpos($texto, 'emocional') !== false) {
        $categorias[] = 'apoio-psicologico';
    }

    if (strpos($texto, 'emprego') !== false || strpos($texto, 'vaga') !== false || strpos($texto, 'aprendiz') !== false || strpos($texto, 'estagio') !== false) {
        $categorias[] = 'emprego';
    }

    if (strpos($texto, 'acolh') !== false || strpos($texto, 'assist') !== false) {
        $categorias[] = 'acolhimento';
    }

    return implode(',', array_unique($categorias));
}

?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php renderizarFavicon(); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postagens - Apoio+</title>
    <link rel="stylesheet" href="<?= caminhoEstilo('estilos.css') ?>">
    <link rel="stylesheet" href="<?= caminhoEstilo('postagens.css') ?>">
</head>
<body>
    <?php renderizarCabecalho('postagens'); ?>
    <main class="postagens-main">
        <section id="posts">
            <h2 class="titulo-tela">Postagens das ONGs</h2>
                <p class="intro-postagens">Explore as publicações mais recentes de ONGs e instituições parceiras. Aqui você encontra cursos gratuitos, vagas de emprego, capacitações e serviços de apoio em um só lugar.
</p>
            <div class="filtro-postagens">
                <button class="botao-filtro" type="button" aria-label="Abrir filtros de categorias" aria-expanded="false">
                    <img src="../imagens/icone_filtro%20(1).svg" alt="Filtrar postagens">
                </button>
                <div class="painel-filtro" hidden>
                    <button type="button" class="filtro-chip" data-categoria="curso">Curso</button>
                    <button type="button" class="filtro-chip" data-categoria="moradia">Moradia</button>
                    <button type="button" class="filtro-chip" data-categoria="apoio-psicologico">Apoio psicológico</button>
                    <button type="button" class="filtro-chip" data-categoria="emprego">Emprego</button>
                    <button type="button" class="filtro-chip" data-categoria="acolhimento">Acolhimento</button>
                </div>
            </div>
            <div class="card-post">

                <?php
                $consulta = $mysqli->prepare("SELECT * FROM postagens ORDER BY data_postagem DESC");
                $consulta->execute();
                $resultado = $consulta->get_result();
                $meses = [
                    1 => 'Jan',
                    2 => 'Fev',
                    3 => 'Mar',
                    4 => 'Abr',
                    5 => 'Mai',
                    6 => 'Jun',
                    7 => 'Jul',
                    8 => 'Ago',
                    9 => 'Set',
                    10 => 'Out',
                    11 => 'Nov',
                    12 => 'Dez'
                ];
                $mostrarIdPost = isset($_SESSION['ong']) && !isset($_SESSION['usuario']);

                while ($post = $resultado->fetch_assoc()) {
                    $foiVisitada = isset($vagasVisitadas[(int) $post['id']]);
                    $timestampPostagem = strtotime($post['data_postagem']);
                    $dataPostagem = date("d", $timestampPostagem) . " " . $meses[(int) date("n", $timestampPostagem)] . " " . date("Y", $timestampPostagem);
                    $categoriasPost = htmlspecialchars(categoriasPostagem($post));

                    echo "<div class='post' data-categorias='" . $categoriasPost . "'>";
                    echo "<h2>" . htmlspecialchars($post['titulo']) . "</h2>";
                    echo "<p class='descricao'>" . nl2br(htmlspecialchars($post['descricao'])) . "</p>";
                    echo "<p><a href='" . htmlspecialchars($post['link']) . "' target='_blank' rel='noopener noreferrer' class='ver-mais" . ($foiVisitada ? " foi-visitado" : "") . "' data-historico-tipo='vaga' data-historico-id='" . (int) $post['id'] . "'" . ($foiVisitada ? " aria-label='Vaga já acessada: " . htmlspecialchars($post['titulo'], ENT_QUOTES, 'UTF-8') . "'" : "") . ">Acessar link</a></p>";
                    if ($mostrarIdPost) {
                        echo "<p class='id-post'>ID:" . htmlspecialchars($post['id']). "</p>";
                    }
                    echo "<p class='data'>Postado em: " . $dataPostagem . "</p>";
                    echo "</div>";
                }
                ?>

            </div>
            <p class="sem-resultados" hidden>Nenhuma postagem encontrada para esta categoria.</p>
        </section>

    </main>
    <?php renderizarRodape(); ?>
    <script src="post-filter.js"></script>
    <?php if ($historicoAtivo) { ?><script src="historico.js?v=1"></script><?php } ?>
</body>
</html>
