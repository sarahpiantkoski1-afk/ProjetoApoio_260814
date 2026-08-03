<?php
require_once('../../backend/funcionalidades/config.php');
require_once(__DIR__ . '/../componentes/layout.php');

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postagens - Apoio+</title>
    <link rel="stylesheet" href="<?= caminhoEstilo('estilos.css') ?>">
    <link rel="stylesheet" href="<?= caminhoEstilo('postagens.css') ?>">
</head>
<body>
    <?php renderizarCabecalho('postagens'); ?>
    <main>
        <div class="filtro-postagens">
            <button class="botao-filtro" type="button" aria-label="Abrir filtros de categorias" aria-expanded="false">
                &#128269;
            </button>
            <div class="painel-filtro" hidden>
                <button type="button" class="filtro-chip" data-categoria="curso">Curso</button>
                <button type="button" class="filtro-chip" data-categoria="moradia">Moradia</button>
                <button type="button" class="filtro-chip" data-categoria="apoio-psicologico">Apoio psicol&oacute;gico</button>
                <button type="button" class="filtro-chip" data-categoria="emprego">Emprego</button>
                <button type="button" class="filtro-chip" data-categoria="acolhimento">Acolhimento</button>
            </div>
        </div>

        <section id="posts">
            <h2>Postagens das ONGs</h2>

            <div class="card-post"> <!-- use a classe correta do CSS flex -->

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
                    $timestampPostagem = strtotime($post['data_postagem']);
                    $dataPostagem = date("d", $timestampPostagem) . " " . $meses[(int) date("n", $timestampPostagem)] . " " . date("Y", $timestampPostagem);
                    $categoriasPost = htmlspecialchars(categoriasPostagem($post));

                    echo "<div class='post' data-categorias='" . $categoriasPost . "'>";
                    echo "<h2>" . htmlspecialchars($post['titulo']) . "</h2>";
                    echo "<p class='descricao'>" . nl2br(htmlspecialchars($post['descricao'])) . "</p>";
                    echo "<p><a href='" . htmlspecialchars($post['link']) . "' target='_blank' class='ver-mais'>Acessar link</a></p>";
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
</body>
</html>
