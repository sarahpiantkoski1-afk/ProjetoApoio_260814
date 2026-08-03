<?php
require_once('../../backend/funcionalidades/config.php');
require_once(__DIR__ . '/../componentes/layout.php');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Apoio+</title>
    <link rel="stylesheet" href="<?= caminhoEstilo('estilos.css') ?>">
    <link rel="stylesheet" href="<?= caminhoEstilo('postagens.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php renderizarCabecalho('inicio'); ?>

    <main>
        <?php
        if (!isset($_SESSION['usuario']) && (!isset($_SESSION['ong']))) {
            echo '<section id="hero">';
            echo '<h1>Apoio+   Conectando jovens egressos a oportunidades reais</h1>';
            echo '<p>Cadastre-se para receber notifica&ccedil;&otilde;es das atividades das ONGs associadas!</p>';
            echo '<img src="../imagens/apoiomais.png" alt="logo" class="foto_comeco">';
            echo '<a href="login/cadastro_egressos.php" class="btn-primary">Cadastre-se j&aacute;</a>';
            echo '</section>';
        }
        ?>

        <section id="oportunidades">
            <h2>Posts Recentes</h2>

            <?php
                $consulta = $mysqli->prepare("SELECT * FROM postagens ORDER BY data_postagem DESC LIMIT 5");
                $consulta->execute();
                $resultado = $consulta->get_result();

                if ($resultado->num_rows == 0) {
                    echo '<div class="card">';
                    echo '<h3>Buscando Postagens recentes</h3>';
                    echo '<p>Nenhuma postagem encontrada!</p>';
                    echo '</div>';
                } else {
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

                    echo "<div class='card-post'>";
                    while ($dados = $resultado->fetch_assoc()) {
                        $timestampPostagem = strtotime($dados['data_postagem']);
                        $dataPostagem = date("d", $timestampPostagem) . " " . $meses[(int) date("n", $timestampPostagem)] . " " . date("Y", $timestampPostagem);

                        echo "<div class='post'>";
                        echo "<h2>" . htmlspecialchars($dados['titulo']) . "</h2>";
                        echo "<p class='descricao'>" . nl2br(htmlspecialchars($dados['descricao'])) . "</p>";
                        echo "<p><a href='" . htmlspecialchars($dados['link']) . "' target='_blank' class='ver-mais'>Acessar link</a></p>";
                        if ($mostrarIdPost) {
                            echo "<p class='id-post'>ID:" . htmlspecialchars($dados['id']). "</p>";
                        }
                        echo "<p class='data'>Postado em: " . $dataPostagem . "</p>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
            ?>
        </section>
    </main>

    <?php renderizarRodape(); ?>
</body>
</html>
